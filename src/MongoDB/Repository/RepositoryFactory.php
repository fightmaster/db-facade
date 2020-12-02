<?php

/**
 * @author Dmitry Petrov <old.fightmaster@gmail.com>
 */

namespace Fightmaster\DB\MongoDB\Repository;

use Fightmaster\DB\RepositoryFactoryInterface;
use Fightmaster\DB\RepositoryInterface;
use MongoDB\Database;

class RepositoryFactory implements RepositoryFactoryInterface
{
    private $database;

    /**
     * @var BaseRepository[]
     */
    private static $repositories = [];

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function get(string $className, string $collectionName): RepositoryInterface
    {
        if (!isset(self::$repositories[$className])) {
            self::$repositories[$className] = new BaseRepository($this->database, $className, $collectionName);
        }

        return self::$repositories[$className];
    }
}
