<?php
/**
 * Created by PhpStorm.
 * User: markmooibroek
 * Date: 26/05/15
 * Time: 15:01
 */

namespace App\Models;


class Assertor extends LDModel
{

    protected $fillable = ["key_id", "date"];
    protected $hidden = ["id", "first_name", "sur_name", "email", "password", "key_id"];

    protected $model_vocs = [
        "earl" => "http://www.w3.org/ns/earl#",
        "wcagem" => "http://www.w3.org/TR/WCAG-EM/#",
        "wcag20" => "http://www.w3.org/TR/WCAG20/#",
        "auto" => "https://www.w3.org/community/auto-wcag/wiki/"
    ];

    protected $ld_properties = [

    ];


}