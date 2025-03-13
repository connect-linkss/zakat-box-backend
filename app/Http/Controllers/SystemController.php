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
        $donates = $this->donates->orderBy('id', 'DESC')->limit(50)->get();
        $dollar_some = number_format((int)Donate::where('payment_currency', 1)->sum('amount'), 0, '', ',');
        $ll_some = number_format((int)Donate::where('payment_currency', 2)->sum('amount'), 0, '', ',');
        return view('admin-views.dashboard', compact('donates', 'dollar_some', 'll_some'));
    }

    public function data(Request $request)
    {
        $search = $request->search;
        $status = $request->status;
        $donates = $this->donates->query();
        if (!empty($search)) {
            $donates->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        if (in_array($status, [1, 2])) {
            $donates->where('status', $status);
        }
        $donates = $donates->orderBy('id', 'DESC')->get();
        $dollar_some = number_format((int)Donate::where('payment_currency', 1)->sum('amount'), 0, '', ',');
        $ll_some = number_format((int)Donate::where('payment_currency', 2)->sum('amount'), 0, '', ',');
        $customersHTML = view('admin-views.partials.donates_rows', compact('donates'))->render();
        return response()->json([
            'customersHTML' => $customersHTML,
            'dollar_some' => $dollar_some,
            'll_some' => $ll_some,
        ]);
    }

    public function status(Request $request): RedirectResponse
    {
        $banner = $this->donates->find($request->id);
        $banner->status = $request->status;
        $banner->save();
        Toastr::success(translate('donates status updated!'));
        return back();
    }
}
