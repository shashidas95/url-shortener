<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UrlController extends Controller
{

    public function shorten(Request $request)
    {
        // Check if the user is authenticated
        if ($request->user()) {
            $user = $request->user();
            $request->validate([
                'long_url' => 'required|url',
            ]);
            $longUrl = $request->input('long_url');

            // Check if the URL already exists
            $url = Url::where('long_url', $longUrl)->first();

            if (!$url) {
                // Generate a unique short URL
                $shortUrl = Str::random(6); // You may use a more sophisticated method

                if (!empty($shortUrl)) {
                    $url = Url::create([
                        'long_url' => $longUrl,
                        'short_url' => $shortUrl,
                        'user_id' => $user->id,
                    ]);
                } else {
                    // Handle the case where $shortUrl is empty (perhaps log an error)
                    return response()->json(['error' => 'Failed to generate a unique short URL'], 500);
                }
            }

            return response()->json(['short_url' => $url->short_url]);
        } else {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
    }

    public function redirect($shortUrl)
    {
        $url = Url::where('short_url', $shortUrl)->first();

        if ($url) {
            // Update visit count
            $url->increment('visit_count');

            return redirect($url->long_url);
        }

        return response()->json(['error' => 'URL not found'], 404);
    }
}
