<?php
//app/Helpers/APIHelper/ZohoCRM/RestClient.php
namespace App\Helpers\APIHelper\ZohoCRM;

use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\crm\crud\ZCRMModule;
use zcrmsdk\oauth\ZohoOAuth;
use zcrmsdk\crm\exception\ZCRMException;
use Carbon\Carbon;

use App\Models\ZCRM\ZCRMOAuthTokens;

class RestClient
{
    private $access_code;
    private $client_id;
    private $client_secret;
    private $redirect_url;
    private $user_email;
        
    /**
    *  Initilize variables from ENV
    */ 
   public  function __construct()
    {
        $this->access_code = env('ZCRM_CODE');
        $this->client_id =  env('ZCRM_CLIENT_ID');
        $this->client_secret = env('ZCRM_SECRET');
        $this->redirect_url = env('ZCRM_REDIRECT_URL');
        $this->user_email = env('ZCRM_USER_EMAIL');     
    }

    /**
     * Get and Store grant tokens.
     *
     * @return boolean
     */
    public function getGrantTokens()
    {    
        $url = "https://accounts.zoho.in/oauth/v2/token?code=" . $this->access_code ."&redirect_uri=". $this->redirect_url . "&client_id=" . $this->client_id . "&client_secret=" .  $this->client_secret . "&grant_type=authorization_code";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
            return false;

        } else {
            $tokens = json_decode($response,true);
            if (isset($tokens['access_token'])) {              
                $zcrmoauth = ZCRMOAuthTokens::where('useridentifier', $this->user_email)->first();
                if(!$zcrmoauth)
                    $zcrmoauth = new ZCRMOAuthTokens(); 
                $zcrmoauth->useridentifier = $this->user_email;
                $zcrmoauth->accesstoken = $tokens['access_token'];
                $zcrmoauth->refreshtoken = $tokens['refresh_token'];
                $zcrmoauth->expirytime = $tokens['expires_in'];   
                $zcrmoauth->expires_at = Carbon::now();   
                $zcrmoauth->save();
            }
            return true;            
        }
    }

     /**
     * Get ZCRMAccounts from ZCRM.
     *
     * @return array
     */
    public function getZCRMAccounts(){        
        try {

            // generate Grant and Refresh Token
            $this->getGrantTokens();

            // Initilize ZCRMRestClient
            $configuration = array(  
                "client_id" => $this->client_id,
                "client_secret" => $this->client_secret,
                "redirect_uri"=> $this->redirect_url,
                "currentUserEmail" => $this->user_email,
                "access_type"=>"offline",
                "accounts_url"=>"https://accounts.zoho.in",
                "apiBaseUrl"=>"www.zohoapis.in",  
                "userIdentifier"=>$this->user_email,         
            );

            // Initilize ZCRMRestClient
            ZCRMRestClient::initialize($configuration);          
            $oAuthClient = ZohoOAuth::getClientInstance(); 

            $zcrmoauth =  new ZCRMOAuthTokens;
            $zcrmoauth = ZCRMOAuthTokens::where('useridentifier',$this->user_email)->firstOrFail();

            //Generate Access Tokens
            if (!$zcrmoauth->tokenExpired()) {
                $oAuthTokens = $oAuthClient->generateAccessTokenFromRefreshToken($zcrmoauth->refreshtoken, $this->user_email);             
            }else{
                $oAuthTokens = $oAuthClient->generateAccessToken($this->access_code);               
            } 

            //create instances for Accounts Module     
            $zcrmModuleIns = ZCRMModule::getInstance("Accounts");
            $bulkAPIResponse = $zcrmModuleIns->getRecords();
            $recordsArray = $bulkAPIResponse->getData(); 
            //Return Accounts Data        
            return $recordsArray;   

        } catch( ZCRMException $e){
            echo $e->getCode();
            echo $e->getMessage();
            echo $e->getExceptionCode();
        }
    }

}