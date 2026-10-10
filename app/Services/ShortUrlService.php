<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\ShortUrl;
use Illuminate\Support\Str;


class ShortUrlService {

    public function shortUrlList(Request $request) {
        $perPage = $request->per_page ? $request->per_page : 10;
        $authUser = $request ->user();
        $authUser->load('role', 'tenant');
        $role = $authUser->role ? $authUser->role->name : null;
        $urls =[];
        if(!$role) {
            return redirect()->back()->with(['error' => 'You are not authorized.'], 403);
        }
        if($role== 'Super Admin') {
            //This is for SuperAdmin
            $urls = ShortUrl::query()->withoutGlobalScope('tenant')->paginate($perPage);
        }elseif($role== 'Admin') {
            //This is for Admin
            $urls = ShortUrl::query()->paginate($perPage);
        }elseif($role== 'Member') {
            //This is for Member
            $urls = ShortUrl::query()->where('created_by', $authUser->id)->paginate($perPage);
        }
        if(count($urls)>0) {
            $urls->load('tenant','createdBy');
        }
        return $urls;
    }

    public function saveShortUrl(Request $request) {
        try{
            $user = $request->user();
            $user->load('role', 'tenant');
            $role = $user->role ? $user->role->name : null;
            if(!$role) {
                return redirect()->back()->with(['error' => 'You are not authorized.'], 403);
            }
            if($role === 'Super Admin') {
                return redirect()->back()->with(['error' => 'You are not authorized.'], 403);
            }

            do{
                $code = Str::random(6);
            } while(
                ShortUrl::where('short_url', $code)->exists()
                );

            $url = new ShortUrl();
            $url->short_url = $code;
            $url->original_url = $request->original_url;
            $url->created_by = $user->id;
            $url->save();
            return redirect()->route('shortUrlList')->with(['success' => 'Short url created successfully!']);

        }catch(\Exception $e) {
            Log::error([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return redirect()->back()->with(['error' => 'Server error.']);
        }
    }

    public function redirectUrl(string $code) {
        $url = ShortUrl::where('short_url', $code)->first();
        $clicks =  intval($url->clicks) + 1;
        if(!$url) {
            return redirect()->back()->with(['error' => "Url doesn't exists."]);
        }
         $url->clicks = $clicks;
         $url->save();
        return redirect()->away($url->original_url);
    }
}
