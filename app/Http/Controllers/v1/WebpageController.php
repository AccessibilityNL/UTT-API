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

        $webpage = Webpage::where(["source" => Input::get('source')])->first();
        if(!$webpage)
            $webpage = Webpage::create(Input::all());

        return $webpage;
    }

    public function getAction($id)
    {
        return [
            "@context" => url("/evaluations/context.jsonld"),
            "@type" => "evaluation",
            "@id" => "utt:evaluations/" . $id,
            "creator" => "utt:assertors/123456",
            "date" => "2015-01-01",
            "auditResult" => [],
            "evaluationScope" => []
        ];

    }


}