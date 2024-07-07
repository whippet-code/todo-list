<?php

use Livewire\Volt\Component;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    //
    public Todo $todo;
    public string $todoName = '';

    public function createTodo()
    {
        $this->validate([
            'todoName' => ['required', 'string', 'min:3', 'max:255'],
        ]);

        Auth::user()->todos()->create([
            'name' => $this->pull('todoName'),
        ]);
    }

    public function deleteTodo(int $id) {

        $todo = Auth::user()->todos()->find($id);
        $this->authorize('delete', $todo);

        $todo->delete();
    }

    public function with()
    {
        return [
            'todos' => Auth::user()->todos()->get(),
    ];
    }
}; ?>

<div>
    <form wire:submit='createTodo' class="flex flex-col gap-2 space-y-2 border-gray-100 border-2 mt-2 p-4 rounded-lg bg-slate-50">
        <x-text-input wire:model="todoName" />  
        <x-primary-button type="submit" class="min-h-12 bg-cyan-800 hover:bg-cyan-600"><span class="mx-auto text-lg">Create</span></x-primary-button> 
        <x-input-error :messages="$errors->get('todoName')" class="mt-2" />
    </form> 

    <div class="flex py-4 gap-4 flex-wrap">

        @foreach ($todos as $todo)
            <div wire:key='{{ $todo->id }}' class="flex flex-col gap-2 space-y-2 justify-between border-gray-100 border-2 mt-2 p-4 rounded-lg bg-slate-50 max-w-xl flex-grow">
                <div class="font-bold text-lg text-cyan-800">
                    {{ $todo->name }}     
                </div>
                <x-secondary-button wire:click="deleteTodo({{ $todo->id }})" class="bg-slate-600 text-cyan-100 ms-auto" >Delete</x-secondary-button>
            </div>
        @endforeach

    </div>
</div>
