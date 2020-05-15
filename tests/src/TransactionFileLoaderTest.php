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
 * TransactionFileLoader class
 *
 * @package  ShahariaAzam\BinList\Tests
 */


namespace ShahariaAzam\BinList\Tests;

use PHPUnit\Framework\TestCase;
use ShahariaAzam\BinList\Exception\UtilityException;
use ShahariaAzam\BinList\TransactionFileLoader;

class TransactionFileLoaderTest extends TestCase
{
    /**
     * @var string
     */
    private $filePath;

    public function setUp(): void
    {
        $this->filePath = sys_get_temp_dir() . '/test_' . rand(0, 9) . '.txt';
        $this->insertDummyTransactions();

        parent::setUp();
    }

    public function testGet()
    {
        $txnFileLoader = new TransactionFileLoader($this->filePath);
        $transactions = $txnFileLoader->get();
        $this->assertIsIterable($transactions);
        $this->assertTrue(5 === iterator_count($transactions));
        $this->assertEquals("45717360", $transactions[0]->getBin());
        $this->assertEquals(100, $transactions[0]->getAmount());
        $this->assertEquals('EUR', $transactions[0]->getCurrency());
    }

    public function testGetShouldThrowExceptionIfFileNotExists()
    {
        $this->expectException(UtilityException::class);
        $loader = new TransactionFileLoader('DUMMY_FILE_PATH');
        $loader->get();
    }

    private function insertDummyTransactions()
    {
        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }

        $file = fopen($this->filePath, 'w');

        $jsonString = '{"bin":"45717360","amount":"100.00","currency":"EUR"}
{"bin":"516793","amount":"50.00","currency":"USD"}
{"bin":"45417360","amount":"10000.00","currency":"JPY"}
{"bin":"41417360","amount":"130.00","currency":"USD"}
{"bin":"4745030","amount":"2000.00","currency":"GBP"}';

        fwrite($file, $jsonString);
        fclose($file);
    }

    public function tearDown(): void
    {
        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }

        parent::tearDown();
    }
}
