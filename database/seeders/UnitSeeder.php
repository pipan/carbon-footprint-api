<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    public function run()
    {
        DB::table('units')->insert([
            [
                'id' => 1,
                'name' => 'Length'
            ],
            [
                'id' => 2,
                'name' => 'Weight'
            ],
            [
                'id' => 3,
                'name' => 'Volume'
            ],
            [
                'id' => 4,
                'name' => 'Density'
            ],
            [
                'id' => 5,
                'name' => 'Power'
            ],
            [
                'id' => 6,
                'name' => 'Time'
            ],
            [
                'id' => 8,
                'name' => 'Value'
            ],
            [
                'id' => 9,
                'name' => 'Carbon'
            ]
        ]);

        DB::table('unit_scales')->insert([
            [
                'label' => 'mm',
                'unit_id' => 1,
                'priority' => 1,
                'multiplier' => 1,
                'devider' => 1
            ],
            [
                'label' => 'cm',
                'unit_id' => 1,
                'priority' => 1,
                'multiplier' => 10,
                'devider' => 1
            ],
            [
                'label' => 'm',
                'unit_id' => 1,
                'priority' => 1,
                'multiplier' => 1000,
                'devider' => 1
            ],
            [
                'label' => 'km',
                'unit_id' => 1,
                'priority' => 1,
                'multiplier' => 1000000,
                'devider' => 1
            ],
            [
                'label' => 'mg',
                'unit_id' => 2,
                'priority' => 1,
                'multiplier' => 1,
                'devider' => 1
            ],
            [
                'label' => 'g',
                'unit_id' => 2,
                'priority' => 1,
                'multiplier' => 1000,
                'devider' => 1
            ],
            [
                'label' => 'kg',
                'unit_id' => 2,
                'priority' => 1,
                'multiplier' => 1000000,
                'devider' => 1
            ],
            [
                'label' => 't',
                'unit_id' => 2,
                'priority' => 1,
                'multiplier' => 1000000000,
                'devider' => 1
            ],
            [
                'label' => 'mm3',
                'unit_id' => 3,
                'priority' => 1,
                'multiplier' => 1,
                'devider' => 1
            ],
            [
                'label' => 'ml',
                'unit_id' => 3,
                'priority' => 10,
                'multiplier' => 1000,
                'devider' => 1
            ],
            [
                'label' => 'cm3',
                'unit_id' => 3,
                'priority' => 1,
                'multiplier' => 1000,
                'devider' => 1
            ],
            [
                'label' => 'dl',
                'unit_id' => 3,
                'priority' => 10,
                'multiplier' => 100000,
                'devider' => 1
            ],
            [
                'label' => 'l',
                'unit_id' => 3,
                'priority' => 10,
                'multiplier' => 1000000,
                'devider' => 1
            ],
            [
                'label' => 'dm3',
                'unit_id' => 3,
                'priority' => 1,
                'multiplier' => 1000000,
                'devider' => 1
            ],
            [
                'label' => 'm3',
                'unit_id' => 3,
                'priority' => 10,
                'multiplier' => 1000000000,
                'devider' => 1
            ],
            [
                'label' => 'kl',
                'unit_id' => 3,
                'priority' => 1,
                'multiplier' => 1000000000,
                'devider' => 1
            ],
            [
                'label' => 'mg/mm3',
                'unit_id' => 4,
                'priority' => 1,
                'multiplier' => 1,
                'devider' => 1
            ],
            [
                'label' => 'g/cm3',
                'unit_id' => 4,
                'priority' => 1,
                'multiplier' => 1,
                'devider' => 1
            ],
            [
                'label' => 'kg/m3',
                'unit_id' => 4,
                'priority' => 1,
                'multiplier' => 1000,
                'devider' => 1
            ],
            [
                'label' => 'mW',
                'unit_id' => 5,
                'priority' => 1,
                'multiplier' => 1,
                'devider' => 1
            ],
            [
                'label' => 'W',
                'unit_id' => 5,
                'priority' => 1,
                'multiplier' => 1000,
                'devider' => 1
            ],
            [
                'label' => 'kW',
                'unit_id' => 5,
                'priority' => 1,
                'multiplier' => 1000000,
                'devider' => 1
            ],
            [
                'label' => 'MW',
                'unit_id' => 5,
                'priority' => 1,
                'multiplier' => 1000000000,
                'devider' => 1
            ],
            [
                'label' => 's',
                'unit_id' => 6,
                'priority' => 1,
                'multiplier' => 1,
                'devider' => 1
            ],
            [
                'label' => 'm',
                'unit_id' => 6,
                'priority' => 1,
                'multiplier' => 60,
                'devider' => 1
            ],
            [
                'label' => 'h',
                'unit_id' => 6,
                'priority' => 1,
                'multiplier' => 3600,
                'devider' => 1
            ],
            [
                'label' => 'd',
                'unit_id' => 6,
                'priority' => 1,
                'multiplier' => 86400,
                'devider' => 1
            ],
            [
                'label' => '',
                'unit_id' => 8,
                'priority' => 1,
                'multiplier' => 1,
                'devider' => 1
            ],
            [
                'label' => 'mg_co2',
                'unit_id' => 9,
                'priority' => 1,
                'multiplier' => 1,
                'devider' => 1
            ],
            [
                'label' => 'g_co2',
                'unit_id' => 9,
                'priority' => 1,
                'multiplier' => 1000,
                'devider' => 1
            ],
            [
                'label' => 'kg_co2',
                'unit_id' => 9,
                'priority' => 1,
                'multiplier' => 1000000,
                'devider' => 1
            ],
            [
                'label' => 't_co2',
                'unit_id' => 9,
                'priority' => 1,
                'multiplier' => 1000000000,
                'devider' => 1
            ],
        ]);
    }
}
