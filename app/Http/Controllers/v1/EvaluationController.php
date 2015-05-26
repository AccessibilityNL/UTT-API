<?php
/**
 * Created by PhpStorm.
 * User: markmooibroek
 * Date: 26/05/15
 * Time: 11:01
 */

namespace app\Http\Controllers\v1;


use App\Http\Controllers\Controller;
use App\Models\Evaluation;

class EvaluationController extends Controller
{

    public function listAction()
    {
        return [];
    }

    public function getAction()
    {
        return Evaluation::find(1);
    }

    public function createAction()
    {
//        $evaluation
    }


}