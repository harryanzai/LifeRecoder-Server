<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticlesController extends ApiController
{

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth.api');

    }


    public function index()
    {

    }


    public function show()
    {

    }

    public function store()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }

}
