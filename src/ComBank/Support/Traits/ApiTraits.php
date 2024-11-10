<?php namespace ComBank\Support\Traits;

use ComBank\Transactions\Contracts\BankTransactionInterface;

trait ApiTraits 
{
    //public function validateEmail (string $email): bool{

    //}
    public function convertBalance(float $balance): float{
        $from = 'EUR';
        $to = 'USD'; 

        $url = "https://api.fxfeed.io/v1/convert?api_key=fxf_FZUKOloOQGT4CFYaqsxq&from=$from&to=$to&amount=$balance";

        $headers = [
            "Accept: application/json",
            "x-api-key: sk_0bedebec6a3c4d07b5bb28045098e4e6" 
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error en la petición: ' . curl_error($ch);
        } else {
            $data = json_decode($response, true);
        }
        
        curl_close($ch);

        return $data["result"];
    }
    //public function detectFraud (BankTransactionInterface $transaction): bool{

    //}
}