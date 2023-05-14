<?php

/**
 * Global user activity helper function.
 *
 * PHP version 7.1.3
 *
 * @category Helper
 * @package  Modules\Helper
 * @author   Vipul Patel <vipul@chetsapp.com>
 * @license
 * @link
 */
use Modules\Notification\Models\Notification;
use Modules\UserActivity\Models\UserActivity;

/**
 * Create user activity descriptions
 *
 * @param string  $moduleName
 * @param string  $requestMethod
 * @param string  $description
 * @param boolean $isStatusChanged
 *
 * @return string
 */
function createActivityDescription($moduleName, $requestMethod, $description, $isStatusChanged)
{
    switch ($requestMethod) {
        case 'POST':
            $requestMethod = 'Added';
            break;
        case 'PUT':
            $requestMethod = 'Updated';
            break;
        case 'DELETE':
            $requestMethod = 'Deleted';
            break;
        default:
            $requestMethod = 'Added';
    }

    $moduleName = ltrim(preg_replace('/[A-Z]/', ' $0', $moduleName));
    if ($isStatusChanged) {
        return $moduleName . " Status Changed : " . '<b>' . $description . '</b>';
    }
    return $moduleName . " " . $requestMethod . " : " . '<b>' . $description . '</b>';
}

/**
 * Create user activity
 *
 * @param String  $moduleName
 * @param Int  $moduleFieldId
 * @param String  $requestMethod
 * @param String  $description
 * @param String  $clientIp
 * @param Int  $selfParentId
 * @param boolean $isStatusChanged
 *
 * @return Void
 */
function createUserActivity(
    $moduleName,
    $moduleFieldId,
    $requestMethod,
    $description,
    $clientIp,
    $selfParentId = null,
    $isStatusChanged = false
) {
    $data = array();
    $user = Auth::user();
    $description = createActivityDescription($moduleName, $requestMethod, $description, $isStatusChanged);
    $userActivities = new UserActivity;
    $data['module'] = $moduleName;
    $data['self_parent_id'] = $selfParentId;
    $data['module_field_id'] = $moduleFieldId;
    $data['action'] = $requestMethod;
    $data['description'] = $description;
    $data['ip_address'] = $clientIp;
    $userActivities->fill($data);
    $user->userActivity()->save($userActivities);
}

/**
 * Create user notification.
 *
 * @param Array   $users [Users Object]
 * @param String  $route [Route]
 * @param Int      $route_related_id [Route Related Id]
 * @param String  $title [Notification Title]
 * @param String  $message [Notification Message]
 * @param String  $method [Request Method]
 *
 * @return Boolean
 */
function createNotification($users, $route, $route_related_id, $title, $message, $method = null)
{
    if (!is_null($users[0])) {
        foreach ($users as $user) {
            $notification = new Notification;
            $notification->user_id = $user->id;
            $notification->route = $route;
            $notification->route_related_id = $route_related_id;
            $notification->title = $title;
            $notification->message = $message;
            $notification->save();
        }
    }

    if($method == 'DELETE'){
        Notification::where('route', $route)->where('route_related_id', $route_related_id)->update(['route_related_id' => null]);
    }

    return true;
}
