<?php

namespace Modules\Tax\Repositories;

use Modules\Tax\Models\Tax;

/**
 * Class TaxRepository
 *
 * Tax CRUD functionality
 *
 * Tax create, update, delete and view
 *
 * PHP version 7.1.3
 *
 * @category  Sales
 * @package   Modules\Tax
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class TaxRepository
{
    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function findAll()
    {
        return Tax::All();
    }

    /**
     * Display a tax of the resource.
     *
     * @param Int $id [Row id]
     *
     * @return Object
     */
    public function findById($id)
    {
        return Tax::findOrFail($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create tax]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $tax = new Tax;
        if ($tax->fill($input)->save()) {
            // --
            // Add activities
            createUserActivity(
                Tax::MODULE_NAME,
                $tax->id,
                $request->method(),
                $tax->tax_name,
                $request->ip()
            );
            return true;
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update tax]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function update($request, $id)
    {
        $input = $request->all();
        $tax = Tax::findOrFail($id);
        if ($tax->fill($input)->save()) {
            // --
            // Add activities
            createUserActivity(
                Tax::MODULE_NAME,
                $tax->id,
                $request->method(),
                $tax->tax_name,
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
     * @param Request $request [Request for delete tax]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function delete($request, $id)
    {
        $tax = Tax::findOrFail($id);

        if ($tax->delete()) {
            // --
            // Add activities
            createUserActivity(
                Tax::MODULE_NAME,
                $tax->id,
                $request->method(),
                $tax->tax_name,
                $request->ip()
            );
            return true;
        }
        return false;
    }
}
