@props(['project'])

<div class="space-y-6">
    <x-app-input
        name="title"
        label="Título"
        :value="old('title', $project->title)"
        placeholder="Ex: E-commerce Laravel"
        required
        :error="$errors->first('title')"
    />

    <x-app-input
        name="name"
        label="Nome curto (opcional)"
        :value="old('name', $project->name)"
        placeholder="Ex: E-commerce"
        :hint="'Se vazio, usa o título.'"
        :error="$errors->first('name')"
    />

    <x-app-textarea
        name="description"
        label="Descrição"
        :error="$errors->first('description')"
    >{{ old('description', $project->description) }}</x-app-textarea>

    <x-app-input
        name="url"
        label="URL do projeto"
        type="url"
        :value="old('url', $project->url)"
        placeholder="https://meu-projeto.com"
        required
        :error="$errors->first('url')"
    />

    <x-app-input
        name="github_url"
        label="Link do repositório no GitHub (opcional)"
        type="url"
        :value="old('github_url', $project->github_url ?? '')"
        placeholder="https://github.com/usuario/repo"
        :error="$errors->first('github_url')"
    />

    {{-- Miniatura: print automático da página OU upload de imagem OU URL --}}
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-4 space-y-4 bg-gray-50/50 dark:bg-gray-800/30">
        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200">Miniatura do card (imagem do projeto)</h3>

        {{-- 1) Print automático da página inicial (screenshot) --}}
        <div x-data="thumbnailFetcher()">
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                <strong>Print automático:</strong> gera um screenshot da primeira página que a URL do projeto abre.
            </p>
            <div class="flex flex-wrap items-center gap-3">
                <button
                    type="button"
                    x-on:click="fetchThumbnail()"
                    x-bind:disabled="loading"
                    class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-lg bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-200 hover:bg-blue-200 dark:hover:bg-blue-900/60 disabled:opacity-50"
                >
                    <span x-show="!loading">Gerar print da página (screenshot)</span>
                    <span x-show="loading">Gerando…</span>
                </button>
                <span x-show="message" x-text="message" class="text-sm text-gray-500 dark:text-gray-400"></span>
            </div>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">Se não funcionar (site bloqueado, etc.), use uma das opções abaixo.</p>
        </div>

        <x-app-divider />

        {{-- 2) Enviar imagem (upload) --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ou envie uma imagem</label>
            <input
                type="file"
                name="thumbnail"
                accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gray-200 file:text-gray-700 dark:file:bg-gray-700 dark:file:text-gray-300"
            />
            @if($errors->first('thumbnail'))
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('thumbnail') }}</p>
            @endif
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">JPG, PNG, GIF ou WebP. Até 2 MB.</p>
        </div>

        <x-app-divider />

        {{-- 3) Ou colar URL de uma imagem --}}
        <x-app-input
            name="thumbnail_url"
            label="Ou cole aqui a URL de uma imagem"
            type="url"
            :value="old('thumbnail_url', $project->thumbnail_url)"
            placeholder="https://exemplo.com/imagem.jpg"
            :error="$errors->first('thumbnail_url')"
            x-ref="thumbnailInput"
        />
    </div>

    @if($project->thumbnail_display_url ?? null)
        <div>
            <span class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Preview da miniatura</span>
            <img src="{{ $project->thumbnail_display_url }}" alt="" class="max-w-xs rounded-lg border border-gray-200 dark:border-gray-700" />
        </div>
    @endif

    <x-app-input
        name="order"
        label="Ordem (número, menor aparece primeiro)"
        type="number"
        min="0"
        :value="old('order', $project->order ?? 0)"
        :error="$errors->first('order')"
    />

    <div>
        <label class="inline-flex items-center gap-2 cursor-pointer">
            <input
                type="checkbox"
                name="is_visible"
                value="1"
                {{ old('is_visible', $project->is_visible ?? true) ? 'checked' : '' }}
                class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500"
            />
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Visível no portfolio</span>
        </label>
    </div>
</div>

<script>
function thumbnailFetcher() {
    return {
        loading: false,
        message: '',
        fetchThumbnail() {
            const urlInput = document.querySelector('input[name="url"]');
            const thumbnailInput = this.$refs.thumbnailInput?.querySelector('input') || this.$refs.thumbnailInput;
            const url = urlInput?.value?.trim();
            if (!url) {
                this.message = 'Informe a URL do projeto primeiro.';
                return;
            }
            this.loading = true;
            this.message = '';
            fetch('{{ route("admin.projects.fetch-thumbnail") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value
                },
                body: JSON.stringify({ url })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success && data.thumbnail_url && thumbnailInput) {
                    thumbnailInput.value = data.thumbnail_url;
                    this.message = 'Miniatura obtida.';
                } else {
                    this.message = 'Não foi possível obter a miniatura. Preencha manualmente ou tente outra URL.';
                }
            })
            .catch(() => {
                this.message = 'Erro ao buscar. Verifique a URL ou preencha a miniatura manualmente.';
            })
            .finally(() => { this.loading = false; });
        }
    };
}
</script>
