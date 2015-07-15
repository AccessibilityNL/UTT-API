<?php
/**
 * Created by PhpStorm.
 * User: markmooibroek
 * Date: 26/05/15
 * Time: 11:01
 */

namespace app\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Webpage;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;


class WebpageController extends Controller
{

    private $creationRules = [
        "@context" => "required",
        "@type" => "required",
        "title" => "required",
        "source" => "required|url",
    ];

    public function createAction()
    {
        $validator = Validator::make(
            Input::all(),
            $this->creationRules
        );

        if($validator->fails()){
            app()->abort(422, $validator->messages()->first());
        }

        $webpage = Webpage::create(Input::all());

        return $this->response($webpage);
    }

    public function getAction($id)
    {
        return $this->response(Webpage::find($id));
    }


}