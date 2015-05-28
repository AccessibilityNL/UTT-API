<?php namespace App\Http\Controllers;

use App\Models\LDModel;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    var $context = '';
    var $type = '';

    public function getContext($model)
    {
        $class = "App\\Models\\" . ucfirst(str_singular($model));
        if(!class_exists($class))
            app()->abort(404, "Class not found");

        /** @var LDModel $instance */
        $instance = new $class();

        if(!method_exists($instance, "getContext"))
            app()->abort(404, "Class not found");

        return [
             "@context" => $instance->getContext(true)
        ];
    }

}
