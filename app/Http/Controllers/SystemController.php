<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Exchange;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Order;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemController extends Controller
{
    public function __construct(
        private User $user
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

    public function restaurantData(): JsonResponse
    {
        $newOrder = Order::where(['status' => 1])->where('created_at', '>', now()->subMinutes(3))->count();
        return response()->json([
            'success' => 1,
            'data' => ['new_order' => $newOrder]
        ]);
    }

    public function ignoreCheckOrder()
    {
        Order::where(['status' => 1])->update(['status' => 2]);
        return redirect()->back();
    }

    public function dashboard(): Factory|View|Application
    {
        $data = [];
        $data['pending'] = Order::where('status', 1)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $data['processing'] = Order::where('status', 2)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $data['finished'] = Order::where('status', 3)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $data['stored'] = Order::where('status', 4)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $data['delivered'] = Order::where('status', 5)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $data['cancel'] = Order::where('status', 6)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();


        $data['total_orders_year'] = Order::whereYear('created_at', Carbon::now()->year)->sum('paid');
        $data['total_orders_month'] = Order::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('paid');

        $data['total_expenses_year'] = Expense::whereYear('created_at', Carbon::now()->year)->sum('amount');
        $data['total_expenses_month'] = Expense::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('amount');
        $today = Carbon::today();
        $sevenDaysAgo = Carbon::today()->subDays(3);
        $week_order = Order::whereDate('created_at', '>=', $sevenDaysAgo)
            ->whereDate('created_at', '<=', $today)
            ->with('items')
            ->get();

        return view('admin-views.dashboard', compact('data', 'week_order'));
    }
    /**
     * @return Application|Factory|View
     */
    public function orderIndex(): Factory|View|Application
    {
        if (!session()->has('from_date')) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }

        return view('admin-views.report.order-index');
    }

    /**
     * @return Application|Factory|View
     */
    public function earningIndex(): Factory|View|Application
    {
        if (!session()->has('from_date')) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }
        return view('admin-views.report.earning-index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function setDate(Request $request): RedirectResponse
    {
        $fromDate = \Carbon\Carbon::parse($request['from'])->startOfDay();
        $toDate = Carbon::parse($request['to'])->endOfDay();

        session()->put('from_date', $fromDate);
        session()->put('to_date', $toDate);
        return back();
    }


    public function getEarningStatitics(Request $request): JsonResponse
    {
        $dateType = $request->type;

        $earningData = array();
        if ($dateType == 'yearEarn') {
            $number = 12;
            $from = \Illuminate\Support\Carbon::now()->startOfYear()->format('Y-m-d');
            $to = Carbon::now()->endOfYear()->format('Y-m-d');

            $earning = Exchange::where([
                'money_type' => 1,
                'type' => 2
            ])->where('status', 1)->select(
                DB::raw('IFNULL(sum(total_labor),0) as sums'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month')
            )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();

            for ($inc = 1; $inc <= $number; $inc++) {
                $earningData[$inc] = 0;
                foreach ($earning as $match) {
                    if ($match['month'] == $inc) {
                        $earningData[$inc] = $match['sums'];
                    }
                }
            }
            $keyRange = array("Jan", "Feb", "Mar", "April", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
        } elseif ($dateType == 'MonthEarn') {
            $from = date('Y-m-01');
            $to = date('Y-m-t');
            $number = date('d', strtotime($to));
            $keyRange = range(1, $number);

            $earning = Exchange::where([
                'money_type' => 1,
                'type' => 2
            ])->where('status', 1)->select(
                DB::raw('IFNULL(sum(total_labor),0) as sums'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month, DAY(created_at) day')
            )->whereBetween('created_at', [$from, $to])->groupby('year', 'month', 'day')->get()->toArray();

            for ($inc = 1; $inc <= $number; $inc++) {
                $earningData[$inc] = 0;
                foreach ($earning as $match) {
                    if ($match['day'] == $inc) {
                        $earningData[$inc] = $match['sums'];
                    }
                }
            }
        } elseif ($dateType == 'WeekEarn') {
            Carbon::setWeekStartsAt(Carbon::SUNDAY);
            Carbon::setWeekEndsAt(Carbon::SATURDAY);

            $from = Carbon::now()->startOfWeek()->format('Y-m-d 00:00:00');
            $to = Carbon::now()->endOfWeek()->format('Y-m-d 23:59:59');
            $dateRange = CarbonPeriod::create($from, $to)->toArray();
            $day_range = array();
            foreach ($dateRange as $date) {
                $day_range[] = $date->format('d');
            }
            $day_range = array_flip($day_range);
            $day_range_keys = array_keys($day_range);
            $day_range_values = array_values($day_range);
            $day_range_intKeys = array_map('intval', $day_range_keys);
            $day_range = array_combine($day_range_intKeys, $day_range_values);

            $earning = Exchange::where([
                'money_type' => 1,
                'type' => 2
            ])->where('status', 1)->select(
                DB::raw('IFNULL(sum(total+labor),0) as sums'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month, DAY(created_at) day')
            )->whereBetween('created_at', [$from, $to])->groupby('year', 'month', 'day')->orderBy('created_at', 'ASC')->pluck('sums', 'day')->toArray();

            $earningData = array();
            foreach ($day_range as $day => $value) {
                $day_value = 0;
                $earningData[$day] = $day_value;
            }

            foreach ($earning as $order_day => $order_value) {
                if (array_key_exists($order_day, $earningData)) {
                    $earningData[$order_day] = $order_value;
                }
            }
            $keyRange = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        }
        $label = $keyRange;
        $earningDataFinal = $earningData;
        $data = array(
            'earning_label' => $label,
            'earning' => array_values($earningDataFinal),
        );
        return response()->json($data);
    }

    /**
     * @param $type
     * @return JsonResponse
     */
    public function generateInvoice(Request $request): JsonResponse
    {
        $date = $request->input('date', now()->toDateString());
        $startOfDay = Carbon::parse($date)->startOfDay();
        $endOfDay = Carbon::parse($date)->endOfDay();

        $orders = Order::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->with('items', 'items.service', 'items.service.category')
            ->get();

        $totalSum = $orders->sum('total');
        $paidSum = $orders->sum('paid');

        return response()->json([
            'success' => 1,
            'view' => view('admin-views.partials.invoice', compact('orders', 'totalSum', 'paidSum', 'date'))->render(),
        ]);
    }

    /**
     * @param $type
     * @return JsonResponse
     */
    public function generateInvoiceById(Request $request): JsonResponse
    {
        $ids = $request->input('order_ids', []);
        $orders = Order::whereIn('id', $ids)
            ->with('items', 'items.service', 'items.service.category')
            ->get();

        $orders = $orders->reverse();

        $date = null;
        $totalSum = $orders->sum('total');
        $paidSum = $orders->sum('paid');
        $dueSum = $totalSum - $paidSum;

        return response()->json([
            'success' => 1,
            'view' => view('admin-views.partials.invoice', compact('orders', 'totalSum', 'paidSum', 'dueSum', 'date'))->render(),
        ]);
    }

    /**
     * @param $type
     * @return JsonResponse
     */
    public function generateInvoiceByCustomerId(Request $request): JsonResponse
    {
        $ids = $request->input('customersIds', []);
        $customers = User::whereIn('id', $ids)->get();
        $customers = $customers->reverse();
        return response()->json([
            'success' => 1,
            'view' => view('admin-views.partials.customer_invoice', compact('customers'))->render(),
        ]);
    }


    /**
     * @return Application|Factory|View
     */
    public function monthlyearning(Request $request): Factory|View|Application
    {
        $month = $request->get('month', now()->format('Y-m'));
        $monthlySummary = Order::getMonthlySummaryWithExpense($month);
        return view('admin-views.report.monthly-index', compact('monthlySummary', 'month'));
    }

    /**
     * @return Application|Factory|View
     */
    public function dailyReport(Request $request): Factory|View|Application
    {
        $date = $request->get('date', now()->format('Y-m-d'));

        // Fetch orders and expenses for the selected day
        $orders = Order::whereDate('created_at', $date)->get();
        $expenses = Expense::whereDate('created_at', $date)->get();

        // Calculate statistics
        $orderCount = $orders->where('status', '!=', 6)->count();
        $deliveredOrdersCount = $orders->where('status', 4)->count();
        $totalPayment = $orders->where('status', '!=', 6)->sum('paid');
        $totalExpenses = $expenses->sum('amount');

        // Pass data to the view
        return view('admin-views.report.daily-index', compact(
            'date',
            'orderCount',
            'deliveredOrdersCount',
            'totalPayment',
            'totalExpenses'
        ));
    }

    /**
     * @return Application|Factory|View
     */
    public function salesReport(Request $request): Factory|View|Application
    {
        $fromDate = $request->get('from', now()->startOfDay()->toDateTimeString());
        $toDate = $request->get('to', now()->endOfDay()->toDateTimeString());
        $branch_id = $request->branch_id;
        if ($branch_id) {
            $orders = Order::whereBetween('created_at', [$fromDate, $toDate])
                ->where('status', '!=', 6)->where('branch_id', $branch_id)
                ->with('user')
                ->get();
        } else {
            $orders = Order::whereBetween('created_at', [$fromDate, $toDate])
                ->where('status', '!=', 6)
                ->with('user')
                ->get();
        }

        $totalOrders = $orders->count();
        $totalSales = $orders->sum('total');
        $totalPaid = $orders->sum('paid');
        $totalDue = $totalSales - $totalPaid;
        $totalSejadeQuantitySum = $orders->sum(function ($order) {
            return $order->getSejadeQuantitySum();
        });
        $monthlySummary = Order::getMonthlySummaryWithExpense(now());
        return view('admin-views.report.sales-index', compact('orders', 'fromDate', 'toDate', 'monthlySummary', 'totalOrders', 'totalSales', 'totalPaid', 'totalDue', 'totalSejadeQuantitySum', 'branch_id'));
    }

    /**
     * @return Application|Factory|View
     */
    public function expenceReport(Request $request): Factory|View|Application
    {
        $fromDate = $request->get('from', now()->startOfDay()->toDateTimeString());
        $toDate = $request->get('to', now()->endOfDay()->toDateTimeString());
        $categoryId = $request->get('category_id');
        $subCategoryId = $request->get('subcategory_id');

        $ordersQuery = Expense::whereBetween('created_at', [$fromDate, $toDate]);

        // Apply filters if selected
        if ($categoryId) {
            $ordersQuery->where('expense_category_id', $categoryId);
        }
        if ($subCategoryId) {
            $ordersQuery->where('sub_category_id', $subCategoryId);
        }

        $orders = $ordersQuery->get();
        $total = $orders->sum('amount');

        $categoriesParent = ExpenseCategory::where('status', 1)->whereNull('parent_id')->get();
        $categoriessub = ExpenseCategory::where('status', 1)->whereNotNull('parent_id')->get();

        return view('admin-views.report.expence-index', compact('fromDate', 'toDate', 'orders', 'categoriesParent', 'categoriessub', 'total'));
    }
}
