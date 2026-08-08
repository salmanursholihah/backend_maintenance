<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
      
 $this->call([
            UserSeeder::class,
            ServiceSeeder::class,
            ComponentSeeder::class,
            MaintenanceLocationSeeder::class,
            BookingSeeder::class,
        ]);
<<<<<<< HEAD
        $this->call([
            MaintenanceExtraSeeder::class,
        ]);
        $this->call([
            NotificationSeeder::class,
        ]);
        $this->call([
            HistoryBookingSeeder::class,
        ]);

=======
>>>>>>> 69c043352d21f709b4522a48431f32db9f90aa90
    }
}
