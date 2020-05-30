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
 * CommissionProcessorTest class
 *
 * @package  src
 */


namespace ShahariaAzam\BinList\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use ShahariaAzam\BinList\BINClientInterface;
use ShahariaAzam\BinList\CommissionProcessor;
use ShahariaAzam\BinList\CommissionRules;
use ShahariaAzam\BinList\Entity\BINEntity;
use ShahariaAzam\BinList\Entity\CountryEntity;
use ShahariaAzam\BinList\Entity\TransactionEntity;
use ShahariaAzam\BinList\Exception\UtilityException;
use ShahariaAzam\BinList\ExchangeRateClientInterface;
use ShahariaAzam\BinList\TransactionStorageInterface;

class CommissionProcessorTest extends TestCase
{
    public function testCommissions()
    {
        // Within EU
        $result = $this->calculateCommission('DK', 7.4573, 100, 'EUR');
        $this->assertEquals(1, $result);

        // Outside EU
        $result = $this->calculateCommission('LT', 1.0792, 50.00, 'USD');
        $this->assertEquals(0.46, $result);

        $result = $this->calculateCommission('JP', 115.48, 10000.00, 'JPY');
        $this->assertEquals(1.73, $result);

        $result = $this->calculateCommission('US', 1.0792, 130.00, 'USD');
        $this->assertEquals(2.41, $result);

        $result = $this->calculateCommission('GB', 0.88495, 2000.00, 'GBP');
        $this->assertEquals(45.20, $result);
    }

    /**
     * Calculate commission
     *
     * @param $binCountryAlpha2
     * @param $currencyExchangeRate
     * @param $transactionAmount
     * @param $transactionCurrency
     * @return string
     * @throws UtilityException
     */
    private function calculateCommission(
        $binCountryAlpha2,
        $currencyExchangeRate,
        $transactionAmount,
        $transactionCurrency
    ) {
        // Mock BIN Entity
        $binEntity = new BINEntity();
        $binEntity->setCountry((new CountryEntity())->setAlpha2($binCountryAlpha2));

        // Set transaction details
        $transaction = new TransactionEntity();
        $transaction->setCurrency($transactionCurrency);
        $transaction->setAmount($transactionAmount);
        $transaction->setBin(123456789);

        /**
         * @var $binClient BINClientInterface
         */
        $binClient = $this->getMockBuilder(BINClientInterface::class)->getMock();
        $binClient->method('get')->willReturn($binEntity);

        /**
         * @var $transactionStorage TransactionStorageInterface
         */
        $transactionStorage = $this->getMockBuilder(TransactionStorageInterface::class)->getMock();
        $transactionStorage->method('get')->willReturn([$transaction]);

        /**
         * @var $exchangeRatesClient ExchangeRateClientInterface
         */
        $exchangeRatesClient = $this->getMockBuilder(ExchangeRateClientInterface::class)->getMock();
        $exchangeRatesClient->method('get')->willReturn(MockUtility::getMockCurrencyRates(
            'EUR',
            [$transactionCurrency => $currencyExchangeRate]
        ));

        /**
         * @var ClientInterface $httpClientMock
         */
        $httpClientMock = $this->getMockBuilder(ClientInterface::class)->getMock();

        $commissionDefaultRules = new CommissionRules(0.01, 0.02);

        $processor = new CommissionProcessor($httpClientMock, $exchangeRatesClient, $binClient, $transactionStorage, $commissionDefaultRules);
        $results = $processor->process();
        return $results[0];
    }
}
