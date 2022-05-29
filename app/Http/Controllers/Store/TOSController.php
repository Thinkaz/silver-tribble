<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class TOSController extends Controller
{
    public function index()
    {
        $tos = Configuration::where('key', 'terms_of_service')->first()->value;
        
        return view('store.tos', compact('tos'));
    }
}
