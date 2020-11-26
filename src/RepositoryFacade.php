<?php

/**
 * @author Dmitry Petrov <old.fightmaster@gmail.com>
 */

namespace Fightmaster\DB;

use Fightmaster\DB\Model\StoreItemInterface;

class RepositoryFacade implements RepositoryInterface
{
    /**
     * @var RepositoryInterface;
     */
    protected $repository;

    /**
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param StoreItemInterface $object
     */
    public function insert(StoreItemInterface $object)
    {
        return $this->repository->insert($object);
    }

    /**
     * @param StoreItemInterface[] $collection
     */
    public function insertCollection($collection)
    {
        return $this->repository->insertCollection($collection);
    }

    /**
     * @param StoreItemInterface $object
     */
    public function update(StoreItemInterface $object)
    {
        return $this->repository->update($object);
    }

    /**
     * @param StoreItemInterface $object
     */
    public function delete(StoreItemInterface $object)
    {
        return $this->repository->delete($object);
    }

    /**
     * @param array $filter
     */
    public function deleteCollection(array $filter)
    {
        return $this->repository->deleteCollection($filter);
    }

    /**
     * @param $id
     * @return bool
     */
    public function exist($id): bool
    {
        return $this->repository->exist($id);
    }

    /**
     * @param string $id
     * @param string|null $findItemClass
     * @return StoreItemInterface|null|array
     */
    public function find(string $id, string $findItemClass = null)
    {
        return $this->repository->find($id, $findItemClass);
    }

    /**
     * @param array $filter
     * @param array $options
     * @param string|null $findItemClass
     * @return StoreItemInterface|null|array
     */
    public function findBy(array $filter = [], array $options = [], string $findItemClass = null)
    {
        return $this->repository->findBy($filter, $options, $findItemClass);
    }
}
