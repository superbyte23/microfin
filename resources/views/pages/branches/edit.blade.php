<?php

use App\Models\Branch;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public Branch $branch;

    public string $name = '';
    public string $code = '';
    public string $address = '';
    public string $phone = '';
    public string $email = '';
    public bool $is_active = true;

    public function mount(): void
    {
        $this->fill($this->branch->only(['name', 'code', 'address', 'phone', 'email', 'is_active']));
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:branches,code,' . $this->branch->id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        $this->branch->update($validated);

        Flux::toast('Branch updated successfully.', variant: 'success');

        $this->redirect(route('branches.index'), navigate: true);
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Edit Branch</flux:heading>
            <flux:subheading>{{ $branch->name }}</flux:subheading>
        </div>
    </div>

    @if ($errors->any())
        <flux:card class="border-red-300 bg-red-50 dark:bg-red-950">
            <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </flux:card>
    @endif

    <form wire:submit="save">
        <flux:card class="max-w-2xl space-y-6">
            @include('pages.branches._form')
        </flux:card>

        <div class="mt-6 flex gap-2 justify-end">
            <flux:button :href="route('branches.index')" variant="ghost" wire:navigate>Cancel</flux:button>
            <flux:button type="submit" variant="primary">Update</flux:button>
        </div>
    </form>
</div>
