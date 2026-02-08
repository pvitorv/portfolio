<x-layouts.admin title="Perfil – Admin">
        <main class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if(session('success'))
                <x-app-alert variant="success" class="mb-6">{{ session('success') }}</x-app-alert>
            @endif

            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Configurações de perfil</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Estes dados aparecem na página inicial do portfolio (currículo e foto).</p>

            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                <x-app-card variant="default" padding="lg">
                    <div class="space-y-6">
                        {{-- Foto --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sua foto</label>
                            @if($user->photo_display_url)
                                <img src="{{ $user->photo_display_url }}" alt="" class="w-24 h-24 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700 mb-2" />
                            @endif
                            <input
                                type="file"
                                name="photo"
                                accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gray-200 file:text-gray-700 dark:file:bg-gray-700 dark:file:text-gray-300"
                            />
                            @if($errors->first('photo'))
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('photo') }}</p>
                            @endif
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">JPG, PNG, GIF ou WebP. Até 2 MB.</p>
                        </div>

                        <x-app-input name="name" label="Nome" :value="old('name', $user->name)" required :error="$errors->first('name')" />
                        <x-app-input name="email" label="E-mail" type="email" :value="old('email', $user->email)" required :error="$errors->first('email')" />

                        <x-app-input name="title" label="Título / Cargo (ex: Desenvolvedor Full Stack)" :value="old('title', $user->title)" :error="$errors->first('title')" />

                        <x-app-textarea name="bio" label="Sobre você / Currículo (texto que aparece na página inicial)" :error="$errors->first('bio')">{{ old('bio', $user->bio) }}</x-app-textarea>

                        <x-app-input name="phone" label="Telefone (opcional)" :value="old('phone', $user->phone)" :error="$errors->first('phone')" />
                        <x-app-input name="location" label="Localização (opcional)" :value="old('location', $user->location)" :error="$errors->first('location')" />
                        <x-app-input name="linkedin_url" label="LinkedIn (URL)" type="url" :value="old('linkedin_url', $user->linkedin_url)" placeholder="https://linkedin.com/in/..." :error="$errors->first('linkedin_url')" />
                        <x-app-input name="github_url" label="GitHub (URL)" type="url" :value="old('github_url', $user->github_url)" placeholder="https://github.com/..." :error="$errors->first('github_url')" />

                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">Alterar senha (deixe em branco para não mudar)</h3>
                            <x-app-input name="password" label="Nova senha" type="password" :error="$errors->first('password')" />
                            <x-app-input name="password_confirmation" label="Confirmar nova senha" type="password" class="mt-2" />
                        </div>
                    </div>
                </x-app-card>
                <x-button type="submit" variant="primary">Salvar perfil</x-button>
            </form>
        </main>
</x-layouts.admin>
