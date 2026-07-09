<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::pluck('id', 'code');

        $clients = [
            ['first_name' => 'Maria',   'middle_name' => 'Santos',     'last_name' => 'Dela Cruz',   'gender' => 'female', 'civil_status' => 'married', 'phone' => '09171234567', 'address' => '123 Rizal St, Brgy Poblacion',                    'branch_code' => 'MKT'],
            ['first_name' => 'Juan',    'middle_name' => 'Mercado',    'last_name' => 'Santos',      'gender' => 'male',   'civil_status' => 'married', 'phone' => '09172223334', 'address' => '456 Mabini St, Brgy San Jose',                    'branch_code' => 'MKT'],
            ['first_name' => 'Elena',   'middle_name' => 'Garcia',     'last_name' => 'Reyes',       'gender' => 'female', 'civil_status' => 'single',  'phone' => '09179876543', 'address' => '789 Luna St, Brgy Bagong Silang',                   'branch_code' => 'QC'],
            ['first_name' => 'Jose',    'middle_name' => null,         'last_name' => 'Gonzales',    'gender' => 'male',   'civil_status' => 'single',  'phone' => '09175556677', 'address' => '321 Katipunan Ave, Brgy Old Balara',                'branch_code' => 'QC'],
            ['first_name' => 'Ana',     'middle_name' => 'Lopez',      'last_name' => 'Cruz',        'gender' => 'female', 'civil_status' => 'married', 'phone' => '09173334455', 'address' => '147 Bonifacio St, Brgy Camputhaw',                  'branch_code' => 'CEB'],
            ['first_name' => 'Pedro',   'middle_name' => null,         'last_name' => 'Fernandez',   'gender' => 'male',   'civil_status' => 'widowed', 'phone' => '09176667788', 'address' => '258 Colon St, Brgy Ermita',                         'branch_code' => 'CEB'],
            ['first_name' => 'Luisa',   'middle_name' => 'Rivera',     'last_name' => 'Villanueva',  'gender' => 'female', 'civil_status' => 'married', 'phone' => '09178889990', 'address' => '369 San Pedro St, Brgy Poblacion District',        'branch_code' => 'DVO'],
            ['first_name' => 'Carlos',  'middle_name' => 'Ramirez',    'last_name' => 'Moreno',      'gender' => 'male',   'civil_status' => 'single',  'phone' => '09179990001', 'address' => '159 Torres St, Brgy Obrero',                        'branch_code' => 'DVO'],
            ['first_name' => 'Rosa',    'middle_name' => 'Cruz',       'last_name' => 'Mendoza',     'gender' => 'female', 'civil_status' => 'married', 'phone' => '09174445566', 'address' => '753 Henson St, Brgy Malabañas',                     'branch_code' => 'AGS'],
            ['first_name' => 'Antonio', 'middle_name' => 'Dizon',      'last_name' => 'Navarro',     'gender' => 'male',   'civil_status' => 'single',  'phone' => '09172225566', 'address' => '951 Sto Rosario St, Brgy San Nicolas',               'branch_code' => 'AGS'],
            ['first_name' => 'Teresa',  'middle_name' => 'Sanchez',    'last_name' => 'Castro',      'gender' => 'female', 'civil_status' => 'married', 'phone' => '09174446677', 'address' => '468 Jaro St, Brgy Arevalo',                         'branch_code' => 'ILO'],
            ['first_name' => 'Manuel',  'middle_name' => null,         'last_name' => 'Aguilar',     'gender' => 'male',   'civil_status' => 'married', 'phone' => '09175558899', 'address' => '573 Plaza Libertad, Brgy City Proper',              'branch_code' => 'ILO'],
            ['first_name' => 'Josefina','middle_name' => 'Ramos',      'last_name' => 'Gutierrez',   'gender' => 'female', 'civil_status' => 'single',  'phone' => '09179992233', 'address' => '852 Lacson St, Brgy Mandalagan',                    'branch_code' => 'BCD'],
            ['first_name' => 'Ricardo', 'middle_name' => 'Tan',        'last_name' => 'Santiago',    'gender' => 'male',   'civil_status' => 'married', 'phone' => '09171112233', 'address' => '963 Araneta St, Brgy Singcang',                     'branch_code' => 'BCD'],
            ['first_name' => 'Nenita',  'middle_name' => 'Luna',       'last_name' => 'Domingo',     'gender' => 'female', 'civil_status' => 'married', 'phone' => '09172228899', 'address' => '741 Velez St, Brgy Carmen',                         'branch_code' => 'CDO'],
            ['first_name' => 'Rolando', 'middle_name' => 'Magsino',    'last_name' => 'Tolentino',   'gender' => 'male',   'civil_status' => 'single',  'phone' => '09174447788', 'address' => '852 Cogon Market, Brgy Cogon',                      'branch_code' => 'CDO'],
            ['first_name' => 'Luzviminda','middle_name' => 'Bautista', 'last_name' => 'Mariano',     'gender' => 'female', 'civil_status' => 'married', 'phone' => '09173336699', 'address' => '147 P. Tuazon Blvd, Brgy Holy Spirit',              'branch_code' => 'QC'],
            ['first_name' => 'Gregorio', 'middle_name' => null,        'last_name' => 'Magsaysay',   'gender' => 'male',   'civil_status' => 'married', 'phone' => '09175557788', 'address' => '258 C. M. Recto Ave, Brgy Sampaloc',                'branch_code' => 'MKT'],
            ['first_name' => 'Corazon',  'middle_name' => 'Alcantara', 'last_name' => 'Villamor',    'gender' => 'female', 'civil_status' => 'widowed', 'phone' => '09178881122', 'address' => '369 Magallanes St, Brgy San Felipe',                'branch_code' => 'CEB'],
            ['first_name' => 'Felipe',   'middle_name' => 'Castillo',  'last_name' => 'Fernando',    'gender' => 'male',   'civil_status' => 'single',  'phone' => '09171118899', 'address' => '159 Quirino Ave, Brgy San Pedro, Davao City',       'branch_code' => 'DVO'],
        ];

        foreach ($clients as $i => $client) {
            $branchCode = $client['branch_code'];
            unset($client['branch_code']);

            Client::create(array_merge($client, [
                'type' => 'individual',
                'member_id' => 'MF-' . now()->format('Y') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'birth_date' => now()->subYears(rand(22, 60))->subDays(rand(1, 364)),
                'branch_id' => $branches[$branchCode],
                'status' => 'active',
            ]));
        }
    }
}
