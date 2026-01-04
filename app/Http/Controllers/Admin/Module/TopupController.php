<?php

namespace App\Http\Controllers\Admin\Module;

use App\Http\Controllers\Controller;
use App\Models\TopUpRequest;
use App\Traits\SendNotification;
use Carbon\Carbon;
use Facades\App\Services\BasicService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TopupController extends Controller
{
    use SendNotification;

    public function topupList(Request $request)
{
    if (!in_array($request->type, ['all', 'pending', 'complete', 'cancel'])) {
        abort(404);
    }
    $data['topupType'] = $request->type;
    $data['topups'] = collect(TopupRequest::selectRaw('COUNT(id) AS totalTopup')
        ->selectRaw('COUNT(CASE WHEN status = 0 THEN id END) AS pendingTopup')
        ->selectRaw('(COUNT(CASE WHEN status = 0 THEN id END) / COUNT(id)) * 100 AS pendingTopupPercentage')
        ->selectRaw('COUNT(CASE WHEN status = 1 THEN id END) AS completeTopup')
        ->selectRaw('(COUNT(CASE WHEN status = 1 THEN id END) / COUNT(id)) * 100 AS completeTopupPercentage')
        ->selectRaw('COUNT(CASE WHEN status = 2 THEN id END) AS cancelTopup')
        ->selectRaw('(COUNT(CASE WHEN status = 2 THEN id END) / COUNT(id)) * 100 AS cancelTopupPercentage')
        ->get()
        ->toArray())->collapse();
    return view('admin.topup.index', $data);
}
public function topupListSearch(Request $request)
{
    $topupType = $request->type;
    $search = $request->search['value'] ?? null;
    $filterName = $request->name;
    $filterStatus = $request->filterStatus;
    $filterDate = explode('-', $request->filterDate);
    $startDate = $filterDate[0];
    $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;
    $topups = TopupRequest::with(['user:id,firstname,lastname,username,image,image_driver'])
        ->orderBy('id', 'DESC')
        ->when(isset($topupType), function ($query) use ($topupType) {
            if ($topupType == 'pending') {
                return $query->where('status', 0);
            } elseif ($topupType == 'complete') {
                return $query->where('status', 1);
            } elseif ($topupType == 'cancel') {
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
    return DataTables::of($topups)
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
            $delete = route('admin.topupDelete', $item->id);
            $view = route('admin.topupView') . '?id=' . $item->id;
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

    public function topupDelete($id)
{
    TopupRequest::findOrFail($id)->delete();
    return back()->with('success', 'Top Up Deleted Successfully');
}
public function topupMultipleDelete(Request $request)
{
    if ($request->strIds == null) {
        session()->flash('error', 'You do not select row.');
        return response()->json(['error' => 1]);
    } else {
        TopupRequest::whereIn('id', $request->strIds)->get()->map(function ($query) {
            $query->delete();
            return $query;
        });
        session()->flash('success', 'Top Up has been deleted successfully');
        return response()->json(['success' => 1]);
    }
}
public function topupView(Request $request)
{
    $topup = TopupRequest::findOrFail($request->id);
    return view('admin.topup.details', compact('topup'));
}
public function topupSend(Request $request, $utr)
{
    $topup = TopupRequest::where(['status' => 0, 'utr' => $utr])->latest()->firstOrFail();
    $user = User::where('id', $topup->user_id)->first();
    $topup->status = 1;
    $topup->save();

    $user->balance += $topup->amount;
    $user->save();

    BasicService::makeTransaction(
        $topup->amount,
        0,
        '+',
        'Top Up Completed',
        $topup->id,
        TopupRequest::class,
        $topup->user_id,
        $topup->amount,
        'USDT'
    );
    $this->sendUserNotification($topup, 'userTopup', 'TOPUP_COMPLETE');
    return back()->with('success', 'Top Up Completed Successfully');
}
public function topupCancel($utr)
{
    $topup = TopupRequest::where(['status' => 0, 'utr' => $utr])->latest()->firstOrFail();
    $topup->status = 2;
    $topup->save();
    $this->sendUserNotification($topup, 'userTopup', 'TOPUP_CANCEL');
    return back()->with('success', 'Top Up Cancel Successfully');
}
}
