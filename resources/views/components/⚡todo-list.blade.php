<?php

use Livewire\Component;

new class extends Component
{
    public string $newTodo = '';
    public array $todos = [];

    public function add(): void
    {
        if (trim($this->newTodo) === '') return;

        $this->todos[] = [
            'text' => $this->newTodo,
            'done' => false,
        ];

        $this->newTodo = '';
    }

    public function toggle(int $index): void
    {
        $this->todos[$index]['done'] = !$this->todos[$index]['done'];
    }

    public function remove(int $index): void
    {
        unset($this->todos[$index]);
        $this->todos = array_values($this->todos);
    }
};
?>

<div>
    <form wire:submit="add" class="flex gap-2 mb-4">
        <input
            wire:model="newTodo"
            type="text"
            placeholder="Nova tarefa..."
            class="flex-1 px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <x-button type="submit">Adicionar</x-button>
    </form>

    <ul class="space-y-2">
        @forelse($todos as $index => $todo)
            <li class="flex items-center justify-between p-3 bg-white rounded shadow-sm">
                <span
                    wire:click="toggle({{ $index }})"
                    class="cursor-pointer {{ $todo['done'] ? 'line-through text-gray-400' : '' }}"
                >
                    {{ $todo['text'] }}
                </span>
                <x-button variant="danger" wire:click="remove({{ $index }})">X</x-button>
            </li>
        @empty
            <li class="text-gray-400 text-center py-4">Nenhuma tarefa ainda.</li>
        @endforelse
    </ul>
</div>
