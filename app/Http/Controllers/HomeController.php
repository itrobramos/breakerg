<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Format;
use App\Models\School;
use App\Models\SentFormat;
use App\Models\SchoolFormat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
            return view('home');
       
    }
}
