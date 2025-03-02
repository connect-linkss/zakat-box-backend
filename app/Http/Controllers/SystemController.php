<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Donate;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function __construct(
        private User $user,
        private Donate $donates
    ) {}
    /**
     * @return Application|Factory|View
     */
    public function settings(): Factory|View|Application
    {
        return view('admin-views.settings');
    }
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function settingsUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ], [
            'name.required' => 'First name is required!',
        ]);

        $admin =  $this->user->find(auth('web')->id());
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        if ($request->hasFile('image')) {
            $destinationPath = public_path('images');
            $old_image = $admin->image;
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = microtime(true) . "." . $extension;
            if (strlen($imageName) > 50) {
                $imageName = substr($imageName, 0, 50 - strlen($extension) - 1) . '.' . $extension;
            }
            if ($image->move($destinationPath, $imageName)) {
                $admin->image = $imageName;
            }
        }
        $admin->save();
        Toastr::success(translate('Admin updated successfully!'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function settingsPasswordUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required|same:confirm_password|min:8',
            'confirm_password' => 'required',
        ]);
        $admin =  $this->user->find(auth('web')->id());
        $admin->password = bcrypt($request['password']);
        $admin->save();
        Toastr::success(translate('Admin password updated successfully!'));
        return back();
    }







    public function index(Request $request)
    {
        $donates = $this->donates->latest()->limit(20)->get();
        $dollar_some = Donate::where('payment_currency', 1)->sum('amount');
        $ll_some = Donate::where('payment_currency', 2)->sum('amount');
        $last_id = $donates->isNotEmpty() ? $donates->first()->id : 0;
        return view('admin-views.dashboard', compact('donates', 'dollar_some', 'll_some', 'last_id'));
    }

    public function data(Request $request)
    {
        $addNew = false;
        $last_id = $request->last_id;
        if ($last_id > 0) {
            $donates = $this->donates->where('id', '>', $last_id)->latest()->get();
            $last_id = $donates->isNotEmpty() ? $donates->first()->id : 0;
        } else {
            $donates = $this->donates->latest()->get();
        }
        if ($donates->count() > 0) {
            $addNew = true;
        }
        $dollar_some = Donate::where('payment_currency', 1)->sum('amount');
        $ll_some = Donate::where('payment_currency', 2)->sum('amount');
        $customersHTML = view('admin-views.partials.donates_rows', compact('donates'))->render();
        return response()->json([
            'customersHTML' => $customersHTML,
            'addNew' => $addNew,
            'last_id' => $last_id,
            'dollar_some' => $dollar_some,
            'll_some' => $ll_some,
        ]);
    }
}
