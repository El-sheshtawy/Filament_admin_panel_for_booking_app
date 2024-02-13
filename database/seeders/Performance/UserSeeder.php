<?php

namespace Database\Seeders\Performance;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(int $owners = 10, int $users = 10): void
    {
        User::factory($owners)->owner()->create();

        User::factory($users)->user()->create();
    }
}
