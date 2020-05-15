<?php

namespace ShahariaAzam\BinList\Entity;

use DateTime;

/**
 * Class ExchangeRateEntity
 */
class ExchangeRateEntity
{
    /**
     * Rates array. i.e: [ 'EUR' => 1, 'JPY' => 2.2 ]
     * @var array
     */
    private $rates;

    /**
     * Base currency
     *
     * @var string
     */
    private $baseCurrency;

    /**
     * Exchange rate last updated time
     *
     * @var DateTime
     */
    private $date;

    /**
     * @return array
     */
    public function getRates(): array
    {
        return $this->rates;
    }

    /**
     * @param array $rates
     * @return ExchangeRateEntity
     */
    public function setRates(array $rates): ExchangeRateEntity
    {
        $this->rates = $rates;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseCurrency(): string
    {
        return $this->baseCurrency;
    }

    /**
     * @param string $baseCurrency
     * @return ExchangeRateEntity
     */
    public function setBaseCurrency(string $baseCurrency): ExchangeRateEntity
    {
        $this->baseCurrency = $baseCurrency;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return ExchangeRateEntity
     */
    public function setDate(DateTime $date): ExchangeRateEntity
    {
        $this->date = $date;
        return $this;
    }
}
