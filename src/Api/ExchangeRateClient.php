<?php

namespace ShahariaAzam\BinList\Api;

use DateTime;
use ShahariaAzam\BinList\Entity\ExchangeRateEntity;
use ShahariaAzam\BinList\Exception\UtilityException;
use ShahariaAzam\BinList\ExchangeRateClientInterface;

class ExchangeRateClient extends CommonAPIClient implements ExchangeRateClientInterface
{
    /**
     * @return ExchangeRateEntity
     * @throws UtilityException
     */
    public function get()
    {
        $data = parent::sendRequest('https://api.exchangeratesapi.io/latest');

        $exchangeRate = new ExchangeRateEntity();
        isset($data['rates']) ? $exchangeRate->setRates($data['rates']) : null;
        isset($data['base']) ? $exchangeRate->setBaseCurrency($data['base']) : null;
        isset($data['date']) ? $exchangeRate->setDate(DateTime::createFromFormat('Y-m-d', $data['date'])) : null;
        return $exchangeRate;
    }
}
