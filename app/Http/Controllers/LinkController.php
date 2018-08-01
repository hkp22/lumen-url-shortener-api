<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Link;

class LinkController extends Controller
{
    public function show(Request $request)
    {
        $code = $request->get('code');

        $link = Link::byCode($code)->first();
        // $link = \Cache::rememberForever("link.{$code}", function () use ($code) {
        //     return Link::byCode($code)->first();
        // });

        if (!$link) {
            return response(null, 404);
        }

        $link->increment('used_count');

        $link->touchTimestamp('last_used');

        return $this->linkResponse($link);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
           'url' => 'required|url'
        ], [
            'url.required' => 'Please enter a valid URL.',
            'url.url' => 'Please enter a valid URL.'
        ]);

        $link = Link::firstOrNew([
            'original_url' => $request->get('url')
        ]);

        if (!$link->exists) {
            $link->save();
        }

        $link->increment('requested_count');

        $link->touchTimestamp('last_requested');

        return $this->linkResponse($link, [], 201);
    }
}
