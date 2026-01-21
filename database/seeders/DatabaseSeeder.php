<?php

namespace Database\Seeders;

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

        // Create regular user
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->command->info('✅ Test User created: test@example.com / password');

        // Create venue owner
        User::create([
            'name' => 'Venue Owner',
            'venue_name' => 'GOR Bulutangkis Sentral',
            'email' => 'venue@cariarena.com',
            'password' => Hash::make('password123'),
            'role' => 'venue',
            'phone' => '081234567890',
            'address' => 'Jl. Contoh Alamat Venue No. 123, Jakarta',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->command->info('✅ Venue Owner created: venue@cariarena.com / password123');

        // Call Admin seeder
        $this->call([
            AdminSeeder::class,
        ]);

        $this->command->info('🎉 All seeders completed successfully!');
        $this->command->info('========================================');
        $this->command->info('LOGIN CREDENTIALS:');
        $this->command->info('Admin: admin@cariarena.com / 12345678');
        $this->command->info('Venue: venue@cariarena.com / password123');
        $this->command->info('User: test@example.com / password');
        $this->command->info('========================================');
    }
}