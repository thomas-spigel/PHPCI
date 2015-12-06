<?php

/**
 * ProjectDocker base store for table: project_docker
 */

namespace PHPCI\Store\Base;

use b8\Database;
use b8\Exception\HttpException;
use PHPCI\Store;
use PHPCI\Model\ProjectDocker;

/**
 * ProjectDocker Base Store
 */
class ProjectDockerStoreBase extends Store
{
    protected $tableName   = 'project_docker';
    protected $modelName   = '\PHPCI\Model\ProjectDocker';
    protected $primaryKey  = 'id';

    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }

    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = 'SELECT * FROM `project_docker` WHERE `id` = :id LIMIT 1';
        $stmt = Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':id', $value);

        if ($stmt->execute()) {
            if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return new ProjectDocker($data);
            }
        }

        return null;
    }
}
