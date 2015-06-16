<?php
/**
 * Created by PhpStorm.
 * User: markmooibroek
 * Date: 26/05/15
 * Time: 15:01
 */

namespace App\Models;


class Evaluation extends LDModel
{

    protected $fillable = ["date"];
    protected $hidden = ["creator_id"];
    protected $appends = ["auditResult"];


    protected $model_vocs = [
        "earl" => "http://www.w3.org/TR/EARL10-Schema/#",
        "wcag20" => "http://www.w3.org/TR/WCAG20/#"
    ];

    protected $ld_properties = [
        "creator" => "dct:creator",
        "auditResult" => [
            "@type" => "@id",
            "@id" => "wcagem:step4"
        ]
    ];


    public function creator(){
        return $this->belongsTo('App\Models\Assertor');
    }


    public function auditResult(){
        return $this->hasMany('App\Models\Assertion');
    }

    public function getAuditResultAttribute(){
        return $this->auditResult()->get();
    }

    protected function getValidationRules()
    {
        $parentValidation = parent::getValidationRules();

        return array_merge(
            $parentValidation,
            $this->getClientValidationRules(),
            $this->getAuditValidationRules()
        );

    }

    protected function getClientValidationRules()
    {
        return [
            "creator"               => "required",
            "creator.@id"           => "required|ldid_exists",
            "creator._privateKey"   => "required|private_key:creator.@id"
        ];
    }

    protected function getAuditValidationRules()
    {
        return [
            "auditResult"                   => "required",
            "auditResult.@type"             => "required|regex:~^\\bAssertion\\b$~",
            "auditResult.subject"           => "required|ldid_exists",
            "auditResult.mode"              => "required",
            "auditResult.assertedBy"        => "required|ldid_model:assertors|ldid_exists",
            "auditResult.test.@id"          => "required",
            "auditResult.test.@type"        => "required|regex:~^\\bTestRequirement\\b$~",
            "auditResult.result.@type"      =>  "required|regex:~^\\bTestResult\\b$~",
            "auditResult.result.outcome"    =>  "required"
        ];
    }
}