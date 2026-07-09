<flux:card class="space-y-6">
    <flux:heading size="lg">Personal Information</flux:heading>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <flux:field>
            <flux:label>First Name</flux:label>
            <flux:input wire:model="first_name" placeholder="Juan" required />
        </flux:field>

        <flux:field>
            <flux:label>Middle Name</flux:label>
            <flux:input wire:model="middle_name" placeholder="Dela Cruz" />
        </flux:field>

        <flux:field>
            <flux:label>Last Name</flux:label>
            <flux:input wire:model="last_name" placeholder="Santos" required />
        </flux:field>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <flux:field>
            <flux:label>Suffix</flux:label>
            <flux:input wire:model="suffix" placeholder="Jr., III" />
        </flux:field>

        <flux:field>
            <flux:label>Client Type</flux:label>
            <flux:select wire:model="type" required>
                <option value="individual">Individual</option>
                <option value="group">Group</option>
                <option value="corporate">Corporate</option>
            </flux:select>
        </flux:field>

        <flux:field>
            <flux:label>Gender</flux:label>
            <flux:select wire:model="gender">
                <option value="">-- Select --</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </flux:select>
        </flux:field>

        <flux:field>
            <flux:label>Civil Status</flux:label>
            <flux:select wire:model="civil_status">
                <option value="">-- Select --</option>
                <option value="single">Single</option>
                <option value="married">Married</option>
                <option value="widowed">Widowed</option>
                <option value="divorced">Divorced</option>
            </flux:select>
        </flux:field>
    </div>

    <flux:field>
        <flux:label>Birth Date</flux:label>
        <flux:input wire:model="birth_date" type="date" />
    </flux:field>
</flux:card>

<flux:card class="space-y-6">
    <flux:heading size="lg">Contact & Address</flux:heading>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <flux:field>
            <flux:label>Phone</flux:label>
            <flux:input wire:model="phone" type="tel" placeholder="09171234567" />
        </flux:field>

        <flux:field>
            <flux:label>Email</flux:label>
            <flux:input wire:model="email" type="email" placeholder="juan@example.com" />
        </flux:field>
    </div>

    <flux:field>
        <flux:label>Address</flux:label>
        <flux:textarea wire:model="address" placeholder="123 Rizal St, Brgy Poblacion" />
    </flux:field>
</flux:card>

<flux:card class="space-y-6">
    <flux:heading size="lg">Assignment</flux:heading>

    <flux:field>
        <flux:label>Branch</flux:label>
        <flux:select wire:model="branch_id" required>
            @foreach ($this->branches() as $branch)
                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
            @endforeach
        </flux:select>
    </flux:field>

    <flux:field>
        <flux:label>Status</flux:label>
        <flux:select wire:model="status" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="restricted">Restricted</option>
        </flux:select>
    </flux:field>
</flux:card>

<flux:card class="space-y-6">
    <flux:heading size="lg">Mobile Access</flux:heading>

    <flux:field>
        <flux:label>Member ID</flux:label>
        @if(property_exists($this, 'client'))
            <flux:input wire:model="member_id" placeholder="MF-2026-0001" />
            <flux:error message="member_id" />
        @else
            <flux:input value="Auto-generated on save" disabled />
        @endif
    </flux:field>

    <flux:field>
        <flux:label>PIN (4 digits)</flux:label>
        <flux:input wire:model="pin" type="password" placeholder="Enter 4-digit PIN" maxlength="4" inputmode="numeric" />
        <flux:error message="pin" />
        @if(property_exists($this, 'client') && $this->client->pin)
            <p class="text-xs text-green-600 mt-1">PIN is set. Leave blank to keep current.</p>
        @else
            <p class="text-xs text-zinc-500 mt-1">Set a PIN to allow mobile app login.</p>
        @endif
    </flux:field>
</flux:card>
