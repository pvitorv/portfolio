<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\ScreenshotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function __construct(
        private ScreenshotService $screenshotService
    ) {}

    public function index()
    {
        $projects = Project::orderBy('order')->orderBy('created_at', 'desc')->get();
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $project = new Project;
        return view('admin.projects.create', compact('project'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|url',
            'github_url' => 'nullable|url|max:500',
            'thumbnail_url' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'order' => 'nullable|integer|min:0',
            'is_visible' => 'boolean',
        ]);
        $validated['is_visible'] = $request->boolean('is_visible');
        $validated['order'] = (int) ($validated['order'] ?? 0);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('projects', 'public');
            $validated['thumbnail_url'] = $path;
        } else {
            $validated['thumbnail_url'] = $this->resolveThumbnailForSave(
                $validated['thumbnail_url'] ?? null,
                $validated['url']
            );
        }
        unset($validated['thumbnail']);

        Project::create($validated);
        return redirect()->route('admin.projects.index')->with('success', 'Projeto criado.');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|url',
            'github_url' => 'nullable|url|max:500',
            'thumbnail_url' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'order' => 'nullable|integer|min:0',
            'is_visible' => 'boolean',
        ]);
        $validated['is_visible'] = $request->boolean('is_visible');
        $validated['order'] = (int) ($validated['order'] ?? $project->order);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('projects', 'public');
            $validated['thumbnail_url'] = $path;
        } else {
            $incoming = $validated['thumbnail_url'] ?? $project->thumbnail_url;
            if ($incoming === '' || $incoming === null) {
                $validated['thumbnail_url'] = $this->resolveThumbnailForSave(null, $validated['url']);
            } else {
                $validated['thumbnail_url'] = $this->resolveThumbnailForSave($incoming, $validated['url']);
            }
        }
        unset($validated['thumbnail']);

        $project->update($validated);
        return redirect()->route('admin.projects.index')->with('success', 'Projeto atualizado.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Projeto removido.');
    }

    /**
     * Gera screenshot da URL do projeto e salva em storage (path local, não link externo).
     */
    public function fetchThumbnail(Request $request)
    {
        $request->validate(['url' => 'required|url']);
        $url = $request->input('url');
        $path = $this->screenshotService->storeThumbnailFromProjectUrl($url);

        return response()->json([
            'success' => $path !== null,
            'thumbnail_url' => $path,
        ]);
    }

    /**
     * Links externos (Microlink etc.) viram arquivo em storage/projects/.
     * Se vazio, tenta gerar automaticamente a partir da URL do projeto.
     */
    private function resolveThumbnailForSave(?string $thumbnailUrl, string $projectUrl): ?string
    {
        if (! empty($thumbnailUrl)) {
            return $this->screenshotService->persistExternalThumbnail($thumbnailUrl);
        }

        return $this->screenshotService->storeThumbnailFromProjectUrl($projectUrl);
    }
}