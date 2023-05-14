<?php

namespace Modules\Menu\Repositories;

use Modules\Helper\Repositories\HelperRepository;
use Modules\Menu\Models\Menu;
use Modules\Setting\Models\Setting;
use Modules\User\Models\Department\DepartmentRoleMenu;

/**
 * Class MenuRepository
 *
 * Create, Update, delete operation for Menu.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Menu
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
*/
class MenuRepository
{
    protected $helperRepo;

    /**
     * Create a new repository instance.
     *
     * @param HelperRepository $helperRepo [Object]
     *
     * @return void
     */
    public function __construct(HelperRepository $helperRepo)
    {
        $this->helperRepo = $helperRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Array
     */
    public function findAll()
    {
        $menus['active_menu'] = $this->buildMenu(
            Menu::where('status', 1)
                ->orderBy('order')
                ->get()
        );
        $menus['inactive_menu'] = $this->buildMenu(
            Menu::where('status', 0)
                ->orderBy('order', 'created_at')
                ->get()
        );

        return $menus;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Array $all_active_menu   [All active menu array]
     * @param Array $all_inactive_menu [All inactive menu array]
     *
     * @return Boolean
     */
    public function create($all_active_menu, $all_inactive_menu)
    {
        // --
        // Active menu
        foreach ($all_active_menu as $key => $value) {
            $jObj = json_decode($value);

            if (!empty($jObj)) {
                foreach ($jObj as $jKey => $jValue) {
                    // --
                    // Update menu
                    $menu = Menu::find($jValue->id);
                    $menu->order = $jKey;
                    $menu->status = 1;
                    $menu->parent_menu_id = 0;
                    $menu->save();

                    // --
                    // Update child menu
                    if (!empty($jValue->children)) {
                        foreach ($jValue->children as $child_sort => $sub_menu) {
                            $menu = Menu::find($sub_menu->id);
                            $menu->order = $child_sort;
                            $menu->status = 1;
                            $menu->parent_menu_id = $jValue->id;
                            $menu->save();

                            // --
                            // Update sub child menu
                            if (!empty($sub_menu->children)) {
                                foreach ($sub_menu->children as $sub_child_sort => $sub_child_menu) {
                                    $menu = Menu::find($sub_child_menu->id);
                                    $menu->order = $sub_child_sort;
                                    $menu->status = 1;
                                    $menu->parent_menu_id = $sub_menu->id;
                                    $menu->save();
                                }
                            }
                        }
                    }
                }
            }
        }

        // --
        // Inactive menu
        foreach ($all_inactive_menu as $key => $value) {
            $jObj = json_decode($value);

            if (!empty($jObj)) {
                foreach ($jObj as $jKey => $jValue) {
                    // --
                    // Update menu
                    $menu = Menu::find($jValue->id);
                    $menu->order = $jKey;
                    $menu->status = 0;
                    $menu->parent_menu_id = 0;
                    $menu->save();

                    // --
                    // Update child menu
                    if (!empty($jValue->children)) {
                        foreach ($jValue->children as $child_sort => $sub_menu) {
                            $menu = Menu::find($sub_menu->id);
                            $menu->order = $child_sort;
                            $menu->status = 0;
                            $menu->parent_menu_id = $jValue->id;
                            $menu->save();

                            // --
                            // Update sub child menu
                            if (!empty($sub_menu->children)) {
                                foreach ($sub_menu->children as $sub_child_sort => $sub_child_menu) {
                                    $menu = Menu::find($sub_child_menu->id);
                                    $menu->order = $sub_child_sort;
                                    $menu->status = 0;
                                    $menu->parent_menu_id = $sub_menu->id;
                                    $menu->save();
                                }
                            }
                        }
                    }
                }
            }
        }
    
        return true;
    }

    /**
     * Get side bar menu.
     *
     * @param Request $request [Request for get menu]
     *
     * @return Response
     */
    public function getSidebarMenu($request)
    {
        $menus = [];
        $menuItems = [];

        // --
        // Get departments/roles
        if (!empty(\Auth::user())) {
            $roles = \Auth::user()->roles()->get()->pluck('id');
            $departments = \Auth::user()->departments()->get()->pluck('id');

            $department_role_menu_table = config('core.acl.department_role_menu_table');
            $menu_table = config('core.acl.menu_table');

            $menus = DepartmentRoleMenu::select($menu_table.'.*')
                ->Join($menu_table, $department_role_menu_table . '.menu_id', '=', $menu_table.'.id')
                ->whereIn($department_role_menu_table. '.department_id', $departments)
                ->whereIn($department_role_menu_table. '.role_id', $roles)
                ->where($menu_table. '.status', 1)
                ->where($menu_table. '.module', '!=', 'settings')
                ->where($menu_table. '.module', '!=', 'utilities')
                ->orderBy($menu_table.'.order')
                ->distinct($menu_table.'.id')
                ->get();

            if ($menus) {
                foreach ($menus as $key => $value) {
                    $menus[$key]->class = '';
                    $menus[$key]->badge = '';
                    $menus[$key]->badgeClass = '';
                    $menus[$key]->isExternalLink = false;
                    $menus[$key]->submenu = [];
                }
            }

            $menuItems['loginUser'] = $this->getRolesAbilities(\Auth::user());
            $menuItems['menus'] = $this->buildSidebarMenu($menus);
        }

        return $menuItems;
    }

    /**
     * Build menu.
     *
     * @param Array $elements [Elements array]
     * @param Int   $parentId [Parent id]
     *
     * @return Array
     */
    public function buildMenu($elements, $parentId = 0)
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['parent_menu_id'] == $parentId) {
                $children = self::buildMenu($elements, $element['id']);
                
                if ($children) {
                    $element['children'] = $children;
                }
                
                $branch[] = $element;
            }
        }

        return $branch;
    }

    /**
     * Build sidebar menu.
     *
     * @param Array $elements [Elements array]
     * @param Int   $parentId [Parent id]
     *
     * @return Array
     */
    public function buildSidebarMenu($elements, $parentId = 0)
    {
        $user = \Auth::user();
        $_user = \Modules\User\Models\User\User::findOrFail($user->id);
        $branch = [];
        foreach ($elements as $element) {
            // --
            // Check admin role
            if ($element->module == 'admin' && !$_user->hasRole('admin')) {
                continue;
            }

            //--
            // Check view permissions
            if (!$user->allow($element->label)) {
                continue;
            }

            if ($element->parent_menu_id == $parentId) {
                $submenu = self::buildSidebarMenu($elements, $element->id);
                
                if ($submenu) {
                    $element->class = 'has-sub';
                    $element->submenu = $submenu;
                }
                
                $branch[] = $element;
            }
        }

        return $branch;
    }

    /**
     * Get roles abilities.
     *
     * @param Object $user [User object]
     *
     * @return Array
     */
    public function getRolesAbilities($user)
    {
        $abilities = [];
        $permissions = [];

        // --
        // Manage roles
        $user['settings'] = Setting::first()->makeHidden(['smtp_password']);
        $user['currency'] = Setting::Currency()->first();
        $user['roles'] = $user->roles()->get()->pluck('slug')->toArray();

        // --
        // Manage roles permissions
        $menus = $this->helperRepo->getRoleDepartmentMenu();
        if (!empty($menus)) {
            foreach ($menus as $key => $value) {
                if (!isset($abilities[$value->slug])) {
                    $abilities[$value->slug] = [];
                }

                // --
                // Check view permission
                if ($value->menu_id == $value->view) {
                    array_push($permissions, str_replace(" ", "", strtolower($value->label). '_view'));
                }

                if ($value->menu_id == $value->created) {
                    array_push($permissions, str_replace(" ", "", strtolower($value->label). '_create'));
                }

                if ($value->menu_id == $value->edited) {
                    array_push($permissions, str_replace(" ", "", strtolower($value->label). '_edit'));
                }

                if ($value->menu_id == $value->deleted) {
                    array_push($permissions, str_replace(" ", "", strtolower($value->label). '_delete'));
                }
            }
        }

        // --
		// Super admin permission
		if($user->is_super_admin) {
			array_push($permissions, 'super_admin');
		}

        // --
        // Add user assign permissions
        // if ($user->permission == 'all') {
        //     // array_push($permissions, 'users_view');
        //     array_push($permissions, 'users_edit');
        //     array_push($permissions, 'users_delete');
        // } else {
        //     foreach ((array)json_decode($user->permission) as $key => $value) {
        //         foreach ($value as $key => $value1) {
        //             array_push($permissions, 'users_'.$value1);
        //         }
        //     }
        // }

        $permissions = array_unique($permissions);

        if (!empty($abilities)) {
            foreach ($abilities as $key => $value) {
                foreach ($permissions as $pkey => $pvalue) {
                    array_push($abilities[$key], $pvalue);
                }
            }
        }

        $user['permissions'] = $abilities;

        return $user;
    }
}
