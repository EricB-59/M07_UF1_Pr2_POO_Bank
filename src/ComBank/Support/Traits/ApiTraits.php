<?php namespace ComBank\Support\Traits;

use ComBank\Transactions\Contracts\BankTransactionInterface;

trait ApiTraits 
{
    //public function validateEmail (string $email): bool{

    //}
    public function convertBalance(float $balance): float{
        $ch = curl_init();
        $url = 'https://manyapis.com/products/currency/eur-to-usd-rate?amount='.$balance;
        var_dump($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => 'sk_4b169a7fa2904da0a5e482d24b859149',
            CURLOPT_SSL_VERIFYPEER => false,
        ));
        $result = curl_exec($ch);
        var_dump($result);
        curl_close($ch);
        $convertJson = json_decode($result);
        return $convertJson->convertedAmount;
    }
    //public function detectFraud (BankTransactionInterface $transaction): bool{

    //}
}