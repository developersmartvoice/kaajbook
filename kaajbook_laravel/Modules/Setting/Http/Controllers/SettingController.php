<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Modules\Helper\Helpers\AdminHelper;
use Modules\Setting\Http\Requests\CreateUpdateSettingRequest;
use Modules\Setting\Repositories\SettingRepository;

/**
 * Class SettingController
 *
 * Setting create, update and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Setting
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class SettingController extends Controller
{
    protected $settingRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param SettingRepository $setting [Object]
     *
     * @return void
     */
    public function __construct(SettingRepository $setting)
    {
        $this->settingRepo = $setting;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->settingRepo->findAll();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getSettings()
    {
        return $this->settingRepo->getSettings();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateUpdateSettingRequest $request [Request for create setting]
     *
     * @return Response
     */
    public function store(CreateUpdateSettingRequest $request)
    {
        // --
        // Check role/permission
        $isAlllow = false;
        if (AdminHelper::can_action(17, 'created') || AdminHelper::can_action(17, 'edited')
        ) {
            $isAlllow = true;
        }
        if (AdminHelper::can_action(18, 'created')  || AdminHelper::can_action(18, 'edited')
        ) {
            $isAlllow = true;
        }
        if (AdminHelper::can_action(20, 'created')  || AdminHelper::can_action(20, 'edited')
        ) {
            $isAlllow = true;
        }
        if (AdminHelper::can_action(21, 'created')  || AdminHelper::can_action(21, 'edited')
        ) {
            $isAlllow = true;
        }
        if (AdminHelper::can_action(23, 'created')  || AdminHelper::can_action(23, 'edited')
        ) {
            $isAlllow = true;
        }
        if (AdminHelper::can_action(25, 'created')  || AdminHelper::can_action(25, 'edited')
        ) {
            $isAlllow = true;
        }

        if ($isAlllow) {
            if ($this->settingRepo->create($request)) {
                Artisan::call('config:cache');
                return response()->json('success', 200);
            } else {
                return response()->json(
                    ['error'],
                    400
                );
            }
        } else {
            return response()->json("Access denied", 403);
        }
    }

    /**
     * Send test email.
     *
     * @return Response
     */
    public function sendTestEmail()
    {
        return $this->settingRepo->sendTestEmail();
    }

    /**
     * Change API URL.
     *
     * @return Response
     */
    // public function changeApiUrl()
    // {
    //     return $this->settingRepo->changeApiUrl();
    // }
}
