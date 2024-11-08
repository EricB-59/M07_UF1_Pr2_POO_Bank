<?php namespace ComBank\Bank;

use ComBank\OverdraftStrategy\NoOverdraft;
use ComBank\Bank\Contracts\BackAccountInterface;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Support\Traits\ApiTraits;

class InternationalBankAccount extends BankAccount
{
    public function getConvertedBalance(): float {
        echo "hola";
        return parent::convertBalance($this->balance);
    }
    public function getConvertedCurrency(): string {
        return "USD";
    }
}