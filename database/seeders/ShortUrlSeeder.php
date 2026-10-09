<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ShortUrl;
use Illuminate\Support\Str;

class ShortUrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereNot('role_id', 1)->inRandomOrder()->limit(5)->get();
        $data = null;
        $now = now();
        $urls = ['https://facebook.com', 'https://sembark.com', 'https://laravel.com', 'https://youtube.com', 'https://www.instagram.com'];
        foreach($users as $key => $user) {
            do{
                $code = Str::random(6);
            } while(
                ShortUrl::where('short_url', $code)->exists()
            );
            $data[]=[
                'tenant_id' => $user->tenant_id,
                'created_by' => $user->id,
                'short_url' => $code,
                'original_url' => $urls[$key] ?? $urls[0]
            ];
        }
        ShortUrl::insert($data);

    }
}
