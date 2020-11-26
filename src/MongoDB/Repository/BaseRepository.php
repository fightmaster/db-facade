<?php

/**
 * @author Dmitry Petrov <old.fightmaster@gmail.com>
 */

namespace Fightmaster\DB\MongoDB\Repository;

use Fightmaster\DB\RepositoryInterface;
use Fightmaster\DB\Model\StoreItemInterface;
use MongoDB\Collection;
use MongoDB\Database;
use MongoDB\Driver\Cursor;

class BaseRepository implements RepositoryInterface
{
    /**
     * @var Database;
     */
    protected $database;

    /**
     * @var Collection
     */
    protected $collection;

    public function __construct(Database $database, $collectionName)
    {
        $this->database = $database;
        $this->collection = $this->database->{$collectionName};
    }

    /**
     * @param StoreItemInterface $object
     */
    public function insert(StoreItemInterface $object)
    {
        $data = $object->store();
        $data['_id'] = $object->getId();
        $this->collection->insertOne($data);
    }

    /**
     * @param StoreItemInterface[] $collection
     */
    public function insertCollection($collection)
    {
        $objects = [];
        foreach ($collection as $item) {
            if (!$item instanceof StoreItemInterface) {
                throw new \LogicException('Every item of collection should be implement StoreItemInterface');
            }
            $data = $item->store();
            $data['_id'] = $item->getId();
            $objects[] = $data;
        }

        $this->collection->insertMany($objects);
    }

    /**
     * @param StoreItemInterface $object
     */
    public function update(StoreItemInterface $object)
    {
        $this->collection->updateOne(['_id' => $object->getId()], ['$set' => $object->store()]);
    }

    /**
     * @param StoreItemInterface $object
     */
    public function delete(StoreItemInterface $object)
    {
        $this->collection->deleteOne(['_id' => $object->getId()]);
    }

    /**
     * @param array $filter
     */
    public function deleteCollection(array $filter)
    {
        $this->collection->deleteMany($filter);
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
     * @param string $id
     * @param string $findItemClass
     * @return StoreItemInterface|null|array
     */
    public function find(string $id, string $findItemClass = null)
    {
        $row = $this->collection->findOne(['_id' => $id], ['typeMap' => $this->getTypeMap()]);

        if (empty($row)) {
            return null;
        }
        return isset($findItemClass) ? $findItemClass::restore($row) : $row;
    }

    /**
     * @param array $filter
     * @param array $options
     * @param string|null $findItemClass
     * @return StoreItemInterface|null|array
     */
    public function findBy(array $filter = [], array $options = [], string $findItemClass = null)
    {
        if (!isset($options['typeMap'])) {
            $options['typeMap'] = $this->getTypeMap();
        }
        $cursor = $this->collection->find($filter, $options);

        return $this->handleCursorResult($cursor, $findItemClass);
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
     * @param string|null $storeItemClass
     * @return array
     */
    protected function handleCursorResult(Cursor $cursor, string $storeItemClass = null): array
    {
        if (empty($cursor)) {
            return [];
        }

        $rows = $cursor->toArray();

        $result = [];
        $isObject = isset($storeItemClass);
        foreach ($rows as $row) {
            unset($row['_id']);
            $result[] = $isObject ? $storeItemClass::restore($row) : $row;
        }

        return $result;
    }
}
