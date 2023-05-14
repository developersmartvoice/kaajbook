<?php

namespace Modules\Holiday\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Holiday\Repositories\HolidayRepository;
use Modules\Holiday\Http\Requests\CreateHolidayRequest;
use Modules\Holiday\Http\Requests\UpdateHolidayRequest;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class HolidayController
 *
 * Holiday create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Holiday
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class HolidayController extends Controller
{
    protected $holidayRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param HolidayRepository $holiday [Object]
     *
     * @return void
     */
    public function __construct(HolidayRepository $holiday)
    {
        $this->holidayRepo = $holiday;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get holidays]
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(9, 'view')) {
            return response()->json("Access denied", 403);
        }
        
        $data = $this->holidayRepo->getHolidays($request->all());
        return $data;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get holidays]
     *
     * @return Response
     */
    public function allHolidays(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(9, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->holidayRepo->findAll($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for store holiday]
     *
     * @return Response
     */
    public function store(CreateHolidayRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(9, 'created')) {
            return response()->json("Access denied", 403);
        }

        if ($this->holidayRepo->create($request)) {
            return response()->json('success');
        } else {
            return response()->json(
                ['error' => 'Holiday has not been created.'],
                400
            );
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
        if (!AdminHelper::can_action(9, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->holidayRepo->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateHolidayRequest $request [Request for update holiday]
     * @param Int                  $id      [Row id]
     *
     * @return Response
     */
    public function update(UpdateHolidayRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(9, 'edited')) {
            return response()->json("Access denied", 403);
        }

        if ($this->holidayRepo->update($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'Holiday has not been found.'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy holiday]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(9, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        if ($this->holidayRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'Holiday has not been found.'], 400);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get holidays]
     *
     * @return Response
     */
    public function yearAllHolidays(Request $request)
    {
        return $this->holidayRepo->yearAllHolidays($request);
    }
}
