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
     */
    public function __construct()
    {
        $this->httpClient = new Psr18Client();

        $this->commissionInEU = 0.01;
        $this->commissionOutsideEU = 0.02;
    }

    /**
     * Add any PSR-18 compatible HTTP Client
     *
     * @param ClientInterface $httpClient
     * @return CommissionProcessor
     */
    public function setHttpClient(ClientInterface $httpClient): CommissionProcessor
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    /**
     * @return ClientInterface
     */
    public function getHttpClient(): ClientInterface
    {
        return $this->httpClient;
    }

    /**
     * Get exchange rate client provider
     *
     * @return ExchangeRateClientInterface
     */
    public function getExchangeRateClient(): ExchangeRateClientInterface
    {
        if (empty($this->exchangeRateClient)) {
            $this->buildExchangeRateClient();
        }

        return $this->exchangeRateClient;
    }

    /**
     * Attach exchange rate client provider
     *
     * @param ExchangeRateClientInterface $exchangeRateClient
     * @return CommissionProcessor
     */
    public function setExchangeRateClient(ExchangeRateClientInterface $exchangeRateClient): CommissionProcessor
    {
        $this->exchangeRateClient = $exchangeRateClient;

        return $this;
    }

    /**
     * Setup Transaction Storage
     *
     * @param TransactionStorageInterface $transactionStorage
     * @return CommissionProcessor
     */
    public function setTransactionStorage(TransactionStorageInterface $transactionStorage): CommissionProcessor
    {
        $this->transactionStorage = $transactionStorage;
        return $this;
    }

    /**
     * @return ExchangeRateEntity
     */
    public function getExchangeRates(): ExchangeRateEntity
    {
        return $this->exchangeRates;
    }

    /**
     * @param ExchangeRateEntity $exchangeRates
     * @return CommissionProcessor
     */
    public function setExchangeRates(ExchangeRateEntity $exchangeRates): CommissionProcessor
    {
        $this->exchangeRates = $exchangeRates;
        return $this;
    }

    /**
     * @return TransactionEntity[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * @param TransactionEntity[] $transactions
     * @return CommissionProcessor
     */
    public function setTransactions(array $transactions): CommissionProcessor
    {
        $this->transactions = $transactions;
        return $this;
    }

    /**
     * @return float
     */
    public function getCommissionInEU(): float
    {
        return $this->commissionInEU;
    }

    /**
     * @param float $commissionInEU
     * @return CommissionProcessor
     */
    public function setCommissionInEU(float $commissionInEU): CommissionProcessor
    {
        $this->commissionInEU = $commissionInEU;
        return $this;
    }

    /**
     * @return float
     */
    public function getCommissionOutsideEU(): float
    {
        return $this->commissionOutsideEU;
    }

    /**
     * @param float $commissionOutsideEU
     * @return CommissionProcessor
     */
    public function setCommissionOutsideEU(float $commissionOutsideEU): CommissionProcessor
    {
        $this->commissionOutsideEU = $commissionOutsideEU;
        return $this;
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
        $this->exchangeRates = $this->getExchangeRateClient()->get();
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
        $binDetails = $this->getBINClient()->get($transaction->getBin());

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
     * @return BINClientInterface
     */
    public function getBINClient(): BINClientInterface
    {
        if (empty($this->BINClient)) {
            $this->buildBINClient();
        }

        return $this->BINClient;
    }

    /**
     * @param BINClientInterface $BINClient
     * @return CommissionProcessor
     */
    public function setBINClient(BINClientInterface $BINClient): CommissionProcessor
    {
        $this->BINClient = $BINClient;
        return $this;
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

    /**
     * @return ExchangeRateClientInterface
     */
    protected function buildExchangeRateClient(): ExchangeRateClientInterface
    {
        if (!isset($this->exchangeRateClient)) {
            $exchangeRateClient = new ExchangeRateClient();
            $exchangeRateClient->setHttpClient($this->httpClient);
            $this->exchangeRateClient = $exchangeRateClient;
        }

        return $this->exchangeRateClient;
    }

    /**
     * @return BINClientInterface
     */
    private function buildBINClient(): BINClientInterface
    {
        if (!isset($this->BINClient)) {
            $binClient = new BINClient();
            $binClient->setHttpClient($this->httpClient);
            $this->BINClient = $binClient;
        }

        return $this->BINClient;
    }
}
