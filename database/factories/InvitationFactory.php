<?php

namespace Database\Factories;

use App\Models\Invitation;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invitation>
 */
class InvitationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomUser = User::whereNot('role_id', 1)->inRandomOrder()->first();
        return [
            'tenant_id' => $randomUser->tenant_id,
            'invited_to' => $randomUser->tenant_id,

        ];
    }
}
