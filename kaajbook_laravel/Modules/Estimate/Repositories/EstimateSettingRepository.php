<?php

namespace Modules\Estimate\Repositories;

use Auth;
use Modules\Estimate\Models\EstimateSetting;
use Modules\Helper\Helpers\UploadFileHelper;

/**
 * Class EstimateSettingRepository
 *
 * Estimate Setting create, update and view.
 *
 * PHP version 7.1.3
 *
 * @category  Sales
 * @package   Modules\Estimate
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class EstimateSettingRepository
{
    protected $fileHelper;

    /**
     * Instantiate a new helper instance.
     *
     * @param UploadFileHelper $fileHelper [Object]
     *
     * @return void
     */
    public function __construct(UploadFileHelper $fileHelper)
    {
        $this->fileHelper = $fileHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return object
     */
    public function findAll()
    {
        return EstimateSetting::first();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create/update estimate setting]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $user = Auth::user();
        $estimateSetting = EstimateSetting::first();
        if (empty($estimateSetting)) {
            $estimateSetting = new EstimateSetting();
        }
        $input = $request->all();

        if (!empty($input['estimate_logo'])) {
            $input['estimate_logo'] = $this->fileHelper->uploadImage('estimate_logo', $input['estimate_logo']);
        }

        // Delete old logo.
        if ($estimateSetting->estimate_logo && $estimateSetting->estimate_logo != $input['estimate_logo']) {
            $this->fileHelper->deleteFile('estimate_logo', $estimateSetting->estimate_logo);
        }

        if ($estimateSetting->fill($input)->save()) {
            // --
            // Add activities
            createUserActivity(
                EstimateSetting::MODULE_NAME,
                $estimateSetting->id,
                'PUT',
                $user->full_name,
                $request->ip()
            );
            return true;
        }
        return false;
    }
}
