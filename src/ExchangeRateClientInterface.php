<?php

namespace ShahariaAzam\BinList; // TODO: "Interfaces" - PhpBasic convention 2.7.1: We use singular for namespaces

use Psr\Http\Client\ClientInterface;
use ShahariaAzam\BinList\Entity\ExchangeRateEntity;

interface ExchangeRateClientInterface
{
    /**
     * @param null $path
     * @return ExchangeRateEntity
     */
    public function get();
}
