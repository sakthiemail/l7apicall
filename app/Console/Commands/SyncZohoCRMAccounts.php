<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\APIHelper\ZohoCRM\RestAPIs;
use App\Models\Account\Account;

class SyncZohoCRMAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:zohoaccounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Zoho CRM accounts to local database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $obj_rest = new RestAPIs();
        $zcrmaccounts = $obj_rest->getZCRMAccounts();
       
        if(isset($zcrmaccounts['data'])){
            $i=0;
            foreach($zcrmaccounts['data'] as $zcrmaccount){
                $account = Account::where('account_id', $zcrmaccount['id'])->first();
                if(!$account){
                    $account = new Account();                    
                }  
                $account->account_id = $zcrmaccount['id'];         
                $account->account_name = $zcrmaccount['Account_Name'];
                $account->description = $zcrmaccount['Description'];
                $account->account_number = $zcrmaccount['Account_Number'];
                $account->account_phone = $zcrmaccount['Phone'];
                $account->account_type = $zcrmaccount['Account_Type'];
                $account->industry = $zcrmaccount['Industry'];
                $account->employees = $zcrmaccount['Employees'];
                $account->website = $zcrmaccount['Website'];
                $account->billing_street = $zcrmaccount['Billing_Street'];
                $account->billing_city = $zcrmaccount['Billing_City'];
                $account->billing_state = $zcrmaccount['Billing_State'];
                $account->billing_country = $zcrmaccount['Billing_Country'];
                $account->billing_code = $zcrmaccount['Billing_Code'];
                $account->save();
                $i++;
            }
            echo $i." Accounts Details are Synced\n";
        }else{
            echo "Accounts Details are not available\n";
        }
    }
}
