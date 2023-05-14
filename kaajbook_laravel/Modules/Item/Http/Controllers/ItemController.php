<?php

namespace Modules\Item\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Helper\Helpers\AdminHelper;
use Modules\Item\Http\Requests\CreateItemRequest;
use Modules\Item\Http\Requests\UpdateItemRequest;
use Modules\Item\Repositories\ItemRepository;

/**
 * Class ItemController
 *
 * Item CRUD functionality.
 *
 * Item create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Sales
 * @package   Modules\Item
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class ItemController extends Controller
{
    protected $itemRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param ItemRepository $item [Object]
     *
     * @return void
     */
    public function __construct(ItemRepository $item)
    {
        $this->itemRepo = $item;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->itemRepo->findAll();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get items]
     *
     * @return Response
     */
    public function getAllItems(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(115, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->itemRepo->getAllItems($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateItemRequest $request [Params for create item]
     *
     * @return Response
     */
    public function store(CreateItemRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(115, 'created')) {
            return response()->json("Access denied", 403);
        }

        if ($this->itemRepo->create($request)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Show the specified resource.
     *
     * @param int $id [Row id]
     *
     * @return Response
     */
    public function show($id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(115, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->itemRepo->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateItemRequest $request [Params for update item]
     * @param int               $id      [Row id]
     *
     * @return Response
     */
    public function update(UpdateItemRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(115, 'edited')) {
            return response()->json("Access denied", 403);
        }

        if ($this->itemRepo->update($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Params for destroy item]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(115, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        if ($this->itemRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }
}
