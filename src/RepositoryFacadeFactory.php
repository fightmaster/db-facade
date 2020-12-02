<?php

/**
 * @author Dmitry Petrov <old.fightmaster@gmail.com>
 */

namespace Fightmaster\DB;

class RepositoryFacadeFactory implements RepositoryFactoryInterface
{
    /**
     * @var RepositoryFactoryInterface[]
     */
    private $repositoryFactories;

    /**
     * @var array
     */
    private $mappingSchema;

    public function __construct(array $repositoryFactories, array $mappingSchema = [])
    {
        $this->repositoryFactories = $repositoryFactories;
        $this->mappingSchema = $mappingSchema;
    }

    public function get(string $className, string $tableName): RepositoryInterface
    {
        return $this->getFactory($className)->get($className, $tableName);
    }

    /**
     * @param string $className
     * @return RepositoryFactoryInterface
     */
    private function getFactory(string $className): RepositoryFactoryInterface
    {
        return $this->repositoryFactories[$this->mappingSchema[$className] ?? null] ?? reset($this->repositoryFactories);
    }
}
