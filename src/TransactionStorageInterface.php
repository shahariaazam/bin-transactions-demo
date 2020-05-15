<?php

namespace ShahariaAzam\BinList;

use ShahariaAzam\BinList\Entity\TransactionEntity;

interface TransactionStorageInterface
{
    /**
     * @return TransactionEntity[]
     */
    public function get();
}
