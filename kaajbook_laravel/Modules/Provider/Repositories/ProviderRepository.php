<?php
namespace Modules\Provider\Repositories;

use Modules\Provider\Models\Provider;

/**
 * Class ProviderRepository
 *
 * Provider CRUD Functionality.
 *
 * Provider create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  CRM
 * @package   Modules\Provider
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class ProviderRepository
{

    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function findAll()
    {
        return Provider::all();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get provider]
     *
     * @return Array
     */
    public function getAllProviders($request)
    {
        $provider = new Provider;
        $totalData = Provider::count();
        $columns = array(
            0 => 'id',
            1 => 'firstname',
            2 => 'lastname',
            3 => 'email',
            4 => 'color',
            5 => 'status'
        );
        
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $provider = Provider::where('id', 'LIKE', "%{$search}%")
                ->orWhere('firstname', 'LIKE', "%{$search}%")
                ->orWhere('lastname', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('color', 'LIKE', "%{$search}%")
                ->orWhere('status', 'LIKE', "%{$search}%");

            $totalFiltered = $provider->count();
        }

        $data = $provider->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        return $json_data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create provider]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $provider = new Provider;
        $provider->fill($input);
        if ($provider->save()) {
            // --
            // Add activities
            createUserActivity(
                Provider::MODULE_NAME,
                $provider->id,
                $request->method(),
                $provider->firstname.' '.$provider->lastname,
                $request->ip()
            );
            return true;
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update provider]
     * @param Int     $id [Row id]
     *
     * @return Boolean
     */
    public function update($request, $id)
    {
        $input = $request->all();
        $provider = Provider::findOrFail($id);
        $provider->fill($input);
        if ($provider->save()) {
            // --
            // Add activities
            createUserActivity(
                Provider::MODULE_NAME,
                $provider->id,
                $request->method(),
                $provider->firstname.' '.$provider->lastname,
                $request->ip()
            );
            return true;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for delete provider]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function delete($request, $id)
    {
        $provider = Provider::findOrFail($id);
        if ($provider->delete()) {
            // --
            // Add activities
            createUserActivity(
                Provider::MODULE_NAME,
                $provider->id,
                $request->method(),
                $provider->firstname.' '.$provider->lastname,
                $request->ip()
            );
            return true;
        } else {
            return false;
        }
    }
}
