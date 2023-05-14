<?php

namespace Modules\Translation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Session;

use Modules\Translation\Repositories\TranslationRepository;
use Modules\Translation\Http\Requests\CreateTranslationRequest;
use Modules\Translation\Http\Requests\UpdateTranslationRequest;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class TranslationController
 *
 * Translation create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Translation
 * @author    Milan Unjiya <milan@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license
 * @version   Release: @1.0@
 * @link
 * @since     Class available since Release 1.0
 */
class TranslationController extends Controller
{
    protected $translationRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param TranslationRepository $translation [Object]
     *
     * @return void
     */
    public function __construct(TranslationRepository $translation)
    {
        $this->translationRepo = $translation;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(30, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->translationRepo->findAll();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get translation]
     *
     * @return Response
     */
    public function getAllTranslations(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(30, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->translationRepo->getAllTranslations($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateTranslationRequest $request [Request for create translation]
     *
     * @return Response
     */
    public function store(CreateTranslationRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(30, 'created')) {
            return response()->json("Access denied", 403);
        }

        if ($this->translationRepo->create($request)) {
            return response()->json('success');
        } else {
            return response()->json(
                ['error' => 'Translation has not been created.'],
                400
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTranslationRequest $request [Request for update translation]
     * @param int                      $id      [Row id]
     *
     * @return Response
     */
    public function update(UpdateTranslationRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(30, 'edited')) {
            return response()->json("Access denied", 403);
        }

        if ($this->translationRepo->update($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json(
                ['error' => 'Translation has not been found.'],
                400
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy translation]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(30, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        if ($this->translationRepo->delete($request, $id)) {
            return response()->json('success', 200);
        } else {
            return response()->json(
                ['error' => 'Translation has not been found.'],
                400
            );
        }
    }

    /**
     * Change status in storage.
     *
     * @param Request $request [Request for change status]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function changeStatus(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(30, 'edited')) {
            return response()->json("Access denied", 403);
        }

        if ($this->translationRepo->update($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json(
                ['error' => 'Translation has not been found.'],
                400
            );
        }
    }

    /**
     * Display a listing of the active status resource.
     *
     * @return Response
     */
    public function getAllActiveTranslations()
    {
        return $this->translationRepo->getAllActiveTranslations();
    }
}
