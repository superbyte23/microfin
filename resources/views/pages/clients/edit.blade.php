<?php

use App\Models\Branch;
use App\Models\Client;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public Client $client;

    public string $first_name = '';
    public string $middle_name = '';
    public string $last_name = '';
    public ?string $suffix = null;
    public ?string $type = 'individual';
    public ?string $birth_date = null;
    public ?string $gender = null;
    public ?string $civil_status = null;
    public ?string $phone = null;
    public ?string $email = null;
    public ?string $address = null;
    public ?string $branch_id = null;
    public ?string $group_id = null;
    public ?string $status = 'active';

    public function mount(): void
    {
        $this->fill($this->client->only([
            'first_name', 'middle_name', 'last_name', 'suffix', 'type',
            'birth_date', 'gender', 'civil_status', 'phone', 'email', 'address',
            'branch_id', 'group_id', 'status',
        ]));
    }

    public function save(): void
    {
        $data = $this->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'type' => 'required|in:individual,group,corporate',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|max:50',
            'civil_status' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'branch_id' => 'required|exists:branches,id',
            'status' => 'required|in:active,inactive,restricted',
        ]);

        $this->client->update($data);

        Flux::toast('Client updated successfully.', variant: 'success');
    }

    public function branches()
    {
        return Branch::orderBy('name')->get();
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">{{ $client->full_name }}</flux:heading>
            <flux:subheading>Edit client information</flux:subheading>
        </div>
        <flux:button variant="ghost" :href="route('clients.index')" wire:navigate>Back</flux:button>
    </div>

    <flux:card>
        <div>
            <flux:heading>Personal Information</flux:heading>
        </div>
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <flux:input wire:model="first_name" label="First Name" required />
                <flux:input wire:model="middle_name" label="Middle Name" />
                <flux:input wire:model="last_name" label="Last Name" required />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <flux:input wire:model="suffix" label="Suffix" placeholder="Jr., III" />
                <flux:select wire:model="type" label="Client Type" required>
                    <option value="individual">Individual</option>
                    <option value="group">Group</option>
                    <option value="corporate">Corporate</option>
                </flux:select>
                <flux:select wire:model="gender" label="Gender">
                    <option value="">-- Select --</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </flux:select>
                <flux:select wire:model="civil_status" label="Civil Status">
                    <option value="">-- Select --</option>
                    <option value="single">Single</option>
                    <option value="married">Married</option>
                    <option value="widowed">Widowed</option>
                    <option value="divorced">Divorced</option>
                </flux:select>
            </div>

            <flux:input wire:model="birth_date" label="Birth Date" type="date" />
        </div>
    </flux:card>

    <flux:card class="mt-6">
        <div>
            <flux:heading>Contact & Address</flux:heading>
        </div>
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="phone" label="Phone" type="tel" />
                <flux:input wire:model="email" label="Email" type="email" />
            </div>
            <flux:textarea wire:model="address" label="Address" />
        </div>
    </flux:card>

    <flux:card class="mt-6">
        <div>
            <flux:heading>Assignment</flux:heading>
        </div>
        <div class="space-y-4">
            <flux:select wire:model="branch_id" label="Branch" required>
                @foreach ($this->branches() as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </flux:select>

            <flux:select wire:model="status" label="Status" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="restricted">Restricted</option>
            </flux:select>
        </div>
    </flux:card>

    <div class="mt-6 flex justify-end gap-4">
        <flux:button variant="ghost" :href="route('clients.index')" wire:navigate>Cancel</flux:button>
        <flux:button variant="primary" wire:click="save">Update Client</flux:button>
    </div>
</div>
