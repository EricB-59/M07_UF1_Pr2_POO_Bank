<?php namespace ComBank\Support\Traits;

use \vendor\src\Resend\Resend;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Person\Person;

require_once '/opt/lampp/htdocs/M07_UF1_Pr2_POO_Bank/vendor/autoload.php';


trait ApiTraits 
{
    public function validateEmail (string $email): bool{
        $url = "https://api.usercheck.com/email/$email?key=6wvX5LJCQn0iqC9kQv1xqNoCbO6dgqaX";

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

        $fraud = false;
        $amountTransaction = $transaction->getAmount();

        foreach ($data as $key => $value) {
            if ($data[$key]['movementType'] == $transaction->getTransactionInfo()) {
                if ($data[$key]['amount'] < $amountTransaction && $data[$key]['amount_max'] > $amountTransaction) {
                    if ($data[$key]['action'] == BankTransactionInterface::TRANSACTION_BLOCKED) {
                        $fraud = true;
                        break;
                    } else {
                        $fraud = false;
                    }
                }
            }
        }

        return $fraud;
    }


    public function sendEmail(Person $person): void{
        // Assign a new Resend Client instance to $resend variable, which is automatically autoloaded...
        $resend = Resend::client('re_cPUrygsJ_F9Mo72L248P7SoL6KjwtHUG6');

        try {
            $result = $resend->emails->send([
                'from' => 'Acme <onboarding@resend.dev>',
                'to' => [$person->getEmail()],
                'subject' => 'Confirmation email',
                'html' => '<strong>This is an email to confirm your new bank account.</strong>',
            ]);
        } catch (\Exception $e) {
            exit('Error: ' . $e->getMessage());
        }

        // Show the response of the sent email to be saved in a log...
        echo $result->toJson();
    }
}