<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2014, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         https://www.phptesting.org/
*/

namespace PHPCI\Model;

use PHPCI\Model;
use b8\Store\Factory;

class Docker extends Model
{

    /**
     * @var string
     */
    protected $tableName = 'docker';

    /**
     * @var string
     */
    protected $modelName = 'Docker';


    /**
     * @var array
     */
    protected $data = array(
        'id' => null,
        'project_id' => null,
        'name' => null,
        'docker_image' => null
    );

    /**
     * @var array
     */
    protected $getters = array(
        // Direct property getters:
        'id' => 'getId',
        'project_id' => 'getProjectId',
        'name' => 'getName',
        'docker_image' => 'getDockerImage',
        // Foreign key getters:
        'Project' => 'getProject',
    );

    /**
     * @var array
     */
    protected $setters = array(
        // Direct property setters:
        'id' => 'setId',
        'project_id' => 'setProjectId',
        'name' => 'setName',
        'docker_image' => 'setDockerImage',

        // Foreign key setters:
        'Project' => 'setProject',
    );

    /**
     * @var array
     */
    public $columns = array(
        'id' => array(
            'type' => 'int',
            'length' => 10,
            'primary_key' => true,
            'auto_increment' => true,
            'default' => null,
        ),
        'project_id' => [
            'type' => 'int',
            'length' => 10,
            'primary_key' => false,
            'auto_increment' => false,
            'default' => null
        ],
        'name' => [
            'type' => 'varchar',
            'length' => 255,
            'default' => null
        ],
        'docker_image' => [
            'type' => 'varchar',
            'length' => 255,
            'default' => null
        ]
    );

    /**
     * @var array
     */
    public $indexes = array(
        'PRIMARY' => array('unique' => true, 'columns' => 'id'),
        'project_id' => array('columns' => 'project_id'),
    );

    /**
     * @var array
     */
    public $foreignKeys = array(
        'build_meta_ibfk_1' => array(
            'local_col' => 'project_id',
            'update' => 'CASCADE',
            'delete' => 'CASCADE',
            'table' => 'project',
            'col' => 'id'
        ),
    );



    /**
     * Get the value of Id / id.
     *
     * @return int
     */
    public function getId()
    {
        $rtn    = $this->data['id'];

        return $rtn;
    }

    /**
     * Get the value of ProjectId / project_id.
     *
     * @return int
     */
    public function getProjectId()
    {
        $rtn    = $this->data['project_id'];

        return $rtn;
    }


    public function getName()
    {
        $rtn = $this->data['name'];

        return $rtn;
    }

    public function getDockerImage()
    {
        $rtn = $this->data['docker_image'];

        return $rtn;
    }

    /**
     * Set the value of Id / id.
     *
     * Must not be null.
     * @param $value int
     */
    public function setId($value)
    {
        $this->_validateNotNull('Id', $value);
        $this->_validateInt('Id', $value);

        if ($this->data['id'] === $value) {
            return;
        }

        $this->data['id'] = $value;

        $this->_setModified('id');
    }

    /**
     * Set the value of ProjectId / project_id.
     *
     * Must not be null.
     * @param $value int
     */
    public function setProjectId($value)
    {
        $this->_validateNotNull('ProjectId', $value);
        $this->_validateInt('ProjectId', $value);

        if ($this->data['project_id'] === $value) {
            return;
        }

        $this->data['project_id'] = $value;

        $this->_setModified('project_id');
    }


    public function setName($value)
    {
        $this->_validateString('Name', $value);

        if($this->data['name'] === $value) {
            return;
        }

        $this->data['name'] = $value;

        $this->_setModified('name');

        return $this;
    }


    public function setDockerImage($value)
    {
        $this->_validateString('Docker Image', $value);

        if($this->data['docker_image'] === $value) {
            return;
        }

        $this->data['docker_image'] = $value;

        $this->_setModified('docker_image');
    }


    /**
     * Get the Project model for this BuildMeta by Id.
     *
     * @uses \PHPCI\Store\ProjectStore::getById()
     * @uses \PHPCI\Model\Project
     * @return \PHPCI\Model\Project
     */
    public function getProject()
    {
        $key = $this->getProjectId();

        if (empty($key)) {
            return null;
        }

        $cacheKey   = 'Cache.Project.' . $key;
        $rtn        = $this->cache->get($cacheKey, null);

        if (empty($rtn)) {
            $rtn    = Factory::getStore('Project', 'PHPCI')->getById($key);
            $this->cache->set($cacheKey, $rtn);
        }

        return $rtn;
    }

    /**
     * Set Project - Accepts an ID, an array representing a Project or a Project model.
     *
     * @param $value mixed
     */
    public function setProject($value)
    {
        // Is this an instance of Project?
        if ($value instanceof \PHPCI\Model\Project) {
            return $this->setProjectObject($value);
        }

        // Is this an array representing a Project item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setProjectId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setProjectId($value);
    }

    /**
     * Set Project - Accepts a Project model.
     *
     * @param $value \PHPCI\Model\Project
     */
    public function setProjectObject(\PHPCI\Model\Project $value)
    {
        return $this->setProjectId($value->getId());
    }


}