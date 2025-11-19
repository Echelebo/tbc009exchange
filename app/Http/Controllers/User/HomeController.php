<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\TopUpRequest;
use App\Models\Deposit;
use App\Models\ExchangeRequest;
use App\Models\Gateway;
use App\Models\Kyc;
use App\Models\PayoutRequest;
use App\Models\Transaction;
use App\Models\UserKyc;
use App\Models\User;
use App\Traits\SendNotification;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class HomeController extends Controller
{
    use Upload, SendNotification;

    // Declare the properties first — this removes the deprecation completely
    public $user;
    public $theme;

    public function __construct()
    {
        // Better way: use proper middleware syntax instead of doing it manually
        $this->middleware('auth');
        
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            $this->theme = template();    // now allowed because $theme is declared above
            return $next($request);
        });
    }

    public function saveToken(Request $request)
    {
        Auth::user()
            ->fireBaseToken()
            ->create([
                'token' => $request->token,
            ]);
        return response()->json([
            'msg' => 'token saved successfully.',
        ]);
    }

    public function index()
    {
        $data['user'] = Auth::user();
        $data['baseColor'] = basicControl()->primary_color;
        $data['firebaseNotify'] = config('firebase');
        return view($this->theme . 'user.dashboard', $data);
    }

    public function getRecords()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        $exchangeRequestQuery = $this->exchangeRequestQuery()->where('user_id', auth()->id());


        $exchangeRecord = collect((clone $exchangeRequestQuery)
                ->whereIn('status', ['2', '4', '6', '7', '8', '9'])
                ->selectRaw('COUNT(id) AS totalExchange')
                ->selectRaw('SUM(amount) AS totalSumExchange')
                ->selectRaw('(COUNT(CASE WHEN status IN (2, 4, 7) THEN id END)) AS pendingExchange')
                ->selectRaw('(COUNT(CASE WHEN status IN (2, 4, 7) AND created_at >= ? THEN id END) / COUNT(CASE WHEN created_at >= ? THEN id END)) * 100 AS last30DaysPendingPercentage', [$thirtyDaysAgo, $thirtyDaysAgo])
                ->selectRaw('(COUNT(CASE WHEN status = 6 THEN id END)) AS refundExchange')
                ->selectRaw('(COUNT(CASE WHEN status = 6 AND created_at >= ? THEN id END) / COUNT(CASE WHEN created_at >= ? THEN id END)) * 100 AS last30DaysRefundPercentage', [$thirtyDaysAgo, $thirtyDaysAgo])
                ->selectRaw('(COUNT(CASE WHEN status = 9 THEN id END)) AS completeExchange')
                ->selectRaw('(COUNT(CASE WHEN status = 9 AND created_at >= ? THEN id END) / COUNT(CASE WHEN created_at >= ? THEN id END)) * 100 AS last30DaysCompletePercentage', [$thirtyDaysAgo, $thirtyDaysAgo])
                ->selectRaw('(COUNT(CASE WHEN status = 8 THEN id END)) AS activeExchange')
                ->selectRaw('(COUNT(CASE WHEN status = 8 AND created_at >= ? THEN id END) / COUNT(CASE WHEN created_at >= ? THEN id END)) * 100 AS last30DaysActivePercentage', [$thirtyDaysAgo, $thirtyDaysAgo])
                ->get()
                ->makeHidden(['tracking_status', 'admin_status', 'user_status'])
                ->toArray())->collapse();

        $topupRequestQuery = $this->topupRequestQuery();

        $topupRecord = collect((clone $topupRequestQuery)->where('user_id', auth()->id())->whereIn('status', [0, 1, 2])->selectRaw('COUNT(id) AS totalTopUp')
            ->selectRaw('(COUNT(CASE WHEN status = 0 THEN id END)) AS pendingTopUp')
            ->selectRaw('(COUNT(CASE WHEN status = 0 AND created_at >= ? THEN id END) / COUNT(CASE WHEN created_at >= ? THEN id END)) * 100 AS last30DaysPendingPercentage', [$thirtyDaysAgo, $thirtyDaysAgo])
            ->selectRaw('(COUNT(CASE WHEN status = 1 THEN id END)) AS completeTopUp')
            ->selectRaw('(COUNT(CASE WHEN status = 1 AND created_at >= ? THEN id END) / COUNT(CASE WHEN created_at >= ? THEN id END)) * 100 AS last30DaysCompletePercentage', [$thirtyDaysAgo, $thirtyDaysAgo])
            ->selectRaw('(COUNT(CASE WHEN status = 2 THEN id END)) AS cancelTopUp')
            ->selectRaw('(COUNT(CASE WHEN status = 2 AND created_at >= ? THEN id END) / COUNT(CASE WHEN created_at >= ? THEN id END)) * 100 AS last30DaysCancelPercentage', [$thirtyDaysAgo, $thirtyDaysAgo])
            ->selectRaw('(COUNT(CASE WHEN status IN (0, 1, 2) THEN id END)) AS totalTopUps')
            ->selectRaw('(COUNT(CASE WHEN status IN (0, 1, 2) AND created_at >= ? THEN id END) / COUNT(CASE WHEN created_at >= ? THEN id END)) * 100 AS last30DaysTotalPercentage', [$thirtyDaysAgo, $thirtyDaysAgo])
            ->get()
            ->makeHidden(['tracking_status', 'admin_status', 'user_status'])
            ->toArray())->collapse();

        $payoutRequestQuery = $this->payoutRequestQuery();

        $payoutRecord = collect((clone $payoutRequestQuery)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['0', '1', '2'])
            ->selectRaw('COUNT(id) AS totalPayout')
            ->selectRaw('(COUNT(CASE WHEN status = 0 THEN id END)) AS pendingPayout')
            ->selectRaw('(COUNT(CASE WHEN status = 0 AND created_at >= ? THEN id END) / COUNT(CASE WHEN created_at >= ? THEN id END)) * 100 AS last30DaysPendingPercentage', [$thirtyDaysAgo, $thirtyDaysAgo])
            ->selectRaw('(COUNT(CASE WHEN status = 1 THEN id END)) AS completePayout')
            ->selectRaw('(COUNT(CASE WHEN status = 1 AND created_at >= ? THEN id END) / COUNT(CASE WHEN created_at >= ? THEN id END)) * 100 AS last30DaysCompletePercentage', [$thirtyDaysAgo, $thirtyDaysAgo])
            ->selectRaw('(COUNT(CASE WHEN status = 2 THEN id END)) AS cancelPayout')
            ->selectRaw('(COUNT(CASE WHEN status = 2 AND created_at >= ? THEN id END) / COUNT(CASE WHEN created_at >= ? THEN id END)) * 100 AS last30DaysCancelPercentage', [$thirtyDaysAgo, $thirtyDaysAgo])
            ->selectRaw('(COUNT(CASE WHEN status IN (0, 1, 2) THEN id END)) AS totalPayouts')
            ->selectRaw('(COUNT(CASE WHEN status IN (0, 1, 2) AND created_at >= ? THEN id END) / COUNT(CASE WHEN created_at >= ? THEN id END)) * 100 AS last30DaysTotalPercentage', [$thirtyDaysAgo, $thirtyDaysAgo])
            ->get()
            ->makeHidden(['tracking_status', 'admin_status', 'user_status'])
            ->toArray())->collapse();

            $balanceRequestQuery = $this->balanceRequestQuery();

        $balanceRecord = collect((clone $balanceRequestQuery)->where('id', auth()->id())
            ->selectRaw('balance AS totalBalance')
            ->get()
            ->makeHidden(['tracking_status', 'admin_status', 'user_status'])
            ->toArray())->collapse();

        $referralbonusRequestQuery = $this->referralbonusRequestQuery();

        $referralbonusRecord = collect((clone $referralbonusRequestQuery)
            ->where('user_id', auth()->id())
            ->where('remarks', 'LIKE', '%Referral Bonus%')
            ->selectRaw('COUNT(id) AS totalReferralBonus')
            ->selectRaw('SUM(amount) AS totalSumReferralBonus')
            ->get()
            ->makeHidden(['tracking_status', 'admin_status', 'user_status'])
            ->toArray())->collapse();


        $returnRequestQuery = $this->returnRequestQuery();

        $returnRecord = collect((clone $returnRequestQuery)
            ->where('user_id', auth()->id())
            ->where('remarks', 'Return')
            ->selectRaw('COUNT(id) AS totalReturn')
            ->selectRaw('SUM(amount) AS totalSumReturn')
            ->get()
            ->makeHidden(['tracking_status', 'admin_status', 'user_status'])
            ->toArray())->collapse();

        return response()->json([
            'totalExchange' => fractionNumber($exchangeRecord['totalExchange'], false),
            'pendingExchange' => fractionNumber($exchangeRecord['pendingExchange'], false),
            'last30DaysPendingPercentage' => fractionNumber($exchangeRecord['last30DaysPendingPercentage']),
            'completeExchange' => fractionNumber($exchangeRecord['completeExchange'], false),
            'last30DaysCompletePercentage' => fractionNumber($exchangeRecord['last30DaysComplPercentage']),
            'activeExchange' => fractionNumber($exchangeRecord['activeExchange'], false),
            'last30DaysActivePercentage' => fractionNumber($exchangeRecord['last30DaysActivePercentage']),
            'refundExchange' => fractionNumber($exchangeRecord['refundExchange'], false),
            'last30DaysRefundPercentage' => fractionNumber($exchangeRecord['last30DaysRefundPercentage']),

            'totalTopUp' => fractionNumber($topupRecord['totalTopUp'], false, false),
            'pendingTopUp' => fractionNumber($topupRecord['pendingTopUp'], false),
            'last30DaysPendingPercentageTopUp' => fractionNumber($topupRecord['last30DaysPendingPercentage']),
            'completeTopUp' => fractionNumber($topupRecord['completeTopUp'], false),
            'last30DaysCompletePercentageTopUp' => fractionNumber($topupRecord['last30DaysCompletePercentage']),
            'cancelTopUp' => fractionNumber($topupRecord['cancelTopUp'], false),
            'last30DaysCancelPercentageTopUp' => fractionNumber($topupRecord['last30DaysCancelPercentage']),
            'last30DaysTotalPercentageTopUp' => fractionNumber($topupRecord['last30DaysTotalPercentage']),

            'totalPayout' => fractionNumber($payoutRecord['totalPayout'], false),
            'pendingPayout' => fractionNumber($payoutRecord['pendingPayout'], false),
            'last30DaysPendingPercentagePayout' => fractionNumber($payoutRecord['last30DaysPendingPercentage']),
            'completePayout' => fractionNumber($payoutRecord['completePayout'], false),
            'last30DaysCompletePercentagePayout' => fractionNumber($payoutRecord['last30DaysCompletePercentage']),
            'cancelPayout' => fractionNumber($payoutRecord['cancelPayout'], false),
            'last30DaysCancelPercentagePayout' => fractionNumber($payoutRecord['last30DaysCancelPercentage']),
            'last30DaysTotalPercentagePayout' => fractionNumber($payoutRecord['last30DaysTotalPercentage']),

            'last30DaysTotalPercentageBalance' => fractionNumber($balanceRecord['last30DaysTotalPercentage']),

            'totalBalance' => fractionNumber($balanceRecord['totalBalance'], false),

            'totalReferralBonus' => fractionNumber($referralbonusRecord['totalReferralBonus'], false),
            'totalSumReferralBonus' => fractionNumber($referralbonusRecord['totalSumReferralBonus']),
            'totalReturn' => fractionNumber($returnRecord['totalReturn'], false),
            'totalSumReturn' => fractionNumber($returnRecord['totalSumReturn']),
            'totalSumExchange' => fractionNumber($exchangeRecord['totalSumExchange']),
        
        ]);
    }

    public function chartExchangeFigures()
    {
        $exchangeRequestQuery = $this->exchangeRequestQuery()->where('user_id', auth()->id());

        $exchangeRecord = collect((clone $exchangeRequestQuery)
            ->whereIn('status', [2, 4, 6, 7, 8, 9])
            ->selectRaw('COUNT(id) AS totalExchange')
            ->selectRaw('(COUNT(CASE WHEN status IN (2, 4, 7) THEN id END)) AS pendingExchange')
            ->selectRaw('(COUNT(CASE WHEN status = 9 THEN id END)) AS completeExchange')
            ->selectRaw('(COUNT(CASE WHEN status = 8 THEN id END)) AS activeExchange')
            ->selectRaw('(COUNT(CASE WHEN status = 6 THEN id END)) AS refundExchange')
            ->get()
            ->toArray())->collapse();

        $data['horizontalBarChatExchange'] = [$exchangeRecord['totalExchange'], $exchangeRecord['pendingExchange'], $exchangeRecord['completeExchange'],
            $exchangeRecord['activeExchange'], $exchangeRecord['refundExchange']];

        return response()->json(['exchangeFigures' => $data]);
    }

    public function chartTopUpFigures()
    {
        $topupRequestQuery = $this->topupRequestQuery();

        $topupRecord = collect((clone $topupRequestQuery)->where('user_id', auth()->id())
            ->whereIn('status', ['0', '1', '2'])
            ->selectRaw('COUNT(id) AS totalTopUp')
            ->selectRaw('(COUNT(CASE WHEN status = 0 THEN id END)) AS pendingTopUp')
            ->selectRaw('(COUNT(CASE WHEN status = 1 THEN id END)) AS completeTopUp')
            ->selectRaw('(COUNT(CASE WHEN status = 2 THEN id END)) AS cancelTopUp')
            ->selectRaw('(COUNT(CASE WHEN status IN (0, 1, 2) THEN id END)) AS totalTopUps')
            ->get()
            ->toArray())->collapse();

        $data['horizontalBarChatTopUp'] = [$topupRecord['totalTopUp'], $topupRecord['pendingTopUp'], $topupRecord['completeTopUp'],
            $topupRecord['cancelTopUp'], $topupRecord['totalTopUps']];

        return response()->json(['topupFigures' => $data]);
    }

    public function chartPayoutFigures()
    {
        $payoutRequestQuery = $this->payoutRequestQuery();
        $payoutRecord = collect((clone $payoutRequestQuery)->where('user_id', auth()->id())
            ->whereIn('status', ['0', '1', '2'])
            ->selectRaw('COUNT(id) AS totalPayout')
            ->selectRaw('(COUNT(CASE WHEN status = 0 THEN id END)) AS pendingPayout')
            ->selectRaw('(COUNT(CASE WHEN status = 1 THEN id END)) AS completePayout')
            ->selectRaw('(COUNT(CASE WHEN status = 2 THEN id END)) AS cancelPayout')
            ->selectRaw('(COUNT(CASE WHEN status IN (0, 1, 2) THEN id END)) AS totalPayouts')
            ->get()
            ->toArray())->collapse();

        $data['horizontalBarChatPayout'] = [$payoutRecord['totalPayout'], $payoutRecord['pendingPayout'], $payoutRecord['completePayout'],
            $payoutRecord['cancelPayout'], $payoutRecord['totalPayouts']];

        return response()->json(['payoutFigures' => $data]);
    }

    public function chartExchangeMovements()
    {
        $currentYear = date('Y');
        $currentMonth = date('m');

        $exchangeRequestQuery = $this->exchangeRequestQuery();

        $exchangeRequests = (clone $exchangeRequestQuery)->where('user_id', auth()->id())->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $currentYear)

            ->whereIn('status', [2, 4, 6, 7, 8, 9])
            ->whereMonth('created_at', '<=', $currentMonth)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        $exchangeMovements = [];

        foreach ($exchangeRequests as $exchangeRequest) {
            $month = date("M", mktime(0, 0, 0, $exchangeRequest->month, 1)); // Convert month number to month name
            $exchangeMovements[$month] = $exchangeRequest->total; // Store month-wise total exchanges
        }
        return response()->json(['exchangeMovements' => $exchangeMovements]);
    }

    public function exchangeRequestQuery()
    {
        return ExchangeRequest::query();
    }
    public function topupRequestQuery()
    {
        return TopUpRequest::query();
    }
    public function payoutRequestQuery()
    {
        return PayoutRequest::query();
    }

    public function balanceRequestQuery()
    {
        return User::query();
    }

    public function referralbonusRequestQuery()
    {
        return Transaction::query();
    }

    public function returnRequestQuery()
    {
        return Transaction::query();
    }

    public function chartTopUpMovements()
    {
        $currentYear = date('Y');
        $currentMonth = date('m');

        $topupRequestQuery = $this->topupRequestQuery();
        $topupRequests = (clone $topupRequestQuery)->where('user_id', auth()->id())->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $currentYear)
            ->whereIn('status', ['0', '1', '2'])

            ->whereMonth('created_at', '<=', $currentMonth)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        $topupMovements = [];

        foreach ($topupRequests as $topupRequest) {
            $month = date("M", mktime(0, 0, 0, $topupRequest->month, 1)); // Convert month number to month name
            $topupMovements[$month] = $topupRequest->total; // Store month-wise total exchanges
        }
        return response()->json(['topupMovements' => $topupMovements]);
    }

    public function chartPayoutMovements()
    {
        $currentYear = date('Y');
        $currentMonth = date('m');

        $payoutRequestBaseQuery = $this->PayoutRequestQuery();
        $payoutRequests = (clone $payoutRequestBaseQuery)
            ->whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereIn('status', ['0', '1', '2'])
            ->where('user_id', auth()->id())
            ->whereMonth('created_at', '<=', $currentMonth)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        $payoutMovements = [];

        foreach ($payoutRequests as $payoutRequest) {
            $month = date("M", mktime(0, 0, 0, $payoutRequest->month, 1)); // Convert month number to month name
            $payoutMovements[$month] = $payoutRequest->total; // Store month-wise total exchanges
        }
        return response()->json(['payoutMovements' => $payoutMovements]);
    }

    function getDataForTimeRange($start, $end, $type)
    {
        $hours = [];
        $counts = [];

        for ($i = 0; $i < 24; $i++) {
            if ($i % 2 == 0) {
                $hour = $start->copy()->subHours($i + 1);
                $formattedHour = $hour->format('hA');
                $hours[] = $formattedHour;

                if ($type == 'exchange') {
                    $count = DB::table('exchange_requests')
                        ->where('user_id', auth()->id())
                        ->whereIn('status', ['2', '4', '6', '7', '8', '9'])
                        ->where('updated_at', '>=', $hour)
                        ->where('updated_at', '<', $hour->copy()->addHours(2))
                        ->count();
                } elseif ($type == 'topup') {
                    $count = DB::table('topup_requests')
                        ->where('user_id', auth()->id())
                        ->whereIn('status', ['0', '1', '2'])
                        ->where('updated_at', '>=', $hour)
                        ->where('updated_at', '<', $hour->copy()->addHours(2))
                        ->count();
                } elseif ($type == 'payout') {
                    $count = DB::table('payout_requests')
                        ->where('user_id', auth()->id())
                        ->whereIn('status', ['0', '1', '2'])
                        ->where('updated_at', '>=', $hour)
                        ->where('updated_at', '<', $hour->copy()->addHours(2))
                        ->count();
                }
                $counts[] = $count;
            }
        }
        $hours = array_reverse($hours);
        $counts = array_reverse($counts);

        $data[] = [
            'hours' => $hours,
            'counts' => $counts,
        ];
        return $data;
    }

    public function kycShow($slug, $id)
    {
        $data['kycs'] = Kyc::where('status', 1)->get();
        $data['kyc'] = Kyc::where('status', 1)->findOrFail($id);
        return view($this->theme . 'user.kyc.show', $data);
    }

    public function kycVerificationSubmit(Request $request, $id)
    {
        $kyc = Kyc::where('status', 1)->findOrFail($id);
        try {
            $params = $kyc->input_form;
            $reqData = $request->except('_token', '_method');
            $rules = [];
            if ($params !== null) {
                foreach ($params as $key => $cus) {
                    $rules[$key] = [$cus->validation == 'required' ? $cus->validation : 'nullable'];
                    if ($cus->type == 'file') {
                        $rules[$key][] = 'image';
                        $rules[$key][] = 'mimes:jpeg,jpg,png';
                        $rules[$key][] = 'max:2048';
                    } elseif ($cus->type == 'text') {
                        $rules[$key][] = 'max:191';
                    } elseif ($cus->type == 'number') {
                        $rules[$key][] = 'integer';
                    } elseif ($cus->type == 'textarea') {
                        $rules[$key][] = 'min:3';
                        $rules[$key][] = 'max:300';
                    }
                }
            }

            $validator = Validator::make($reqData, $rules);
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            $reqField = [];
            foreach ($request->except('_token', '_method', 'type') as $k => $v) {
                foreach ($params as $inKey => $inVal) {
                    if ($k == $inKey) {
                        if ($inVal->type == 'file' && $request->hasFile($inKey)) {
                            try {
                                $file = $this->fileUpload($request[$inKey], config('filelocation.kyc.path'), null, null, 'webp', 60);
                                $reqField[$inKey] = [
                                    'field_name' => $inVal->field_name,
                                    'field_value' => $file['path'],
                                    'field_driver' => $file['driver'],
                                    'validation' => $inVal->validation,
                                    'type' => $inVal->type,
                                ];
                            } catch (\Exception $exp) {
                                session()->flash('error', 'Could not upload your ' . $inKey);
                                return back()->withInput();
                            }
                        } else {
                            $reqField[$inKey] = [
                                'field_name' => $inVal->field_name,
                                'validation' => $inVal->validation,
                                'field_value' => $v,
                                'type' => $inVal->type,
                            ];
                        }
                    }
                }
            }

            UserKyc::create([
                'user_id' => auth()->id(),
                'kyc_id' => $kyc->id,
                'kyc_type' => $kyc->name,
                'kyc_info' => $reqField
            ]);

            return back()->with('success', 'KYC Sent Successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function verificationCenter()
    {
        $data['userKycs'] = UserKyc::own()->latest()->get();
        return view($this->theme . 'user.kyc.verification-center', $data);
    }


    public function addFund()
    {
        $data['basic'] = basicControl();
        $data['gateways'] = Gateway::where('status', 1)->orderBy('sort_by', 'ASC')->get();
        return view($this->theme . 'user.fund.add_fund', $data);
    }


    public function fund(Request $request)
    {
        $basic = basicControl();
        $userId = Auth::id();
        $funds = Deposit::with(['depositable', 'gateway'])
            ->where('user_id', $userId)
            ->where('payment_method_id', '>', 999)
            ->orderBy('id', 'desc')
            ->latest()->paginate($basic->paginate);
        return view($this->theme . 'user.fund.index', compact('funds'));

    }

    public function payout(Request $request)
    {
        $basic = basicControl();
        $userId = Auth::id();
        $commission = PayoutRequest::where('user_id', $userId)
        ->sum('amount');
        $commissions = PayoutRequest::where('user_id', $userId)
        ->get();
        $data['commission'] = $commission;
        $data['commissions'] = $commissions;
        $data['payouts'] = PayoutRequest::where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->latest()->paginate($basic->paginate);
    
        $data['uniqueAddresses'] = ExchangeRequest::query()
        ->where('user_id', $userId)
        ->select('destination_wallet')
        ->distinct()
        ->get()
        ->pluck('destination_wallet');

        return view($this->theme . 'user.payout.index', $data);

    }


    public function topup(Request $request)
    {
        $basic = basicControl();
        $userId = Auth::id();
        $commission = TopUpRequest::where('user_id', $userId)
        ->sum('amount');
        $commissions = TopUpRequest::where('user_id', $userId)
        ->get();
        $data['commission'] = $commission;
        $data['commissions'] = $commissions;
        $data['topups'] = TopUpRequest::where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->latest()->paginate($basic->paginate);
        return view($this->theme . 'user.topup.index', $data);

    }



    public function transaction(Request $request)
    {
        $search = $request->all();
        $dateSearch = $request->datetrx;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);
        $userId = Auth::id();
        $transactions = Transaction::where('user_id', $userId)
            ->when(@$search['transaction_id'], function ($query) use ($search) {
                return $query->where('trx_id', 'LIKE', "%{$search['transaction_id']}%");
            })
            ->when(@$search['remark'], function ($query) use ($search) {
                return $query->where('remarks', 'LIKE', "%{$search['remark']}%");
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->orderBy('id', 'desc')
            ->paginate(basicControl()->paginate);
        return view($this->theme . 'user.transaction.index', compact('transactions'));
    }

    public function referral()
    {
        $data['userId']= $userId = Auth::id();

        $data['commission'] = Transaction::where('user_id', $userId)
        ->where('remarks', 'LIKE', '%Referral Bonus%')
        ->sum('amount');

        $data['referrals'] = User::where('referral_by', $userId)
        ->orderBy('id', 'desc')
        ->paginate(basicControl()->paginate);

        $data['uplineId'] = $uplineId = User::where('id', $userId)
        ->whereNotNull('referral_by')
        ->first();

        if ($uplineId) {
            $data['upline'] = User::where('id', $uplineId->referral_by)
            ->first();
        }
        

        return view($this->theme . 'user.referral.index', $data);
    }

    public function referralBonus()
    {
        $data['userId']= $userId = Auth::id();

        $data['referrals'] = Transaction::where('user_id', $userId)
        ->where('remarks', 'LIKE', '%Referral Bonus%')
        ->orderBy('id', 'desc')
        ->paginate(basicControl()->paginate);
        
        return view($this->theme . 'user.referral.bonus', $data);
    }

    public function payoutSubmit(Request $request)
    {
        // Validate incoming data
        $validator = Validator::make($request->all(), [
            'method' => 'required|string|in:usdterc20,usdttrc20,usdtbep20', // adjust as needed
            'amount' => 'required|numeric|min:0.01',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user = Auth::user();
            $amount = $request->amount;
            $method = $request->method;
            $selectedAddress = $request->address;

            if ($user->balance < $amount) {
                return back()->withInput()->with('error', 'Insufficient balance');
            }
            
            $user->balance -= $amount;
            $user->save();

            // Create deposit with status = 0
            $payout = PayoutRequest::create([
                'utr' => uniqid('P'),
                'user_id' => $user->id,
                'method' => $method,
                'amount' => $amount,
                'address' => $selectedAddress,
                'status' => 0, // Pending
            ]);

        $this->sendAdminNotification($payout, 'adminpayout');
        
        return back()->with('success', 'Payout request submitted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function topupSubmit(Request $request)
    {
        // Validate incoming data
        $validator = Validator::make($request->all(), [
            'method' => 'required|string|in:usdterc20,usdttrc20,usdtbep20', // adjust as needed
            'amount' => 'required|numeric|min:0.01',
            'hash' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user = Auth::user();
            $amount = $request->amount;
            $method = $request->method;
            // Create deposit with status = 0
    $topup = TopUpRequest::create([
                'utr' => uniqid('TOPUP-'),
                'user_id' => $user->id,
                'method' => $method,
                'amount' => $amount,
                'hash' => $request->hash_id,
                'status' => 0, // Pending
            ]);

        $this->sendAdminNotification($topup, 'admintopup');
        
        return back()->with('success', 'Top Up request submitted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}