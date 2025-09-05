<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExpertAdvisorController extends Controller
{
    public function index()
    {
        return view('account.index');
    }
}
