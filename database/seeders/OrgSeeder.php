<?php

namespace Database\Seeders;

use App\Models\Org;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orgs = [
            [
                'name' => 'Acme Corporation',
                'slug' => 'acme-corp',
                'timezone' => 'America/New_York',
            ],
            [
                'name' => 'TechStart Solutions',
                'slug' => 'techstart',
                'timezone' => 'America/Los_Angeles',
            ],
            [
                'name' => 'Global Dynamics',
                'slug' => 'global-dynamics',
                'timezone' => 'Europe/London',
            ],
            [
                'name' => 'Innovation Labs',
                'slug' => 'innovation-labs',
                'timezone' => 'Australia/Sydney',
            ],
            [
                'name' => 'Future Systems',
                'slug' => 'future-systems',
                'timezone' => 'Asia/Tokyo',
            ],
        ];

        foreach ($orgs as $orgData) {
            Org::create($orgData);
        }
    }
}
