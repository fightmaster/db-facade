<?php

/**
 * @author Dmitry Petrov <old.fightmaster@gmail.com>
 */

namespace Fightmaster\DB;

use Fightmaster\DB\Model\StoreItemInterface;

abstract class RepositoryFacade implements RepositoryInterface
{
    /**
     * @var RepositoryInterface;
     */
    protected $repository;

    /**
     * @param RepositoryFacadeFactory $repositoryFacadeFactory
     */
    public function __construct(RepositoryFacadeFactory $repositoryFacadeFactory)
    {
        $this->repository = $repositoryFacadeFactory->get($this->getClassName(), $this->getTableName());
    }

    /**
     * @return string
     */
    abstract public function getClassName(): string;

    /**
     * @return string
     */
    abstract public function getTableName(): string;

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
    public function insertCollection(array $collection)
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
     * @return StoreItemInterface|null|array
     */
    public function find(string $id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param array $filter
     * @param array $options
     * @return StoreItemInterface|null|array
     */
    public function findBy(array $filter = [], array $options = [])
    {
        return $this->repository->findBy($filter, $options);
    }
}
