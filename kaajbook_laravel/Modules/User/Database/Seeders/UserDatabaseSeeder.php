<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Modules\Menu\Models\Menu;
use Modules\User\Models\Role\Role;
use Modules\User\Models\Department\Department;
use Modules\User\Models\Department\DepartmentRoleMenu;
use Modules\User\Models\Department\DepartmentRoleUser;
use Modules\User\Models\User\User;

use DB;

/**
 * Class UserDatabaseSeeder
 *
 * The Migrations is Defined for User Database Seeder.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\User
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // --
        // Roles table.
        Role::whereIn('id', [1, 2, 3, 4, 5, 6])->delete();
        $roles = [
            [
                'id' => 1,
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'User has access to all system functionality.',
                'status' => 1
            ],
            [
                'id' => 2,
                'name' => 'Staff',
                'slug' => 'staff',
                'description' => 'Staff role.',
                'status' => 1
            ],
            [
                'id' => 3,
                'name' => 'Client',
                'slug' => 'client',
                'description' => 'Client role.',
                'status' => 1
            ],
            [
                'id' => 4,
                'name' => 'Project Manager',
                'slug' => 'project_manager',
                'description' => 'Project Manager role.',
                'status' => 1
            ],
            [
                'id' => 5,
                'name' => 'HR Manager',
                'slug' => 'hr_manager',
                'description' => 'HR Manager role.',
                'status' => 1
            ],
            [
                'id' => 6,
                'name' => 'Sales Manager',
                'slug' => 'sales_manager',
                'description' => 'Sales Manager role.',
                'status' => 1
            ]
        ];
        Role::insert($roles);

        // --
        // Departments table.
        // $department = Department::find(1);
        // if ($department) {
        //     $department->forceDelete();
        // }
        $department = new Department();
        $department->id = 1;
        $department->name = 'Administration';
        $department->save();
        $department->roles()->sync([1, 2, 3]);

        $department = new Department();
        $department->id = 2;
        $department->name = 'Project';
        $department->save();
        $department->roles()->sync([4]);

        $department = new Department();
        $department->id = 3;
        $department->name = 'HR';
        $department->save();
        $department->roles()->sync([5]);

        $department = new Department();
        $department->id = 4;
        $department->name = 'Sales';
        $department->save();
        $department->roles()->sync([6]);

        // --
        // Role department menu tables.
        $menus = Menu::get();
        if (!empty($menus)) {
            foreach ($menus as $key => $value) {
                $departmentRoleMenu = new DepartmentRoleMenu();
                $departmentRoleMenu->department_id = 1;
                $departmentRoleMenu->role_id = 1;
                $departmentRoleMenu->menu_id = $value->id;
                $departmentRoleMenu->view = $value->id;
                $departmentRoleMenu->created = $value->id;
                $departmentRoleMenu->edited = $value->id;
                $departmentRoleMenu->deleted = $value->id;
                $departmentRoleMenu->save();

                $departmentRoleMenu1 = new DepartmentRoleMenu();
                $departmentRoleMenu1->department_id = 1;
                $departmentRoleMenu1->role_id = 2;
                $departmentRoleMenu1->menu_id = $value->id;
                $departmentRoleMenu1->view = $value->id;
                $departmentRoleMenu1->created = 0;
                $departmentRoleMenu1->edited = 0;
                $departmentRoleMenu1->deleted = 0;
                $departmentRoleMenu1->save();

                $departmentRoleMenu2 = new DepartmentRoleMenu();
                $departmentRoleMenu2->department_id = 1;
                $departmentRoleMenu2->role_id = 3;
                $departmentRoleMenu2->menu_id = $value->id;
                $departmentRoleMenu2->view = $value->id;
                $departmentRoleMenu2->created = 0;
                $departmentRoleMenu2->edited = 0;
                $departmentRoleMenu2->deleted = 0;
                $departmentRoleMenu2->save();

                $departmentRoleMenu3 = new DepartmentRoleMenu();
                $departmentRoleMenu3->department_id = 2;
                $departmentRoleMenu3->role_id = 4;
                $departmentRoleMenu3->menu_id = $value->id;
                $departmentRoleMenu3->view = $value->id;
                $departmentRoleMenu3->created = 0;
                $departmentRoleMenu3->edited = 0;
                $departmentRoleMenu3->deleted = 0;
                $departmentRoleMenu3->save();

                $departmentRoleMenu3 = new DepartmentRoleMenu();
                $departmentRoleMenu3->department_id = 3;
                $departmentRoleMenu3->role_id = 5;
                $departmentRoleMenu3->menu_id = $value->id;
                $departmentRoleMenu3->view = $value->id;
                $departmentRoleMenu3->created = 0;
                $departmentRoleMenu3->edited = 0;
                $departmentRoleMenu3->deleted = 0;
                $departmentRoleMenu3->save();

                $departmentRoleMenu3 = new DepartmentRoleMenu();
                $departmentRoleMenu3->department_id = 4;
                $departmentRoleMenu3->role_id = 6;
                $departmentRoleMenu3->menu_id = $value->id;
                $departmentRoleMenu3->view = $value->id;
                $departmentRoleMenu3->created = 0;
                $departmentRoleMenu3->edited = 0;
                $departmentRoleMenu3->deleted = 0;
                $departmentRoleMenu3->save();
            }
        }

        // --
        // User table.
        // $user = User::find(1);
        // if ($user) {
        //     $user->forceDelete();
        // }
        // $user = new User();
        // $user->id = 1;
        // $user->user_generated_id = "USR0001";
        // $user->firstname = 'Vipul';
        // $user->lastname = 'Patel';
        // $user->username = 'vipspatel';
        // $user->email = 'admin@gmail.com';
        // $user->password = 'Admin123!';
        // $user->is_super_admin = 1;
        // $user->is_active = 1;
        // $user->email_verified = 1;
        // $user->emp_id = 'EMP0001';
        // $user->permission = 'all';

        // if ($user->save()) {
        //     $departmentRoleUsers1 = new DepartmentRoleUser();
        //     $departmentRoleUsers1->department_id = 1;
        //     $departmentRoleUsers1->role_id = 1;
        //     $departmentRoleUsers1->user_id = $user->id;
        //     $departmentRoleUsers1->save();
        // }
    }
}
