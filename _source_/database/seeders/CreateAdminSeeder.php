<?php

namespace Database\Seeders;

use App\DataTypes\AdminRole;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminUsers = [
            [
                'name'  => 'System Admin',
                'phone' => '01920409252',
                'role'  => AdminRole::admin()->value(),
                'image' => 'https://i.pravatar.cc/150?img=53',
                'password' => Hash::make('vivasoft',[ 'rounds' => 8 ]),
                'active' => true,
            ],
            [
                'name'  => 'Samiul Amin',
                'phone' => '01817536215',
                'role'  => AdminRole::manager()->value(),
                'image' => 'https://i.pravatar.cc/150?img=53',
                'password' => Hash::make('vivasoft',[ 'rounds' => 8 ]),
                'active' => true,
            ],
        ];

        foreach ( $adminUsers as $adminUser ) {
            if ( !($admin = Admin::wherePhone($adminUser['phone'])->first()) ) {
                Admin::create($adminUser);
            }
        }
    }
}
