<?php

/**
 * @author Dmitry Petrov <old.fightmaster@gmail.com>
 */

namespace Fightmaster\DB;


use Fightmaster\DB\Model\StoreItemInterface;

interface RepositoryInterface
{
    /**
     * @param StoreItemInterface $object
     */
    public function insert(StoreItemInterface $object);

    /**
     * @param StoreItemInterface[] $collection
     */
    public function insertCollection($collection);

    /**
     * @param StoreItemInterface $object
     */
    public function update(StoreItemInterface $object);

    /**
     * @param StoreItemInterface $object
     */
    public function delete(StoreItemInterface $object);

    /**
     * @param array $filter
     */
    public function deleteCollection(array $filter);

    /**
     * @param $id
     * @return bool
     */
    public function exist($id): bool;

    /**
     * @param string $id
     * @param string|null $findItemClass
     * @return StoreItemInterface|null|array
     */
    public function find(string $id, string $findItemClass = null);

    /**
     * @param array $filter
     * @param array $options
     * @param string|null $findItemClass
     * @return StoreItemInterface|null|array
     */
    public function findBy(array $filter = [], array $options = [], string $findItemClass = null);
}
