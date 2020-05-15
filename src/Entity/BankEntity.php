<?php

namespace ShahariaAzam\BinList\Entity;

/**
 * Class BankEntity
 */
class BankEntity
{
    /**
     * Bank name
     *
     * @var string
     */
    private $name;

    /**
     * Bank URL
     *
     * @var string
     */
    private $url;

    /**
     * Bank phone number
     *
     * @var string
     */
    private $phone;

    /**
     * Bank city
     *
     * @var string
     */
    private $city;

    /**
     * Name of the Bank
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return BankEntity
     */
    public function setName(string $name): BankEntity
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return BankEntity
     */
    public function setUrl(string $url): BankEntity
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return BankEntity
     */
    public function setPhone(string $phone): BankEntity
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return BankEntity
     */
    public function setCity(string $city): BankEntity
    {
        $this->city = $city;
        return $this;
    }
}
