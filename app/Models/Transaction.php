<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusAttribute($status)
    {
        switch ($status) {
            case '0':
                $status = 'ناموفق';
                break;
            case '1':
                $status = 'موفق';
                break;
            default:
                $status = 'نظری ندارم';
                break;
        }

        return $status;
    }

    public function scopeGetData($query , $month , $status)
    {
        $v = verta()->startMonth()->subMonths($month - 1);
        $date = verta()->jalaliToGregorian($v->year, $v->month, $v->day);

        return $query->where('created_at', '>', Carbon::create($date[0], $date[1], $date[2], 0, 0, 0))->orderBy('created_at' , 'asc')->where('status', $status)->get();;
    }
}
