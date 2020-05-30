<?php

namespace ShahariaAzam\BinList;

use Psr\Http\Client\ClientInterface;
use ShahariaAzam\BinList\Entity\BINEntity;

/**
 * Interface BINClientInterface
 *
 * Build and configure any kinds of BIN Client provider by implementing this interface
 */
interface BINClientInterface
{
    /**
     * @param string $bin
     * @return BINEntity
     */
    public function get($bin);
}
