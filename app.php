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

use ShahariaAzam\BinList\Api\BINClient;
use ShahariaAzam\BinList\Api\ExchangeRateClient;
use ShahariaAzam\BinList\CommissionProcessor;
use ShahariaAzam\BinList\CommissionRules;
use ShahariaAzam\BinList\TransactionFileLoader;
use Symfony\Component\HttpClient\Psr18Client;

// Include dependencies
require __DIR__ . DIRECTORY_SEPARATOR . "vendor/autoload.php";

// If not enough argument passed, no need to proceed
if (count($argv) < 2) {
    exit("[ERROR] Invalid command" . PHP_EOL);
}

$httpClient = new Psr18Client();
$exchangeRateClient = new ExchangeRateClient($httpClient);
$BINClient = new BINClient($httpClient);
$transactionStorage = new TransactionFileLoader($argv[1]);
$commissionRules = new CommissionRules(0.01, 0.02);

// Initialize commission processor
$commissionProcessor = new CommissionProcessor($exchangeRateClient, $BINClient, $transactionStorage, $commissionRules);
$outputs = $commissionProcessor->process();
foreach ($outputs as $output) {
    echo $output . PHP_EOL;
}