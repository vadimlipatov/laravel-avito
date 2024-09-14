<?php

namespace App\Http\Controllers\Cabinet;

use App\Entity\Region;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();

        return view('cabinet.profile.home', compact('user'));
    }


    public function edit()
    {
        $user = Auth::user();

        return view('cabinet.profile.edit', compact('user'));;
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => "required|string|max:255",
            'last_name' => "required|string|max:255",
        ]);

        $user = Auth::user();

        $user->update([
            'name' => $request['name'],
            'last_name' => $request['last_name'],
        ]);

        return redirect()->route('cabinet.profile.home');
    }
}
