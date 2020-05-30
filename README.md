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
git clone https://github.com/shahariaazam/bin-transactions-demo.git
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

**Output for `php app.php tests/input.txt`**
```
1
0.46
1.73
2.41
45.08
```

#### Within Application

```php
<?php

use ShahariaAzam\BinList\Api\BINClient;
use ShahariaAzam\BinList\Api\ExchangeRateClient;
use ShahariaAzam\BinList\CommissionProcessor;
use ShahariaAzam\BinList\CommissionRules;
use ShahariaAzam\BinList\TransactionFileLoader;
use Symfony\Component\HttpClient\Psr18Client;

require __DIR__ . DIRECTORY_SEPARATOR . "vendor/autoload.php";

$httpClient = new Psr18Client();
$exchangeRateClient = new ExchangeRateClient($httpClient);
$BINClient = new BINClient($httpClient);
$transactionStorage = new TransactionFileLoader(__DIR__ . "/tests/input.txt");
$commissionRules = new CommissionRules(0.01, 0.02);

// Initialize commission processor
$commissionProcessor = new CommissionProcessor($exchangeRateClient, $BINClient, $transactionStorage, $commissionRules);
$outputs = $commissionProcessor->process();
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
