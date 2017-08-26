<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class MainController
 * @package App\Http\Controllers
 */
class MainController extends Controller
{
    /**
     * @param Request $request
     *
     * @return array
     */
    public function calculate(Request $request)
    {
        $sum   = $request->get('sum');
        $range = $request->get('range');
        $rate  = $request->get('rate');
        $month = $request->get('month');
        $year  = $request->get('year');

        $monthRate   = ($rate / 100 / 12);
        $coefficient = ($monthRate * pow((1 + $monthRate), $range)) / (pow((1 + $monthRate), $range) - 1);
        $payment     = round($coefficient * $sum, 2);
        $result      = [];

        foreach (range(1, $range) as $i) {
            $percentPayment = round($sum * $monthRate, 2);
            $creditPayment  = round($payment - $percentPayment, 2);

            $result[$i]['month']       = $month;
            $result[$i]['position']    = $i;
            $result[$i]['year']        = $year;
            $result[$i]['debt']        = $sum;
            $result[$i]['main_debt']   = $payment - $percentPayment;
            $result[$i]['percent_pay'] = $percentPayment;
            $result[$i]['credit_pay']  = $creditPayment;
            $result[$i]['payment']     = $payment;

            $sum -= $creditPayment;

            if ($month++ >= 12) {
                $month = 1;
                $year++;
            }
        }

        return array_values($result);
    }
}