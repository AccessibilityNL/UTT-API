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
        "@vocab" => "http://www.w3.org/ns/earl#",
        "earl" => "http://www.w3.org/ns/earl#",
        "wcagem" => "http://www.w3.org/TR/WCAG-EM/#",
        "wcag20" => "http://www.w3.org/TR/WCAG20/#",
        "auto" => "https://www.w3.org/community/auto-wcag/wiki/",
    ];

    protected $ld_properties = [
        "subject" => ["@type" => "@id", "@id" => "earl:subject"],
        "assertedBy" => ["@type" => "@id", "@id" => "earl:assertedBy"],
        "mode" => ["@type" => "@id", "@id" => "earl:mode"],
        "outcome" => ["@type" => "@id", "@id" => "earl:outcome"],
        "isPartOf" => [
            "@type" => "@id",
            "@id" => "dct:isPartOf"
        ],
        "evaluation" => [
            "@type" => "@id",
            "@reverse" => "wcagem:step4"
        ],
    ];

}