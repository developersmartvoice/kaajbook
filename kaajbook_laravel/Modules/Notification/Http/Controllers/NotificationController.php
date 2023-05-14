<?php

namespace Modules\Notification\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Notification\Repositories\NotificationRepository;

/**
 * Class NotificationController
 *
 * Notification create, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Notification
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2022 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.2@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.2
 */
class NotificationController extends Controller
{
    protected $notificationRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param NotificationRepository $notificationRepo [Object]
     *
     * @return void
     */
    public function __construct(NotificationRepository $notificationRepo)
    {
        $this->notificationRepo = $notificationRepo;
    }

    /**
     * Get notification.
     *
     * @param Request $rerquest [Params for get notification]
     *
     * @return Response
     */
    public function getNotifications(Request $rerquest)
    {
        return $this->notificationRepo->getNotifications($rerquest);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy notification]
     *
     * @return Response
     */
    public function destroy(Request $request)
    {
        if ($this->notificationRepo->delete($request)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }
}
