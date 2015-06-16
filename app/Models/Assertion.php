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
    protected $hidden = ["id", "asserted_by", "subject_id", "evaluation_id", "evaluation"];
    protected $autoExpandArray = [
        'EvaluationController@addAction',
        'AssertionController@getAction'
    ];

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


    /** Override parent to manually set test en result arrays */
    public function toArray()
    {

        $routeUses = str_replace('App\Http\Controllers\v1\\','', app('request')->route()[1]["uses"]);

        $expand = LDModel::hasInclude("auditResult") || in_array($routeUses, $this->autoExpandArray);

        if ($expand) {
            $relations["assertedBy"] = $this->assertor()->getResults()->getLDId(true);
            $relations["evaluation"] = $this->evaluation()->getResults()->getLDId(true);
            $relations["subject"] = $this->subject()->getResults()->getLDId(true);

            $attributes = array_merge($relations,$this->attributesToArray());

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
        } else {
            return $this->getLDId();
        }
    }

    protected function getValidationRules()
    {
        $parentValidation = parent::getValidationRules();

        return array_merge(
            $parentValidation,
            $this->getAuditValidationRules()
        );

    }

    protected function getAuditValidationRules()
    {
        return [
            "subject" => "required|ldid_exists",
            "mode" => "required",
            "assertedBy.@id" => "required|ldid_model:assertors|ldid_exists",
            "assertedBy._privateKey" => "required|private_key:assertedBy.@id",
            "test.@id" => "required",
            "test.@type" => "required|regex:~^\\bTestRequirement\\b$~",
            "result.@type" => "required|regex:~^\\bTestResult\\b$~",
            "result.outcome" => "required"
        ];
    }
}