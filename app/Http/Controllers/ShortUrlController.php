<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\ShortUrlService;
use App\Http\Requests\StoreShortUrlRequest;


class ShortUrlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ShortUrlService $shortUrlService)
    {
        $urls = $shortUrlService->shortUrlList($request);
        return view('short_url.index', compact('urls'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = request()->user();
        if($user->role_id === 1) {
            return redirect()->back()->with(['error' => 'You are not authorized.']);
        }
        return view('short_url.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShortUrlRequest $request, ShortUrlService $shortUrlService)
    {
        $validated = $request->validated();
        return $shortUrlService->saveShortUrl($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShortUrl $shortUrl)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShortUrl $shortUrl)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShortUrl $shortUrl)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShortUrl $shortUrl)
    {
        //
    }

    public function redirect(string $code, ShortUrlService $shortUrlService)
    {
        return $shortUrlService->redirectUrl($code);
    }
}
