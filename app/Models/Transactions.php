<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transactions extends Model
{
    use HasFactory, SoftDeletes;
     protected $fillable = [
        'jhajan_id',
        'user_id',
        'quantity',
        'total',
        'status',
        'payment_url',
    ];

    public function jhajan()
    {
        return $this->hasOne(Jhajan::class,'id','jhajan_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getCreatedAtAttribute($value) // ini asesor buat rubah tanggal 
    {
        return Carbon::parse($value)->timestamp;
    }
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }
}
