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
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@iuse-ippi.com',
            'password' => Hash::make('eshal070722'),
            'role' => 'admin',
        ]);

        // Create regular users
        // User::create([
        //     'name' => 'User 1',
        //     'email' => 'user1@iuse-ippi.com',
        //     'password' => Hash::make('ippi54321'),
        //     'role' => 'user',
        // ]);

        // User::create([
        //     'name' => 'User 2',
        //     'email' => 'user2@iuse-ippi.com',
        //     'password' => Hash::make('ippi54321'),
        //     'role' => 'user',
        // ]);

        // Seed items
        // $this->seedItems();
    }

    /**
     * Seed sample items.
     */
    // protected function seedItems(): void
    // {
    //     // Raw Material items
    //     \App\Models\Item::create([
    //         'code' => 'RM-001',
    //         'name' => 'Steel Plate 1mm',
    //         'category' => 'raw_material',
    //         'description' => 'Cold rolled steel plate 1mm thickness',
    //         'unit' => 'sheet',
    //     ]);

    //     \App\Models\Item::create([
    //         'code' => 'RM-002',
    //         'name' => 'Steel Plate 2mm',
    //         'category' => 'raw_material',
    //         'description' => 'Cold rolled steel plate 2mm thickness',
    //         'unit' => 'sheet',
    //     ]);

    //     \App\Models\Item::create([
    //         'code' => 'RM-003',
    //         'name' => 'Aluminum Sheet 1.5mm',
    //         'category' => 'raw_material',
    //         'description' => 'Aluminum sheet 1.5mm thickness',
    //         'unit' => 'sheet',
    //     ]);

    //     // WIP items
    //     \App\Models\Item::create([
    //         'code' => 'WIP-001',
    //         'name' => 'Bracket Semi Finish',
    //         'category' => 'wip',
    //         'description' => 'Bracket stamped but not finished',
    //         'unit' => 'pcs',
    //     ]);

    //     \App\Models\Item::create([
    //         'code' => 'WIP-002',
    //         'name' => 'Cover Semi Finish',
    //         'category' => 'wip',
    //         'description' => 'Cover stamped but not finished',
    //         'unit' => 'pcs',
    //     ]);

    //     \App\Models\Item::create([
    //         'code' => 'WIP-003',
    //         'name' => 'Frame Semi Finish',
    //         'category' => 'wip',
    //         'description' => 'Frame stamped but not finished',
    //         'unit' => 'pcs',
    //     ]);

    //     // Finish Part items
    //     \App\Models\Item::create([
    //         'code' => 'FG-001',
    //         'name' => 'Bracket Complete',
    //         'category' => 'finish_part',
    //         'description' => 'Finished bracket ready for shipment',
    //         'unit' => 'pcs',
    //     ]);

    //     \App\Models\Item::create([
    //         'code' => 'FG-002',
    //         'name' => 'Cover Complete',
    //         'category' => 'finish_part',
    //         'description' => 'Finished cover ready for shipment',
    //         'unit' => 'pcs',
    //     ]);

    //     \App\Models\Item::create([
    //         'code' => 'FG-003',
    //         'name' => 'Frame Complete',
    //         'category' => 'finish_part',
    //         'description' => 'Finished frame ready for shipment',
    //         'unit' => 'pcs',
    //     ]);
    // }
}
