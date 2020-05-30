<?php

namespace ShahariaAzam\BinList;

use Psr\Http\Client\ClientInterface;
use ShahariaAzam\BinList\Api\BINClient;
use ShahariaAzam\BinList\Api\ExchangeRateClient;
use ShahariaAzam\BinList\Entity\ExchangeRateEntity;
use ShahariaAzam\BinList\Entity\TransactionEntity;
use ShahariaAzam\BinList\Exception\UtilityException;
use Symfony\Component\HttpClient\Psr18Client;

/**
 * Main class for this Utility operation
 */
class CommissionProcessor   //phpcs:ignore  Paysera/psr_1_file_side_effects
{
    /**
     * We can create BIN provider by implementing this interface
     * Default provider: ShahariaAzam\BinList\Api\BINClient
     *
     * @var BINClientInterface
     */
    private $BINClient;

    /**
     * We can create Exchange Rate provider by implementing this interface
     * Default provider: ShahariaAzam\BinList\Api\ExchangeRateClient
     *
     * @var ExchangeRateClientInterface
     */
    private $exchangeRateClient;

    /**
     * A PSR-18 compatible HTTP client.
     * Default client: Symfony\Component\HttpClient\Psr18Client
     *
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * We can build and attach more custom transaction storage.
     * Default storage provider: ShahariaAzam\BinList\TransactionFileLoader
     *
     * @var TransactionStorageInterface
     */
    private $transactionStorage;

    /**
     * @var TransactionEntity[]
     */
    private $transactions;

    /**
     * To perform the operation for every transaction, we can hold the exchange rates data.
     * It will reduce HTTP calls
     *
     * @var ExchangeRateEntity
     */
    private $exchangeRates;

    /**
     * Commission inside EU
     * @var float
     */
    private $commissionInEU;

    /**
     * Commission outside EU
     * @var float
     */
    private $commissionOutsideEU;

    /**
     * App Construction with default HTTP Client, BIN Client and ExchangeRate Client.
     * @param ClientInterface $httpClient
     * @param ExchangeRateClientInterface $exchangeRateClient
     * @param BINClientInterface $BINClient
     * @param TransactionStorageInterface $transactionStorage
     */
    public function __construct(
        ClientInterface $httpClient,
        ExchangeRateClientInterface $exchangeRateClient,
        BINClientInterface $BINClient,
        TransactionStorageInterface $transactionStorage,
        CommissionRules $rules
    ) {
        $this->httpClient = $httpClient;
        $this->BINClient = $BINClient;
        $this->exchangeRateClient = $exchangeRateClient;
        $this->transactionStorage = $transactionStorage;

        $this->commissionInEU = $rules->getInsideEU();
        $this->commissionOutsideEU = $rules->getOutsideEU();
    }

    /**
     * Finally process all transactions
     *
     * @throws UtilityException
     * @return string[]
     */
    public function process()
    {
        $this->loadTransactions();
        $this->checkExchangeRates();

        $output = [];
        foreach ($this->transactions as $transaction) {
            try {
                $output[] = $this->processSingleTransaction($transaction);
            } catch (UtilityException $utilityException) {
                // Log errors
            }
        }

        return $output;
    }

    /**
     * @return CommissionProcessor
     */
    public function checkExchangeRates(): CommissionProcessor
    {
        $this->exchangeRates = $this->exchangeRateClient->get();
        return $this;
    }

    /**
     * Load all the transactions from transaction storage
     *
     * @return CommissionProcessor
     * @throws UtilityException
     */
    public function loadTransactions(): CommissionProcessor
    {
        if (!isset($this->transactionStorage)) {
            throw new UtilityException(
                "No transaction storage found that implements 
                ShahariaAzam\BinList\Interfaces\TransactionStorageInterface interface"
            );
        }

        $this->transactions = $this->transactionStorage->get();
        return $this;
    }

    /**
     * Process single transaction
     *
     * @param TransactionEntity $transaction
     * @throws UtilityException
     * @return float
     */
    public function processSingleTransaction(TransactionEntity $transaction)
    {
        // Fetch the BIN details
        $binDetails = $this->BINClient->get($transaction->getBin());

        // Check whether BIN country is in EU or outside of EU
        $locatedInEU = self::isLocatedInEU($binDetails->getCountry()->getAlpha2());

        $amount = null;

        /**
         * "EUR" is the base currency.
         * For rest of the currency, calculate with exchange rate
         */
        if ($transaction->getCurrency() === 'EUR') {
            $amount = $transaction->getAmount();
        } else {
            $eRate = $this->exchangeRates->getRates()[$transaction->getCurrency()];
            $amount = $transaction->getAmount() / $eRate;
        }

        // Inside/Outside EU commission
        $finalAmount = $amount * ($locatedInEU ? $this->commissionInEU : $this->commissionOutsideEU);
        return round($finalAmount, 2, PHP_ROUND_HALF_UP);
    }

    /**
     * @param string $country
     * @return bool
     */
    private function isLocatedInEU($country)
    {
        $euCountries = [
            'AT',
            'BE',
            'BG',
            'CY',
            'CZ',
            'DE',
            'DK',
            'EE',
            'ES',
            'FI',
            'FR',
            'GR',
            'HR',
            'HU',
            'IE',
            'IT',
            'LT',
            'LU',
            'LV',
            'MT',
            'NL',
            'PO',
            'PT',
            'RO',
            'SE',
            'SI',
            'SK',
        ];
        return in_array(strtoupper($country), $euCountries, true);
    }
}
