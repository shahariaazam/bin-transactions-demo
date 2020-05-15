<?php

namespace ShahariaAzam\BinList\Entity;

/**
 * Class NumberEntity
 */
class NumberEntity
{
    /**
     * @var int
     */
    private $length;

    /**
     * @var bool
     */
    private $luhn;

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @param int $length
     * @return NumberEntity
     */
    public function setLength(int $length): NumberEntity
    {
        $this->length = $length;
        return $this;
    }

    /**
     * @return bool
     */
    public function isLuhn(): bool
    {
        return $this->luhn;
    }

    /**
     * @param bool $luhn
     * @return NumberEntity
     */
    public function setLuhn(bool $luhn): NumberEntity
    {
        $this->luhn = $luhn;
        return $this;
    }
}
