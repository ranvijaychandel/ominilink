<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->command->info('Seeding Database...');
        $this->call(RoleSeeder::class);
        $role = Role::where('slug', 'super-admin')->first();
        User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@omnilink.com',
            'phone' => '7007701235',
            'email_verified_at' => now(),
            'role_id' =>  $role->id,
            'password' => Hash::make('password'),
        ]);
        $this->call([
            TenantSeeder::class,
            UserSeeder::class,
            ShortUrlSeeder::class
        ]);
        $this->command->info('Database seeding completed.');
    }
}
