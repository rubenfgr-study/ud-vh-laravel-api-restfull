<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'admin']);

        $create_post = Permission::create(['name' => 'create posts']);
        $edit_post = Permission::create(['name' => 'edit posts']);
        $delete_post = Permission::create(['name' => 'delete posts']);

        $role->syncPermissions([$create_post, $edit_post, $delete_post]);
    }
}
