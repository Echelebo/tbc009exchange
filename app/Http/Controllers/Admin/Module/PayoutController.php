<?php

namespace App\Http\Controllers\Admin\Module;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use App\Traits\SendNotification;
use Carbon\Carbon;
use Facades\App\Services\BasicService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PayoutController extends Controller
{
    use SendNotification;

    public function payoutList(Request $request)
{
    if (!in_array($request->type, ['all', 'pending', 'complete', 'cancel'])) {
        abort(404);
    }
    $data['payoutType'] = $request->type;
    $data['payouts'] = collect(PayoutRequest::selectRaw('COUNT(id) AS totalPayout')
        ->selectRaw('COUNT(CASE WHEN status = 0 THEN id END) AS pendingPayout')
        ->selectRaw('(COUNT(CASE WHEN status = 0 THEN id END) / COUNT(id)) * 100 AS pendingPayoutPercentage')
        ->selectRaw('COUNT(CASE WHEN status = 1 THEN id END) AS completePayout')
        ->selectRaw('(COUNT(CASE WHEN status = 1 THEN id END) / COUNT(id)) * 100 AS completePayoutPercentage')
        ->selectRaw('COUNT(CASE WHEN status = 2 THEN id END) AS cancelPayout')
        ->selectRaw('(COUNT(CASE WHEN status = 2 THEN id END) / COUNT(id)) * 100 AS cancelPayoutPercentage')
        ->get()
        ->toArray())->collapse();
    return view('admin.payout.index', $data);
}
public function payoutListSearch(Request $request)
{
    $payoutType = $request->type;
    $search = $request->search['value'] ?? null;
    $filterName = $request->name;
    $filterStatus = $request->filterStatus;
    $filterDate = explode('-', $request->filterDate);
    $startDate = $filterDate[0];
    $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;
    $payouts = PayoutRequest::with(['user:id,firstname,lastname,username,image,image_driver'])
        ->orderBy('id', 'DESC')
        ->when(isset($payoutType), function ($query) use ($payoutType) {
            if ($payoutType == 'pending') {
                return $query->where('status', 0);
            } elseif ($payoutType == 'complete') {
                return $query->where('status', 1);
            } elseif ($payoutType == 'cancel') {
                return $query->where('status', 2);
            } else {
                return $query->whereIn('status', [0, 1, 2]);
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
                    ->orWhere('amount', 'LIKE', "%$search%");
            });
        });
    return DataTables::of($payouts)
        ->addColumn('checkbox', function ($item) {
            return '<input type="checkbox" id="chk-' . $item->id . '"
                                   class="form-check-input row-tic tic-check" name="check" value="' . $item->id . '"
                                   data-id="' . $item->id . '">';
        })
        ->addColumn('trx_id', function ($item) {
            return $item->utr;
        })
        ->addColumn('amount', function ($item) {
            return $item->amount;
        })
        ->addColumn('payable_amount', function ($item) {
            return $item->amount;
        })
        ->addColumn('status', function ($item) {
            return $item->status;
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
            $delete = route('admin.payoutDelete', $item->id);
            $view = route('admin.payoutView') . '?id=' . $item->id;
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
        ->rawColumns(['checkbox', 'trx_id', 'amount', 'payable_amount', 'status', 'requester', 'created_at', 'action'])
        ->make(true);
}

    public function payoutDelete($id)
{
    PayoutRequest::findOrFail($id)->delete();
    return back()->with('success', 'Payout Deleted Successfully');
}
public function payoutMultipleDelete(Request $request)
{
    if ($request->strIds == null) {
        session()->flash('error', 'You do not select row.');
        return response()->json(['error' => 1]);
    } else {
        PayoutRequest::whereIn('id', $request->strIds)->get()->map(function ($query) {
            $query->delete();
            return $query;
        });
        session()->flash('success', 'Payout has been deleted successfully');
        return response()->json(['success' => 1]);
    }
}
public function payoutView(Request $request)
{
    $payout = PayoutRequest::findOrFail($request->id);
    return view('admin.payout.details', compact('payout'));
}
public function payoutSend(Request $request, $utr)
{
    $payout = PayoutRequest::where(['status' => 0, 'utr' => $utr])->latest()->firstOrFail();
    $user = User::where('id', $payout->user_id)->first();
    $payout->status = 1;
    $payout->save();
    BasicService::makeTransaction(
        $payout->amount,
        0,
        '-',
        'Payout Completed',
        $payout->id,
        PayoutRequest::class,
        $payout->user_id,
        $payout->final_amount,
        optional($payout->currency)->code
    );
    $this->sendUserNotification($payout, 'userPayout', 'PAYOUT_COMPLETE');
    return back()->with('success', 'Payout Completed Successfully');
}
public function payoutCancel($utr)
{
    $payout = PayoutRequest::where(['status' => 0, 'utr' => $utr])->latest()->firstOrFail();
    $payout->status = 2;
    $payout->save();
    $this->sendUserNotification($payout, 'userPayout', 'PAYOUT_CANCEL');
    return back()->with('success', 'Payout Cancel Successfully');
}
}
