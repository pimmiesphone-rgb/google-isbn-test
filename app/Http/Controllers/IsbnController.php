<?php

namespace App\Http\Controllers;

use App\Services\GoogleBooksService;
use Illuminate\Http\Request;

class IsbnController extends Controller
{
    public function index()
    {
        return view('isbn-lookup');
    }
}
