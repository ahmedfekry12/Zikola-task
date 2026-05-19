<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected $paginate;
    
    public function __construct()
    {
       $this->paginate=request()->paginate?? 10;
    }
}
