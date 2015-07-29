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
use App\Models\LDModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class AssertionController extends Controller
{

    public function listAction()
    {
        return $this->response(
            Assertion::all()
        );
    }

    public function getAction($id)
    {
        return $this->response(
            Assertion::find($id)
        );
    }

    public function createAction()
    {
        $model = new Assertion();
        $input = Input::all();

        $validator = $model->validateInput($input);

        if ($validator->fails()) {
            app()->abort(422, $validator->errors()->first());
        }

        $model->fill([
            "date" => new Carbon,
            "mode" => Input::get("mode"),
            "test_id" => Input::get("test.@id"),
            "test_type" => Input::get("test.@type"),
            "result_type" => Input::get("result.@type"),
            "result_outcome" => Input::get("result.outcome"),
            "subject_id" => LDModel::getIdFromLdId(Input::get("subject")),
            "asserted_by" => LDModel::getIdFromLdId(Input::get("assertedBy.@id")),
            "evaluation_id" => LDModel::getIdFromLdId(Input::get("evaluation")),
        ]);

        $model->save();

        return $this->response($model);
    }


}