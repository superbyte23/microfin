<?php

use App\Models\Branch;
use App\Models\Client;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
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
    public ?string $pin = null;

    public function mount(): void
    {
        $this->branch_id = auth()->user()->branch_id;
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
            'pin' => 'nullable|string|digits:4',
        ]);

        $data['member_id'] = $this->generateMemberId();

        if (! $data['pin']) {
            unset($data['pin']);
        }

        $client = Client::create($data);

        Flux::toast('Client created successfully.', variant: 'success');

        $this->redirect(route('clients.edit', $client), navigate: true);
    }

    protected function generateMemberId(): string
    {
        $prefix = 'MF-' . now()->format('Y') . '-';
        $last = Client::where('member_id', 'like', $prefix . '%')
            ->orderByRaw("CAST(SUBSTRING(member_id, LENGTH('$prefix') + 1) AS UNSIGNED) DESC")
            ->value('member_id');

        $next = $last ? (int) substr($last, strlen($prefix)) + 1 : 1;

        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
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
            <flux:heading size="xl">New Client</flux:heading>
            <flux:subheading>Register a new client</flux:subheading>
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
            <flux:button type="submit" variant="primary">Save Client</flux:button>
        </div>
    </form>
</div>
