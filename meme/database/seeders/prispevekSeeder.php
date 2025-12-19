<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Prispevek;

class prispevekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Seeder User',
            'email' => 'seeder@example.com',
        ]);

        $posts = [
            ['fotka' => 'prispevky/uprimne.jpg', 'lajky' => 15],
            ['fotka' => 'prispevky/opice.jpg', 'lajky' => 2],
            ['fotka' => 'prispevky/shrek.jpg', 'lajky' => 5],
        ];

        foreach ($posts as $p) {
            Prispevek::create([
                'fotka' => $p['fotka'],
                'id_uzivatel' => $user->id,
                'lajky' => $p['lajky'] ?? 0,
            ]);
        }
    }
}
