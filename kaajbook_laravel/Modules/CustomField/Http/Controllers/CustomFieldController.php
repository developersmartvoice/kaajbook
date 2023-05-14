<?php

namespace Modules\CustomField\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\CustomField\Repositories\CustomFieldRepository;
use Modules\CustomField\Http\Requests\CreateCustomFieldRequest;
use Modules\CustomField\Http\Requests\UpdateCustomFieldRequest;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class CustomFieldController
 *
 * CustomField CRUD functionality.
 *
 * CustomField create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\CustomField
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CustomFieldController extends Controller
{
    protected $customfieldRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param CustomFieldRepository $CustomField [Object]
     *
     * @return void
     */
    public function __construct(CustomFieldRepository $CustomField)
    {
        $this->customfieldRepo = $CustomField;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->customfieldRepo->findAll();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Params for get custom field]
     *
     * @return Response
     */
    public function getAllCustomFields(Request $request)
    {
        return $this->customfieldRepo->getAllCustomFields($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateCustomFieldRequest $request [Params for create custom field]
     *
     * @return Response
     */
    public function store(CreateCustomFieldRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(28, 'created')) {
            return response()->json("Access denied", 403);
        }

        if ($this->customfieldRepo->create($request)) {
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
        if (!AdminHelper::can_action(28, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->customfieldRepo->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCustomFieldRequest $request [Params for update custom field]
     * @param int                      $id      [Row id]
     *
     * @return Response
     */
    public function update(UpdateCustomFieldRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(28, 'edited')) {
            return response()->json("Access denied", 403);
        }

        if ($this->customfieldRepo->update($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Params for destroy custom field]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(28, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        if ($this->customfieldRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }

    /**
     * Change custom field status.
     *
     * @param Request $request [Params for change custom field status]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function changeCustomFieldStatus(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(28, 'edited')) {
            return response()->json("Access denied", 403);
        }

        if ($this->customfieldRepo->changeCustomFieldStatus(
            $request,
            $id
        )
        ) {
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }

    /**
     * Get form tables for custom fields.
     *
     * @return Response
     */
    public function getFormTables()
    {
        return $this->customfieldRepo->getFormTables();
    }

    /**
     * Get particular module custom field detail.
     *
     * @param Int     $form_id [Form id]
     * @param Boolean $is_view [Show on details]
     *
     * @return Response
     */
    public function getCustomFieldByForm($form_id, $is_view = 0)
    {
        return $this->customfieldRepo->getCustomFieldByForm($form_id, $is_view);
    }
}
