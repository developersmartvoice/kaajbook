<?php
namespace Modules\Translation\Repositories;

use Modules\Translation\Models\Translation;
use Modules\Helper\Helpers\UploadFileHelper;

/**
 * Class TranslationRepository
 *
 * Translation create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Translation
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class TranslationRepository
{
    protected $fileHelper;

    /**
     * Instantiate a new helper instance.
     *
     * @param UploadFileHelper $fileHelper [Object]
     *
     * @return void
     */
    public function __construct(
        UploadFileHelper $fileHelper
    ) {
        $this->fileHelper = $fileHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function findAll()
    {
        return Translation::all();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get translation]
     *
     * @return Array
     */
    public function getAllTranslations($request)
    {
        $translation = new Translation;
        $totalData = Translation::count();
        $columns = array(
            0 => 'id',
            1 => 'language',
            2 => 'status'
        );
        
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $translation = Translation::where('id', 'LIKE', "%{$search}%")
                ->orWhere('language', 'LIKE', "%{$search}%")
                ->orWhere('status', 'LIKE', "%{$search}%");

            $totalFiltered = $translation->count();
        }

        $data = $translation->offset($start)
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
     * @param Request $request [Request for create translation]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $translation = new Translation;
        $input['icon'] = $this->fileHelper->uploadImage(
            'translation',
            $input['icon']
        );
        $translation->fill($input);
        if ($translation->save()) {
            // --
            // Add activities
            createUserActivity(
                Translation::MODULE_NAME,
                $translation->id,
                $request->method(),
                $translation->language,
                $request->ip()
            );
            return true;
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update translation]
     * @param Int     $id [Row id]
     *
     * @return Boolean
     */
    public function update($request, $id)
    {
        $input = $request->all();
        $translation = Translation::findOrFail($id);
        if (isset($input['icon']) && $input['icon'] != '' && $translation->icon != $input['icon']) {
            // --
            // Remove old icon.
            $files = public_path() .'/uploads/translation/'. $translation->icon;
            if (file_exists($files)) {
                unlink($files);
            }

            // --
            // Upload new icon.
            $input['icon'] = $this->fileHelper->uploadImage(
                'translation',
                $input['icon']
            );
        }
        
        $translation->fill($input);
        if ($translation->save()) {
            // --
            // Add activities
            createUserActivity(
                Translation::MODULE_NAME,
                $translation->id,
                $request->method(),
                $translation->language,
                $request->ip()
            );
            return true;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for delete translation]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function delete($request, $id)
    {
        $translation = Translation::findOrFail($id);
        $files = public_path() .'/uploads/translation/'. $translation->icon;
        if (file_exists($files)) {
            unlink($files);
        }
        if ($translation->delete()) {
            // --
            // Add activities
            createUserActivity(
                Translation::MODULE_NAME,
                $translation->id,
                $request->method(),
                $translation->language,
                $request->ip()
            );
            return true;
        } else {
            return false;
        }
    }

    /**
     * Display a listing of the active status resource.
     *
     * @return Response
     */
    public function getAllActiveTranslations()
    {
        return Translation::where('status', 1)->get();
    }
}
