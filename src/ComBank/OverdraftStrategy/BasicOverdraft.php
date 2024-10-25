<?php namespace ComBank\OverdraftStrategy;
      use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 12:27 PM
 */

/**
 * @description: Grant 50.00 overdraft funds.
 * */
class BasicOverdraft implements OverdraftInterface
{
    public function isGrantOverdraftFunds(float $newAmount): bool{
        return $newAmount + $this->getOverdraftFundsAmount() >= 0;
    }
    public function getOverdraftFundsAmount(): float{
        return 50.0;
    }
}