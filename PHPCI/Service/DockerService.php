<?php

namespace PHPCI\Service;

use b8\Store\Factory;
use PHPCI\Model\Docker;
use PHPCI\Model\ProjectDocker;
use PHPCI\Store\DockerStore;

class DockerService
{
    /**
     * @var DockerStore
     */
    protected $dockerStore;


    public function __construct(DockerStore $dockerStore)
    {
        $this->dockerStore = $dockerStore;
    }


    public function updateDocker($projectId, $options)
    {
        $this->dockerStore->deleteLinksByProjectId($projectId);

        foreach($options as $dockerId)
        {
            $store = Factory::getStore('ProjectDocker');
            $docker = new ProjectDocker(['project_id' => $projectId, 'docker_id' => $dockerId]);
            $store->save($docker,true);
        }
        return $this;
    }

    public function deleteDockerImage(Docker $docker)
    {
        $this->dockerStore->deleteLinksByDockerId($docker->getId());
        $this->dockerStore->delete($docker);

        return $this;
    }
}
