<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Buat admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@cariarena.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->command->info('✅ Admin user created: admin@cariarena.com / 12345678');

        // Buat venue owner alternatif
        User::create([
            'name' => 'Venue Owner',
            'venue_name' => 'Lapangan Futsal Merdeka',
            'email' => 'venueowner@cariarena.com',
            'password' => Hash::make('87654321'),
            'role' => 'venue',
            'phone' => '081298765432',
            'address' => 'Jl. Merdeka No. 45, Bandung',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->command->info('✅ Venue Owner created: venueowner@cariarena.com / 87654321');

        User::create([
            'name' => 'User Biasa',
            'venue_name' => 'Lapangan Futsal Merdeka',
            'email' => 'user@cariarena.com',
            'password' => Hash::make('310531'),
            'role' => 'user',
            'phone' => '085748016858',
            'address' => 'Jl. Merdeka No. 45, Bandung',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->command->info('✅ User Biasa created: user@cariarena.com / 310531');
    }
}