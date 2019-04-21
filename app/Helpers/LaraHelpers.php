<?php

namespace App\Helpers;

use App\Models\Permission;
use Auth;
use File;
use Storage;

class LaraHelpers
{

    /*
     *  get Users permission who logged in by call this func GetUserPermissions()
     *
     *  @return array
     * */
    public static function GetUserPermissions()
    {
        $permission = new Permission();
        $userPermission = array();
        foreach (Auth::user()->UserPermission as $row) {
            $userPermission[] = $permission->getPermissionByField($row->permission_id, 'id')->code;
        }
        if (in_array(Auth::user()->role_id, [1, 2])) {
            $userPermission[] = 'department_management';
            $userPermission[] = 'employee_management';
        }
        return $userPermission;
    }


    /*
     *  Upload file
     *
     *  @param string $filepath
     *  @param array $image_name
     *  @param mixed $unlink_image
     *  @return mixed
     * */
    public static function upload_image($filepath, $image_name, $unlink_image = '') {

        if (!is_dir($filepath)) {
            if(env('FILE_STORAGE') == 'Storage'){
                Storage::makeDirectory($filepath, 777);
            }else{
                File::makeDirectory($filepath, 777);
            }
        }

        if (!empty($image_name)) {

            $file = $image_name;
            $filename = time() . '_' . $file->getClientOriginalName();
            $size = $file->getClientSize();

            $extension = "";
            $extension = '.' . $file->getClientOriginalExtension();
            $publicPath = $filepath;
            $file->move($publicPath, $filename);

            if (isset($unlink_image) && $unlink_image != "") {
                if(file_exists($filepath . $unlink_image)){
                    unlink($filepath . $unlink_image);
                }
            }
            return $filename;
        }
        return $unlink_image;
    }
}
