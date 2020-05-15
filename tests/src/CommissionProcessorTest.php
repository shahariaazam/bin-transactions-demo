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

use Nyholm\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use ShahariaAzam\BinList\BINClientInterface;
use ShahariaAzam\BinList\CommissionProcessor;
use ShahariaAzam\BinList\Entity\BankEntity;
use ShahariaAzam\BinList\Entity\BINEntity;
use ShahariaAzam\BinList\Entity\CountryEntity;
use ShahariaAzam\BinList\Entity\ExchangeRateEntity;
use ShahariaAzam\BinList\Entity\NumberEntity;
use ShahariaAzam\BinList\Entity\TransactionEntity;
use ShahariaAzam\BinList\Exception\UtilityException;
use ShahariaAzam\BinList\ExchangeRateClientInterface;
use ShahariaAzam\BinList\TransactionStorageInterface;
use Symfony\Component\Config\Definition\Processor;

class CommissionProcessorTest extends TestCase
{
    public function testConstructor()
    {
        $processor = new CommissionProcessor();
        $this->assertEquals(0.01, $processor->getCommissionInEU());
        $this->assertEquals(0.02, $processor->getCommissionOutsideEU());
        $this->assertTrue($processor->getHttpClient() instanceof ClientInterface);
    }

    public function testSetHttpClient()
    {
        $mockClient = $this->getMockBuilder(ClientInterface::class)->getMock();

        $procesor = new CommissionProcessor();
        $procesor->setHttpClient($mockClient);
        $this->assertTrue($procesor->getHttpClient() instanceof ClientInterface);
    }

    /**
     * Exchange Rate client shouldn't be available if we don't attach it.
     */
    public function testGetExchangeRateClientDefaultClientAlwaysMustBeAvailable()
    {
        $processor = new CommissionProcessor();
        $this->assertTrue($processor->getExchangeRateClient() instanceof ExchangeRateClientInterface);
    }

    public function testSetExchangeRateClient()
    {
        /**
         * @var $mockExchangeClient ExchangeRateClientInterface
         */
        $mockExchangeClient = $this->getMockBuilder(ExchangeRateClientInterface::class)->getMock();

        $processor = new CommissionProcessor();
        $processor->setExchangeRateClient($mockExchangeClient);
        $this->assertTrue($processor->getExchangeRateClient() instanceof ExchangeRateClientInterface);
    }

    public function testSetTransactionStorage()
    {
        /**
         * @var $mockStorage TransactionStorageInterface
         */
        $mockStorage = $this->getMockBuilder(TransactionStorageInterface::class)->getMock();

        $mockStorage->method('get')->willReturn([
            (new TransactionEntity())
                ->setCurrency('EUR')->setAmount(100)->setBin(123456)
        ]);

        $processor = new CommissionProcessor();
        $processor->setTransactionStorage($mockStorage);
        $processor->loadTransactions();

        $this->assertCount(1, $processor->getTransactions());
        $this->assertEquals(123456, $processor->getTransactions()[0]->getBin());
    }

    public function testGetExchangeRates()
    {
        /**
         * @var $mockExchangeClient ExchangeRateClientInterface
         */
        $mockExchangeClient = $this->getMockBuilder(ExchangeRateClientInterface::class)->getMock();
        $mockExchangeClient->method('get')->willReturn(MockUtility::getMockCurrencyRates('BDT'));

        $processor = new CommissionProcessor();
        $processor->setExchangeRateClient($mockExchangeClient);
        $processor->checkExchangeRates();
        $this->assertEquals('BDT', $processor->getExchangeRates()->getBaseCurrency());
    }

    public function testSetTransactions()
    {
        $transactions = [
            (new TransactionEntity())->setBin(123456789)->setAmount(100)->setCurrency('EUR')
        ];

        $processor = new CommissionProcessor();
        $processor->setTransactions($transactions);
        $this->assertEquals('EUR', $processor->getTransactions()[0]->getCurrency());
        $this->assertEquals(100, $processor->getTransactions()[0]->getAmount());
        $this->assertEquals(123456789, $processor->getTransactions()[0]->getBin());
    }

    public function testSetCommissionInAndOutEU()
    {
        $processor = new CommissionProcessor();
        $processor->setCommissionInEU(0.011);
        $processor->setCommissionOutsideEU(0.012);
        $this->assertEquals(0.011, $processor->getCommissionInEU());
        $this->assertEquals(0.012, $processor->getCommissionOutsideEU());
    }

    public function testLoadTransactionWithoutStorageShouldThrowException()
    {
        $this->expectException(UtilityException::class);
        $processor = new CommissionProcessor();
        $processor->loadTransactions();
    }

    public function testGetBINClient()
    {
        $processor = new CommissionProcessor();
        $this->assertTrue($processor->getBINClient() instanceof BINClientInterface);
    }

    public function testSetBINClient()
    {
        $processor = new CommissionProcessor();
        $processor->setBINClient($this->getMockBuilder(BINClientInterface::class)->getMock());
        $this->assertTrue($processor->getBINClient() instanceof BINClientInterface);
    }

    public function testSetExchangeRates()
    {
        $exchangeRateUpdateDate = new \DateTime();

        $exchangeRate = new ExchangeRateEntity();
        $exchangeRate->setRates(['BDT' => 1.2]);
        $exchangeRate->setBaseCurrency('EUR');
        $exchangeRate->setDate($exchangeRateUpdateDate);

        $processor = new CommissionProcessor();
        $processor->setExchangeRates($exchangeRate);
        $this->assertEquals('EUR', $processor->getExchangeRates()->getBaseCurrency());
        $this->assertEquals($exchangeRateUpdateDate, $processor->getExchangeRates()->getDate());
        $this->assertEquals(1.2, $processor->getExchangeRates()->getRates()['BDT']);
    }

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
     * @param array $bins
     * @return MockObject|BINClientInterface
     */
    private function getBINMockClient(array $bins = [])
    {
        $binClient = $this->getMockBuilder(BINClientInterface::class)->getMock();

        foreach ($bins as $bin => $countryCode) {
            $binClient->method('get')->withAnyParameters()->willReturnCallback(function ($arg) use ($bins) {
                return $bins[$arg];
            });
        }

        return $binClient;
    }

    /**
     * @param array $overrideRates
     * @return MockObject|ExchangeRateClientInterface
     */
    private function getExchangeRateMockClient($overrideRates = [])
    {
        $exchangeRateClient = $this->getMockBuilder(ExchangeRateClientInterface::class)->getMock();
        $rates = MockUtility::getMockCurrencyRates('EUR', $overrideRates);
        $exchangeRateClient->method('get')->withAnyParameters()->willReturn($rates);

        return $exchangeRateClient;
    }

    /**
     * @param array $transactions
     * @return MockObject|TransactionStorageInterface
     */
    private function getTransactionStorageMock($transactions = [])
    {
        $storage = $this->getMockBuilder(TransactionStorageInterface::class)->getMock();
        $storage->method('get')->withAnyParameters()->willReturn($transactions);
        return $storage;
    }

    /**
     * @param Response $response
     * @return MockObject|ClientInterface
     */
    private function getMockHttpClient(Response $response)
    {
        $httpClient = $this->getMockBuilder(ClientInterface::class)->getMock();
        $httpClient->method('sendRequest')->willReturn($response);
        return $httpClient;
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

        $processor = new CommissionProcessor();
        $processor->setBINClient($binClient);
        $processor->setTransactionStorage($transactionStorage);
        $processor->setExchangeRateClient($exchangeRatesClient);
        $results = $processor->process();
        return $results[0];
    }
}
