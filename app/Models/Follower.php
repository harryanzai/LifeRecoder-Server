<?php

namespace App\Models;

use App\Helpers\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use RecordsActivity;

    protected $guarded = [];


    /**
     * 记录用户的行为不会自动触发created，通过此方法手动存储用户行为
     */
    public function recordFollows()
    {
        $this->recordActivity('created');
    }

    public function unRecordFollows()
    {
        $this->activity()->delete();
    }



}
