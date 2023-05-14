<?php

namespace Modules\Setting\Repositories;

use Auth;
use File;
use Illuminate\Support\Facades\Mail;
use Modules\Helper\Helpers\UploadFileHelper;
use Modules\Setting\Models\Setting;

/**
 * Class SettingRepository
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
class SettingRepository
{
	protected $fileHelper;

	/**
	 * Instantiate a new helper instance.
	 *
	 * @param UploadFileHelper $fileHelper [Object]
	 *
	 * @return void
	 */
	public function __construct(UploadFileHelper $fileHelper) {
		$this->fileHelper = $fileHelper;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return object
	 */
	public function findAll()
	{
		return Setting::first()->makeHidden(['smtp_password']);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return object
	 */
	public function getSettings()
	{
		return Setting::select([
			'login_background', 'company_logo', 'theme_layout', 'default_language', 'allowed_for_registration', 'is_demo'
		])->first();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request [Request for create user setting]
	 *
	 * @return Boolean
	 */
	public function create($request)
	{
		if (!empty(Auth::user())) {
			$user = Auth::user();
			$setting = Setting::first();
			$oldSetting = $setting->toArray();

			$input = $request->all();
			if( isset($input['form_for']) && $input['form_for'] == 'theme_setting'){
				$themeSettings = $input["settings_images"];
				//--
				// Upload login backgound
				if (array_key_exists('login_background',$themeSettings)) {
					$input['login_background'] = $this->fileHelper->uploadImage(
						'login_bg',
						$themeSettings['login_background']
					);
					if( !empty($setting->login_background) && $setting->login_background != $input['login_background']){
						$this->fileHelper->deleteFile('login_bg',$setting->login_background);
					}
				}
				// --
				// Upload company logo
				if (array_key_exists('company_logo',$themeSettings)) {
					$input['company_logo'] = $this->fileHelper->uploadImage(
						'company_logo',
						$themeSettings['company_logo']
					);
					if( !empty($setting->company_logo) && $setting->company_logo != $input['company_logo']){
						$this->fileHelper->deleteFile('company_logo',$setting->company_logo);
					}
				}
				// --
				// Upload company sidebar logo
				if(array_key_exists('company_sidebar_logo',$themeSettings)){					
					$input['company_sidebar_logo'] = $this->fileHelper->uploadImage(
						'company_sidebar_logo',
						$themeSettings['company_sidebar_logo']
					);
					if( !empty($setting->company_sidebar_logo) && $setting->company_sidebar_logo != $input['company_sidebar_logo']){
						$this->fileHelper->deleteFile('company_sidebar_logo',$setting->company_sidebar_logo);
					}
				}
				// --
				// Upload sidear background images
				$tempImgArr = [];
				if (isset($themeSettings['sidebar_background_images_obj']) && !empty($themeSettings['sidebar_background_images_obj'])) {
					
					foreach ($themeSettings['sidebar_background_images_obj'] as $value) {
						$imageName = $this->fileHelper->uploadImage(
							'sidebar_background_images',
							$value["file"]
						);
						array_push($tempImgArr, $imageName);
					}
				}
				if(isset($themeSettings['sidebar_background_images']) && !empty($themeSettings['sidebar_background_images'])){
					$tempImgArr = array_merge($tempImgArr,$themeSettings['sidebar_background_images']);
				}
				// --
				// Delete sidebar background images.
				if(File::isDirectory(public_path('uploads/sidebar_background_images'))){
					$files = File::files(public_path('uploads/sidebar_background_images'));
					foreach ($files as $file) {
						if(!in_array($file->getFilename(), $tempImgArr)){
							File::delete(public_path('uploads/sidebar_background_images/'.$file->getFilename()));
						}
					}
			    }

				if(!empty($tempImgArr)){
					$input['sidebar_background_images'] = json_encode($tempImgArr);
				}else{
					$input['sidebar_background_images'] = null;
				}
			} elseif(isset($input['form_for']) && $input['form_for'] == 'system_setting'){
				$input['php_date_format'] = $this->_getPHPDateFormat($input['date_format']);
				$input['php_time_format'] = $this->_getPHPTimeFormat($input['time_format']);
				$input['php_date_time_format'] = $input['php_date_format']. ' ' . $input['php_time_format'];
			} elseif(isset($input['form_for']) && $input['form_for'] == 'email_setting'){
				if($input['smtp_password'] == null){
					$input['smtp_password'] = $oldSetting['smtp_password'];
				}
			}

			$setting->fill($input)->save();

			if (!empty($setting)) {
				
				if (isset($input['form_for'])) {
					// --
					// Set system settings
					if ($input['form_for'] == 'system_setting') {
						// --
						// Set timezone
						if (isset($input['timezone'])) {
							setEnv(
								'TIMEZONE',
								$input['timezone'],
								config('app.timezone')
							);
						}

						// --
						// Set local
						if (isset($input['default_language'])) {
							setEnv(
								'LOCALE',
								$input['default_language'],
								$oldSetting['default_language']
							);
						}
					}
					// --
					// Set email settings
					if ($input['form_for'] == 'email_setting') {
						if (isset($input['smtp_protocol'])) {
							setEnv(
								'MAIL_DRIVER',
								$input['smtp_protocol'],
								config('mail.driver')
							);
						}
						if (isset($input['smtp_host'])) {
							setEnv(
								'MAIL_HOST',
								$input['smtp_host'],
								config('mail.host')
							);
						}
						if (isset($input['smtp_port'])) {
							setEnv(
								'MAIL_PORT',
								$input['smtp_port'],
								config('mail.port')
							);
						}
						if (isset($input['smtp_encryption'])) {
							setEnv(
								'MAIL_ENCRYPTION',
								$input['smtp_encryption'],
								config('mail.encryption')
							);
						}
						if (isset($input['smtp_user'])) {
							setEnv(
								'MAIL_USERNAME',
								$input['smtp_user'],
								config('mail.username')
							);
						}
						if (isset($input['smtp_password'])) {
							setEnv(
								'MAIL_PASSWORD',
								$input['smtp_password'],
								config('mail.password')
							);
						}
						if (isset($input['mail_from_name'])) {
							setEnv(
								'MAIL_FROM_NAME',
								"'".$input['mail_from_name']."'",
								"'".config('mail.from.name')."'"
							);
						}
						if (isset($input['company_from_email'])) {
							setEnv(
								'MAIL_FROM_ADDRESS',
								$input['company_from_email'],
								config('mail.from.address')
							);
						}
						// if (isset($input['mailgun_domain'])) {
						// 	setEnv(
						// 		'MAILGUN_DOMAIN',
						// 		$input['mailgun_domain'],
						// 		config('services.mailgun.domain')
						// 	);
						// }
						// if (isset($input['mailgun_secret'])) {
						// 	setEnv(
						// 		'MAILGUN_SECRET',
						// 		$input['mailgun_secret'],
						// 		config('services.mailgun.secret')
						// 	);
						// }
						// if (isset($input['sparkpost_secret'])) {
						// 	setEnv(
						// 		'SPARKPOST_SECRET',
						// 		$input['sparkpost_secret'],
						// 		config('services.sparkpost.secret')
						// 	);
						// }
						// if (isset($input['mandrill_secret'])) {
						// 	setEnv(
						// 		'MANDRILL_SECRET',
						// 		$input['mandrill_secret'],
						// 		config('services.mandrill.secret')
						// 	);
						// }
					}
				}

				// --
				// Add activities
				createUserActivity(
					Setting::MODULE_NAME,
					$setting->id,
					'PUT',
					$user->full_name,
					$request->ip()
				);
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

    /**
     * Get PHP date format.
     *
     * @param String $date_format [Date format]
     *
     * @return id
     */
    public function _getPHPDateFormat($date_format)
    {
        $format = [
            'DD-MM-YYYY' => 'd-m-Y',
            'MM-DD-YYYY' => 'm-d-Y',
            'YYYY-MM-DD' => 'Y-m-d',
            'D-M-YY' => 'j-n-y',
            'DD.MM.YYYY' => 'd.m.Y',
            'MM.DD.YYYY' => 'm.d.Y',
            'YYYY.MM.DD' => 'Y.m.d',
            'D.M.YY' => 'j.n.y',
        ];
        return $format[$date_format];
    }

    /**
     * Get PHP time format.
     *
     * @param String $time_format [Time format]
     *
     * @return id
     */
    public function _getPHPTimeFormat($time_format)
    {
        $format = [
            'hh:mm A' => 'h:i A',
            'HH:mm' => 'H:i'
        ];
        return $format[$time_format];
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return object
	 */
	public function sendTestEmail()
	{
		try {
			$setting = Setting::first();
            Mail::send([], [], function ($message) use ($setting) {
					$message->replyTo('noreply@example.com','Example');
                    $message->to($setting->company_from_email, $setting->company_name);
                    $message->setBody("<b>Email setting is ok!</b>", 'text/html');
                    $message->subject("Test Email Setting");
                }
            );
            return [ 'status' => true, 'email' => $setting->company_from_email ];
        } catch (\Exception $e) {
            return [ 'status' => false, 'msg' => $e->getMessage() ];
        }
	}

	/**
     * Change API URL.
     *
     * @return Array
     */
    // public function changeApiUrl()
    // {
    //     try {
    //     	$find = "http://chetsapp.de:8896";
    //     	$replace = "http://chetsapp.de:8897";
    //         $path_to_file = base_path('public/vendor/main.f8f46161a18a5ca486e7.js');
    //         $file_contents = file_get_contents($path_to_file);
    //         $file_contents = str_replace($find, $replace, $file_contents);
    //         file_put_contents($path_to_file,$file_contents);
    //     } catch (Exception $e) {
    //         return $this->response($e->getMessage());
    //     }
    // }
}
