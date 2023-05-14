<?php

namespace Modules\Slack\Repositories;

use Modules\Slack\Models\Slack;
use Modules\Setting\Models\Setting;
use Modules\Slack\Models\SlackSetting;

/**
 * Class SlackRepository
 *
 * Slack create, update and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Slack
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class SlackRepository
{
    /**
     * Create slack activities.
     *
     * @param String $text   [Text]
     * @param Object $data   [Data]
     * @param String $module [Module]
     *
     * @return object
     */
    public function postActivitiesMessage($text, $data, $module)
    {
    	$slackSetting = SlackSetting::first();
		if(empty($slackSetting) || !$slackSetting->is_authenticate || !$slackSetting->slack_status) {
			return false;
		}

        $postData = [];
        $slack = Slack::where('user_id', \Auth::user()->id)->first();

        if (!empty($slack)) {
            switch ($module) {
            case 'Project':
                $postData = $this->_getProjectModuleMessage($slack, $text, $data);
                break;
            case 'Task':
                $postData = $this->_getTaskModuleMessage($slack, $text, $data);
                break;
            case 'Defect':
                $postData = $this->_getDefectModuleMessage($slack, $text, $data);
                break;
            case 'Incident':
                $postData = $this->_getIncidentModuleMessage($slack, $text, $data);
                break;
            default:
                break;
            }

            $curl = curl_init();
            curl_setopt_array(
                $curl, array(
                CURLOPT_URL => 'https://slack.com/api/chat.postMessage',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($postData),
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ".$slack->access_token,
                    "content-type: application/json"
                ),
                )
            );

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $res = json_decode($response);

                if (!empty($res->error)) {
                    return $res;
                }

                return $res;
            }
        }
    }

    /**
     * Create project activities on slack.
     *
     * @param Object $slack   [Slack object]
     * @param String $text    [Text]
     * @param Object $project [Project details]
     *
     * @return boolean
     */
    private function _getProjectModuleMessage($slack, $text, $project)
    {
        $user = \Auth::user();
		$setting = Setting::select('php_date_format')->first();
        $url = config('app.front_url').'/#/projects/detail/'.$project->id;
        $project_status_key_value = [];
        $project_status_key_value[1] = 'Open';
        $project_status_key_value[2] = 'In Progress';
        $project_status_key_value[3] = 'On Hold';
        $project_status_key_value[4] = 'Cancel';
        $project_status_key_value[5] = 'Completed';

        return [
            'channel' => $slack->channel_id,
            // "text" => $text,
            "as_user" => true,
            "blocks" => '[{
				"type": "section",
				"text": {
					"type": "mrkdwn",
					"text": "'.$text. '\n' . $url .'"
				}
			}]',
            "attachments" => '[
				{
					"color": "#2F8568",
					"blocks": [
						{
							"type": "section",
							"fields": [
								{
									"type": "mrkdwn",
									"text": "*Project Name:* \n'.$project->project_name.'"
								},
								{
									"type": "mrkdwn",
									"text": "*Project Version:* \n'.$project->project_version.'"
								}
							]
						},
						{
							"type": "section",
							"fields": [
								{
									"type": "mrkdwn",
									"text": "*Status:* \n'.$project_status_key_value[$project->status].'"
								},
								{
									"type": "mrkdwn",
									"text": "*Created By:* \n'.$user->firstname .' '.$user->lastname.'"
								}
							]
						},
						{
							"type": "section",
							"fields": [
								{
									"type": "mrkdwn",
									"text": "*Start Date:* \n'.$project->start_date->format($setting->php_date_format).'"
								},
								{
									"type": "mrkdwn",
									"text": "*End Date:* \n'.$project->end_date->format($setting->php_date_format).'"
								}
							]
						},
						{
							"type": "section",
							"text": {
								"type": "mrkdwn",
								"text": "*Description:* \n'.$project->description.'"
							}
						}
					]
				}
			]'
        ];
    }
    
    /**
     * Create task activities on slack.
     *
     * @param Object $slack [Slack object]
     * @param String $text  [Text]
     * @param Object $task  [Task details]
     *
     * @return boolean
     */
    private function _getTaskModuleMessage($slack, $text, $task)
    {
        $user = \Auth::user();
		$setting = Setting::select('php_date_format')->first();
        $url = config('app.front_url').'/#/tasks/detail/'.$task->id;
        $task_status_key_value = [];
        $task_status_key_value[1] = 'Open';
        $task_status_key_value[2] = 'In Progress';
        $task_status_key_value[3] = 'On Hold';
        $task_status_key_value[4] = 'Waiting';
        $task_status_key_value[5] = 'Cancel';
        $task_status_key_value[6] = 'Completed';

        $task_priority_key_value = [];
        $task_priority_key_value[1] = "Urgent";
        $task_priority_key_value[2] = "Very High";
        $task_priority_key_value[3] = "High";
        $task_priority_key_value[4] = "Medium";
        $task_priority_key_value[5] = "Low";

		$task_start_date = '';
        if ($task->task_start_date) {
            $task_start_date = $task->task_start_date->format($setting->php_date_format);
        }

		$task_end_date = '';
        if ($task->task_end_date) {
            $task_end_date = $task->task_end_date->format($setting->php_date_format);
        }

        return [
            'channel' => $slack->channel_id,
            // "text" => $text,
            "as_user" => true,
            "blocks" => '[{
				"type": "section",
				"text": {
					"type": "mrkdwn",
					"text": "'.$text. '\n' . $url .'"
				}
			}]',
            "attachments" => '[
				{
					"color": "#2F8568",
					"blocks": [
						{
							"type": "section",
							"fields": [
								{
									"type": "mrkdwn",
									"text": "*Task Name:* \n'.$task->name.'"
								}
							]
						},
						{
							"type": "section",
							"fields": [
								{
									"type": "mrkdwn",
									"text": "*Status:* \n'.$task_status_key_value[$task->status].'"
								},
								{
									"type": "mrkdwn",
									"text": "*Priority:* \n'.$task_priority_key_value[$task->priority].'"
								}
							]
						},
						{
							"type": "section",
							"fields": [
								{
									"type": "mrkdwn",
									"text": "*Created By:* \n'.$user->firstname .' '.$user->lastname.'"
								},
								{
									"type": "mrkdwn",
									"text": "*Estimated Hours:* \n'.$task->estimated_hours.'"
								}
							]
						},
						{
							"type": "section",
							"fields": [
								{
									"type": "mrkdwn",
									"text": "*Start Date:* \n'.$task_start_date.'"
								},
								{
									"type": "mrkdwn",
									"text": "*End Date:* \n'.$task_end_date.'"
								}
							]
						}
					]
				}
			]'
        ];
    }

    /**
     * Create defect activities on slack.
     *
     * @param Object $slack  [Slack object]
     * @param String $text   [Text]
     * @param Object $defect [Defect details]
     *
     * @return boolean
     */
    private function _getDefectModuleMessage($slack, $text, $defect)
    {
        $user = \Auth::user();
		$setting = Setting::select('php_date_format')->first();
        $url = config('app.front_url').'/#/defects/detail/'.$defect->id;
        $defect_status_key_value = [];
        $defect_status_key_value[1] = 'Assigned';
        $defect_status_key_value[2] = 'Closed';
        $defect_status_key_value[3] = 'In Progress';
        $defect_status_key_value[4] = 'Open';
        $defect_status_key_value[5] = 'Solved';
        $defect_status_key_value[5] = 'Reopen';
        $defect_status_key_value[6] = 'Deferred';

        $defect_priority_key_value = [];
        $defect_priority_key_value[1] = "Low";
        $defect_priority_key_value[2] = "Medium";
        $defect_priority_key_value[3] = "High";
        $defect_priority_key_value[4] = "Very High";
        $defect_priority_key_value[5] = "Urgent";

		$start_date = '';
        if ($defect->start_date) {
            $start_date = $defect->start_date->format($setting->php_date_format);
        }

		$end_date = '';
        if ($defect->end_date) {
            $end_date = $defect->end_date->format($setting->php_date_format);
        }

        return [
            'channel' => $slack->channel_id,
            // "text" => $text,
            "as_user" => true,
            "blocks" => '[{
				"type": "section",
				"text": {
					"type": "mrkdwn",
					"text": "'.$text. '\n' . $url .'"
				}
			}]',
            "attachments" => '[
				{
					"color": "#2F8568",
					"blocks": [
						{
							"type": "section",
							"fields": [
								{
									"type": "mrkdwn",
									"text": "*Defect Name:* \n'.$defect->defect_name.'"
								}
							]
						},
						{
							"type": "section",
							"fields": [
								{
									"type": "mrkdwn",
									"text": "*Status:* \n'.$defect_status_key_value[$defect->status].'"
								},
								{
									"type": "mrkdwn",
									"text": "*Priority:* \n'.$defect_priority_key_value[$defect->severity].'"
								}
							]
						},
						{
							"type": "section",
							"fields": [
								{
									"type": "mrkdwn",
									"text": "*Created By:* \n'.$user->firstname .' '.$user->lastname.'"
								},
								{
									"type": "mrkdwn",
									"text": "*Estimated Hours:* \n'.$defect->estimated_hours.'"
								}
							]
						},
						{
							"type": "section",
							"fields": [
								{
									"type": "mrkdwn",
									"text": "*Start Date:* \n'.$start_date.'"
								},
								{
									"type": "mrkdwn",
									"text": "*End Date:* \n'.$end_date.'"
								}
							]
						}
					]
				}
			]'
        ];

        return $message;
    }

    /**
     * Create incident activities on slack.
     *
     * @param Object $slack    [Slack object]
     * @param String $text     [Text]
     * @param Object $incident [Incident details]
     *
     * @return boolean
     */
    private function _getIncidentModuleMessage($slack, $text, $incident)
    {
        $user = \Auth::user();
		$setting = Setting::select('php_date_format')->first();
        $url = config('app.front_url').'/#/incidents/detail/'.$incident->id;
        $incident_status_key_value = [];
        $incident_status_key_value[1] = 'Open';
        $incident_status_key_value[2] = 'Assigned';
        $incident_status_key_value[3] = 'In Progress';
        $incident_status_key_value[4] = 'Solved';
        $incident_status_key_value[5] = 'Deferred';
        $incident_status_key_value[6] = 'Reopen';
        $incident_status_key_value[7] = 'Closed';

        $incident_severity_key_value = [];
        $incident_severity_key_value[1] = "Low";
        $incident_severity_key_value[2] = "Medium";
        $incident_severity_key_value[3] = "High";
        $incident_severity_key_value[4] = "Very High";
        $incident_severity_key_value[5] = "Urgent";

		$start_date = '';
        if ($incident->start_date) {
            $start_date = $incident->start_date->format($setting->php_date_format);
        }

		$end_date = '';
        if ($incident->end_date) {
            $end_date = $incident->end_date->format($setting->php_date_format);
        }

        return [
            'channel' => $slack->channel_id,
            // "text" => $text,
            "as_user" => true,
            "blocks" => '[{
				"type": "section",
				"text": {
					"type": "mrkdwn",
					"text": "'.$text. '\n' . $url .'"
				}
			}]',
            "attachments" => '[
				{
					"color": "#2F8568",
					"blocks": [
						{
							"type": "section",
							"fields": [
								{
									"type": "mrkdwn",
									"text": "*Incident Name:* \n'.$incident->incident_name.'"
								}
							]
						},
						{
							"type": "section",
							"fields": [
								{
									"type": "mrkdwn",
									"text": "*Status:* \n'.$incident_status_key_value[$incident->status].'"
								},
								{
									"type": "mrkdwn",
									"text": "*Priority:* \n'.$incident_severity_key_value[$incident->priority].'"
								}
							]
						},
						{
							"type": "section",
							"fields": [
								{
									"type": "mrkdwn",
									"text": "*Created By:* \n'.$user->firstname .' '.$user->lastname.'"
								},
								{
									"type": "mrkdwn",
									"text": "*Estimated Hours:* \n'.$incident->estimated_hours.'"
								}
							]
						},
						{
							"type": "section",
							"fields": [
								{
									"type": "mrkdwn",
									"text": "*Start Date:* \n'.$start_date.'"
								},
								{
									"type": "mrkdwn",
									"text": "*End Date:* \n'.$end_date.'"
								}
							]
						}
					]
				}
			]'
        ];

        return $message;
    }
}
