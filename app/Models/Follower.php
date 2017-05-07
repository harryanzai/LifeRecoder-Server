<?php

namespace App\Models;

use App\Helpers\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use RecordsActivity;

    protected $guarded = [];
}
