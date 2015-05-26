<?php
/**
 * Created by PhpStorm.
 * User: markmooibroek
 * Date: 26/05/15
 * Time: 11:01
 */

namespace app\Http\Controllers\v1;

use App\Http\Controllers\Controller;

class WebpageController extends Controller
{


    public function listAction()
    {
        return [];
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