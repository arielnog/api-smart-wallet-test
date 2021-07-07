<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'lojista',
                'guard_name' => 'web',
                'permission' => '2'
            ],
            [
                'name' => 'consumidor',
                'guard_name' => 'web',
                'permission' => ['1', '2']
            ]
        ];

        foreach ($roles as $value) {
            $role = Role::create([
                'name' => $value['name'],
                'guard_name' => $value['guard_name']
            ]);
            $role->save();
            $role->syncPermissions($value['permission']);
        }
    }
}
