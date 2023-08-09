<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username'=>'Owner',
            'email' => 'owner@gmail.com',
            // 'password' => '$2y$10$96EUB6HsVNFLgrtUqpBqOeR3Z9S2q2.TAnT5TyLcZ47YNMjqZYY7C',
            'password' => Hash::make('passward'),
            'usertype' => 'owner',
            'created_at' => '2023-08-08 01:42:51',
            'updated_at' => '2023-08-08 01:42:51'
        ]);

    }
}
