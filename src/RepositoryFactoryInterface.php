<?php

/**
 * @author Dmitry Petrov <old.fightmaster@gmail.com>
 */

namespace Fightmaster\DB;


interface RepositoryFactoryInterface
{
    /**
     * @param string $collectionName
     */
    public function get(string $collectionName): RepositoryInterface;
}
