<?php
/**
 * Created by PhpStorm.
 * User: markmooibroek
 * Date: 26/05/15
 * Time: 11:01
 */

namespace app\Http\Controllers\v1;


use App\Http\Controllers\Controller;
use App\Models\Assertion;
use App\Models\Assertor;
use App\Models\Evaluation;
use App\Models\LDModel;
use App\Models\Webpage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

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
        $model = new Evaluation();
        $input = Input::all();

        $validator = $model->validateInput($input);

        if ($validator->fails()) {
            app()->abort(422, $validator->errors()->first());
        }


        $assertion = new Assertion([
            "date" => new Carbon,
            "mode" => Input::get("auditResult.mode"),
            "test_id" => Input::get("auditResult.test.@id"),
            "test_type" => Input::get("auditResult.test.@type"),
            "result_type" => Input::get("auditResult.result.@type"),
            "result_outcome" => Input::get("auditResult.result.outcome")
        ]);

        DB::transaction(function () use ($model, $assertion) {

            /** @var Assertor $assertor */
            $assertor = Assertor::find(LDModel::getIdFromLdId(Input::get("creator.@id")));

            $model->fill(["date" => new Carbon]);
            $model->creator()->associate($assertor);
            $model->save();

            /** @var Webpage $subject */
            $subject = Webpage::find(LDModel::getIdFromLdId(Input::get("auditResult.subject")));

            $assertion->assertor()->associate($assertor);
            $assertion->subject()->associate($subject);
            $assertion->evaluation()->associate($model);

            $assertion->save();
        });

        return $model;
    }


}