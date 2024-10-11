<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AIController extends Controller
{
    protected $service;

    public function __construct(AIService $service)
    {
        $this->service = $service;
    }
}
