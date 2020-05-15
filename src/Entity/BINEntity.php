<?php

namespace ShahariaAzam\BinList\Entity;

/**
 * Class BINEntity
 */
class BINEntity
{
    /**
     * BIN number
     *
     * @var NumberEntity
     */
    private $number;

    /**
     * BIN scheme
     *
     * @var string
     */
    private $scheme;

    /**
     * BIN Type
     *
     * @var string
     */
    private $type;

    /**
     * Name of the Brand
     *
     * @var string
     */
    private $brand;

    /**
     * Is prepaid or not
     *
     * @var bool|null
     */
    private $prepaid;

    /**
     * BIN Country details
     *
     * @var CountryEntity
     */
    private $country;

    /**
     * BIN Bank information
     *
     * @var BankEntity
     */
    private $bank;

    /**
     * Build entity from array
     *
     * @param array $bin
     * @return $this
     */
    public function build(array $bin)
    {
        isset($bin['scheme']) ? $this->setScheme($bin['scheme']) : null;

        isset($bin['prepaid']) ? $this->setPrepaid($bin['prepaid']) : $this->setPrepaid(false);

        isset($bin['type']) ? $this->setType($bin['type']) : null;
        isset($bin['brand']) ? $this->setBrand($bin['brand']) : null;

        $numberEntity = new NumberEntity();
        isset($bin['number']['length']) ? $numberEntity->setLength($bin['number']['length']) : null;
        isset($bin['number']['luhn']) ? $numberEntity->setLuhn($bin['number']['luhn']) : null;
        $this->setNumber($numberEntity);

        $countryEntity = new CountryEntity();
        isset($bin['country']['numeric']) ? $countryEntity->setNumeric($bin['country']['numeric']) : null;
        isset($bin['country']['alpha2']) ? $countryEntity->setAlpha2($bin['country']['alpha2']) : null;
        isset($bin['country']['name']) ? $countryEntity->setName($bin['country']['name']) : null;
        isset($bin['country']['emoji']) ? $countryEntity->setEmoji($bin['country']['emoji']) : null;
        isset($bin['country']['currency']) ? $countryEntity->setCurrency($bin['country']['currency']) : null;
        isset($bin['country']['latitude']) ? $countryEntity->setLatitude($bin['country']['latitude']) : null;
        isset($bin['country']['longitude']) ? $countryEntity->setLongitude($bin['country']['longitude']) : null;
        $this->setCountry($countryEntity);

        $bankEntity = new BankEntity();
        isset($bin['bank']['name']) ? $bankEntity->setName($bin['bank']['name']) : null;
        isset($bin['bank']['url']) ? $bankEntity->setUrl($bin['bank']['url']) : null;
        isset($bin['bank']['phone']) ? $bankEntity->setPhone($bin['bank']['phone']) : null;
        isset($bin['bank']['city']) ? $bankEntity->setCity($bin['bank']['city']) : null;
        $this->setBank($bankEntity);

        return $this;
    }

    /**
     * @return NumberEntity
     */
    public function getNumber(): NumberEntity
    {
        return $this->number;
    }

    /**
     * @param NumberEntity $number
     * @return BINEntity
     */
    public function setNumber(NumberEntity $number): BINEntity
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * @param string $scheme
     * @return BINEntity
     */
    public function setScheme(string $scheme): BINEntity
    {
        $this->scheme = $scheme;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return BINEntity
     */
    public function setType(string $type): BINEntity
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getBrand(): string
    {
        return $this->brand;
    }

    /**
     * @param string $brand
     * @return BINEntity
     */
    public function setBrand(string $brand): BINEntity
    {
        $this->brand = $brand;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPrepaid(): bool
    {
        return $this->prepaid;
    }

    /**
     * @param bool $prepaid
     * @return BINEntity
     */
    public function setPrepaid(bool $prepaid): BINEntity
    {
        $this->prepaid = $prepaid;
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

    /**
     * @return BankEntity
     */
    public function getBank(): BankEntity
    {
        return $this->bank;
    }

    /**
     * @param BankEntity $bank
     * @return BINEntity
     */
    public function setBank(BankEntity $bank): BINEntity
    {
        $this->bank = $bank;
        return $this;
    }
}
