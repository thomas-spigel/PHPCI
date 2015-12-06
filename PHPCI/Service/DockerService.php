<?php

namespace PHPCI\Service;

use PHPCI\Model\Docker;
use PHPCI\Store\DockerStore;

class DockerService
{
    /**
     * @var DockerStore
     */
    protected $dockerStore;


    protected $instances = [
        'php:5.4-apache',
        'php:5.5-apache',
        'php:5.6-apache',
        'php:7.0-apache',
    ];

    public function __construct(DockerStore $dockerStore)
    {
        $this->dockerStore = $dockerStore;
    }


    public function updateDocker($projectId, $options)
    {
        $this->dockerStore->deleteByProjectId($projectId);

        foreach ($this->instances as $dockerInstance)
        {
            if (array_key_exists($dockerInstance, $options) && !empty($options[$dockerInstance])) {
                $newInstance = new Docker();
                $newInstance->setName($dockerInstance)
                    ->setDockerImage($dockerInstance)
                    ->setProjectId($projectId);
                $this->dockerStore->save($newInstance);
            }
        }
        return $this;

    }
}
