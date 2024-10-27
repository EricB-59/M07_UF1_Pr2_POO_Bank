<?php namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 11:30 AM
 */

use ComBank\Bank\Contracts\BackAccountInterface;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class HistoryTransaction
{
    private BankTransactionInterface $transactionType;
    private float $amount;
    private string $date;
    public array $historyTransaction = [];

    public function __construct(BankTransactionInterface $transactionType, float $amount, array $historyTransaction)
    {
        $this->transactionType = $transactionType;
        $this->amount = $amount;
        $this->date = date("d/m/Y H:i");
        $this->historyTransaction = $historyTransaction;
        $this->addTransactionToHistory();
    }

    private function addTransactionToHistory(): void
    {
        $this->historyTransaction[] = [
            'type' => $this->transactionType,
            'amount' => $this->amount,
            'date' => $this->date,
        ];
    }
}

