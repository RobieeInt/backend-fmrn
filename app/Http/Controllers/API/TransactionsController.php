<?php

namespace App\Http\Controllers\API;

use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class TransactionsController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $food_id = $request->input('food_id');
        $status = $request->input('status');

        // $price_from = $request->input('price_from');
        // $price_to = $request->input('price_to');

        // $rate_from = $request->input('rate_from');
        // $rate_to = $request->input('rate_to');

        if($id)
        {
            $transactions = Transactions::with(['food','user'])->find($id);

            if ($transactions)
            {
                return ResponseFormatter::success(
                    $transactions,
                    'Data Transaksi Berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data Transaksi Gada',
                    404
                );
            }
        }

        $transactions = Transactions::with(['food','user'])
                        ->where('user_id', Auth::user()->id);

        if($food_id)
        {
            $transactions->where('food_id', $food_id );
        }

       if($status)
        {
            $transactions->where('status', $status );
        }

       
        return ResponseFormatter::success(
            $transactions->paginate($limit),
            'Data List Transaksi Berhasil diambil'
        );
    }

    public function update(Request $request, $id)
    {
        $transactions = Transactions::findOrFail($id);

        $transactions->update($request->all());

        return ResponseFormatter::success($transactions, 'Transaksi Berhasil diperbarui');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'food_id' => 'required|exists:food,id',
            'user_id' => 'required|exists:users,id',
            'quantity' => 'required',
            'total' => 'required',
            'status' => 'required',
        ]);

        $transactions = Transactions::create([
            'food_id' => $request->food_id,
            'user_id' => $request->user_id,
            'quantity' => $request->quantity,
            'total' => $request->total,
            'status' => $request->status,
            'payment_url' => '',
        ]);

        //Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        //Panggil Transaksi yang baru dibuat
        $transactions = Transactions::with(['food', 'user'])->find($transactions->id);

        //Ngebuat Transaksi Midtrans
        $midtrans = [
            'transaction_details' => [
                'order_id' => $transactions->id,
                'gross_amount' => (int) $transactions->total,
            ],
            'customer_details' => [
                'first_name' => $transactions->user->name,
                'email' => $transactions->user->email,
            ],
            'enabled_payment' => ['gopay','bank_transfer'],
            'vtweb' => []
        ];

        // Manggil Midtrana
        try {
            //Ambil Halaman Midtrans payment
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;

            $transactions->payment_url = $paymentUrl;
            $transactions->save();

            // kembaliin data ke API 
            return ResponseFormatter::success($transactions, 'Transaksi Berhasil');


        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 'Transaksi Gagal');
        }


    }
}

