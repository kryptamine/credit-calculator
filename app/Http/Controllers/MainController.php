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
    const MONTH_PER_YEAR  = 12;
    const ROUND_PRECISION = 2;

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

        $monthRate   = ($rate / 100 / static::MONTH_PER_YEAR);
        $coefficient = ($monthRate * pow((1 + $monthRate), $range)) / (pow((1 + $monthRate), $range) - 1);
        $payment     = round($coefficient * $sum, static::ROUND_PRECISION);
        $result      = [];

        foreach (range(1, $range) as $i) {
            $percentPayment = round($sum * $monthRate, static::ROUND_PRECISION);
            $creditPayment  = round($payment - $percentPayment, static::ROUND_PRECISION);
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

            if ($month++ >= static::MONTH_PER_YEAR) {
                $month = 1;
                $year++;
            }
        }

        return array_values($result);
    }
}