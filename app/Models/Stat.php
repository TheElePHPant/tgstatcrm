<?php

namespace App\Models;

use App\Traits\TimeZone;
use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    use TimeZone;
    protected $guarded = ['id'];
}
