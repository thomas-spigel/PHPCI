<?php
namespace PHPCI\Store;

use b8\Database;
use PHPCI\Model\Docker;
use PHPCI\Model\Project;
use PHPCI\Store;

class DockerStore extends Store
{

    protected $primaryKey  = 'id';
    protected $tableName   = 'docker';
    protected $modelName   = '\PHPCI\Model\Docker';
    /**
     * Get a Docker image by primary key (Id)
     */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }

    /**
     * Get a single Docker by Id.
     * @return null|Docker
     */
    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = 'SELECT * FROM `docker` WHERE `id` = :id LIMIT 1';
        $stmt = Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':id', $value);

        if ($stmt->execute()) {
            if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return new Docker($data);
            }
        }

        return null;
    }

    public function getAll($useConnection = 'read')
    {
        $query = 'SELECT * FROM docker';

        $stmt = Database::getConnection('read')->prepare($query);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Docker($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return array();
        }
    }

    public function getByProjectId($value, $useConnection = 'read')
    {
        $query = 'SELECT docker.* FROM docker
                  LEFT JOIN project_docker ON (docker.id = project_docker.docker_id)
                    WHERE project_docker.project_id = :project';

        $stmt = Database::getConnection('read')->prepare($query);

        $stmt->bindValue(':project', $value, \PDO::PARAM_INT);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Docker($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return array();
        }
    }



    public function deleteLinksByProjectId($projectId, $useConnection = 'write')
    {

        $query = "DELETE FROM project_docker WHERE project_id = :project";

        $stmt = Database::getConnection($useConnection)->prepare($query);

        $stmt->bindValue(':project', $projectId, \PDO::PARAM_INT);

        $stmt->execute();

        return $this;

    }


    public function deleteLinksByDockerId($dockerId, $useConnection = 'write')
    {
        $query = "DELETE FROM project_docker WHERE docker_id = :docker";

        $stmt = Database::getConnection($useConnection)->prepare($query);

        $stmt->bindValue(':project', $dockerId, \PDO::PARAM_INT);

        $stmt->execute();

        return $this;

    }

    public function deleteLinkByProjectAndDockerId($projectId, $dockerId, $useConnection = 'write')
    {
        $query = "DELETE FROM project_docker WHERE project_id = :project AND docker_id = :docker";

        $stmt = Database::getConnection($useConnection)->prepare($query);

        $stmt->bindValue(':project', $projectId, \PDO::PARAM_INT);
        $stmt->bindValue(':docker', $dockerId, \PDO::PARAM_INT);

        $stmt->execute();

        return $this;
    }
}
