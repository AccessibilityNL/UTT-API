<?php
/**
 * Created by PhpStorm.
 * User: markmooibroek
 * Date: 22/05/15
 * Time: 11:47
 */

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Models\User;

class WelcomeController extends Controller
{

    public function showIndex()
    {
        return ["msg" => "Ho"];
    }

}