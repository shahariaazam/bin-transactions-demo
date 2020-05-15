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
     * Attach HTTP Client if needed
     *
     * @param ClientInterface $client // PSR-18 compatible HTTP Client
     * @return BINClientInterface
     */
    public function setHttpClient(ClientInterface $client);

    /**
     * @param string $bin
     * @return BINEntity
     */
    public function get($bin);
}
