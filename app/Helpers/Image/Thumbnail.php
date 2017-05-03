<?php

namespace App\Helpers\Image;

use Image;

class Thumbnail
{

    protected $width;
    protected $height;

    public function __construct($width=200,$height=200)
    {
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }




    public function make($src,$dest)
    {


        Image::make($src)
            ->fit($this->width,$this->height)
            ->save($dest);


    }

    public function resizeWithWidth($src,$desc)
    {
        $img = Image::make($src);
        $img->resize($this->width, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        $img->save($desc);
    }

    public function resizeWithHeight($src,$desc)
    {
        $img = Image::make($src);
        $img->resize(null, $this->height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($desc);
    }


    public function makeWithPath($path)
    {
        return Image::make($path)->fit($this->width,$this->height);
    }

}