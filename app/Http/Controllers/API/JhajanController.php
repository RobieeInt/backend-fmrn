<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Jhajan;
use Illuminate\Http\Request;

class JhajanController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('name');
        $types = $request->input('types');

        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');

        $rate_from = $request->input('rate_from');
        $rate_to = $request->input('rate_to');

        if($id)
        {
            $jhajan = Jhajan::find($id);

            if ($jhajan)
            {
                return ResponseFormatter::success(
                    $jhajan,
                    'Data Jhajanan Berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data Jhajanan Gada',
                    404
                );
            }
        }

        $jhajan = Jhajan::query();

        if($name)
        {
            $jhajan->where('name', 'like','%'. $name . '%');
        }

        if($types)
        {
            $jhajan->where('types', 'like','%'. $types . '%');
        }

        if($price_from)
        {
            $jhajan->where('price_from','>=',$price_from);
        }

        if($price_to)
        {
            $jhajan->where('price_to','<=',$price_to);
        }
        
        if($rate_from)
        {
            $jhajan->where('rate_from','>=',$rate_from);
        }

        if($rate_to)
        {
            $jhajan->where('rate_to','<=',$rate_to);
        }

        return ResponseFormatter::success(
            $jhajan->paginate($limit),
            'Data List Jhajanan Berhasil diambil'
        );
    }
}
