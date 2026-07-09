@php $editing = $editing ?? false; @endphp

<flux:card class="space-y-4">
    <flux:heading size="lg">Client</flux:heading>

    @if ($editing)
        <flux:field>
            <flux:label>Client</flux:label>
            <flux:input :value="$share_capital->client->full_name" readonly variant="filled" />
        </flux:field>
    @else
        <flux:field>
            <flux:label>Search Clients</flux:label>
            <flux:input wire:model.live.debounce.300ms="search" placeholder="Search clients..." icon="magnifying-glass" />
        </flux:field>

        <div class="space-y-2 max-h-48 overflow-y-auto">
            @foreach ($this->clients as $client)
                <label class="flex items-center gap-3 p-2 rounded-lg cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800">
                    <input type="radio" name="client_id" wire:model="client_id" value="{{ $client->id }}" class="text-indigo-600 focus:ring-indigo-500">
                    <div>
                        <div class="font-medium">{{ $client->full_name }}</div>
                        <flux:text class="text-sm">{{ $client->branch?->name }}</flux:text>
                    </div>
                </label>
            @endforeach
        </div>
        <flux:error name="client_id" />
    @endif
</flux:card>

<flux:card class="space-y-4">
    <flux:heading size="lg">Share Details</flux:heading>

    <flux:field>
        <flux:label>Number of Shares</flux:label>
        <flux:input wire:model="total_shares" type="number" required />
    </flux:field>

    <flux:field>
        <flux:label>Price Per Share</flux:label>
        <flux:input wire:model="share_price" type="number" step="0.01" required />
    </flux:field>

    <flux:field>
        <flux:label>Branch</flux:label>
        @if ($editing)
            <flux:input :value="$this->share_capital->branch?->name ?? '—'" readonly variant="filled" />
        @else
            <flux:select wire:model="branch_id" required>
                @foreach (\App\Models\Branch::orderBy('name')->get() as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </flux:select>
        @endif
    </flux:field>

    @if($total_shares && $share_price)
        <flux:card class="bg-zinc-50 dark:bg-zinc-800">
            <flux:heading size="sm">Total Amount: {{ number_format((int)$total_shares * (float)$share_price, 2) }}</flux:heading>
        </flux:card>
    @endif
</flux:card>
