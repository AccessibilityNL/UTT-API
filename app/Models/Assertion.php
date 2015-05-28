<?php
/**
 * Created by PhpStorm.
 * User: markmooibroek
 * Date: 26/05/15
 * Time: 15:01
 */

namespace App\Models;


class Assertion extends LDModel
{

    protected $model_vocs = [
        "earl" => "http://www.w3.org/TR/EARL10-Schema/#"
    ];

    protected $ld_properties = [
        "outcome" => ["@type" => "@id", "@id" => "earl:outcome"],
        "subject" => ["@type" => "@id", "@id" => "earl:subject"],
        "assertedBy" => ["@type" => "@id", "@id" => "earl:assertedBy"],
        "testRequirement" => ["@type" => "@id", "@id" => "earl:testRequirement"],
        "result" => ["@id" => "earl:result"]
    ];

}