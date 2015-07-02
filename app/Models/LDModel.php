<?php
/**
 * Created by PhpStorm.
 * User: markmooibroek
 * Date: 26/05/15
 * Time: 15:02
 */

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class LDModel extends Model
{

    const NS = 'utt';

    public $timestamps = false;

    private $global_vocs = [
        "dct" => "http://purl.org/dc/terms/#"
    ];

    protected $has_context = true;

    protected $dates = ["date"];

    protected $model_vocs = [];
    protected $ld_properties = [];

    protected $hidden = ['deleted_at', 'id'];


    public static function create(array $attributes = [])
    {
        $model = new static($attributes);
        $model->date = new Carbon;
        $model->save();

        return $model;
    }

    protected function getLDHeader()
    {
        if ($this->has_context) {
            $expand = LDModel::hasInclude("context");
            return [
                "@context" => $this->getContext($expand),
                "@id" => $this->getLDId(),
                "@type" => $this->getType(),
            ];
        } else {
            return [
                "@id" => $this->getLDId(),
                "@type" => $this->getType(),
            ];
        }
    }


    public function getContext($expand = false)
    {

        if ($expand) {
            return array_merge(
                $this->getVocs(),
                $this->getProperties()
            );
        } else {
            return url('contexts/' . $this->getType(true) . '.jsonld');
        }
    }

    protected function getType($plural = false)
    {
        return $this->getTrueType($plural);
    }

    private function getTrueType($plural = false)
    {
        $type = strtolower(str_replace("controller", "", strtolower($this->getClassName())));
        return $plural ? str_plural($type) : $type;
    }

    protected function getLDId($shorthand = false)
    {
        if ($this->has_context || $shorthand) {
            return LDModel::NS . ":" . $this->getTrueType(true) . "/" . $this->id;
        } else {
            return url() . "/" . $this->getTrueType(true) . "/" . $this->id;
        }
    }

    public static function getIdFromLdId($ldId)
    {
        preg_match("~\\S+/([0-9]+)~", $ldId, $matches);
        if(count($matches) < 2)
            return null;
        return $matches[1];
    }

    public static function hasInclude($value)
    {
        return Input::has('include') && str_contains(Input::get('include'), $value);
    }

    public function toArray()
    {
        $attributes = $this->attributesToArray();
        return array_merge($this->getLDHeader(), $attributes, $this->relationsToArray());
    }

    protected function getClassName()
    {
        return $this->parse_classname(get_class($this))['classname'];
    }


    public function getDateAttribute($value)
    {
        return $this->asDateTime($value)->toIso8601String();
    }

    private function parse_classname($name)
    {
        return array(
            'namespace' => array_slice(explode('\\', $name), 0, -1),
            'classname' => join('', array_slice(explode('\\', $name), -1)),
        );
    }

    private function getVocs()
    {
        return array_merge($this->global_vocs, $this->model_vocs);
    }

    private function getProperties()
    {
        $sharedProperties = [];

        $sharedProperties[LDModel::NS] = url() . '/';
        $sharedProperties["date"] = ["@type" => "http://www.w3.org/2001/XMLSchema#dateTime", "@id" => "dct:date"];

        return array_merge($sharedProperties, $this->ld_properties);
    }

    protected function getValidationRules()
    {
        $type = $this->getType();

        return [
            "@context" => "required|check_context",
            "@type" => "required|regex:~\\b$type\\b~"
        ];
    }

    public function validateInput(array $values)
    {

        Validator::extend("check_context", function($atts, $value, $parameters) use ($values){

            preg_match("~^.+/([a-zA-Z]+).jsonld$~", $value, $matches);
            if(count($matches) == 0)
                return false;

            $model = ucfirst(str_singular($matches[1])); //0 = entire string
            $allowed_models = ["Webpage", "Evaluation", "Assertion", "Assertor"];

            $class = "App\\Models\\" . $model;
            if(!class_exists($class) || !in_array($model, $allowed_models))
                return false;

            /** @var LDModel $instance */
            $instance = new $class();
            if(!method_exists($instance, "getContext"))
                return false;

            return true;

        });

        Validator::extend('private_key', function ($attr, $value, $parameters) use ($values) {


            if (!isset($parameters[0]))
                return false;

            $ldid = array_get($values, $parameters[0]);
            $id = $this->getIdFromLdId($ldid);

            if(!$id)
                return false;

            $result = Assertor::where(['id' => $id, 'key_id' => $value])->first();

            return $result != null;
        });

        Validator::extend('ldid_exists', function ($attr, $value, $parameters) {

            preg_match("~^id:([a-z]+)/([0-9]+)$~", $value, $matches);
            if(count($matches) == 0)
                preg_match("~^utt:([a-z]+)/([0-9]+)$~", $value, $matches);
            if(count($matches) == 0)
                preg_match("~^" .  url("/") . "([a-z]+)/([0-9]+)$~", $value, $matches);

            if (count($matches) != 3)
                return false;

            $id = $matches[2];

            $model = "App\\Models\\" . str_singular(ucfirst($matches[1]));

            if (!class_exists($model))
                return false;


            $result = $model::where(['id' => $id])->first();
            return $result != null;
        });

        Validator::extend('ldid_model', function ($attr, $value, $parameters) {

            preg_match("~^id:([a-z]+)/([0-9]+)$~", $value, $matches);

            if(count($matches) == 0)
                preg_match("~^utt:([a-z]+)/([0-9]+)$~", $value, $matches);
            if (count($matches) != 3)
                return false;

            //limit results to specific model
            if (!isset($parameters[0]))
                return false;

            $invalidModel = $matches[1] != $parameters[0];
            if ($invalidModel)
                return false;

            return true;
        });

        return Validator::make($values, $this->getValidationRules());
    }

}