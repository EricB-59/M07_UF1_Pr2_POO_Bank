<?php namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:22 PM
 */

use ComBank\Bank\Contracts\BackAccountInterface;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class WithdrawTransaction extends BaseTransaction implements BankTransactionInterface
{
    public function __construct($amount){
        parent::validateAmount($amount);
        $this->amount = $amount;
    }
    public function applyTransaction(BackAccountInterface $bankAccount): float{
        $balance = $bankAccount->getBalance();
        $amountTransaction = $this->getAmount(); 
        $finalBalance = $balance - $amountTransaction;

        $overdraft = $bankAccount->getOverdraft()->isGrantOverdraftFunds($finalBalance);
        
        if ($finalBalance < 0) {
            $path = "ComBank\OverdraftStrategy"; 
            $rate = 0;

            match ($bankAccount->getOverdraft()::class) {
                $path."\NoOverdraft" => $rate = 0,
                $path."\BasicOverdraft" => $rate = 5,
                $path."\SilverOverdraft" => $rate = 2,
                $path."\GoldOverdraft" => $rate = 1,
            };
            if ($overdraft) {
                return $finalBalance * (1 + $rate / 100);
            }else{
                throw new InvalidOverdraftFundsException("Insufficient balance to complete the withdrawal.");
            }
        } 
        return $finalBalance;
    }
    public function getTransactionInfo(): string{
        return 'WITHDRAW_TRANSACTION';
    }
    public function getAmount(): float{
        return $this->amount;
    }
   
}
