<?php

namespace App\Models;

use App\Helpers\Traits\Favoritable;
use App\Helpers\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes,RecordsActivity,Favoritable;

    protected $dates = ['deleted_at'];



}
