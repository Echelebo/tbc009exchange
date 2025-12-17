<?php

namespace App\Http\Controllers\Admin\Module;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRequest;
use App\Models\ExchangeActivation;
use App\Models\User;
use App\Traits\CalculateFees;
use App\Traits\CryptoWalletGenerate;
use App\Traits\SendNotification;
use Carbon\Carbon;
use Facades\App\Services\BasicService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ExchangeController extends Controller
{
    use CalculateFees, SendNotification;

    public function exchangeList(Request $request)
    {
        if (!in_array($request->type, ['all', 'pending', 'complete', 'cancel', 'refund', 'checking', 'runing', 'expired'])) {
            abort(404);
        }
        $data['exchangeType'] = $request->type;
        $data['exchanges'] = collect(ExchangeRequest::selectRaw('COUNT(id) AS totalExchange')
            ->selectRaw('COUNT(CASE WHEN status = 2 THEN id END) AS pendingExchange')
            ->selectRaw('(COUNT(CASE WHEN status = 2 THEN id END) / COUNT(id)) * 100 AS pendingExchangePercentage')
            ->selectRaw('COUNT(CASE WHEN status = 3 THEN id END) AS completeExchange')
            ->selectRaw('(COUNT(CASE WHEN status = 3 THEN id END) / COUNT(id)) * 100 AS completeExchangePercentage')
            ->selectRaw('COUNT(CASE WHEN status = 5 THEN id END) AS cancelExchange')
            ->selectRaw('(COUNT(CASE WHEN status = 5 THEN id END) / COUNT(id)) * 100 AS cancelExchangePercentage')
            ->selectRaw('COUNT(CASE WHEN status = 6 THEN id END) AS refundExchange')
            ->selectRaw('(COUNT(CASE WHEN status = 6 THEN id END) / COUNT(id)) * 100 AS refundExchangePercentage')
            ->selectRaw('COUNT(CASE WHEN status IN (7, 4) THEN id END) AS checkingExchange')
            ->selectRaw('(COUNT(CASE WHEN status IN (7, 4) THEN id END) / COUNT(id)) * 100 AS checkingExchangePercentage')
            ->selectRaw('COUNT(CASE WHEN status = 8 THEN id END) AS runingExchange')
            ->selectRaw('(COUNT(CASE WHEN status = 8 THEN id END) / COUNT(id)) * 100 AS runingExchangePercentage')
            ->selectRaw('COUNT(CASE WHEN status = 9 THEN id END) AS expiredExchange')
            ->selectRaw('(COUNT(CASE WHEN status = 9 THEN id END) / COUNT(id)) * 100 AS expiredExchangePercentage')
            ->get()
            ->toArray())->collapse();
        return view('admin.exchange.index', $data);
    }

    public function exchangeListSearch(Request $request)
    {
        $exchangeType = $request->type;
        $search = $request->search['value'] ?? null;
        $filterName = $request->name;
        $filterStatus = $request->filterStatus;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $exchanges = ExchangeRequest::with(['sendCurrency', 'getCurrency', 'user:id,firstname,lastname,username,image,image_driver'])
            ->orderBy('id', 'DESC')
            ->when(isset($exchangeType), function ($query) use ($exchangeType) {
                if ($exchangeType == 'pending') {
                    return $query->where('status', 2);
                } elseif ($exchangeType == 'complete') {
                    return $query->where('status', 3);
                } elseif ($exchangeType == 'awaitingTBC') {
                    return $query->where('status', 4);
                } elseif ($exchangeType == 'cancel') {
                    return $query->where('status', 5);
                } elseif ($exchangeType == 'refund') {
                    return $query->where('status', 6);
                } elseif ($exchangeType == 'checking') {
                    return $query->where('status', 7);
                } elseif ($exchangeType == 'runing') {
                    return $query->where('status', 8);
                } elseif ($exchangeType == 'expired') {
                    return $query->where('status', 9);
                } else {
                    return $query->whereIn('status', ['2', '3', '4', '5', '6', '7', '8', '9']);
                }
            })
            ->when(isset($filterName), function ($query) use ($filterName) {
                return $query->where('utr', 'LIKE', '%' . $filterName . '%');
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus != "all") {
                    return $query->where('status', $filterStatus);
                }
            })
            ->when(!empty($request->filterDate) && $endDate == null, function ($query) use ($startDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $query->whereDate('created_at', $startDate);
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where(function ($subquery) use ($search) {
                    $subquery->where('utr', 'LIKE', "%$search%")
                        ->orWhere('send_amount', 'LIKE', "%$search%")
                        ->orWhere('final_amount', 'LIKE', "%$search%");
                });
            });
        return DataTables::of($exchanges)
            ->addColumn('checkbox', function ($item) {
                return '<input type="checkbox" id="chk-' . $item->id . '"
                                       class="form-check-input row-tic tic-check" name="check" value="' . $item->id . '"
                                       data-id="' . $item->id . '">';
            })
            ->addColumn('trx_id', function ($item) {
                return $item->utr;
            })
            ->addColumn('send_amount', function ($item) {
                $url = getFile(optional($item->sendCurrency)->driver, optional($item->sendCurrency)->image);
                return '<a class="d-flex align-items-center me-2">
                                <div class="flex-shrink-0">
                                  <div class="avatar avatar-sm avatar-circle">
                                    <img class="avatar-img" src="' . $url . '" alt="Image Description">
                                  </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . rtrim(rtrim($item->send_amount, 0), '.') . ' ' . optional($item->sendCurrency)->code . '</h5>
                                  <span class="fs-6 text-body">' . optional($item->sendCurrency)->currency_name . '</span>
                                </div>
                              </a>';
            })
            ->addColumn('receive_amount', function ($item) {
                $url = getFile(optional($item->getCurrency)->driver, optional($item->getCurrency)->image);
                return '<a class="d-flex align-items-center me-2">
                                <div class="flex-shrink-0">
                                  <div class="avatar avatar-sm avatar-circle">
                                    <img class="avatar-img" src="' . $url . '" alt="Image Description">
                                  </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . rtrim(rtrim($item->final_amount, 0), '.') . ' ' . optional($item->getCurrency)->code . '</h5>
                                  <span class="fs-6 text-body">' . optional($item->getCurrency)->currency_name . '</span>
                                </div>
                              </a>';
            })
            //            ->addColumn('rate', function ($item) {
            //                $symbol = $item->rate_type == 'floating' ? '~' : '=';
            //                return '1 ' . optional($item->sendCurrency)->code . ' ' . $symbol . ' ' . rtrim(rtrim($item->exchange_rate, 0), '.') . ' ' . optional($item->getCurrency)->code;
            //            })
            ->addColumn('status', function ($item) {
                return $item->admin_status;
            })
            ->addColumn('requester', function ($item) {
                if (optional($item->user)->image) {
                    $url = getFile(optional($item->user)->image_driver, optional($item->user)->image);
                } else {
                    $url = asset('assets/admin/img/anonymous.png');
                }
                $fullname = optional($item->user)->fullname ?? 'Anonymous';
                return '<a class="d-flex align-items-center me-2" href="javascript:void(0)">
                                <div class="flex-shrink-0">
                                  <div class="avatar avatar-sm avatar-circle">
                                    <img class="avatar-img" src="' . $url . '" alt="Image Description">
                                  </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . $fullname . '</h5>
                                  <span class="fs-6 text-body">' . optional($item->user)->username . '</span>
                                </div>
                              </a>';
            })
            ->addColumn('created_at', function ($item) {
                return dateTime($item->created_at, basicControl()->date_time_format);
            })
            ->addColumn('action', function ($item) {
                $delete = route('admin.exchangeDelete', $item->id);
                $view = route('admin.exchangeView') . '?id=' . $item->id;

                $html = '<div class="btn-group" role="group">
                      <a href="' . $view . '" class="btn btn-white btn-sm">
                        <i class="fal fa-eye me-1"></i> ' . trans('View') . '
                      </a>';

                $html .= '<div class="btn-group">
                      <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="userEditDropdown" data-bs-toggle="dropdown" aria-expanded="false"></button>
                      <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="userEditDropdown">
                        <a class="dropdown-item delete_btn" href="javascript:void(0)" data-bs-target="#delete"
                           data-bs-toggle="modal" data-route="' . $delete . '">
                          <i class="fal fa-trash dropdown-item-icon"></i> ' . trans("Delete") . '
                       </a>
                      </div>
                    </div>';

                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['checkbox', 'trx_id', 'send_amount', 'receive_amount', 'status', 'requester', 'created_at', 'action'])
            ->make(true);
    }

    public function exchangeDelete($id)
    {
        ExchangeRequest::findOrFail($id)->delete($id);
        return back()->with('success', 'Exchange Deleted Successfully');
    }

    public function exchangeMultipleDelete(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select row.');
            return response()->json(['error' => 1]);
        } else {
            ExchangeRequest::whereIn('id', $request->strIds)->get()->map(function ($query) {
                $query->delete();
                return $query;
            });
            session()->flash('success', 'Exchange has been deleted successfully');
            return response()->json(['success' => 1]);
        }
    }

    public function exchangeView(Request $request)
    {
        $exchange = ExchangeRequest::findOrFail($request->id);
        if ($exchange->status == 2 && $exchange->rate_type == 'floating') {
            $exchange = $this->rateUpdate($exchange);
        }

        return view('admin.exchange.details', compact('exchange'));
    }

    public function rateUpdate($exchange)
    {
        $sendCurrency = $exchange->sendCurrency;
        $getCurrency = $exchange->getCurrency;

        $sendAmount = $exchange->send_amount;
        $exchangeRate = $sendCurrency->usd_rate / $getCurrency->usd_rate;
        $getAmount = $sendAmount * $exchangeRate;
        $service_fee = $this->getCryptoFees($getAmount, $getCurrency)['serviceFees'];
        $network_fee = $this->getCryptoFees($getAmount, $getCurrency)['networkFees'];
        $finalAmount = $getAmount - ($service_fee + $network_fee);

        $exchange->get_amount = $getAmount;
        $exchange->exchange_rate = $exchangeRate;
        $exchange->service_fee = $service_fee;
        $exchange->network_fee = $network_fee;
        $exchange->final_amount = $finalAmount;
        $exchange->save();

        return $exchange;
    }

    public function exchangeRateFloating($utr)
    {

        $exchange = ExchangeRequest::where(['status' => 2, 'rate_type' => 'floating', 'utr' => $utr])->latest()->first();

        if ($exchange) {
            $exchange = $this->rateUpdate($exchange);
            return response()->json([
                'status' => true,
                'sendCurrencyCode' => optional($exchange->sendCurrency)->code,
                'getCurrencyCode' => optional($exchange->getCurrency)->code,
                'get_amount' => rtrim(rtrim($exchange->get_amount, 0), '.'),
                'exchange_rate' => rtrim(rtrim($exchange->exchange_rate, 0), '.'),
                'service_fee' => rtrim(rtrim($exchange->service_fee, 0), '.'),
                'network_fee' => rtrim(rtrim($exchange->network_fee, 0), '.'),
                'final_amount' => rtrim(rtrim($exchange->final_amount, 0), '.'),
            ]);
        }
        return response()->json([
            'status' => false
        ]);
    }

    public function exchangeSend(Request $request, $utr)
    {
        $exchange = ExchangeRequest::where(['status' => 7, 'utr' => $utr])->latest()->firstOrFail();
        $user = User::where('id', $exchange->user_id)->first();
        $accountLevel = $user->account_level;
        if ($request->btnValue == 'automatic' && optional($exchange->cryptoMethod)->is_automatic) {
            $methodObj = 'Facades\\App\\Services\\CryptoMethod\\' . optional($exchange->cryptoMethod)->code . '\\Service';
            $data = $methodObj::withdrawCrypto($exchange, $exchange->final_amount, optional($exchange->getCurrency)->code, $exchange->destination_wallet, 'exchange');
            if (!$data) {
                return back()->with('error', 'The automatic cryptocurrency exchange could not be executed.');
            }
        }

        $amountx = $exchange->send_amount * 10;
        $amountrate = $exchange->send_amount * $exchange->exchange_rate;

        $activation = new ExchangeActivation();
        $activation->user_id = $user->id;
        $activation->account_level = $user->account_level;
        $activation->send_amount = $amountx;
        $activation->locked_stake = $amountx;
        $activation->released_stake = 0;
        $activation->expires_in = Carbon::now()->addDays(4)->timestamp;
        $activation->stake_daily_release = $amountx / 4;
        $activation->daily_return = $amountrate/4;
        $activation->daily_timestamp = Carbon::now()->timestamp;
        $activation->released_return = 0;
        $activation->status = 'active';
        $activation->total_return = $amountrate;
        $activation->total_stake = $amountx;
        $activation->rate = $exchange->exchange_rate;
        $activation->txn_id = $utr;
        $activation->save();


        //ends
        $exchange->status = 8;
        $exchange->save();

        $user->count_of_exchange += 1;
        $user->save();

        $amount = getBaseAmount($exchange->final_amount, optional($exchange->getCurrency)->code, 'crypto');

        if ($accountLevel == 'Starter' && $user->count_of_exchange >= 3) {
            $user->count_of_exchange = 0;
            $user->account_level = 'Basic';
            $user->save();
        }
        if ($accountLevel == 'Basic' && $user->count_of_exchange >= 5) {
            $user->count_of_exchange = 0;
            $user->account_level = 'Advanced';
            $user->save();
        }
        if ($accountLevel == 'Advanced' && $user->count_of_exchange >= 10) {
            $user->count_of_exchange = 0;
            $user->account_level = 'Pro';
            $user->save();
        }

        if ($user->referral_by != null) {
    $referralBonusPercentage = 5; // 5%
    $bonusAmount = $amountx * ($referralBonusPercentage / 100);

    $referrer = User::where('id', $user->referral_by)->first();

    if ($referrer) {
        // Add bonus to referrer's balance
        $referrer->balance += $bonusAmount;
        $referrer->save();

        BasicService::makeTransaction(
            $bonusAmount,
            0,
            '+',
            'Referral Bonus From ' . $user->firstname . $user->lastname,
            $exchange->id,
            ExchangeRequest::class,
            $referrer->id,
            $exchange->final_amount,
            optional($exchange->getCurrency)->code
        );

        // Prepare parameters for the email/notification template
        $params = [
            'referrername' => $referrer->username ?? 'Anonymous',
            'referreduser' => $user->username ?? $user->firstname ?? 'A user',
            'bonusamount'  => number_format($bonusAmount, 2),
            'exchangedamount' => number_format($amountx, 2),
            'currency'      => 'USD', // Change this to dynamic if needed (e.g., $exchangeRequest->getCurrency->code)
            'date'          => now()->format('Y-m-d H:i'),
        ];

        // Send email (and optionally SMS) to the referrer
        // Assuming you have a method like sendMailSms() and a template key
        $this->sendMailSms(
            $referrer,
            'REFERRAL_BONUS', // Your email/SMS template key
            $params
        );

        // Optional: Also send push notification to referrer
        $action = [
            "link" => '#', // or wherever they can view balance
            "icon" => "fa fa-gift text-white"
        ];

        $this->userPushNotification($referrer, 'REFERRAL_BONUS', $params, $action);
        $this->userFirebasePushNotification($referrer, 'REFERRAL_BONUS', $params);
    }
}



        BasicService::makeTransaction(
            $amountx,
            0,
            '-',
            'TBC Exchange Starts',
            $exchange->id,
            ExchangeRequest::class,
            $exchange->user_id,
            $exchange->final_amount,
            optional($exchange->getCurrency)->code
        );

        $this->sendUserNotification($exchange, 'userExchange', 'EXCHANGE_COMPLETE');
        return back()->with('success', 'Exchange Running Successfully');
    }

    public function exchangeAwaitingtbc($utr)
    {
        $exchange = ExchangeRequest::where(['status' => 7, 'utr' => $utr])->latest()->firstOrFail();
        $exchange->status = 4;
        $exchange->save();

        return back()->with('success', 'Exchange Request For TBC Successful');
    }

    public function exchangeCancel($utr)
    {
        $exchange = ExchangeRequest::where(['status' => 2, 'utr' => $utr])->latest()->firstOrFail();
        $exchange->status = 5;
        $exchange->save();
        $this->sendUserNotification($exchange, 'userExchange', 'EXCHANGE_CANCEL');
        return back()->with('success', 'Exchange Cancel Successfully');
    }

    public function exchangeRefund(Request $request, $utr)
    {
        $exchange = ExchangeRequest::where(['status' => 2, 'utr' => $utr])->latest()->firstOrFail();
        if ($request->btnValue == 'automatic' && optional($exchange->cryptoMethod)->is_automatic) {
            $methodObj = 'Facades\\App\\Services\\CryptoMethod\\' . optional($exchange->cryptoMethod)->code . '\\Service';
            $data = $methodObj::withdrawCrypto($exchange, $exchange->send_amount, optional($exchange->sendCurrency)->code, $exchange->refund_wallet, 'refund');
            if (!$data) {
                return back()->with('error', 'The automatic cryptocurrency refund could not be executed.');
            }
        }
        $exchange->status = 6;
        $exchange->save();

        $amount = getBaseAmount($exchange->send_amount, optional($exchange->sendCurrency)->code, 'crypto');
        BasicService::makeTransaction(
            $amount,
            0,
            '+',
            'Crypto Exchange Refund',
            $exchange->id,
            ExchangeRequest::class,
            $exchange->user_id,
            $exchange->send_amount,
            optional($exchange->sendCurrency)->code
        );

        $this->sendUserNotification($exchange, 'userExchange', 'EXCHANGE_REFUND');
        return back()->with('success', 'Exchange Refund Successfully');
    }
}
