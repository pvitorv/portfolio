<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BioLink;
use App\Models\User;
use App\Services\LinkHubService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BioLinkController extends Controller
{
    public function __construct(
        private LinkHubService $linkHub
    ) {}

    public function index()
    {
        $links = BioLink::orderBy('sort_order')->orderBy('id')->get();
        $profile = User::first();
        $portfolioSections = collect($this->linkHub->sections($profile))
            ->reject(fn (array $section) => $section['label'] === 'Links');
        $totalClicks = $this->linkHub->totalClicks();
        $clicksLast7Days = $this->linkHub->clicksLastDays(7);

        return view('admin.bio_links.index', compact(
            'links',
            'portfolioSections',
            'totalClicks',
            'clicksLast7Days'
        ));
    }

    public function create()
    {
        $bioLink = new BioLink(['is_active' => true, 'sort_order' => 0, 'type' => 'url']);

        return view('admin.bio_links.create', compact('bioLink'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateBioLink($request);
        BioLink::create($validated);

        return redirect()
            ->route('admin.bio-links.index')
            ->with('success', 'Link criado com sucesso.');
    }

    public function edit(BioLink $bioLink)
    {
        return view('admin.bio_links.edit', compact('bioLink'));
    }

    public function update(Request $request, BioLink $bioLink)
    {
        $validated = $this->validateBioLink($request);
        $bioLink->update($validated);

        return redirect()
            ->route('admin.bio-links.index')
            ->with('success', 'Link atualizado.');
    }

    public function destroy(BioLink $bioLink)
    {
        $bioLink->delete();

        return redirect()
            ->route('admin.bio-links.index')
            ->with('success', 'Link removido.');
    }

    public function resetStats(BioLink $bioLink)
    {
        $bioLink->clicks()->delete();
        $bioLink->update([
            'click_count' => 0,
            'last_clicked_at' => null,
        ]);

        return back()->with('success', 'Estatísticas do link zeradas.');
    }

    private function validateBioLink(Request $request): array
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'type' => ['required', Rule::in(['url', 'whatsapp', 'email', 'phone'])],
            'url' => 'required|string|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $this->validateUrlForType($validated['type'], $validated['url']);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        return $validated;
    }

    private function validateUrlForType(string $type, string $url): void
    {
        $error = match ($type) {
            'url' => filter_var($url, FILTER_VALIDATE_URL) ? null : 'Informe uma URL válida (https://...).',
            'email' => filter_var($url, FILTER_VALIDATE_EMAIL) ? null : 'Informe um e-mail válido.',
            'whatsapp', 'phone' => preg_match('/^\+?[\d\s\-()]{8,20}$/', $url) ? null : 'Informe um telefone válido.',
            default => null,
        };

        if ($error) {
            throw ValidationException::withMessages(['url' => $error]);
        }
    }
}
