<?php
/**
 * Created by PhpStorm.
 * User: markmooibroek
 * Date: 26/05/15
 * Time: 15:01
 */

namespace App\Models;

/**
 * Class Evaluation
 * @package App\Models
 *
 * @property Assertor creator
 */
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


    /**
     * @return Assertor|LDModel
     */
    public function creator()
    {
        return $this->belongsTo('App\Models\Assertor');
    }


    public function auditResult()
    {
        return $this->hasMany('App\Models\Assertion');
    }

    public function getAuditResultAttribute()
    {
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
            "creator" => "required",
            "creator.@id" => "required|ldid_exists",
            "creator.utt:_privateKey" => "required|private_key:creator.@id"
        ];
    }

    protected function getAuditValidationRules()
    {
        return [
            "auditResult" => "required|array"
        ];
    }

    /** Override parent to manually set test en result arrays */
    public function toArray()
    {

        $attributes = $this->attributesToArray();
        $all =  array_merge($this->getLDHeader(), $attributes, $this->relationsToArray());

        if(!$this->hasInclude('creator')){
            $all['creator'] = $this->creator->getLDId(true);
        }

        return $all;

    }

}