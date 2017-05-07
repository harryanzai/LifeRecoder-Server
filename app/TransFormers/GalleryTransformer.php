<?php

namespace App\Transformers;


use App\Models\Gallery;
use League\Fractal\TransformerAbstract;
use App\Transformers\Phototransformer;

class GalleryTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'user','photos'
    ];

    public function transform(Gallery $gallery)
    {
        return [
            'id' => $gallery->id,
            'title' => $gallery->title,
            'content' => $gallery->content,
            'create_at' =>$gallery->created_at->toDateTimeString()
        ];
    }

    public function includeUser(Gallery $gallery)
    {

        $user = $gallery->user;
        return $this->item($user, new UserTransformer);
    }

    public function includePhotos(Gallery $gallery)
    {

        $photos = $gallery->photos;
        return $this->collection($photos,new Phototransformer,true);

    }

}