<?php
/**
 * Created by PhpStorm.
 * User: markmooibroek
 * Date: 22/05/15
 * Time: 11:47
 */

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;

class WelcomeController extends Controller
{


    public function showIndex()
    {
        return ["msg" => "Hey"];
    }


}