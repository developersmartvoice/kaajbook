<?php

namespace Modules\FileBrowser\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\FileBrowser\Repositories\FileBrowserRepository;
use Modules\FileBrowser\Http\Requests\CreateFolderRequest;
use Modules\FileBrowser\Http\Requests\EditFolderRequest;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class FileBrowserController
 *
 * FileBrowser create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\FileBrowser
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class FileBrowserController extends Controller
{
    protected $fileBrowserRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param FileBrowserRepository $fileBrowser [Object]
     *
     * @return void
     */
    public function __construct(FileBrowserRepository $fileBrowser)
    {
        $this->fileBrowserRepo = $fileBrowser;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get folder]
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(15, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->fileBrowserRepo->findAll($request->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateFolderRequest $request [Request for create folder]
     *
     * @return Response
     */
    public function store(CreateFolderRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(15, 'created')) {
            return response()->json("Access denied", 403);
        }

        $fileBrowser = $this->fileBrowserRepo->create($request);
        if ($fileBrowser) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'Error while creating.'], 400);
        }
    }

    /**
     * Show the specified resource.
     *
     * @param Int $id [Row id]
     *
     * @return Response
     */
    public function show($id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(15, 'view')) {
            return response()->json("Access denied", 403);
        }

        $filebrowser = $this->fileBrowserRepo->findById($id);
        return $filebrowser->parent_folder;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EditFolderRequest $request [Request for update folder]
     * @param Int               $id      [Row id]
     *
     * @return Response
     */
    public function update(EditFolderRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(15, 'edited')) {
            return response()->json("Access denied", 403);
        }

        $filebrowser = $this->fileBrowserRepo->update($request, $id);
        if ($filebrowser) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'Error while updating.'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy folder]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(15, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        if ($this->fileBrowserRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'Error while deleting.'], 400);
        }
    }

    /**
     * Get Folder breadcrumbs.
     *
     * @param Request $request [Request for get folder breadcrumb]
     *
     * @return Response
     */
    public function getFolderBreadcrumb(Request $request)
    {
        return $this->fileBrowserRepo->getFolderBreadcrumb($request);
    }
}
