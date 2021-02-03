<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class IndexWebController extends CommonController{

    public function __construct()
    {

        $this->middleware(['jwt.auth','refreshtoken'])->except(['index']);
    }


}