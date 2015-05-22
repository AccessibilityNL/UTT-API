<?php
/**
 * Created by PhpStorm.
 * User: markmooibroek
 * Date: 22/05/15
 * Time: 13:30
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{

    use SoftDeletes;

}