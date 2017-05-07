<?php

namespace App\Helpers\Traits;

use Illuminate\Support\Facades\Auth;

trait RecordsActivity
{
    protected static function bootRecordsActivity()
    {
        if (Auth::guest()){
            return;
        }

        foreach (static::getActivitiesToRecord() as $event){

            static::$event(function ($model) use ($event){
                $model->recordActivity($event);
            });
        }

        static::deleting(function ($model) {
            $model->activity()->delete();
        });

    }

    protected static function getActivitiesToRecord()
    {

        return ['created'];
    }

    protected function recordActivity($event){
        $this->activity()->create([
            'user_id' => Auth::user()->id,
            'type' => $this->getActivityType($event)
        ]);

    }

    public function activity()
    {
        return $this->morphMany('App\Models\Activity', 'subject');
    }

    protected function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
    }





}