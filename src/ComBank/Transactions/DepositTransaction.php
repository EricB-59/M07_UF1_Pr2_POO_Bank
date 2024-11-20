<?php namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 11:30 AM
 */

use ComBank\Bank\Contracts\BackAccountInterface;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Support\Traits\ApiTraits;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class DepositTransaction extends BaseTransaction implements BankTransactionInterface
{
    use ApiTraits;
    public function __construct($amount){
        parent::validateAmount($amount);
        $this->amount = $amount;
    }
    public function applyTransaction(BackAccountInterface $bankAccount): float{
        if ($this->detectFraud($this)) {
            throw new FailedTransactionException('Blocked by possible fraud');
        }
        $balance = $bankAccount->getBalance();
        $amountTransaction = $this->getAmount(); 
        return $balance += $amountTransaction;
    }
    public function getTransactionInfo(): string{
        return 'DEPOSIT_TRANSACTION';
    }
    public function getAmount(): float{
        return $this->amount;
    }
}
