<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2020 Shaharia Azam <mail@shaharia.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * MockHttpClient class
 *
 * @package  ShahariaAzam\BinList\Tests
 */


namespace ShahariaAzam\BinList\Tests;

use Http\Mock\Client;
use Nyholm\Psr7\Factory\HttplugFactory;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Psr\Http\Client\ClientInterface;
use ShahariaAzam\BinList\Entity\BINEntity;
use ShahariaAzam\BinList\Entity\CountryEntity;
use ShahariaAzam\BinList\Entity\ExchangeRateEntity;
use ShahariaAzam\BinList\Entity\TransactionEntity;

class MockUtility
{
    public static function getMockCurrencyRates($baseCurrency = 'EUR', array $overrideValues = [])
    {
        $rates = [
            "CAD" => 1.5224,
            "HKD" => 8.3647,
            "ISK" => 158.3,
            "PHP" => 54.591,
            "DKK" => 7.4573,
            "HUF" => 354.66,
            "CZK" => 27.571,
            "AUD" => 1.6805,
            "RON" => 4.8375,
            "SEK" => 10.6418,
            "IDR" => 16134.8,
            "INR" => 81.592,
            "BRL" => 6.3925,
            "RUB" => 80.0145,
            "HRK" => 7.573,
            "JPY" => 115.48,
            "THB" => 34.675,
            "CHF" => 1.0512,
            "SGD" => 1.5374,
            "PLN" => 4.5666,
            "BGN" => 1.9558,
            "TRY" => 7.5159,
            "CNY" => 7.6655,
            "NOK" => 11.0598,
            "NZD" => 1.8068,
            "ZAR" => 20.1637,
            "USD" => 1.0792,
            "MXN" => 26.247,
            "ILS" => 3.8209,
            "GBP" => 0.88495,
            "KRW" => 1329.17,
            "MYR" => 4.6843,
        ];

        $finalRates = array_merge($rates, $overrideValues);

        $currency = new ExchangeRateEntity();
        $currency->setDate((new \DateTime('now')));
        $currency->setBaseCurrency($baseCurrency);
        $currency->setRates($finalRates);
        return $currency;
    }

    public static function BINCountryMapping(array $data)
    {
        $bins = [];

        foreach ($data as $bin => $country){
            $bins[$bin] = (new BINEntity())->setCountry((new CountryEntity())->setAlpha2($country));
        }
        return $bins;
    }

    public static function mockDatasets(array $bins = [])
    {
        $d = [];
        $d['1'] = [
            'bin' => 45717360,
            'binCountry' => 'DK',
            'transaction'
        ];
    }

    /**
     * @param array $data
     * @return TransactionEntity[]
     */
    public static function transactionMap(array $data)
    {
        $transactions = [];
        foreach ($data as $txn){
            $transactions[] = (new TransactionEntity())->setBin($txn[0])->setAmount($txn[1])->setCurrency($txn[2]);
        }
        return $transactions;
    }
}
