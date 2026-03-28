<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;

abstract class Controller
{
    use Authorizable, Authenticatable;
}
