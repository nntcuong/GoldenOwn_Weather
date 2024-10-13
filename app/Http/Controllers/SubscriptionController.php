<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    

    public function subscribe(Request $request)
{
    $user = Auth::user();

    if (!$user->is_confirmed && $request->has('city')) {
        $user->is_confirmed = true;

        $user->address = $request->input('city'); 
        $user->save();

        return redirect()->back()->with('success', 'You have successfully subscribed to the daily weather forecast!');
    }

    return redirect()->back()->with('error', 'You are already subscribed.');
}


    public function unsubscribe(Request $request)
    {
        $user = Auth::user();

        if ($user->is_confirmed) {
            $user->is_confirmed = false;
            $user->save();

            return redirect()->back()->with('success', 'You have successfully unsubscribed from the daily weather forecast.');
        }

        return redirect()->back()->with('error', 'You are not subscribed yet.');
    }
}
