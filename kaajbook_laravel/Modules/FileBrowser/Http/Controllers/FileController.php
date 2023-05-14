<?php

namespace Modules\FileBrowser\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\FileBrowser\Models\File;
use Modules\FileBrowser\Repositories\FileRepository;
use Modules\FileBrowser\Http\Requests\EditFileRequest;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class FileController
 *
 * File create, update, delete and view.
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
class FileController extends Controller
{
    protected $fileRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param FileRepository $file [Object]
     *
     * @return void
     */
    public function __construct(FileRepository $file)
    {
        $this->fileRepo = $file;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get file]
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

        return $this->fileRepo->findAll($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EditFileRequest $request [Request for update file]
     * @param Int             $id      [Row id]
     *
     * @return Response
     */
    public function update(EditFileRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(15, 'edited')) {
            return response()->json("Access denied", 403);
        }

        $file = $this->fileRepo->update($request, $id);
        if ($file) {
            return response()->json('success', 200);
        } else {
            return response()->json(['error' => 'Error while updating.'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy file]
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

        if ($this->fileRepo->delete($request, $id)) {
            return response()->json('success', 200);
        } else {
            return response()->json(['error' => 'Error while deleting.'], 400);
        }
    }

    /**
     * Upload file.
     *
     * @param Request $request [Request for upload file]
     *
     * @return Response
     */
    public function uploadFile(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(15, 'created')) {
            return response()->json("Access denied", 403);
        }

        if ($request->hasFile('file')) {
            $user_id = \Auth::id();
            // --
            // Image file
            $size = $request->file('file')->getClientSize();
            $fileName = $request->file('file')->getClientOriginalName();
            $fileExt = $request->file('file')->getClientOriginalExtension();
            $fileBaseName = basename(
                $request->file('file')->getClientOriginalName(),
                '.'.$request->file('file')->getClientOriginalExtension()
            );
            $path = uniqid().'.'.$fileExt;
            $folder = $request->input('folder');
            $request->file('file')->move(public_path("/uploads/filebrowser"), $path);

            if (!(\File::exists(public_path('/uploads/filebrowser/'.'index.php')))) {
                \File::put(public_path('/uploads/filebrowser/'.'index.php'), "");
            }

            // --
            // Save file
            $file = new File();
            $file->user_id = $user_id;
            $file->folder_id = $folder;
            $file->file_name = $fileBaseName.'.'.$fileExt;
            $file->file_extension = $fileExt;
            $file->file_size = $size;
            $file->file_hash = $path;
            $file->save();

            return response()->json([
                'success' => 'File has been uploaded.',
                'id' => $file->id
            ], 200);
        } else {
            return response()->json(['error' => 'Error while creating.'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for remove attachment]
     *
     * @return Response
     */
    public function removeAttachementFile(Request $request)
    {
        $request->validate(
            [
            'id' => 'required'
            ]
        );
        // --
        // Check role/permission
        if (!AdminHelper::can_action(15, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        $id = $request->get('id');
        if ($this->fileRepo->removeAttachementFile($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'Error while deleting.'], 400);
        }
    }
}
