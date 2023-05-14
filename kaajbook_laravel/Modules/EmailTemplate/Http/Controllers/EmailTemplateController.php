<?php

namespace Modules\EmailTemplate\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\EmailTemplate\Repositories\EmailTemplateRepository;
use Modules\EmailTemplate\Http\Requests\CreateEmailTemplateRequest;
use Modules\EmailTemplate\Http\Requests\UpdateEmailTemplateRequest;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class EmailTemplateController
 *
 * Email Template insert, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\EmailTemplate
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class EmailTemplateController extends Controller
{
    protected $emailtemplateRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param EmailTemplateRepository $emailtemplate [Object]
     *
     * @return void
     */
    public function __construct(EmailTemplateRepository $emailtemplate)
    {
        $this->emailtemplateRepo = $emailtemplate;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get email template]
     *
     * @return Response
     */
    public function index(Request $request)
    {
        return $this->emailtemplateRepo->findAll();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateEmailTemplateRequest $request [Request for create email template]
     *
     * @return Response
     */
    public function store(CreateEmailTemplateRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(19, 'created')) {
            return response()->json("Access denied", 403);
        }

        if ($this->emailtemplateRepo->create($request)) {
            return response()->json('success');
        } else {
            return response()->json(
                ['error' => 'Emailtemplate has not been created.'],
                400
            );
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
        if (!AdminHelper::can_action(19, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->emailtemplateRepo->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateEmailTemplateRequest $request [Request for update email template]
     * @param int                        $id      [Row id]
     *
     * @return Response
     */
    public function update(UpdateEmailTemplateRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(19, 'edited')) {
            return response()->json("Access denied", 403);
        }

        if ($this->emailtemplateRepo->update($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json(
                ['error' => 'Emailtemplate has not been updated.'],
                400
            );
        }
    }
}
