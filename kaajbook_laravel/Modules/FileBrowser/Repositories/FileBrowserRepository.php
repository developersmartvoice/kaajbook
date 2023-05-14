<?php

namespace Modules\FileBrowser\Repositories;

use Modules\FileBrowser\Models\FileBrowser;
use Modules\FileBrowser\Models\File;

use Auth;

/**
 * Class FileBrowserRepository
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
class FileBrowserRepository
{
    /**
     * Display a listing of the resource.
     *
     * @param Array $input [Input array]
     *
     * @return object
     */
    public function findAll($input)
    {
        $user_id = Auth::id();
        $folders = FileBrowser::where('parent_folder', $input['folder'])
            ->where('user_id', $user_id)
            ->orderBy('folder_name', 'asc')
            ->get()
            ->toArray();
        return array_reverse($folders);
    }

    /**
     * Display the specified resource in storage.
     *
     * @param Int $id [Row id]
     *
     * @return Object
     */
    public function findById($id)
    {
        return FileBrowser::where('id', $id)
            ->select('parent_folder')
            ->firstOrFail();
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
        $user_id = Auth::id();
        $input = $request->all();
        $fileBrowser = new FileBrowser;
        $fileBrowser->folder_name = $input['folder_name'];
        $fileBrowser->user_id = $user_id;
        $fileBrowser->parent_folder = isset($input['parent_folder']) ? $input['parent_folder'] : 0;
        $fileBrowser->save();
        if ($fileBrowser->save()) {
            // --
            // Add activities
            createUserActivity(
                FileBrowser::MODULE_NAME,
                $fileBrowser->id,
                $request->method(),
                $fileBrowser->folder_name,
                $request->ip()
            );
            return true;
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Array $request [Request for update file]
     * @param Int   $id      [Row id]
     *
     * @return Boolean
     */
    public function update($request, $id)
    {
        $input = $request->all();
        $filebrowser = FileBrowser::find($id);
        if ($filebrowser->fill($input)->save()) {
            // --
            // Add activities
            createUserActivity(
                FileBrowser::MODULE_NAME,
                $filebrowser->id,
                $request->method(),
                $filebrowser->folder_name,
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
        // --
        // Delete Files.
        $files = File::where('folder_id', $id)->get();
        if (!empty($files)) {
            foreach ($files as $key => $value) {
                // --
                // Remove file from folder
                $file = public_path() .'/uploads/filebrowser/'. $value->file_hash;
                if (file_exists($file)) {
                    unlink($file);
                }
            }
            $files = File::where('folder_id', $id)->delete();
        }

        // --
        // Delete Child Folder.
        $folders = FileBrowser::where('parent_folder', $id)->delete();

        // --
        // Delete Folder.
        $filebrowser = FileBrowser::findOrFail($id);
        if ($filebrowser->delete()) {
            // --
            // Add activities
            createUserActivity(
                FileBrowser::MODULE_NAME,
                $filebrowser->id,
                $request->method(),
                $filebrowser->folder_name,
                $request->ip()
            );
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get Folder breadcrumbs.
     *
     * @param Request $request [Request for get folder breadcrumb]
     *
     * @return Response
     */
    public function getFolderBreadcrumb($request)
    {
        $folders = [];
        $id = $request->get('folder');

        if ($id != 0) {
            // --
            // Get the current folder
            $folders = FileBrowser::where('id', $id)->get();

            // --
            // Check for parent folder
            $parentId = $folders[0]["parent_folder"];

            if ($parentId != 0) {
                $looping = true;

                while ($looping) {
                    // --
                    // Get the parent details
                    $nextFolder = FileBrowser::where('id', $parentId)->get();
                    $parentId = $nextFolder[0]["parent_folder"];
                    $folders = $folders->merge($nextFolder);
                    $looping = $parentId != 0;
                }
            }
            return array_reverse($folders->toArray());
        }
        return $folders;
    }
}
