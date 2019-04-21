<?php
/**
 * Created by Nikhil Jain
 * User: Nikhil Jain
 * Date: 19-01-2017
 * Time: 13:59
 */

return [
    'FROM_EMAIL' => 'cooolniky@gmail.com',
    'FROM_NAME' => 'Pet Pooja',
    'permissions' => [
        1 => "Add",
        2 => "Edit",
        3 => "Export",
        4 => "Import",
    ],
    /*
     * userDataTableFieldArray use for sorting
     * */
    "userDataTableFieldArray" => [

        "first_name",
        "last_name",
        "",
        "email",
        "status"
    ],
    "departmentDataTableFieldArray" => [
        "name",
        "status",
        ""
    ],
    "employeeDataTableFieldArray" => [
        "name",
        "email",
        "",
        "status",
        ""
    ]

];

