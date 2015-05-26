<?php
/**
 * Created by PhpStorm.
 * User: markmooibroek
 * Date: 26/05/15
 * Time: 15:01
 */

namespace App\Models;


class Webpage extends LDModel{

    protected $model_vocs = [
        "dcat" => "http://www.w3.org/ns/dcat#"
    ];

    protected $ld_properties = [
        "title"         => ["@id" => "dct:title"],
        "description"   => ["@id" => "dct:description"],
        "source"        => ["@id" => "dcat:accessURL"]
    ];

    protected function getType($plural = false)
    {
        return "wcag-em:webpage";
    }
}