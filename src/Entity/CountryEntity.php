<?php

namespace ShahariaAzam\BinList\Entity;

/**
 * Class CountryEntity
 */
class CountryEntity
{
    /**
     * Country numeric code
     *
     * @var string
     */
    private $numeric;

    /**
     * ALPHA2 Country Code. i.e: BD
     *
     * @var string
     */
    private $alpha2;

    /**
     * Name of country
     *
     * @var string
     */
    private $name;

    /**
     * Emoji
     *
     * @var string
     */
    private $emoji;

    /**
     * Currency
     *
     * @var string
     */
    private $currency;

    /**
     * Latitude
     *
     * @var float
     */
    private $latitude;

    /**
     * Longitude
     *
     * @var float
     */
    private $longitude;

    /**
     * @return string
     */
    public function getNumeric(): string
    {
        return $this->numeric;
    }

    /**
     * @param string $numeric
     * @return CountryEntity
     */
    public function setNumeric(string $numeric): CountryEntity
    {
        $this->numeric = $numeric;
        return $this;
    }

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

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return CountryEntity
     */
    public function setName(string $name): CountryEntity
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmoji(): string
    {
        return $this->emoji;
    }

    /**
     * @param string $emoji
     * @return CountryEntity
     */
    public function setEmoji(string $emoji): CountryEntity
    {
        $this->emoji = $emoji;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return CountryEntity
     */
    public function setCurrency(string $currency): CountryEntity
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     * @return CountryEntity
     */
    public function setLatitude(float $latitude): CountryEntity
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     * @return CountryEntity
     */
    public function setLongitude(float $longitude): CountryEntity
    {
        $this->longitude = $longitude;
        return $this;
    }
}
