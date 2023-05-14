<?php

namespace Modules\EmailGroup\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\EmailGroup\Repositories\EmailGroupRepository;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class EmailGroupController
 *
 * EmailGroup create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\EmailGroup
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class EmailGroupController extends Controller
{
    protected $emailgroupRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param EmailGroupRepository $emailgroup [Object]
     *
     * @return void
     */
    public function __construct(EmailGroupRepository $emailgroup)
    {
        $this->emailgroupRepo = $emailgroup;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->emailgroupRepo->findAll();
    }

    /**
     * Show all emailgroup name from helper function.
     *
     * @return Array
     */
    public function getGroup()
    {
        return $this->emailgroupRepo->getEmailGroup();
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
        return $this->emailgroupRepo->getById($id);
    }
}
