<?php

namespace App\Models\ZCRM;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ZCRMOAuthTokens extends Model
{
    /**
     * The table associated with the model. 
     *
     * @var string
     */
    protected $table = 'oauthtokens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'useridentifier', 'accesstoken', 'refreshtoken', 'expirytime','expires_at'
    ];

    // Probably on the user model, but pick wherever the data is
    public function tokenExpired()
    {
        if (Carbon::parse($this->attributes['expires_at']) < Carbon::now()) {
            return true;
        }
        return false;
    }    
    /**
     * add 45 mins to expires_at's value .
     *
     * @param  Datetime  $value
     * @return void
     */

    public function setExpiresAtAttribute($value)
    {
        $this->attributes['expires_at'] = Carbon::parse($value)->addMinutes(45);
    }

}
