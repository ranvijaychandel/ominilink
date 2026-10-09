<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tenant;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating Tenants...');
        $tenants = [
            [
                'name' => 'Orbit Tech',
                'email' => 'admin@orbittech.in',
                'phone' => fake()->optional()->numerify('7#########'),
                'website' => 'https:://orbittech.in'
            ],
            [
                'name' => 'Indsys Tech',
                'email' => 'admin@indsys.in',
                'phone' => fake()->optional()->numerify('7#########'),
                'website' => 'https:://indsys.in'
            ]
        ];
        foreach($tenants as $tenant) {
            Tenant::create($tenant);
        }
        $this->command->info('Two tenant created successfully!');
    }
}
