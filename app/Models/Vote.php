<?php

namespace App\Models;

use App\Helpers\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    public function voted()
    {
        return $this->morphTo();
    }
}
