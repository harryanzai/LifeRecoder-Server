<?php

namespace App\Transformers;

use App\Models\Topic;
use League\Fractal\TransformerAbstract;

class Topictransformer extends TransformerAbstract
{
    public function transform(Topic $topic)
    {
        return [
            'id' => $topic->id,
            'name' => $topic->name,
            'bio' => $topic->bio,
            'created_at' => $topic->created_at->toDateTimeString()
        ];
    }
}