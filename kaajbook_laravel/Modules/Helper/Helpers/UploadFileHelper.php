<?php

namespace Modules\Helper\Helpers;

use Storage;
use File;

/**
 * Class UploadFileHelper
 *
 * The Helper is Defined for upload images and attachments.
 *
 * PHP version 7.1.3
 *
 * @category  Helper
 * @package   Modules\Helper
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class UploadFileHelper
{
    /**
     * Upload image.
     *
     * @param String $dir   [Directory path]
     * @param String $image [Image base64]
     *
     * @return String
     */
    public function uploadImage($dir, $image)
    {
        if ($image) {
            $image_parts = explode(";base64,", $image);
            if (count($image_parts) >= 2) {
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_base64 = base64_decode($image_parts[1]);
                $filename = uniqid() . '.'. $image_type_aux[1];
                if (!Storage::disk($dir)->put($filename, $image_base64)) {
                    return null;
                } else {
                    return $filename;
                }
            } else {
                return $image;
            }
        } else {
            return null;
        }
    }

    /**
     * Upload image.
     *
     * @param String $dir   [Directory path]
     * @param String $image [Image base64]
     *
     * @return void
     */
    public function deleteFile($dir, $file)
    {
        $file = public_path() .'/uploads/'.$dir.'/'.$file;
        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * Upload attachment.
     *
     * @param Array  $input [File]
     * @param String $path  [Storage Path]
     *
     * @return String
     */
    public function uploadAttachment($input, $path)
    {
        $file_name = '';
        if (isset($input['file'])) {
            $image_parts = explode(";base64,", $input['file']);
            
            if (count($image_parts) >= 2) {
                $image_base64 = base64_decode($image_parts[1]);
                $file_name = uniqid() . '.'. $input['file_extension'];

                if (!Storage::disk($path)->put($file_name, $image_base64)) {
                    return response()->error('File has not been uploaded', 401);
                }
            }
        }
        return $file_name;
    }

    /**
     * Upload attachment.
     *
     * @param Array  $attachments [Attachments Array]
     * @param String $path        [Storage Path]
     *
     * @return Array
     */
    public function uploadMultipleAttachment($attachments, $path)
    {
        $i = 0;
        $files = [];
        foreach ($attachments as $key => $value) {
            $image_parts = explode(";base64,", $value['file']);
            
            if (count($image_parts) >= 2) {
                $image_base64 = base64_decode($image_parts[1]);
                $filename = uniqid() . '.'. $value['extension'];

                if (!Storage::disk($path)->put($filename, $image_base64)) {
                    return response()->error('File has not been uploaded', 401);
                }
            }
            
            $value['file'] = $filename;
            $files[$key] = $value;
            $i++;
        }

        if ($i > 0) {
            return json_encode($files);
        } else {
            return null;
        }
    }
}
