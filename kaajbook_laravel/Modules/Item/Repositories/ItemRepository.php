<?php

namespace Modules\Item\Repositories;

use Modules\Item\Models\Item;
use Modules\Setting\Models\Setting;

/**
 * Class ItemRepository
 *
 * Item CRUD functionality
 *
 * Item create, update, delete and view
 *
 * PHP version 7.1.3
 *
 * @category  Sales
 * @package   Modules\Items
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class ItemRepository
{
    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function findAll()
    {
        return Item::with('taxes')->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get items]
     *
     * @return Object
     */
    public function getAllItems($request)
    {
        $items_table = config('core.acl.items_table');

        $items = Item::with(['taxes']);

        $columns = array(
            0 => $items_table.'.id',
            1 => $items_table.'.name',
            2 => $items_table.'.description',
            3 => $items_table.'.id',
            4 => $items_table.'.unit',
            5 => $items_table.'.price'
        );

        $input = $request->input();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalData = $items->count();
        $totalFiltered = $totalData;

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $items = $items->where(
                function ($query) use ($search, $items_table) {
                    $query->where($items_table.'.name', 'LIKE', "%{$search}%")
                        ->orWhere($items_table.'.description', 'LIKE', "%{$search}%")
                        ->orWhere($items_table.'.price', 'LIKE', "%{$search}%")
                        ->orWhere($items_table.'.unit', 'LIKE', "%{$search}%");
                }
            );

            $totalFiltered = $items->count();
        }

        $data = $items->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $settings = Setting::Currency()->first();

        return array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data,
            "settings"        => $settings
        );
    }

    /**
     * Display a item of the resource.
     *
     * @param Int $id [Row id]
     *
     * @return Object
     */
    public function findById($id)
    {
        return Item::with('taxes')->findOrFail($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create item]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $item = new Item;
        $input = $request->all();
        if ($item->fill($input)->save()) {
            if (isset($input['taxes'])) {
                $item->taxes()->sync($input['taxes']);
            }
            // --
            // Add activities
            createUserActivity(
                Item::MODULE_NAME,
                $item->id,
                $request->method(),
                $item->name,
                $request->ip()
            );
            return true;
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update item]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function update($request, $id)
    {
        $input = $request->all();
        $item = Item::findOrFail($id);
        if ($item->fill($input)->save()) {
            if (isset($input['taxes'])) {
                $item->taxes()->sync($input['taxes']);
            }
            // --
            // Add activities
            createUserActivity(
                Item::MODULE_NAME,
                $item->id,
                $request->method(),
                $item->name,
                $request->ip()
            );
            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for delete project]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function delete($request, $id)
    {
        $item = Item::findOrFail($id);
        $item->taxes()->detach();

        if ($item->delete()) {
            // --
            // Add activities
            createUserActivity(
                Item::MODULE_NAME,
                $item->id,
                $request->method(),
                $item->name,
                $request->ip()
            );
            return true;
        }
        return false;
    }
}
