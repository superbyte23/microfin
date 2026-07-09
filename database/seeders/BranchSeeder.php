<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            ['name' => 'Makati Main',        'code' => 'MKT', 'address' => '123 Ayala Avenue, Makati City',              'phone' => '0281234567'],
            ['name' => 'Quezon City',        'code' => 'QC',  'address' => '456 Commonwealth Ave, Quezon City',          'phone' => '0287654321'],
            ['name' => 'Cebu City',          'code' => 'CEB', 'address' => '789 Osmeña Blvd, Cebu City',                'phone' => '0323456789'],
            ['name' => 'Davao City',         'code' => 'DVO', 'address' => '321 Rizal Street, Davao City',              'phone' => '0829876543'],
            ['name' => 'Angeles City',       'code' => 'AGS', 'address' => '654 Henson Street, Angeles City, Pampanga', 'phone' => '0452345678'],
            ['name' => 'Iloilo City',        'code' => 'ILO', 'address' => '987 Mabini Street, Iloilo City',            'phone' => '0338765432'],
            ['name' => 'Bacolod City',       'code' => 'BCD', 'address' => '147 Lacson Street, Bacolod City',           'phone' => '0344321098'],
            ['name' => 'Cagayan de Oro City','code' => 'CDO', 'address' => '258 Capt. Vicente Roa St, CDO City',        'phone' => '0885678901'],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}
