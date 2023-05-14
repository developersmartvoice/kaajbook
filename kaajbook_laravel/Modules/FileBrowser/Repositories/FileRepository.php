<?php

namespace Modules\FileBrowser\Repositories;

use Modules\FileBrowser\Models\File;

use Auth;

/**
 * Class FileRepository
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
class FileRepository
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get file]
     *
     * @return Object
     */
    public function findAll($request)
    {
        $input = $request->all();
        $user_id = Auth::id();
        $files = File::where('folder_id', $input['folder'])
            ->where('user_id', $user_id)
            ->orderBy('file_name', 'asc')
            ->get();

        return $files;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create file]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $folderId = $input['folderId'];
        $fileId = $input['fileId'];

        $file = File::where('id', $fileId)->first();

        $file->folder_id = $folderId;
        if ($file->save()) {
            //--
            // Add activities
            createUserActivity(
                File::MODULE_NAME,
                $file->id,
                $request->method(),
                $file->file_name,
                $request->ip()
            );
            return true;
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update file]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function update($request, $id)
    {
        $input = $request->all();
        $file = File::find($id);

        if ($file->fill($input)->save()) {
            //--
            // Add activities
            createUserActivity(
                File::MODULE_NAME,
                $file->id,
                $request->method(),
                $file->file_name,
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
     * @param Request $request [Request for delete file]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function delete($request, $id)
    {
        $file = File::findOrFail($id);

        // --
        // Remove file from folder
        $files = public_path() .'/uploads/filebrowser/'. $file->file_hash;
        if (file_exists($files)) {
            unlink($files);
        }

        if ($file->delete()) {
            //--
            // Add activities
            createUserActivity(
                File::MODULE_NAME,
                $file->id,
                $request->method(),
                $file->file_name,
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
     * @param Request $request [Request for remove attachment]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function removeAttachementFile($request, $id)
    {
        $fileAttachment = File::findOrFail($id);
        // --
        // Remove file from folder
        $file = public_path() .'/uploads/filebrowser/'. $fileAttachment->file_hash;
        if (file_exists($file)) {
            unlink($file);
        }
        if ($fileAttachment->delete()) {
            // --
            // Add activities
            createUserActivity(
                File::MODULE_NAME,
                $fileAttachment->id,
                $request->method(),
                $fileAttachment->file_name,
                $request->ip()
            );
            return true;
        } else {
            return false;
        }
    }
}
