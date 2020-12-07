<?php

/**
 * @author Dmitry Petrov <old.fightmaster@gmail.com>
 */

namespace Fightmaster\DB\Model;

class HandleCollection
{
    /**
     * @param StoreItemInterface[] $collection
     * @return array
     */
    public static function store(array $collection): array
    {
        $rows = [];
        foreach ($collection as $item) {
            if (!$item instanceof StoreItemInterface) {
                throw new \InvalidArgumentException(sprintf('Expects %s, got %s', StoreItemInterface::class, get_class($item)));
            }

            $rows[$item->getId()] = $item->store();
        }

        return $rows;
    }

    /**
     * @param array $rows
     * @param $storeItemClass
     * @return StoreItemInterface[]
     */
    public static function restore(array $rows, $storeItemClass): array
    {
        if (is_subclass_of($storeItemClass, StoreItemInterface::class)) {
            throw new \InvalidArgumentException(sprintf('Expects %s, got %s', StoreItemInterface::class, $storeItemClass));
        }

        $collection = [];
        foreach ($rows as $row) {
            $item = $storeItemClass::restore($row);
            $collection[$item->getId()] = $item;
        }

        return $collection;
    }
}
