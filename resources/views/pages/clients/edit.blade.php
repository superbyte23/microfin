<?php

use App\Models\Branch;
use App\Models\Client;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public Client $client;

    public string $first_name = '';
    public ?string $middle_name = null;
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
    public ?string $member_id = null;
    public ?string $pin = null;
    public bool $showPin = false;

    public function mount(): void
    {
        $this->fill($this->client->only([
            'first_name', 'middle_name', 'last_name', 'suffix', 'type',
            'birth_date', 'gender', 'civil_status', 'phone', 'email', 'address',
            'branch_id', 'group_id', 'status', 'member_id',
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
            'member_id' => 'required|string|max:50|unique:clients,member_id,' . $this->client->id,
            'pin' => 'nullable|string|digits:4',
        ]);

        if (! $data['pin']) {
            unset($data['pin']);
        }

        $this->client->update($data);

        Flux::toast('Client updated successfully.', variant: 'success');
    }

    public function branches()
    {
        return Branch::orderBy('name')->get();
    }
};
?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ $client->full_name }}</flux:heading>
            <flux:subheading>Edit client information</flux:subheading>
        </div>
        <flux:button variant="ghost" :href="route('clients.index')" wire:navigate>Back</flux:button>
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

    <form wire:submit="save" class="space-y-6">
        @include('pages.clients._form')

        <div class="flex justify-end gap-4">
            <flux:button variant="ghost" :href="route('clients.index')" wire:navigate>Cancel</flux:button>
            <flux:button type="submit" variant="primary">Update Client</flux:button>
        </div>
    </form>
</div>
