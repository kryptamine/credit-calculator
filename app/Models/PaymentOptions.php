<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PaymentOptions
 * @package App\Models
 */
class PaymentOptions extends Model
{
    const MONTH_PER_YEAR  = 12;
    const ROUND_PRECISION = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sum', 'rate', 'range', 'month', 'year',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * @return $this
     */
    public function calculatePayments()
    {
        $monthRate   = ($this->rate / 100 / static::MONTH_PER_YEAR);
        $coefficient = ($monthRate * pow((1 + $monthRate), $this->range)) / (pow((1 + $monthRate), $this->range) - 1);
        $payment     = round($coefficient * $this->sum, static::ROUND_PRECISION);
        $sum         = $this->sum;
        $month       = $this->month;
        $year        = $this->year;
        $result      = [];

        foreach (range(1, $this->range) as $i) {
            $percentPayment = round($sum * $monthRate, static::ROUND_PRECISION);
            $creditPayment  = round($payment - $percentPayment, static::ROUND_PRECISION);

            $sum -= $creditPayment;

            $result[$i] = [
                'position'        => (int)$i,
                'month'           => (int)$month,
                'year'            => (int)$this->year,
                'debt'            => round($sum, static::ROUND_PRECISION),
                'main_debt'       => $payment - $percentPayment,
                'percent_payment' => $percentPayment,
                'credit_payment'  => $creditPayment,
                'payment'         => $payment,
            ];

            if ($month++ >= static::MONTH_PER_YEAR) {
                $month = 1;
                $year++;
            }
        }

        $this->payments()->createMany(array_values($result));

        return $this;
    }
}