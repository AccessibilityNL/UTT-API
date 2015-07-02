<?php namespace App\Http\Controllers;

use App\Models\LDModel;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    var $context = '';
    var $type = '';

    public function response($data, $code = 200, $headers = [ "Access-Control-Allow-Origin" => "*"])
    {
        return response($data, $code, $headers);
    }

    public function getContext($version, $model)
    {
        $model = ucfirst(str_singular($model));
        $allowed_models = ["Webpage", "Evaluation", "Assertion", "Assertor"];

        $class = "App\\Models\\" . $model;
        if(!class_exists($class) || !in_array($model, $allowed_models))
            app()->abort(404, "Class '$class' not found");

        /** @var LDModel $instance */
        $instance = new $class();

        if(!method_exists($instance, "getContext"))
            app()->abort(404, "Class not found");

        return [
             "@context" => $instance->getContext(true)
        ];
    }
}
