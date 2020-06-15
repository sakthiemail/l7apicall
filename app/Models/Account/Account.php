<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * The table associated with the model. 
     *
     * @var string
     */
    protected $table = 'accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id', 
        'account_name', 
        'description', 
        'account_number', 
        'account_phone',
        'account_type',
        'industry',
        'employees',
        'website',
        'billing_street',
        'billing_city',
        'billing_state',
        'billing_country',
        'billing_code',
    ];
}
