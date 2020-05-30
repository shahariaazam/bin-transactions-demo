<?php

namespace ShahariaAzam\BinList\Entity;

/**
 * Class CountryEntity
 */
class CountryEntity
{
    /**
     * ALPHA2 Country Code. i.e: BD
     *
     * @var string
     */
    private $alpha2;

    /**
     * @return string
     */
    public function getAlpha2(): string
    {
        return $this->alpha2;
    }

    /**
     * @param string $alpha2
     * @return CountryEntity
     */
    public function setAlpha2(string $alpha2): CountryEntity
    {
        $this->alpha2 = $alpha2;
        return $this;
    }
}
