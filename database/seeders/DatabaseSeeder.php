<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'username' => 'test-user',
            'ss_number' => '123456789',
        ]);

        $namesAndEmailsWithRole = [
            ['name' => 'Welcoming Wendy', 'email' => 'host@example.com'],
            ['name' => 'Espresso Eddie', 'email' => 'barista@example.com'],
            ['name' => 'Cha-Ching Charlie', 'email' => 'cashier@example.com'],
            ['name' => 'Gourmet Gary', 'email' => 'chef@example.com'],
            ['name' => 'Prep-Master Pete', 'email' => 'cook@example.com'],
            ['name' => 'Boss Betty', 'email' => 'manager@example.com'],
        ];

        collect($namesAndEmailsWithRole)->each(function ($nameAndEmail) {
            ['name' => $name, 'email' => $email] = $nameAndEmail;

            User::factory()->create([
                'name' => $name,
                'email' => $email,
            ]);
        });

        $this->call([
            MenuItemSeeder::class,
            SupplierSeeder::class,
        ]);
    }
}
