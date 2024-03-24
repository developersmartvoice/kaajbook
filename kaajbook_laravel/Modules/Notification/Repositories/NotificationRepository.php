<?php

namespace Modules\Notification\Repositories;

use Modules\Notification\Models\Notification;

/**
 * Class NotificationRepository
 *
 * Notification create, update, delete and view.
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
class NotificationRepository
{
    /**
     * Get notification.
     *
     * @param Request $request [Params for get notification]
     *
     * @return Object
     */
    public function getNotifications($request)
    {
        $data = Notification::where('user_id', auth()->user()->id)->orderBy('id', 'desc');

        if ($request->has('length')) {
            $data = $data->take($request->get('length'));
        }

        return $data->get();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for delete notification]
     *
     * @return Boolen
     */
    public function delete($request)
    {
        Notification::where('id', $request->get('id'))->delete();
        return true;
    }


    /**
     * Remove all specified resource from storage.
     *
     * @param Request $request [Request for delete all notification]
     *
     * @return Boolen
     */
    public function deleteAll()
    {
        Notification::where('user_id', auth()->user()->id)->delete();
        return true;
    }
}
