<?php namespace ComBank\Bank;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:25 PM
 */

use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\OverdraftStrategy\NoOverdraft;
use ComBank\Bank\Contracts\BackAccountInterface;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Support\Traits\AmountValidationTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class BankAccount implements BackAccountInterface
{
    private $balance;
    private $status;
    private $overdraft;

    function __construct($balance) {
        $this->balance = $balance;
        $this->status = BackAccountInterface::STATUS_OPEN;
    }

    public function transaction(BankTransactionInterface $bankTransaction): void{
        $this->setBalance($bankTransaction->applyTransaction($this));
    }
    public function openAccount(): bool {
        return 0;
    }
    public function reopenAccount(): void {
        if ($this->status === BackAccountInterface::STATUS_CLOSED) {
            $this->status = BackAccountInterface::STATUS_OPEN;
            pl('My account is now reopened. ');
        }
    }
    public function closeAccount(): void {
        if ($this->status === BackAccountInterface::STATUS_OPEN) {
            $this->status = BackAccountInterface::STATUS_CLOSED;
            pl('My account is now closed. ');
        }
    }
    public function getBalance(): float {
        return $this->balance;
    }
    
    public function getOverdraft(): OverdraftInterface {

    }
    public function applyOverdraft($OverdraftInterface): void {

    }
    public function setBalance($float): void {
        $this->balance = $float;
    }
}
