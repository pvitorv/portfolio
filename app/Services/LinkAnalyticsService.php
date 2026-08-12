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

class LinkAnalyticsService
{
    public function __construct(
        private LinkHubService $linkHub
    ) {}

    public function recordPageView(): void
    {
        HubPageView::record();
    }

    /** @param Collection<int, LinkHubItem> $items */
    public function recordImpressions(Collection $items): void
    {
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
     *     items: Collection<int, array<string, mixed>>
     * }
     */
    public function dashboard(?User $profile): array
    {
        $items = $this->linkHub->allItems($profile)->map(fn (LinkHubItem $item) => $this->metricsForItem($item));

        return [
            'page_views' => HubPageView::total(),
            'page_views_7d' => HubPageView::lastDays(7),
            'total_impressions' => (int) $items->sum('impressions'),
            'total_clicks' => (int) $items->sum('clicks'),
            'items' => $items,
        ];
    }

    /** @return array<string, mixed> */
    public function metricsForItem(LinkHubItem $item): array
    {
        $source = $item->source;
        $key = $item->meta ?? '';

        $impressions = HubImpression::countFor($source, $key);
        $impressions7d = HubImpression::countForLastDays($source, $key, 7);
        $clicks = $this->clickCount($source, $key);
        $clicks7d = $this->clickCountLastDays($source, $key, 7);
        $lastImpression = HubImpression::lastFor($source, $key);
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
            return BioLinkClick::query()->where('bio_link_id', $key)->count();
        }

        return HubClick::countFor($source, $key);
    }

    public function clickCountLastDays(string $source, string $key, int $days = 7): int
    {
        $since = now()->subDays($days);

        if ($source === 'custom') {
            return BioLinkClick::query()
                ->where('bio_link_id', $key)
                ->where('clicked_at', '>=', $since)
                ->count();
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
            $at = BioLinkClick::query()->where('bio_link_id', $key)->max('clicked_at');

            return $at ? Carbon::parse($at) : null;
        }

        $at = HubClick::query()
            ->where('source_type', $source)
            ->where('source_key', $key)
            ->max('clicked_at');

        return $at ? Carbon::parse($at) : null;
    }

    public function resetItem(string $source, string $key): void
    {
        HubImpression::query()
            ->where('source_type', $source)
            ->where('source_key', $key)
            ->delete();

        if ($source === 'custom') {
            $link = BioLink::find($key);
            if ($link) {
                $link->clicks()->delete();
                $link->update(['click_count' => 0, 'last_clicked_at' => null]);
            }

            return;
        }

        HubClick::query()
            ->where('source_type', $source)
            ->where('source_key', $key)
            ->delete();
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
