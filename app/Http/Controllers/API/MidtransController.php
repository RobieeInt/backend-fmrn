<?php

namespace App\Http\Controllers\API;

use Midtrans\Config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transactions;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function callback (Request $request)
    {
        //Set Config Midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        //buat Instante notif midtrans
        $notification = new Notification();

        //Assign Variable biar mudah Coding
        $status = $notification->status;
        $type = $notification->payment_type;
        $fraud = $notification->fraud_status;
        $order_id = $notification->order_id;

        // cari transaksi berdasarkan ID
        $transactions = Transactions::findorFail($order_id);

        // Handle Notifikasi status midtrans
        if($status == 'capture')
        {
            if($type == 'credit_card')
            {
                if($fraud == 'challenge')
                {
                    $transactions->status='PENDING';
                } else {
                    $transactions->status='SUCCESS';
                }
            }
            
        }
        elseif($status == 'settlement')
        {
            $transactions->status='SUCCESS';
        }
        elseif($status =='pending')
        {
            $transactions->status='PENDING';
        }
        elseif($status =='deny')
        {
            $transactions->status='CANCELLED';
        }
        elseif($status =='expire')
        {
            $transactions->status='CANCELLED';
        }
        elseif($status =='cancel')
        {
            $transactions->status='CANCELLED';
        }

        // cimpan transaksi
        $transactions->save();
    }

    public function success()
    {
        return view('midtrans.success');
    }

    public function unfinish()
    {
        return view('midtrans.unfinish');
    }

    public function error()
    {
        return view('midtrans.error');
    }
}
