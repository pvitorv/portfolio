@props(['bioLink'])

<div class="space-y-6">
    <x-app-input
        name="title"
        label="Título do botão"
        :value="old('title', $bioLink->title)"
        placeholder="Ex: Meu Instagram"
        required
        :error="$errors->first('title')"
    />

    <x-app-textarea
        name="description"
        label="Descrição curta (opcional)"
        rows="2"
        placeholder="Uma linha sobre este link"
        :error="$errors->first('description')"
    >{{ old('description', $bioLink->description) }}</x-app-textarea>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
        <select
            name="type"
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
        >
            @foreach(['url' => 'Link (URL)', 'whatsapp' => 'WhatsApp', 'email' => 'E-mail', 'phone' => 'Telefone'] as $value => $label)
                <option value="{{ $value }}" @selected(old('type', $bioLink->type ?? 'url') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @if($errors->first('type'))
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('type') }}</p>
        @endif
    </div>

    <x-app-input
        name="url"
        label="Destino"
        :value="old('url', $bioLink->url)"
        placeholder="https://instagram.com/... ou número/e-mail"
        required
        :hint="'URL completa, número com DDD (WhatsApp/telefone) ou e-mail, conforme o tipo.'"
        :error="$errors->first('url')"
    />

    <x-app-input
        name="sort_order"
        label="Ordem (menor aparece primeiro)"
        type="number"
        min="0"
        :value="old('sort_order', $bioLink->sort_order ?? 0)"
        :error="$errors->first('sort_order')"
    />

    <div>
        <label class="inline-flex items-center gap-2 cursor-pointer">
            <input
                type="checkbox"
                name="is_active"
                value="1"
                {{ old('is_active', $bioLink->is_active ?? true) ? 'checked' : '' }}
                class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500"
            />
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Ativo na página pública</span>
        </label>
    </div>
</div>
