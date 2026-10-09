<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleIds = Role::query()->whereNot('id', 1)->get()->pluck('id');
        $tenants = Tenant::query()->where('status', 'active')->get();

        foreach($tenants as $tenant) {
            for($i=0; $i<5; $i++) {
                User::create([
                    'name' => fake()->name(),
                    'email' => fake()->safeEmail(),
                    'tenant_id' => $tenant->id,
                    'phone' => fake()->numerify('8#########'),
                    'role_id' => fake()->randomElement($roleIds->toArray()),
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                ]);
            }
        }

    }
}
