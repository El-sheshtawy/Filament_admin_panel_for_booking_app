<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PerformanceTestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        Telescope::stopRecording();

//        $this->call([
//            RoleSeeder::class,
//            PermissionSeeder::class
//        ]);
//
//        // CREATE ADMIN USER
//        User::factory()->create([
//            'name' => 'Mohamed Elsheshtawy',
//            'email' => 'ramyalfe22@gmail.com',
//        ])->assignRole(Role::ROLE_ADMINISTRATOR);

        $this->callWith(Performance\UserSeeder::class, [
            'owners' => 1,
            'users' => 1
        ]);

        $this->callWith(Performance\CountrySeeder::class, [
            'count' => 10
        ]);
        $this->callWith(Performance\CitySeeder::class, [
            'count' => 10
        ]);
        $this->callWith(Performance\GeoobjectSeeder::class, [
            'count' => 10
        ]);
        $this->callWith(Performance\PropertySeeder::class, [
            'count' => 10
        ]);
        $this->callWith(Performance\ApartmentSeeder::class, [
            'count' => 10
        ]);

        $this->callWith(Performance\BookingSeeder::class, [
            'withRatings' => 10,
            'withoutRatings' => 10
        ]);
    }
}
