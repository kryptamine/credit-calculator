<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RequestModel
 * @package App\Models
 */
class Request extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sum', 'rate', 'range', 'month', 'year',
    ];

}