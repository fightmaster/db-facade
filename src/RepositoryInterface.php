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
    public function insertCollection(array $collection);

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
     * @return StoreItemInterface|null|array
     */
    public function find(string $id);

    /**
     * @param array $filter
     * @param array $options
     * @return StoreItemInterface[]|array
     */
    public function findBy(array $filter = [], array $options = []);

    /**
     * @param array $filter
     * @param array $options
     * @return StoreItemInterface|null|array
     */
    public function findOneBy(array $filter = [], array $options = []);

    /**
     * @return string
     */
    public function getClassName(): string;

    /**
     * @return string
     */
    public function getTableName(): string;
}
