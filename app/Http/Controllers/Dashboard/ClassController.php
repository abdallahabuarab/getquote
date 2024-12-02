<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\ClassName;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $classes = ClassName::all();
        return view('portal.classes.index', compact('classes'));
    }
}
