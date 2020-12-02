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

final class BaseRepository implements RepositoryInterface
{
    /**
     * @var Database;
     */
    protected $database;

    /**
     * @var string
     */
    protected $collectionName;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var Collection
     */
    protected $collection;

    public function __construct(Database $database, string $className, string $collectionName)
    {
        $this->database = $database;
        $this->className = $className;
        $this->collectionName = $collectionName;
        $this->collection = $this->database->{$this->getTableName()};
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->collectionName;
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
    public function insertCollection(array $collection)
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
     * @param array $options
     * @return StoreItemInterface|null|array
     */
    public function find(string $id, array $options = [])
    {
        if (!isset($options['typeMap'])) {
            $options['typeMap'] = $this->getTypeMap();
        }
        $row = $this->collection->findOne(['_id' => $id], $options);

        if (empty($row)) {
            return null;
        }
        $findItemClass = $this->getClassNameByOptions($options);

        return is_subclass_of($findItemClass, StoreItemInterface::class) ? $findItemClass::restore($row) : $row;
    }

    /**
     * @param array $filter
     * @param array $options
     * @return StoreItemInterface|null|array
     */
    public function findBy(array $filter = [], array $options = [])
    {
        if (!isset($options['typeMap'])) {
            $options['typeMap'] = $this->getTypeMap();
        }
        $cursor = $this->collection->find($filter, $options);

        return $this->handleCursorResult($cursor, $this->getClassNameByOptions($options));
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
     * @param StoreItemInterface|string|null $storeItemClass
     * @return array
     */
    protected function handleCursorResult(Cursor $cursor, string $storeItemClass = null): array
    {
        if (empty($cursor)) {
            return [];
        }

        $rows = $cursor->toArray();

        $result = [];
        $isObject = is_subclass_of($storeItemClass, StoreItemInterface::class);
        foreach ($rows as $row) {
            unset($row['_id']);
            $result[] = $isObject ? $storeItemClass::restore($row) : $row;
        }

        return $result;
    }

    /**
     * @param array $options
     * @return string|null
     */
    private function getClassNameByOptions(array $options): ?string
    {
        $findItemClass = null;
        if (!isset($options['assoc']) || $options['assoc'] !== true) {
            return $this->getClassName();
        }

        return null;
    }
}
