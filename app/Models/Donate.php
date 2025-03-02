<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donate extends Model
{
    use HasFactory;
    protected $table = "donates";
    protected $fillable = [
        'address',
        'phone',
        'name',
        'note',
        'amount',
        'payment_type',
        'payment_currency',
        'whastapp',
        'status',

    ];
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }
    public static function getTodayTotalByType(): array
    {
        $totals = self::selectRaw("
            payment_currency,
            SUM(amount) as total_sum
        ")
            ->groupBy('payment_currency')
            ->get()
            ->pluck('total_sum', 'payment_currency')
            ->toArray();
        return [
            'dollar' => isset($totals[1]) ? (float) $totals[1]  : 0.0,
            'lebanon' => isset($totals[2]) ? (float) $totals[2] : 0.0,
        ];
    }
}
