<?php

namespace App\Services;

use App\Models\BioLink;
use App\Models\BioLinkClick;
use App\Models\HubClick;
use App\Models\HubImpression;
use App\Models\HubPageView;
use App\Models\User;
use App\Support\LinkHubItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class LinkAnalyticsService
{
    public function __construct(
        private LinkHubService $linkHub
    ) {}

    public function tablesReady(): bool
    {
        return Schema::hasTable('hub_impressions')
            && Schema::hasTable('hub_page_views');
    }

    public function recordPageView(): void
    {
        if (! $this->tablesReady()) {
            return;
        }

        HubPageView::record();
    }

    /** @param Collection<int, LinkHubItem> $items */
    public function recordImpressions(Collection $items): void
    {
        if (! $this->tablesReady()) {
            return;
        }

        $now = now();

        foreach ($items as $item) {
            HubImpression::create([
                'source_type' => $item->source,
                'source_key' => $item->meta ?? '',
                'viewed_at' => $now,
            ]);
        }
    }

    /**
     * @return array{
     *     page_views: int,
     *     page_views_7d: int,
     *     total_impressions: int,
     *     total_clicks: int,
     *     items: Collection<int, array<string, mixed>>,
     *     migration_pending: bool
     * }
     */
    public function dashboard(?User $profile): array
    {
        $ready = $this->tablesReady();
        $items = $this->linkHub->allItems($profile)
            ->map(fn (LinkHubItem $item) => $this->metricsForItem($item, $ready));

        return [
            'page_views' => $ready ? HubPageView::total() : 0,
            'page_views_7d' => $ready ? HubPageView::lastDays(7) : 0,
            'total_impressions' => $ready ? (int) $items->sum('impressions') : 0,
            'total_clicks' => (int) $items->sum('clicks'),
            'items' => $items,
            'migration_pending' => ! $ready,
        ];
    }

    /** @return array<string, mixed> */
    public function metricsForItem(LinkHubItem $item, ?bool $ready = null): array
    {
        $ready ??= $this->tablesReady();
        $source = $item->source;
        $key = $item->meta ?? '';

        $impressions = $ready ? HubImpression::countFor($source, $key) : 0;
        $impressions7d = $ready ? HubImpression::countForLastDays($source, $key, 7) : 0;
        $clicks = $this->clickCount($source, $key);
        $clicks7d = $this->clickCountLastDays($source, $key, 7);
        $lastImpression = $ready ? HubImpression::lastFor($source, $key) : null;
        $lastClick = $this->lastClick($source, $key);

        $ctr = $impressions > 0 ? round(($clicks / $impressions) * 100, 1) : 0.0;

        return [
            'title' => $item->title,
            'description' => $item->description,
            'source' => $source,
            'source_key' => $key,
            'source_label' => $this->sourceLabel($source),
            'impressions' => $impressions,
            'impressions_7d' => $impressions7d,
            'clicks' => $clicks,
            'clicks_7d' => $clicks7d,
            'ctr' => $ctr,
            'last_impression' => $lastImpression ? Carbon::parse($lastImpression) : null,
            'last_click' => $lastClick,
            'href' => $item->href,
            'editable' => $item->editable,
            'bio_link_id' => $source === 'custom' ? (int) $key : null,
        ];
    }

    public function clickCount(string $source, string $key): int
    {
        if ($source === 'custom') {
            if (! Schema::hasTable('bio_link_clicks')) {
                return 0;
            }

            return BioLinkClick::query()->where('bio_link_id', $key)->count();
        }

        if (! Schema::hasTable('hub_clicks')) {
            return 0;
        }

        return HubClick::countFor($source, $key);
    }

    public function clickCountLastDays(string $source, string $key, int $days = 7): int
    {
        $since = now()->subDays($days);

        if ($source === 'custom') {
            if (! Schema::hasTable('bio_link_clicks')) {
                return 0;
            }

            return BioLinkClick::query()
                ->where('bio_link_id', $key)
                ->where('clicked_at', '>=', $since)
                ->count();
        }

        if (! Schema::hasTable('hub_clicks')) {
            return 0;
        }

        return HubClick::query()
            ->where('source_type', $source)
            ->where('source_key', $key)
            ->where('clicked_at', '>=', $since)
            ->count();
    }

    public function lastClick(string $source, string $key): ?Carbon
    {
        if ($source === 'custom') {
            if (! Schema::hasTable('bio_link_clicks')) {
                return null;
            }

            $at = BioLinkClick::query()->where('bio_link_id', $key)->max('clicked_at');

            return $at ? Carbon::parse($at) : null;
        }

        if (! Schema::hasTable('hub_clicks')) {
            return null;
        }

        $at = HubClick::query()
            ->where('source_type', $source)
            ->where('source_key', $key)
            ->max('clicked_at');

        return $at ? Carbon::parse($at) : null;
    }

    public function resetItem(string $source, string $key): void
    {
        if ($this->tablesReady()) {
            HubImpression::query()
                ->where('source_type', $source)
                ->where('source_key', $key)
                ->delete();
        }

        if ($source === 'custom') {
            $link = BioLink::find($key);
            if ($link) {
                $link->clicks()->delete();
                $link->update(['click_count' => 0, 'last_clicked_at' => null]);
            }

            return;
        }

        if (Schema::hasTable('hub_clicks')) {
            HubClick::query()
                ->where('source_type', $source)
                ->where('source_key', $key)
                ->delete();
        }
    }

    private function sourceLabel(string $source): string
    {
        return match ($source) {
            'custom' => 'Link extra',
            'project' => 'Projeto',
            'profile' => 'Perfil',
            default => ucfirst($source),
        };
    }
}
