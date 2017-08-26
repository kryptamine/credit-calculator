<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreditCalculateRequest;
use App\Models\Request as RequestModel;

/**
 * Class MainController
 * @package App\Http\Controllers
 */
class MainController extends Controller
{
    /**
     * @param CreditCalculateRequest $request
     * @param RequestModel           $requestModel
     *
     * @return array
     */
    public function calculate(CreditCalculateRequest $request, RequestModel $requestModel): array
    {
        $sum   = $request->get('sum');
        $range = $request->get('range');
        $rate  = $request->get('rate');
        $month = $request->get('month');
        $year  = $request->get('year');

        $requestModel->fill($request->only(['sum', 'range', 'rate', 'month', 'year']))->save();

        $monthRate   = ($rate / 100 / 12);
        $coefficient = ($monthRate * pow((1 + $monthRate), $range)) / (pow((1 + $monthRate), $range) - 1);
        $payment     = round($coefficient * $sum, 2);
        $result      = [];

        foreach (range(1, $range) as $i) {
            $percentPayment = round($sum * $monthRate, 2);
            $creditPayment  = round($payment - $percentPayment, 2);
            $result[$i]     = [
                'position'        => $i,
                'month'           => $month,
                'year'            => $year,
                'debt'            => $sum,
                'main_debt'       => $payment - $percentPayment,
                'percent_payment' => $percentPayment,
                'credit_payment'  => $creditPayment,
                'payment'         => $payment,
            ];

            $sum -= $creditPayment;

            if ($month++ >= 12) {
                $month = 1;
                $year++;
            }
        }

        return array_values($result);
    }
}