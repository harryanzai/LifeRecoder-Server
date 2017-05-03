<?php
/**
 * Created by PhpStorm.
 * User: wangju
 * Date: 2017/5/3
 * Time: 下午12:20
 */

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
            'path' => $photo->path,
            'thumbnail_path' => $photo->thumbnail_path,
            'description' => $photo->description

        ];
    }
}