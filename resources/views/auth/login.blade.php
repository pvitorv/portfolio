<x-layouts.app title="Entrar – Portfolio">
    <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-950 px-4">
        <x-app-card variant="default" padding="lg" class="w-full max-w-md">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">Entrar no admin</h1>

            @if($errors->any())
                <x-app-alert variant="danger" class="mb-4">
                    {{ $errors->first() }}
                </x-app-alert>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <x-app-input
                    name="email"
                    label="E-mail"
                    type="email"
                    :value="old('email')"
                    placeholder="seu@email.com"
                    required
                    autofocus
                    :error="$errors->first('email')"
                />
                <x-app-input
                    name="password"
                    label="Senha"
                    type="password"
                    required
                    :error="$errors->first('password')"
                />
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500" />
                    <span class="text-sm text-gray-600 dark:text-gray-400">Lembrar de mim</span>
                </label>
                <div class="pt-2">
                    <x-button type="submit" variant="primary" class="w-full">Entrar</x-button>
                </div>
            </form>
        </x-app-card>
    </div>
</x-layouts.app>
