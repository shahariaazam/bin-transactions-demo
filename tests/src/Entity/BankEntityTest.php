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
 * BankEntity class
 *
 * @package  ShahariaAzam\BinList\Tests\Entity
 */


namespace ShahariaAzam\BinList\Tests\Entity;

use PHPUnit\Framework\TestCase;
use ShahariaAzam\BinList\Entity\BankEntity;

class BankEntityTest extends TestCase
{
    public function testEntity()
    {
        $bank = new \ShahariaAzam\BinList\Entity\BankEntity();
        $bank->setName('Demo Bank');
        $bank->setUrl('http://example.com');
        $bank->setPhone('+123456789');
        $bank->setCity('DEMO');

        $this->assertEquals('Demo Bank', $bank->getName());
        $this->assertEquals('http://example.com', $bank->getUrl());
        $this->assertEquals('+123456789', $bank->getPhone());
        $this->assertEquals('DEMO', $bank->getCity());
    }
}
