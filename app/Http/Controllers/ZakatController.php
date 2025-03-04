<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Donate;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ZakatController extends Controller
{
    public function __construct(
        private Donate $donates,
    ) {}
    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $donates = $this->donates->latest()->limit(20)->get();

        $dollar_some = number_format((int)Donate::where('payment_currency', 1)->sum('amount'), 0, '', ',');
        $ll_some = number_format((int)Donate::where('payment_currency', 2)->sum('amount'), 0, '', ',');
        $donateCount = Donate::count();
        $last_id = $donates->isNotEmpty() ? $donates->first()->id : 0;
        return view('front.index', compact('donates', 'dollar_some', 'll_some', 'last_id', 'donateCount'));
    }

    public function donate()
    {
        return view('front.donate');
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
        $donateCount = Donate::count();
        $customersHTML = view('front.partials.donates_rows', compact('donates'))->render();
        return response()->json([
            'customersHTML' => $customersHTML,
            'addNew' => $addNew,
            'last_id' => $last_id,
            'dollar_some' => $dollar_some,
            'll_some' => $ll_some,
            'donateCount' => $donateCount,
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'amount' => 'required',
            'payment_type' => 'required',
            'payment_currency' => 'required',
            // 'whastapp' => 'required',
        ]);
        try {
            $exchange = new Donate();
            $exchange->address = $request->address;
            $exchange->phone = $request->phone;
            $exchange->name = $request->name;
            // $exchange->note = $request->note;
            $exchange->amount = $request->amount;
            $exchange->payment_type = $request->payment_type;
            $exchange->payment_currency = $request->payment_currency;
            // $exchange->whastapp = $request->whastapp;
            $exchange->status = 1;
            $exchange->save();
            DB::commit();
            return response()->json(['message' => translate('exchange added successfully'), 'id' => $exchange->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => translate('error occurred while saving'), 'errors' => [['message' => translate('error occurred while saving')]]], 400);
        }
    }
}
