<?php
/**
 * Created by PhpStorm.
 * User: markmooibroek
 * Date: 26/05/15
 * Time: 15:01
 */

namespace App\Models;


use Illuminate\Support\Facades\Input;

class Assertion extends LDModel
{
    protected $hidden = ["asserted_by", "subject_id", "evaluation_id"];

    protected $fillable = [
        "date",
        "mode",
        "asserted_by",
        "subject_id",
        "test_id",
        "test_type",
        "test_partof_id",
        "result_type",
        "result_outcome"
    ];

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


    public function assertor()
    {
        return $this->belongsTo('App\Models\Assertor', "asserted_by");
    }

    public function subject()
    {
        return $this->belongsTo('App\Models\Webpage');
    }

    public function evaluation()
    {
        return $this->belongsTo('App\Models\Evaluation');
    }

    /** Override parent to manually set test en result arrays right */
    public function toArray()
    {
        $expand = LDModel::hasInclude("auditResult");

        if($expand) {
            $attributes = $this->attributesToArray();

            $attributes["test"] = [
                "@id" => $attributes["test_id"],
                "@type" => $attributes["test_type"]
            ];

            unset($attributes["test_id"]);
            unset($attributes["test_type"]);

            $attributes["result"] = [
                "@type" => $attributes["result_type"],
                "outcome" => $attributes["result_outcome"]
            ];

            unset($attributes["result_type"]);
            unset($attributes["result_outcome"]);


            //TODO handle partof
            unset($attributes["test_partof_id"]);
            unset($attributes["test_partof_type"]);

            return array_merge($this->getLDHeader(), $attributes, $this->relationsToArray());
        }else{
            return $this->getLDId();
        }
    }
}