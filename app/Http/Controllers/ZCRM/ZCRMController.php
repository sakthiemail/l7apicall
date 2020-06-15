<?php

namespace App\Http\Controllers\ZCRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//use App\Helpers\APIHelper\ZohoCRM\RestClient;
use App\Helpers\APIHelper\ZohoCRM\RestAPIs;
use App\Models\Account\Account;

class ZCRMController extends Controller
{
    /**
    * List the ZCRM accounts
    * 
    */
    public function index()
    {
        $accounts = Account::all();
        return view('zcrm.accounts.index', compact('accounts'));
    }

    public function getZCRMResponse()
    {
        //$obj_rest = new RestClient();
        $obj_rest = new RestAPIs();
        $accounts = $obj_rest->getZCRMAccounts();
        dd($accounts);
    }    
}
