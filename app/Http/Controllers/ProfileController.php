<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Level;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * ProfileController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ["except" => "showProfile"]);
    }

    /**
     * Show profile by url
     *
     * @param $name
     * @return mixed
     */
    public function showProfile($name)
    {
        $user = User::getUser($name);
        return view('profile.index', compact('user'));
    }

    /**
     * Save account settings
     *
     * @param UpdateProfileRequest|Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveAccountSettings(UpdateProfileRequest $request)
    {
        // Check uniqueness for slug
        $slug = $request->input('slug');

        if (!is_null(User::where('display_name', $slug)->first())) {
            return redirect()->back()->with('status', trans('validation.unique', ["attribute" => trans('setting/account.slug')]));
        }

        $user = auth()->user();

        // If the user only changed slug
        if ($request->input('display_name') == $user->display_name &&
            $request->input('email') == $user->email &&
            $request->input('description') == $user->description &&
            $request->input('password') == "") {
            $user->update($request->only('slug'));
            return redirect()->back()->with('status', trans('messages.slug_updated'));
        }

        // Update profile and sync
        if (!$user->updateProfile($request->except('_token'))) {
            return redirect()->back()->with('status', trans('messages.retry'));
        }

        // Update password
        if ($request->input('password') != "") {
            $user->update($request->except(['_token', 'password']));
            $user->password = bcrypt($request->input('password'));
            $user->save();
        } else {
            $user->update($request->except(['_token', "password"]));
        }

        return redirect()->back()->with('status', trans('messages.account_updated'));
    }

    /**
     * My profile
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myProfile()
    {
        $user = auth()->user();
        return view('profile.index', compact('user'));
    }

    /**
     * Show settings page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showSettings()
    {
        return view('profile.settings.index');
    }

    /**
     * Show settings subscription page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showSubscription()
    {
        return view('profile.settings.subscription');
    }

    /**
     * Show notification settings page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showNotificationSettings()
    {
        return view('profile.settings.notification');
    }

    /**
     * Refresh user's data
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function refresh()
    {
        return auth()->user()->refresh()
            ? redirect()
            ->back()
            ->with('status', trans("messages.refresh_success"))
            : redirect()
            ->back()
            ->with('notification', trans("messages.retry"));
    }

    /**
     * Subscribe
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function subscribe()
    {
        if (!auth()->user()->subscribed()) {
            return auth()->user()->subscribe() ?
                redirect()->back()->with('status', trans("messages.subscribe_success"))
                : redirect()->back()->with('notification', trans('messages.retry'));
        }
        return redirect()->back();
    }
    /**
     * Unsubscribe from everything
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unsubscribe()
    {
        if (auth()->user()->subscribed()) {
            return auth()->user()->unsubscribe() ?
                redirect()->back()->with('status', trans("messages.unsubscribe_success"))
                : redirect()->back()->with('notification', trans('messages.retry'));
        }
        return redirect()->back();
    }

    /**
     * Show levels page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLevel()
    {
        $levels = Level::all();
        return view('profile.settings.level', compact('levels'));
    }


    public function watchLaters()
    {
        return view();
    }

    public function favorites()
    {
        return view();
    }


}
