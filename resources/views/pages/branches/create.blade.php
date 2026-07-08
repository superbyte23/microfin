<?php

use App\Models\Branch;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public string $name = '';
    public string $code = '';
    public string $address = '';
    public string $phone = '';
    public string $email = '';
    public bool $is_active = true;

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:branches,code',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        Branch::create($validated);

        Flux::toast('Branch created successfully.', variant: 'success');

        $this->redirect(route('branches.index'), navigate: true);
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">New Branch</flux:heading>
            <flux:subheading>Create a new branch or office</flux:subheading>
        </div>
    </div>

    <flux:card class="max-w-2xl">
        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="name" label="Branch Name" required />
                <flux:input wire:model="code" label="Branch Code" required hint="Unique identifier" />
            </div>

            <flux:textarea wire:model="address" label="Address" />

            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="phone" label="Phone" type="tel" />
                <flux:input wire:model="email" label="Email" type="email" />
            </div>

            <flux:switch wire:model="is_active" label="Active" />

            <div class="flex gap-2 justify-end">
                <flux:button :href="route('branches.index')" variant="ghost" wire:navigate>Cancel</flux:button>
                <flux:button type="submit" variant="primary">Save</flux:button>
            </div>
        </form>
    </flux:card>
</div>
