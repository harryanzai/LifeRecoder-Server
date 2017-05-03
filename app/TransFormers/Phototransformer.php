<?php
/**
 * Created by PhpStorm.
 * User: wangju
 * Date: 2017/5/3
 * Time: 下午12:20
 */

namespace App\Transformers;


use App\Models\Photo;
use function GuzzleHttp\Psr7\uri_for;
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