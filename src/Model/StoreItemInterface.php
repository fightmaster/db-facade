<?php

/**
 * @author Dmitry Petrov <old.fightmaster@gmail.com>
 */

namespace Fightmaster\DB\Model;

interface StoreItemInterface
{
    public function getId();

    /**
     * @return array
     */
    public function store(): array;

    /**
     * @param array $row
     * @return mixed
     */
    public static function restore(array $row);
}
