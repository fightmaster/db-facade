<?php

/**
 * @author Dmitry Petrov <old.fightmaster@gmail.com>
 */

namespace Fightmaster\MongoDB\Model;

interface StoreItemInterface extends ItemInterface
{
    /**
     * @return array
     */
    public function toStoreArray(): array;

    /**
     * @param array $row
     * @return mixed
     */
    public static function restore(array $row);
}
