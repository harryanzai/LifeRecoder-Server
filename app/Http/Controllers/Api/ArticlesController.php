<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticlesController extends ApiController
{

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth.api')->except('index');

    }


    public function index()
    {

        $res1 = DB::table('galleries')
            ->select(DB::raw('gallerty as type * as data'));

        return $res1->get();

        $res2 = DB::table('articles ')
            ->select(DB::raw('articles as type, * as data'));

        $res = $res1->union($res2);

        return $res->get();


        dd($res);

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
