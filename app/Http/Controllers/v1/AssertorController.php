<?php
/**
 * Created by PhpStorm.
 * User: markmooibroek
 * Date: 02/06/15
 * Time: 11:01
 */

namespace app\Http\Controllers\v1;


use App\Http\Controllers\Controller;
use App\Models\Assertor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class AssertorController extends Controller{


    public function getorCreateAction(){

        if(!Input::has('q'))
            app()->abort(422, "Invalid user key");


        $criteria = ["key_id" => Input::get('q')];
        $assertor = Assertor::where($criteria)->get()->first();

        $criteria["date"] = new Carbon;
        if(!$assertor)
            $assertor = Assertor::create($criteria);

        return Assertor::find($assertor->id);

    }

    public function getAction($id)
    {
        $assertor = Assertor::find($id);
        if(!$assertor)
            app()->abort(404, "Assertor not found");
        return $assertor;
    }

}