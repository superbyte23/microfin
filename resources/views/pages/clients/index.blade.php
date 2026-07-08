<?php

use App\Models\Client;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::app')] class extends Component {
    use WithPagination;

    public string $search = '';

    #[Computed]
    public function clients()
    {
        return Client::query()
            ->with('branch')
            ->when($this->search, fn($q) => $q->whereAny(['first_name', 'middle_name', 'last_name', 'phone', 'email'], 'like', "%{$this->search}%"))
            ->orderBy('last_name')
            ->paginate(15);
    }

    public function delete(Client $client): void
    {
        $client->delete();
        Flux::toast('Client deleted successfully.', variant: 'success');
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Clients</flux:heading>
            <flux:subheading>Manage clients and borrowers</flux:subheading>
        </div>
        <flux:button icon="plus" :href="route('clients.create')" wire:navigate>New Client</flux:button>
    </div>

    <flux:input wire:model.live.debounce.300ms="search" placeholder="Search clients..." icon="magnifying-glass" class="mb-4" />

    <flux:table>
        <flux:table.columns>
            <flux:table.column>Name</flux:table.column>
            <flux:table.column>Contact</flux:table.column>
            <flux:table.column>Branch</flux:table.column>
            <flux:table.column>Type</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->clients as $client)
                <flux:table.row :key="$client->id">
                    <flux:table.cell>
                        <flux:heading size="sm">{{ $client->full_name }}</flux:heading>
                        <flux:text>{{ $client->address }}</flux:text>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:text>{{ $client->phone }}</flux:text>
                        <flux:text>{{ $client->email }}</flux:text>
                    </flux:table.cell>
                    <flux:table.cell>{{ $client->branch?->name ?? '-' }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge size="sm">{{ ucfirst($client->type) }}</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:badge :color="$client->status === 'active' ? 'green' : ($client->status === 'restricted' ? 'yellow' : 'zinc')">
                            {{ ucfirst($client->status) }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown position="bottom" align="end">
                            <flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" />
                            <flux:menu>
                                <flux:menu.item icon="pencil" :href="route('clients.edit', $client)" wire:navigate>
                                    Edit
                                </flux:menu.item>
                                <flux:menu.separator />
                                <flux:menu.item icon="trash" variant="danger" wire:click="delete({{ $client->id }})" wire:confirm="Delete this client?">
                                    Delete
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    {{ $this->clients->links() }}
</div>
