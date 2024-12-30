<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {

        $month = 12;

        $successTransaction = Transaction::getData($month, 1);
        $successTransactionCart = $this->chart($successTransaction, $month);

        $unSuccessTransaction = Transaction::getData($month, 0);
        $unSuccessTransactionCart = $this->chart($unSuccessTransaction, $month);



        return view('admin.dashboard', [
            'successTransactionCart' => array_values($successTransactionCart),
            'unSuccessTransactionCart' => array_values($unSuccessTransactionCart),
            'labels' => array_keys($successTransactionCart),
            'transactionsCount' =>  [$successTransaction->count() , $unSuccessTransaction->count()]
        ]);
    }


    public function chart($transaction, $month)
    {

        $monthName =  $transaction->map(function ($item) {
            return verta($item->created_at)->format('%B %y');
        });

        $amounts =  $transaction->map(function ($item) {
            return $item->amount;
        });

        foreach ($monthName as $i => $v) {
            if (!isset($resulte[$v])) {
                $resulte[$v] = 0;
            }
            $resulte[$v] += $amounts[$i];
        }

        if (count($resulte) != $month) {

            for ($i = 0; $i < $month; $i++) {
                $monthName = verta()->subMonths($i)->format('%B %y');

                $shamsiMonth[$monthName] = 0;
            }
            return array_reverse(array_merge($shamsiMonth, $resulte));
        }

        return $resulte;
    }
}
