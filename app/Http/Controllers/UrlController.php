<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    public function shorten(Request $request)
    {
        $user = $request->user();
        $long_url = $request->input('long_url');
        $url = Url::where('long_url', $long_url)->first();
       if(!$url){
        $shortUrl = Str::random(6);
        $url = Url::create([
            "short_url"=>$request
        ])
       }
    }
}
