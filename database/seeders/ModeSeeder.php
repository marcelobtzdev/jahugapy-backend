<?php

namespace Database\Seeders;

use App\Models\Mode;
use Illuminate\Database\Seeder;

class ModeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Mode::firstOrCreate(
            [
                'id' => 1
            ],
            [
                'name' => 'trios',
                'quantity' => 3
            ]
        );
    }
}
