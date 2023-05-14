<?php

namespace Modules\Announcement\Repositories;

use Auth;
use Modules\Announcement\Models\Announcement;
use Modules\Helper\Helpers\EmailsHelper;
use Modules\User\Models\User\User;

/**
 * Class AnnouncementRepository
 *
 * Announcement create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Announcement
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class AnnouncementRepository
{
    protected $emailsHelper;

    /**
     * Instantiate a new repository instance.
     *
     * @param EmailsHelper $emailsHelper [Object]
     *
     * @return void
     */
    public function __construct(EmailsHelper $emailsHelper)
    {
        $this->emailsHelper = $emailsHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function findAll()
    {
        return Announcement::with(
            ['user' => function ($query) {
                $query->select('id', 'firstname', 'lastname');
            }]
        )->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get announcement]
     *
     * @return Array
     */
    public function getAllAnnouncements($request)
    {
        $users_table = config('core.acl.users_table');
        $announcements_table = config('core.acl.announcements_table');

        $Announcement = Announcement::join(
            $users_table,
            $announcements_table . '.user_id',
            '=',
            $users_table . '.id'
        );

        $totalData = $Announcement->count();

        $columns = array(
            0 => 'id',
            1 => 'title',
            2 => 'firstname',
            3 => 'start_date',
            4 => 'end_date',
            5 => 'status',
        );

        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $Announcement = $Announcement->where('title', 'LIKE', "%{$search}%")
                ->orWhere('firstname', 'LIKE', "%{$search}%")
                ->orWhere('start_date', 'LIKE', "%{$search}%")
                ->orWhere('end_date', 'LIKE', "%{$search}%");

            $totalFiltered = $Announcement->count();
        }

        $Announcement = $Announcement->select(
            $announcements_table . '.*',
            $users_table . '.firstname',
            $users_table . '.lastname',
            $users_table . '.avatar'
        )
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $Announcement,
        );

        return $json_data;
    }

    /**
     * Display a announcement of the resource.
     *
     * @param Int $id [Row id]
     *
     * @return Object
     */
    public function findById($id)
    {
        return Announcement::findOrFail($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create announcement]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $user = Auth::user();
        $input = $request->all();
        $announcement = new Announcement;
        $announcement->fill($input);
        $announcement = $user->announcement()->save($announcement);

        if ($announcement) {
            if ($input['status']) {
                if (isset($input['all_client']) && $input['all_client']) {
                    $userlist = User::select('id', 'email', 'firstname', 'lastname')
                        ->where('is_active', '=', 1)
                        ->get();
                } else {
                    $userlist = User::select('id', 'email', 'firstname', 'lastname')
                        ->where('is_active', '=', 1)
                        ->where('is_client', 0)
                        ->get();
                }

                if (!$userlist->isEmpty()) {
                    $this->emailsHelper->sendAnnouncementEmails($userlist, $announcement);
                    // Add Notification.
                    createNotification($userlist, 'announcements', null, 'Announcement Created.', $announcement->title);
                }
            }
            //
            // Add activity
            createUserActivity(
                Announcement::MODULE_NAME,
                $announcement->id,
                $request->method(),
                $input['title'],
                $request->ip()
            );
            return true;
        }
        return false;
    }

    /**
     * Update announcement
     *
     * @param Request $request [Request for update announcement]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function update($request, $id)
    {
        $announcement = Announcement::findOrFail($id);
        $input = $request->all();
        $announcement->fill($input)->save();
        if ($announcement) {
            if ($input['status']) {
                if (isset($input['all_client']) && $input['all_client']) {
                    $userlist = User::select('id', 'email', 'firstname', 'lastname')
                        ->where('is_active', 1)
                        ->get();
                } else {
                    $userlist = User::select('id', 'email', 'firstname', 'lastname')
                        ->where('is_active', 1)
                        ->where('is_client', 0)
                        ->get();
                }
                if (!$userlist->isEmpty()) {
                    $this->emailsHelper->sendAnnouncementEmails($userlist, $announcement);
                    // Add Notification.
                    createNotification($userlist, 'announcements', null, 'Announcement Updated.', $announcement->title);
                }
            }
            //
            // Add activity
            createUserActivity(
                Announcement::MODULE_NAME,
                $announcement->id,
                $request->method(),
                $input['title'],
                $request->ip()
            );
            return true;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for delete announcement]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function delete($request, $id)
    {
        $announcement = Announcement::findOrFail($id);
        if ($announcement->delete()) {
            if ($announcement->status) {
                if ($announcement->all_client) {
                    $userlist = User::select('id', 'email', 'firstname', 'lastname')
                        ->where('is_active', 1)
                        ->get();
                } else {
                    $userlist = User::select('id', 'email', 'firstname', 'lastname')
                        ->where('is_active', 1)
                        ->where('is_client', 0)
                        ->get();
                }

                if (!$userlist->isEmpty()) {
                    // Add Notification.
                    createNotification($userlist, 'announcements', null, 'Announcement Deleted.', $announcement->title, $request->method());
                }
            }
            // --
            // Add activity
            createUserActivity(
                Announcement::MODULE_NAME,
                $id,
                $request->method(),
                $announcement->title,
                $request->ip()
            );
            return true;
        }
        return false;
    }
}
