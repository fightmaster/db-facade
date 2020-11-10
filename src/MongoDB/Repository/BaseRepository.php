<?php

/**
 * @author Dmitry Petrov <old.fightmaster@gmail.com>
 */

namespace Fightmaster\MongoDB\Repository;

use Fightmaster\MongoDB\Model\StoreItemInterface;
use MongoDB\Collection;
use MongoDB\Database;
use MongoDB\DeleteResult;
use MongoDB\Driver\Cursor;
use MongoDB\InsertManyResult;
use MongoDB\InsertOneResult;
use MongoDB\UpdateResult;

abstract class BaseRepository
{
    /**
     * @var Database;
     */
    protected $database;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @return string
     */
    abstract protected function getCollectionName(): string;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->collection = $this->database->{$this->getCollectionName()};
    }

    /**
     * @param StoreItemInterface $object
     * @return InsertOneResult
     */
    public function insert(StoreItemInterface $object): InsertOneResult
    {
        return $this->collection->insertOne($object->toStoreArray());
    }

    /**
     * @param StoreItemInterface[] $collection
     * @return InsertManyResult
     */
    public function insertCollection($collection): InsertManyResult
    {
        $objects = [];
        foreach ($collection as $item) {
            if (!$item instanceof StoreItemInterface) {
                throw new \LogicException('Every item of collection should be implement StoreItemInterface');
            }
            $objects[] = $item->toStoreArray();
        }

        return $this->collection->insertMany($objects);
    }

    /**
     * @param StoreItemInterface $object
     * @return UpdateResult
     */
    public function update(StoreItemInterface $object): UpdateResult
    {
        return $this->collection->updateOne(['_id' => $object->getId()], ['$set' => $object->toStoreArray()]);
    }

    /**
     * @param StoreItemInterface $object
     * @return DeleteResult
     */
    public function delete(StoreItemInterface $object): DeleteResult
    {
        return $this->collection->deleteOne(['_id' => $object->getId()]);
    }

    /**
     * @param array $filter
     * @return DeleteResult
     */
    public function deleteCollection(array $filter): DeleteResult
    {
        return $this->collection->deleteMany($filter);
    }

    /**
     * @param $id
     * @return bool
     */
    public function exist($id): bool
    {
        return !empty($this->collection->findOne(['_id' => $id]));
    }

    /**
     * @return string[]
     */
    protected function getTypeMap(): array
    {
        return ['root' => 'array', 'document' => 'array', 'array' => 'array'];
    }

    /**
     * @param Cursor $cursor
     * @param string $storeItemClass
     * @return array
     */
    protected function handleCursorResult(Cursor $cursor, string $storeItemClass): array
    {
        if (empty($cursor)) {
            return [];
        }

        $rows = $cursor->toArray();

        $result = [];
        foreach ($rows as $row) {
            $result[] = $storeItemClass::restore($row);
        }

        return $result;
    }

    /**
     * @param string $id
     * @param string $findItemClass
     * @return StoreItemInterface|null
     */
    protected function _find(string $id, string $findItemClass): ?StoreItemInterface
    {
        $row = $this->collection->findOne(['_id' => $id], ['typeMap' => $this->getTypeMap()]);

        if (empty($row)) {
            return null;
        }

        return $findItemClass::restore($row);
    }
}
