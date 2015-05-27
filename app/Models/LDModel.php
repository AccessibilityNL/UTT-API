<?php
/**
 * Created by PhpStorm.
 * User: markmooibroek
 * Date: 26/05/15
 * Time: 15:02
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;

class LDModel extends Model
{

    const NS = 'utt';

    private $global_vocs = [
        "dct" => "http://purl.org/dc/terms/#"
    ];


    protected $dates = ["date"];

    protected $model_vocs = [];
    protected $ld_properties = [];

    protected $hidden = ['deleted_at', 'id'];

    protected function getLDHeader()
    {
        $expand = Input::has('include') && str_contains(Input::get('include'), 'context');
        return [
            "@context" => $this->getContext($expand),
            "@type" => $this->getType(),
            "@id" => $this->getLDId()
        ];
    }

    public function getContext($expand = false)
    {

        if ($expand) {
            return array_merge(
                $this->getVocs(),
                $this->getProperties()
            );
        } else {
            return url($this->getType(true) . '/context.jsonld');
        }
    }

    protected function getType($plural = false)
    {
        $type = strtolower(str_replace("controller", "", strtolower($this->getClassName())));
        return $plural ? str_plural($type) : $type;
    }

    protected function getLDId()
    {
        return LDModel::NS . ":" . $this->getType(true) . "/" . $this->id;
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


    private function parse_classname($name)
    {
        return array(
            'namespace' => array_slice(explode('\\', $name), 0, -1),
            'classname' => join('', array_slice(explode('\\', $name), -1)),
        );
    }

    private function getVocs(){
        return array_merge($this->global_vocs, $this->model_vocs);
    }

    private function getProperties(){
        $sharedProperties = [];

        $sharedProperties[LDModel::NS] = url() . '/';

        if($this->timestamps){
            $sharedProperties["created_at"] = [ "@id" => "dct:date"];
            $sharedProperties["updated_at"] = [ "@id" => "dct:date"];
        }
        return array_merge($sharedProperties, $this->ld_properties);
    }


}