<?php

namespace Modules\Provider\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Helper\Helpers\AdminHelper;
use Modules\Provider\Repositories\ProviderRepository;
use Modules\Provider\Http\Requests\ProviderRequest;

/**
 * Class ProviderController
 *
 * Provider CRUD Functionality.
 *
 * Provider create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  CRM
 * @package   Modules\Provider
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class ProviderController extends Controller
{
    protected $providerRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param ProviderRepository $provider [Object]
     *
     * @return void
     */
    public function __construct(ProviderRepository $provider)
    {
        $this->providerRepo = $provider;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return $this->providerRepo->findAll();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get provider]
     *
     * @return Response
     */
    public function getAllProviders(Request $request)
    {
        return $this->providerRepo->getAllProviders($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProviderRequest $request [Request for create provider]
     *
     * @return Response
     */

    public function store(ProviderRequest $request)
    {
        if ($this->providerRepo->create($request)) {
            return response()->json('success');
        } else {
            return response()->json(
                ['error' => 'Provider has not been created.'],
                400
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProviderRequest $request [Request for update provider]
     * @param int                      $id      [Row id]
     *
     * @return Response
     */
    public function update(ProviderRequest $request, $id)
    {
        if ($this->providerRepo->update($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json(
                ['error' => 'Provider has not been found.'],
                400
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy provider]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        if ($this->providerRepo->delete($request, $id)) {
            return response()->json('success', 200);
        } else {
            return response()->json(
                ['error' => 'Provider has not been found.'],
                400
            );
        }
    }
}
