<?php

namespace Modules\Invoice\Repositories;

use Auth;
use Modules\Helper\Helpers\UploadFileHelper;
use Modules\Invoice\Models\InvoiceSetting;

/**
 * Class InvoiceSettingRepository
 *
 * Invoice Setting create, update and view.
 *
 * PHP version 7.1.3
 *
 * @category  Sales
 * @package   Modules\Invoice
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class InvoiceSettingRepository
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
        return InvoiceSetting::first();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create/update invoice setting]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $user = Auth::user();
        $invoiceSetting = InvoiceSetting::first();
        if (empty($invoiceSetting)) {
            $invoiceSetting = new InvoiceSetting();
        }
        $input = $request->all();

        if (!empty($input['invoice_logo'])) {
            $input['invoice_logo'] = $this->fileHelper->uploadImage('invoice_logo', $input['invoice_logo']);
        }

        // Delete old logo.
        if ($invoiceSetting->invoice_logo && $invoiceSetting->invoice_logo != $input['invoice_logo']) {
            $this->fileHelper->deleteFile('invoice_logo', $invoiceSetting->invoice_logo);
        }

        if ($invoiceSetting->fill($input)->save()) {
            // --
            // Add activities
            createUserActivity(
                InvoiceSetting::MODULE_NAME,
                $invoiceSetting->id,
                'PUT',
                $user->full_name,
                $request->ip()
            );
            return true;
        }
        return false;
    }
}
