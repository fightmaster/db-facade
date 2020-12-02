<?php

/**
 * @author Dmitry Petrov <old.fightmaster@gmail.com>
 */

namespace Fightmaster\DB;

interface RepositoryFactoryInterface
{
    /**
     * @param string $className
     * @param string $tableName
     * @return RepositoryInterface
     */
    public function get(string $className, string $tableName): RepositoryInterface;
}
