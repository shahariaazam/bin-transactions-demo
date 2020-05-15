<?php

namespace ShahariaAzam\BinList\Api;

use Psr\Http\Client\ClientExceptionInterface;
use ShahariaAzam\BinList\Entity\BINEntity;
use ShahariaAzam\BinList\BINClientInterface;

/**
 * BIN API Client where we can get BIN details
 *
 * Class BINClient
 */
class BINClient extends CommonAPIClient implements BINClientInterface
{
    /**
     * @param null $bin
     * @return BINEntity
     * @throws ClientExceptionInterface
     */
    public function get($bin)
    {
        $data = parent::sendRequest('https://lookup.binlist.net/' . $bin);
        return (new BINEntity())->build($data);
    }
}
