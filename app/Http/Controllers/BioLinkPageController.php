<?php

namespace App\Http\Controllers;

use App\Models\BioLink;
use App\Models\HubClick;
use App\Models\Project;
use App\Models\User;
use App\Services\LinkHubService;
use Illuminate\Http\Request;

class BioLinkPageController extends Controller
{
    public function __construct(
        private LinkHubService $linkHub
    ) {}

    public function index()
    {
        $profile = User::first();
        $sections = $this->linkHub->sections($profile);

        return view('links.index', compact('profile', 'sections'));
    }

    public function go(Request $request, BioLink $bioLink)
    {
        if (! $bioLink->is_active) {
            abort(404);
        }

        $bioLink->recordClick();

        return redirect()->away($bioLink->destination_url);
    }

    public function goProject(Project $project)
    {
        return $this->redirectProject($project, github: false);
    }

    public function goProjectGithub(Project $project)
    {
        return $this->redirectProject($project, github: true);
    }

    private function redirectProject(Project $project, bool $github): \Illuminate\Http\RedirectResponse
    {
        if (! $project->is_visible) {
            abort(404);
        }

        $key = $github ? $project->id.'-github' : (string) $project->id;
        $url = $github ? $project->github_url : $project->url;

        if (empty($url)) {
            abort(404);
        }

        HubClick::record('project', $key);

        return redirect()->away($url);
    }

    public function goSocial(string $channel)
    {
        $profile = User::first();
        if (! $profile) {
            abort(404);
        }

        $url = $this->resolveProfileUrl($profile, $channel);
        if (empty($url)) {
            abort(404);
        }

        HubClick::record('profile', $channel);

        return redirect()->away($url);
    }

    private function resolveProfileUrl(User $profile, string $channel): ?string
    {
        return match ($channel) {
            'portfolio' => url('/'),
            'linkedin' => $profile->linkedin_url,
            'github' => $profile->github_url,
            'email' => $profile->email ? 'mailto:'.$profile->email : null,
            'whatsapp' => $profile->phone
                ? 'https://wa.me/'.preg_replace('/\D/', '', $profile->phone)
                : null,
            default => null,
        };
    }
}
