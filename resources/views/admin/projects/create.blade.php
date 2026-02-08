<x-layouts.admin title="Novo projeto – Admin">
        <main class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Novo projeto</h1>

            <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <x-app-card variant="default" padding="lg">
                    @include('admin.projects._form', ['project' => $project])
                </x-app-card>
                <div class="flex gap-3">
                    <x-button type="submit" variant="primary">Criar projeto</x-button>
                    <a href="{{ route('admin.projects.index') }}" class="inline-flex items-center justify-center font-medium rounded-lg px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">Cancelar</a>
                </div>
            </form>
        </main>
</x-layouts.admin>
