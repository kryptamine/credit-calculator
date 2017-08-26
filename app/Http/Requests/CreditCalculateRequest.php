<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreditCalculateRequest
 * @package App\Http\Requests
 */
class CreditCalculateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sum'   => 'required',
            'range' => 'required',
            'rate'  => 'required',
            'year'  => 'required',
            'month' => 'required|between:1,12',
        ];
    }
}
