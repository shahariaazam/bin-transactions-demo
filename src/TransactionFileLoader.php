<?php

namespace ShahariaAzam\BinList;

use ArrayIterator;
use ShahariaAzam\BinList\Entity\TransactionEntity;
use ShahariaAzam\BinList\Exception\UtilityException;
use SplFileObject;

/**
 * Class TransactionFileLoader
 */
class TransactionFileLoader implements TransactionStorageInterface
{
    private $filePath;

    /**
     * TransactionFileLoader constructor.
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @return TransactionEntity[]
     * @throws UtilityException
     */
    public function get()
    {
        if (!file_exists($this->filePath)) {
            throw new UtilityException($this->filePath . ' is not valid or couldn\'t be loaded');
        }

        return $this->parseFile($this->filePath);
    }

    /**
     * @param string $filename
     * @return TransactionEntity[]
     */
    private function parseFile($filename)
    {
        /** @var $iterator TransactionEntity[]|ArrayIterator */
        $iterator = new ArrayIterator();

        $file = new SplFileObject($filename);
        while ($file->valid()) {
            $line = $file->fgets();

            $data = json_decode($line, true);

            if (JSON_ERROR_NONE === json_last_error()) {
                $iterator->append((new TransactionEntity())->build($data));
            }

            unset($line);
        }

        $file = null;

        return $iterator;
    }
}
