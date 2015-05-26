<?php
/**
 * Created by PhpStorm.
 * User: markmooibroek
 * Date: 26/05/15
 * Time: 15:01
 */

namespace App\Models;


class Evaluation extends LDModel{

    protected $model_vocs = [
        "earl" => "http://www.w3.org/TR/EARL10-Schema/#",
        "wcag20" => "http://www.w3.org/TR/WCAG20/#"
    ];

    protected $ld_properties = [

    ];

}