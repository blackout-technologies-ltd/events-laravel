<?php

namespace Database\Seeders;

use App\Models\Org;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orgs = Org::all();
        
        // Create 20 users distributed across organizations
        for ($i = 0; $i < 20; $i++) {
            User::factory()->create([
                'org_id' => $orgs->random()->id,
            ]);
        }
    }
}
