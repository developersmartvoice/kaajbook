<?php

namespace Modules\DatabaseBackup\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\DatabaseBackup\Repositories\DatabaseBackupRepository;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class DatabaseBackupController
 *
 * DatabaseBackup create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\DatabaseBackup
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class DatabaseBackupController extends Controller
{
    protected $databaseBackupRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param DatabaseBackupRepository $dataBasebackup [Object]
     *
     * @return void
     */
    public function __construct(DatabaseBackupRepository $dataBasebackup)
    {
        $this->databaseBackupRepo = $dataBasebackup;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->databaseBackupRepo->findAll();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get DB Backup]
     *
     * @return Response
     */
    public function getAllDatabaseBackups(Request $request)
    {
        return $this->databaseBackupRepo->getAllDatabaseBackups($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create DB Backup]
     *
     * @return Response
     */
    public function store(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(27, 'created')) {
            return response()->json("Access denied", 403);
        }

        $dataBasebackup = $this->databaseBackupRepo->create($request);

        if ($dataBasebackup) {
            return response()->json('success');
        } else {
            return response()->json(
                ['error' => 'Database backup has not been created.'],
                400
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update DB Backup]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(27, 'edited')) {
            return response()->json("Access denied", 403);
        }

        $databaseBackup = $this->databaseBackupRepo->update($request, $id);
        if ($databaseBackup) {
            return response()->json('success');
        } else {
            return response()->json(
                ['error' => 'Database backup has not been found.'],
                400
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy DB Backup]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(27, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        if ($this->databaseBackupRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json(
                ['error' => 'Database backup has not been found.'],
                400
            );
        }
    }
}
