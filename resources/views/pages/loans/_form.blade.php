@php $editing = $editing ?? false; @endphp

<flux:card>
    <flux:field>
        <flux:label>Client</flux:label>
        @if ($editing)
            <flux:input :value="$this->loan->client->full_name" readonly variant="filled" />
            <flux:error name="client_id" />
        @else
            <div x-data="{ open: false }" @click.away="open = false" class="relative">
                <flux:input
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search clients..."
                    @focus="open = true"
                    @keydown.escape="open = false"
                />

                @if ($this->selectedClient)
                    <flux:badge size="lg" inset="top" class="mb-4">
                        {{ $this->selectedClient->full_name }}
                        <button type="button" wire:click="$set('client_id', null)" class="ml-1 hover:text-red-500">&times;</button>
                    </flux:badge>
                @endif

                <div
                    x-show="open"
                    x-transition
                    class="absolute z-10 w-full bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-lg max-h-56 overflow-y-auto"
                    style="display: none;"
                >
                    @forelse ($this->clients as $client)
                        <button
                            type="button"
                            wire:click="selectClient('{{ $client->id }}')"
                            @click="open = false"
                            class="w-full text-left px-4 py-2 hover:bg-zinc-50 dark:hover:bg-zinc-700 flex items-center gap-3"
                        >
                            <div>
                                <div class="font-medium">{{ $client->full_name }}</div>
                                <flux:text class="text-sm">{{ $client->phone }} · {{ $client->branch?->name }}</flux:text>
                            </div>
                        </button>
                    @empty
                        <div class="px-4 py-6 text-center text-zinc-400">
                            @if (strlen($this->search))
                                No clients found matching "{{ $this->search }}"
                            @else
                                Type a name or phone to search for clients.
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>
            <flux:error name="client_id" />
        @endif
    </flux:field>
</flux:card>

<flux:card class="space-y-6">
    <flux:heading size="lg">Loan Details</flux:heading>

    @if ($editing)
        <flux:field>
            <flux:label>Loan Product</flux:label>
            <flux:input :value="$this->loan->product?->name ?? '-'" readonly variant="filled" />
        </flux:field>

        <flux:field>
            <flux:label>Principal Amount</flux:label>
            <flux:input :value="number_format((float) $this->principal_amount, 2)" readonly variant="filled" />
        </flux:field>

        <flux:field>
            <flux:label>Loan Purpose</flux:label>
            <flux:input :value="$this->purpose ?: '—'" readonly variant="filled" />
        </flux:field>
    @else
        <flux:field>
            <flux:label>Loan Product</flux:label>
            <flux:select wire:model.live="loan_product_id">
                <option value="">-- Select Product --</option>
                @foreach ($this->products as $product)
                    <option value="{{ $product->id }}">
                        {{ $product->name }} ({{ $product->interest_rate }}% {{ ucfirst($product->interest_type) }})
                    </option>
                @endforeach
            </flux:select>
        </flux:field>

        @if ($this->product_term_months > 0)
            <flux:field>
                <flux:label>Principal Amount</flux:label>
                <flux:input
                    wire:model="principal_amount"
                    type="number"
                    step="0.01"
                    placeholder="₱{{ number_format((float) $this->product_min_amount, 0) }} – ₱{{ number_format((float) ($this->product_max_amount ?: 999999), 0) }}"
                />
                <flux:description>Allowed range: ₱{{ number_format((float) $this->product_min_amount, 0) }} – ₱{{ number_format((float) ($this->product_max_amount ?: 999999), 0) }}</flux:description>
            </flux:field>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <flux:field>
                    <flux:label>Interest Rate</flux:label>
                    <flux:input :value="$this->product_interest_rate . '% ' . ucfirst($this->product_interest_type)" readonly variant="filled" />
                </flux:field>

                <flux:field>
                    <flux:label>Term</flux:label>
                    <flux:input :value="$this->product_term_months . ' months'" readonly variant="filled" />
                </flux:field>

                <flux:field>
                    <flux:label>Payment Frequency</flux:label>
                    <flux:input :value="ucfirst($this->product_payment_frequency)" readonly variant="filled" />
                </flux:field>
            </div>
        @endif

        <flux:field>
            <flux:label>Loan Purpose</flux:label>
            <flux:textarea wire:model="purpose" />
        </flux:field>
    @endif
</flux:card>

<div>
    <flux:card class="space-y-4">
        <flux:field>
            <flux:label>Branch</flux:label>
            <flux:input :value="$this->branch?->name ?? '—'" readonly variant="filled" />
        </flux:field>
    </flux:card>
</div>
