<?php

namespace App\Services;

use App\Models\BioLink;
use App\Models\HubClick;
use App\Models\Project;
use App\Models\User;
use App\Support\LinkHubItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class LinkHubService
{
    /**
     * @return array<int, array{label: string, items: Collection<int, LinkHubItem>}>
     */
    public function sections(?User $profile): array
    {
        $sections = [];

        $custom = $this->customLinks();
        if ($custom->isNotEmpty()) {
            $sections[] = ['label' => 'Links', 'items' => $custom];
        }

        $projects = $this->projectLinks();
        if ($projects->isNotEmpty()) {
            $sections[] = ['label' => 'Projetos', 'items' => $projects];
        }

        $profileLinks = $this->profileLinks($profile);
        if ($profileLinks->isNotEmpty()) {
            $sections[] = ['label' => 'Portfólio & contato', 'items' => $profileLinks];
        }

        return $sections;
    }

    /** @return Collection<int, LinkHubItem> */
    public function allItems(?User $profile): Collection
    {
        return collect($this->sections($profile))
            ->flatMap(fn (array $section) => $section['items']);
    }

    /** @return Collection<int, LinkHubItem> */
    private function customLinks(): Collection
    {
        return BioLink::activeOrdered()->get()->map(function (BioLink $link) {
            return new LinkHubItem(
                title: $link->title,
                href: route('links.go', $link),
                description: $link->description ? Str::limit(strip_tags($link->description), 90) : null,
                source: 'custom',
                editable: true,
                meta: (string) $link->id,
            );
        });
    }

    /** @return Collection<int, LinkHubItem> */
    private function projectLinks(): Collection
    {
        return Project::query()
            ->where('is_visible', true)
            ->orderBy('order')
            ->orderByDesc('created_at')
            ->get()
            ->flatMap(function (Project $project) {
                $items = collect([
                    new LinkHubItem(
                        title: $project->display_name,
                        href: route('links.go.project', $project),
                        description: $project->description
                            ? Str::limit(strip_tags($project->description), 90)
                            : null,
                        source: 'project',
                        meta: (string) $project->id,
                    ),
                ]);

                if ($project->github_url) {
                    $items->push(new LinkHubItem(
                        title: $project->display_name.' — GitHub',
                        href: route('links.go.project.github', $project),
                        description: 'Código-fonte no GitHub',
                        source: 'project',
                        meta: $project->id.'-github',
                    ));
                }

                return $items;
            });
    }

    /** @return Collection<int, LinkHubItem> */
    private function profileLinks(?User $profile): Collection
    {
        if (! $profile) {
            return collect();
        }

        $items = collect();

        $items->push(new LinkHubItem(
            title: 'Ver portfólio completo',
            href: route('links.go.social', 'portfolio'),
            description: 'Projetos, bio e formulário de contato',
            source: 'profile',
            meta: 'portfolio',
        ));

        if ($profile->linkedin_url) {
            $items->push(new LinkHubItem(
                title: 'LinkedIn',
                href: route('links.go.social', 'linkedin'),
                description: 'Perfil profissional',
                source: 'profile',
                meta: 'linkedin',
            ));
        }

        if ($profile->github_url) {
            $items->push(new LinkHubItem(
                title: 'GitHub',
                href: route('links.go.social', 'github'),
                description: 'Repositórios e código aberto',
                source: 'profile',
                meta: 'github',
            ));
        }

        if ($profile->email) {
            $items->push(new LinkHubItem(
                title: 'E-mail',
                href: route('links.go.social', 'email'),
                description: $profile->email,
                source: 'profile',
                meta: 'email',
            ));
        }

        if ($profile->phone) {
            $items->push(new LinkHubItem(
                title: 'WhatsApp',
                href: route('links.go.social', 'whatsapp'),
                description: $profile->phone,
                source: 'profile',
                meta: 'whatsapp',
            ));
        }

        return $items;
    }

    public function totalClicks(): int
    {
        return (int) BioLink::query()->sum('click_count') + (int) HubClick::query()->count();
    }

    public function clicksLastDays(int $days = 7): int
    {
        $customRecent = \App\Models\BioLinkClick::query()
            ->where('clicked_at', '>=', now()->subDays($days))
            ->count();

        return $customRecent + HubClick::countLastDays($days);
    }
}
