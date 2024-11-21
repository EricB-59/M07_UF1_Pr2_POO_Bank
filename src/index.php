<?php

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:24 PM
 */

use ComBank\Bank\BankAccount;
use ComBank\Bank\InternationalBankAccount;
use ComBank\Bank\NationalBankAccount;
use ComBank\OverdraftStrategy\SilverOverdraft;
use ComBank\Transactions\DepositTransaction;
use ComBank\Transactions\WithdrawTransaction;
use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\Person\Person;

require_once 'bootstrap.php';


//---[Bank account 1]---/
// create a new account1 with balance 400
pl('--------- [Start testing bank account #1, No overdraft] --------');

try {
    $bankAccount1 = new BankAccount(400);

    // show balance account
    pl('My balance : ' . $bankAccount1->getBalance());
    
    // close account
    $bankAccount1->closeAccount();
    
    // reopen account
    $bankAccount1->reopenAccount();

    // deposit +150 
    pl('Doing transaction deposit (+150) with current balance ' . $bankAccount1->getBalance());
    $bankAccount1->transaction(bankTransaction: new DepositTransaction(150));
    pl('My new balance after deposit (+150) : ' . $bankAccount1->getBalance());

    // withdrawal -25
    pl('Doing transaction withdrawal (-25) with current balance ' . $bankAccount1->getBalance());
    $bankAccount1->transaction(bankTransaction: new WithdrawTransaction(25));
    pl('My new balance after withdrawal (-25) : ' . $bankAccount1->getBalance());

    // withdrawal -600
    pl('Doing transaction withdrawal (-600) with current balance ' . $bankAccount1->getBalance());
    $bankAccount1->transaction(bankTransaction: new WithdrawTransaction(600));
} catch (ZeroAmountException $e) {
    pl($e->getMessage());
} catch (BankAccountException $e) {
    pl($e->getMessage());
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My balance after failed last transaction : ' . $bankAccount1->getBalance());
try {
    $bankAccount1->closeAccount();
} catch (BankAccountException $e) {
    pl($e->getMessage());
}

//---[Bank account 2]---/
pl('--------- [Start testing bank account #2, Silver overdraft (100.0 funds)] --------');
try {
    $bankAccount2 = new BankAccount(200, new SilverOverdraft);
    // show balance account
    pl('My balance : ' . $bankAccount2->getBalance());

    // deposit +100
    pl('Doing transaction deposit (+100) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new DepositTransaction(100));
    pl('My new balance after deposit (+100) : ' . $bankAccount2->getBalance());

    // withdrawal -300
    pl('Doing transaction withdraw (-300) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(300));
    pl('My new balance after withdrawal (-300) : ' . $bankAccount2->getBalance());

    // withdrawal -50
    pl('Doing transaction withdrawal (-50) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(50));
    pl('My new balance after withdrawal (-50) with funds : ' . $bankAccount2->getBalance());

    // withdrawal -120
    pl('Doing transaction withdrawal (-120) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(120));

} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My balance after failed last transaction : ' . $bankAccount2->getBalance());

try {
    pl('Doing transaction withdrawal (-20) with current balance : ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(20));

} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My new balance after withdrawal (-20) with funds : ' . $bankAccount2->getBalance());

$bankAccount2->closeAccount();

try {
    $bankAccount2->closeAccount();
} catch (BankAccountException $e) {
    pl($e->getMessage());
}


//---[Bank account 3]---/
pl('--------- [Start testing national account (No conversion)] --------');
$bankAccount3 = new NationalBankAccount(500);
pl('My balance : ' . $bankAccount3->getBalance());

//---[Bank account 4]---/
// create a new account3 with balance 400 - currency EUR
pl('--------- [Start testing bank account #3, No overdraft] --------');
$bankAccount4 = new InternationalBankAccount(300, null);
pl('My balance : ' . $bankAccount4->getBalance() . ' € (' . $bankAccount4->getCurrency() . ')');
pl('Converted baance : ' . $bankAccount4->getConvertedBalance(). " (".$bankAccount4->getConvertedCurrency().")");

pl('--------- [Start testing national account] --------');
$person1 = new Person("Eric", "23","john.doe@example.com");

pl('--------- [Start testing international account] --------');
$person2 = new Person("Eric", "23","john.doe@invalid-email");

pl('--------- [Start testing deposit block] --------');
$bankAccount5 = new BankAccount(600);

pl('Doing transaction deposit (+100) with current balance ' . $bankAccount5->getBalance());
$bankAccount5->transaction(bankTransaction: new DepositTransaction(100));
pl('My new balance after deposit (+100) : ' . $bankAccount5->getBalance());
try {
    pl('Doing transaction deposit (+20001) with current balance ' . $bankAccount5->getBalance());
    $bankAccount5->transaction(bankTransaction: new DepositTransaction(20001));
    pl('My new balance after deposit (+20001) : ' . $bankAccount5->getBalance());
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}

pl('--------- [Start testing withdraw block] --------');
$bankAccount6 = new BankAccount(5000);

pl('Doing transaction withdraw (-100) with current balance ' . $bankAccount6->getBalance());
$bankAccount6->transaction(bankTransaction: new WithdrawTransaction(100));
pl('My new balance after withdraw (-100) : ' . $bankAccount6->getBalance());
try {
    pl('Doing transaction withdraw (+5001) with current balance ' . $bankAccount6->getBalance());
    $bankAccount6->transaction(bankTransaction: new WithdrawTransaction(5001));
    pl('My new balance after withdraw (+5001) : ' . $bankAccount6->getBalance());
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}


pl('--------- [Start testing send email] --------');
$person4 = new Person('Eric', '3','ebaenac59@gmail.com');
$person4->sendEmailToPerson();
pl('hola');