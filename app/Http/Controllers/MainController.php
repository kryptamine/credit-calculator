<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreditCalculateRequest;
use App\Models\PaymentOptions;

/**
 * Class MainController
 * @package App\Http\Controllers
 */
class MainController extends Controller
{
    /**
     * @param CreditCalculateRequest $request
     * @param PaymentOptions         $paymentOptions
     *
     * @return array
     */
    public function calculate(CreditCalculateRequest $request, PaymentOptions $paymentOptions): array
    {
        $paymentOptions->fill($request->only(['sum', 'range', 'rate', 'month', 'year']))->save();

        return $paymentOptions->calculatePayments()->payments->toArray();
    }
}