<?php

namespace ShahariaAzam\BinList\Entity;

/**
 * Class BINEntity
 */
class BINEntity
{
    /**
     * BIN Country details
     *
     * @var CountryEntity
     */
    private $country;

    /**
     * Build entity from array
     *
     * @param array $bin
     * @return $this
     */
    public function build(array $bin)
    {
        $countryEntity = new CountryEntity();
        isset($bin['country']['alpha2']) ? $countryEntity->setAlpha2($bin['country']['alpha2']) : null;
        $this->setCountry($countryEntity);

        return $this;
    }

    /**
     * @return CountryEntity
     */
    public function getCountry(): CountryEntity
    {
        return $this->country;
    }

    /**
     * @param CountryEntity $country
     * @return BINEntity
     */
    public function setCountry(CountryEntity $country): BINEntity
    {
        $this->country = $country;
        return $this;
    }
}
