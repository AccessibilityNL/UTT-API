<?php
/**
 * Created by PhpStorm.
 * User: markmooibroek
 * Date: 26/05/15
 * Time: 15:01
 */

namespace App\Models;


class Assertor extends LDModel{

    protected $fillable = ["key_id", "date"];

    protected $has_context = false;

    protected $model_vocs = [];
    protected $ld_properties = [];

    protected $hidden = ["password"];

    protected function getType($plural = false)
    {
        return "http://xmlns.com/foaf/spec/#Person";
    }

}