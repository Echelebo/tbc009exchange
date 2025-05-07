<?php

namespace App\Traits;

use App\Models\CryptoMethod;
use App\Models\ExchangeRequest;
use App\Models\SellRequest;
use Facades\App\Services\BasicService;

trait CryptoWalletGenerate
{
    use SendNotification;

    public function getCryptoWallet($cryptoCode, $type = 'exchange')
    {
        $activeMethod = CryptoMethod::where('status', 1)->first();
        if (!$activeMethod) {
            return $this->errorMsg('Active crypto method not found');
        }

        $methodObj = 'Facades\\App\\Services\\CryptoMethod\\' . $activeMethod->code . '\\Service';
        $data = $methodObj::prepareData($activeMethod, $cryptoCode, $type);
        if ($data) {
            return $this->successMsg($data);
        }

        return $this->errorMsg('something went wrong');
    }

    public function walletUpgration($object, $type): void
    {
        if ($type == 'exchange') {
            $object->status = 2;
            $object->save();
            $amount = getBaseAmount($object->send_amount, optional($object->sendCurrency)->code, 'crypto');
            $charge = getBaseAmount($object->service_fee + $object->network_fee, optional($object->getCurrency)->code, 'crypto');

            BasicService::makeTransaction($amount, $charge, '-', 'Crypto Deposit For Exchange',
                $object->id, ExchangeRequest::class, $object->user_id, $object->send_amount, optional($object->sendCurrency)->code);

            $this->sendAdminNotification($object, 'exchange');
        } elseif ($type == 'sell') {
            $object->status = 2;
            $object->save();

            $amount = getBaseAmount($object->send_amount, optional($object->sendCurrency)->code, 'crypto');
            $charge = getBaseAmount($object->processing_fee, optional($object->getCurrency)->code, 'fiat');

            BasicService::makeTransaction($amount, $charge, '-', 'Crypto Deposit For Sell',
                $object->id, SellRequest::class, $object->user_id, $object->send_amount, optional($object->sendCurrency)->code);

            $this->sendAdminNotification($object, 'sell');
        }
    }


    public function errorMsg($msg)
    {
        return [
            'status' => false,
            'message' => $msg,
        ];
    }

    public function successMsg($msg)
    {
        return [
            'status' => true,
            'message' => $msg,
        ];
    }
}
