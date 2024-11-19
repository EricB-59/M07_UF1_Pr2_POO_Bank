<?php namespace ComBank\Support\Traits;

use ComBank\Transactions\Contracts\BankTransactionInterface;

trait ApiTraits 
{
    public function validateEmail (string $email): bool{
        $url = "https://api.usercheck.com/email/$email";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        
        curl_close($ch);
        
        $data = json_decode($response, true);
        
        return $data["status"] == 200;
    }
    
    public function convertBalance(float $balance): float{
        $from = 'EUR';
        $to = 'USD'; 

        $url = "https://api.fxfeed.io/v1/convert?api_key=fxf_FZUKOloOQGT4CFYaqsxq&from=$from&to=$to&amount=$balance";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $data = json_decode($response, true);
        
        curl_close($ch);

        return $data["result"];
    }
    public function detectFraud (BankTransactionInterface $transaction): bool{
        $url = "https://673782ba4eb22e24fca55f05.mockapi.io/api/fraud/fraud-api";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $data = json_decode($response, true);
        curl_close($ch);

        $action = false;
        $amountTransaction = $transaction->getAmount();

        for ($i=0; $i < count($data); $i++) { 
            foreach ($data[$i] as $key => $value) {
                
            }
        }

        
        return $action;
    }
}