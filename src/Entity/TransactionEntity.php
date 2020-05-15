<?php

namespace ShahariaAzam\BinList\Entity;

/**
 * Class TransactionEntity
 */
class TransactionEntity
{
    /**
     * @var string
     */
    private $bin;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    /**
     * @param array $transaction
     * @return TransactionEntity
     */
    public function build(array $transaction): TransactionEntity
    {
        $trn = new self();
        isset($transaction['bin']) ? $trn->setBin($transaction['bin']) : null;
        isset($transaction['amount']) ? $trn->setAmount((float)$transaction['amount']) : null;
        isset($transaction['currency']) ? $trn->setCurrency($transaction['currency']) : null;
        return $trn;
    }

    /**
     * @return string
     */
    public function getBin(): string
    {
        return $this->bin;
    }

    /**
     * @param string $bin
     * @return TransactionEntity
     */
    public function setBin(string $bin): TransactionEntity
    {
        $this->bin = $bin;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return TransactionEntity
     */
    public function setAmount(float $amount): TransactionEntity
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return TransactionEntity
     */
    public function setCurrency(string $currency): TransactionEntity
    {
        $this->currency = $currency;
        return $this;
    }
}
