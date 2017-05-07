<?php

namespace App\Transformers;

use App\Models\Photo;
use League\Fractal\TransformerAbstract;

class Phototransformer extends TransformerAbstract
{
    public function transform(Photo $photo)
    {
        return [
            'id' => $photo->id,
            'name' => $photo->name,
            'path' => url($photo->path),
            'thumbnail_path' => url($photo->thumbnail_path),
            'description' => $photo->description

        ];
    }
}