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
 * BINEntityTest class
 *
 * @package  ShahariaAzam\BinList\Tests\Entity
 */


namespace ShahariaAzam\BinList\Tests\Entity;


use PHPUnit\Framework\TestCase;
use ShahariaAzam\BinList\Entity\BankEntity;
use ShahariaAzam\BinList\Entity\BINEntity;
use ShahariaAzam\BinList\Entity\CountryEntity;
use ShahariaAzam\BinList\Entity\NumberEntity;

class BINEntityTest extends TestCase
{
    public function testBINEntity()
    {
        $bin = new BINEntity();

        $country = new CountryEntity();
        $bank = new BankEntity();
        $number = new NumberEntity();

        $bin->setBank($bank);
        $bin->setNumber($number);
        $bin->setCountry($country);
        $bin->setScheme('DEMO');
        $bin->setPrepaid(true);
        $bin->setType('DEMO');
        $bin->setBrand('DEMO');

        $this->assertTrue($bin->isPrepaid());
        $this->assertEquals('DEMO', $bin->getType());
        $this->assertEquals('DEMO', $bin->getBrand());
        $this->assertEquals('DEMO', $bin->getScheme());

        $this->assertTrue($bin->getNumber() instanceof NumberEntity);
        $this->assertTrue($bin->getCountry() instanceof CountryEntity);
        $this->assertTrue($bin->getBank() instanceof BankEntity);
    }

    public function testBuildFromArray()
    {
        $dataString = json_decode('{"number":{"length":16,"luhn":true},"scheme":"visa","type":"debit","brand":"Traditional","prepaid":null,"country":{"numeric":"826","alpha2":"GB","name":"United Kingdom of Great Britain and Northern Ireland","emoji":"🇬🇧","currency":"GBP","latitude":54,"longitude":-2},"bank":{}}', true);
        $bin = new BINEntity();
        $bin->build($dataString);

        $this->assertFalse($bin->isPrepaid());
        $this->assertEquals('debit', $bin->getType());
        $this->assertEquals('Traditional', $bin->getBrand());
        $this->assertEquals('visa', $bin->getScheme());

        $this->assertTrue($bin->getNumber() instanceof NumberEntity);
        $this->assertTrue($bin->getCountry() instanceof CountryEntity);
        $this->assertTrue($bin->getBank() instanceof BankEntity);
    }
}