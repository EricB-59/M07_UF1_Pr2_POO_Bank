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


class BankAccount extends BankAccountException implements BackAccountInterface 
{
    private $balance;
    private $status;
    private $overdraft;

    function __construct($balance) {
        $this->balance = $balance;
        $this->status = BackAccountInterface::STATUS_OPEN;
        $this->overdraft = new NoOverdraft();
    }

    public function transaction(BankTransactionInterface $bankTransaction): void{
        if ($this->status === BackAccountInterface::STATUS_OPEN) {
            try {
                $this->setBalance($bankTransaction->applyTransaction($this));
            } catch (BankAccountException $e) {
                throw new BankAccountException($e->getMessage(), $e->getCode(), $e);
            }
        }
    }
    public function openAccount(): bool {
        if ($this->status == BackAccountInterface::STATUS_OPEN) {
            return true;
        }else {
            return false;
        }
    }
    public function reopenAccount(): void {
        if ($this->status == BackAccountInterface::STATUS_OPEN) {
            throw new BankAccountException('Cannot reopen an account that is already open.');
        }
    
        if ($this->status == BackAccountInterface::STATUS_CLOSED) {
            $this->status = BackAccountInterface::STATUS_OPEN;
            echo('<br> My account is now reopened.<br> ');
        }
    }
    
    public function closeAccount(): void {
        if ($this->status == BackAccountInterface::STATUS_CLOSED) {
            throw new BankAccountException('Cannot close an account that is already closed.');
        }
    
        $this->status = BackAccountInterface::STATUS_CLOSED;
        echo('<br> My account is now closed. <br> ');
    }
    
    public function getBalance(): float {
        return $this->balance;
    }
    
    public function getOverdraft(): OverdraftInterface {
        return $this->overdraft;
    }
    public function applyOverdraft(OverdraftInterface $overdraft): void {
        $this->overdraft = $overdraft;
    }
    public function setBalance($float): void {
        $this->balance = $float;
    }
}
