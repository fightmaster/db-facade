<?php

/**
 * @author Dmitry Petrov <old.fightmaster@gmail.com>
 */

namespace Fightmaster\DB\Model;

interface ItemInterface
{
    public function getId();

    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @param array $row
     * @return mixed
     */
    public static function fromArray(array $row);
}
