<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(ChartOfAccountSeeder::class);
        $this->call(BranchSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(LoanProductSeeder::class);

        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@microfin.app',
        ]);
        $admin->assignRole('Admin');

        $manager = User::factory()->create([
            'name' => 'Branch Manager',
            'email' => 'manager@microfin.app',
        ]);
        $manager->assignRole('Branch Manager');
    }
}
