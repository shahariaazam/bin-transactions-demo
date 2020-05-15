## Transaction Commission Calculate

![Code Checks](https://github.com/shahariaazam/bin-transactions-demo/workflows/Code-Checks/badge.svg)
![Build](https://scrutinizer-ci.com/g/shahariaazam/bin-transactions-demo/badges/build.png?b=master)
![Code Coverage](https://scrutinizer-ci.com/g/shahariaazam/bin-transactions-demo/badges/coverage.png?b=master)
![Code Rating](https://scrutinizer-ci.com/g/shahariaazam/bin-transactions-demo/badges/quality-score.png?b=master)
![Code Intellegence](https://scrutinizer-ci.com/g/shahariaazam/bin-transactions-demo/badges/code-intelligence.svg?b=master)

This is an utility library that will calculate commissions based on BIN data and transaction data.

**This is only for code demonstration. You shouldn't use it in production**

### Installation (Git Clone)
It's very simple. Just clone this repository and install all dependencies.

```
git clone https://github.com
```

After cloning, install all dependencies.

```
composer install
```

That's it.


### Usage

#### CLI
After installing, just run with the following command

```
php app.php transaction_file.txt
```

For demonstration, you can use the demo transaction files from `tests/input.txt`. So your command would be `php app.php tests/input.txt`

#### Within Application

```php
<?php

use ShahariaAzam\BinList\CommissionProcessor;
use ShahariaAzam\BinList\TransactionFileLoader;

require __DIR__ . DIRECTORY_SEPARATOR . "vendor/autoload.php";

$commissionProcessor = new CommissionProcessor();
$commissionProcessor->setTransactionStorage(new TransactionFileLoader($filePath));
$outputs = $commissionProcessor->process();     // Output will be array
```

### Extend it

#### Custom BIN API Client
You can create your own BIN Client to get BIN data from other sources. Just create new
provider by implementing `ShahariaAzam\BinList\BINClientInterface` and attach with `CommissionProcessor` by

```php
$processor = new \ShahariaAzam\BinList\CommissionProcessor();
$processor->setBINClient($CustomBINAPIClient);
```

#### Custom Exchange Rate API Client
Similiar to BIN Client, you can create your own Exchange Rate API client to get Exchange Rate data from other sources. Just create new
provider by implementing `ShahariaAzam\BinList\ExchangeRateClientInterface` and attach with `CommissionProcessor` by

```
$processor = new \ShahariaAzam\BinList\CommissionProcessor();
$processor->setExchangeRateClient($CustomExchangeRateClient);
```

#### Custom Transaction Storage
Currently this library supports loading transactions for calculation from file system. But you can create your own loader. Just create new
transaction storage provider by implementing `ShahariaAzam\BinList\TransactionStorageInterface` and attach with `CommissionProcessor` by

```
$processor = new \ShahariaAzam\BinList\CommissionProcessor();
$processor->setTransactionStorage($CustomTransactionStorage);
```

#### PSR-18 Compatible HTTP Client
You can attach any PSR-18 compatible HTTP Client attach with `CommissionProcessor` or `BINClient` or `ExchangeRateClient` by

```
$processor = new \ShahariaAzam\BinList\CommissionProcessor();
$processor->setHttpClient($PSR18HTTPClient);

$BINClient = new \ShahariaAzam\BinList\Api\BINClient();
$BINClient->setHttpClient($PSR18HTTPClient);

$exchangeRateClient = new \ShahariaAzam\BinList\Api\ExchangeRateClient();
$exchangeRateClient->setHttpClient($PSR18HTTPClient);
```

### Run Tests
```
composer run phpunit
```

**OR**

```
./vendor/bin/phpunit --configuration ./phpunit.xml --bootstrap ./tests/bootstrap.php
```

### LICENSE

Copyright (c) 2020 Shaharia Azam <mail@shaharia.com>

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.


### Disclaimer

This library is only for coding demonstration. You should not use it in any production application. This library may or may not 
been updated in future.

### Contribution
- [Shaharia Azam](https://github.com/shahariaazam)
  
Contact me at [mail@shaharia.com](mailto:mail@shaharia.com)
