<?php

/**
 * ProjectDocker base model for table: project_docker
 */

namespace PHPCI\Model\Base;

use PHPCI\Model;
use b8\Store\Factory;

/**
 * ProjectDocker Base Model
 */
class ProjectDockerBase extends Model
{
    /**
    * @var array
    */
    public static $sleepable = array();

    /**
    * @var string
    */
    protected $tableName = 'project_docker';

    /**
    * @var string
    */
    protected $modelName = 'ProjectDocker';

    /**
    * @var array
    */
    protected $data = array(
        'id' => null,
        'project_id' => null,
        'docker_id' => null,
    );

    /**
    * @var array
    */
    protected $getters = array(
        // Direct property getters:
        'id' => 'getId',
        'project_id' => 'getProjectId',
        'docker_id' => 'getDockerId',

        // Foreign key getters:
    );

    /**
    * @var array
    */
    protected $setters = array(
        // Direct property setters:
        'id' => 'setId',
        'project_id' => 'setProjectId',
        'docker_id' => 'setDockerId',

        // Foreign key setters:
    );

    /**
    * @var array
    */
    public $columns = array(
        'id' => array(
            'type' => 'int',
            'length' => 11,
            'primary_key' => true,
            'auto_increment' => true,
            'default' => null,
        ),
        'project_id' => array(
            'type' => 'int',
            'length' => 11,
            'default' => null,
        ),
        'docker_id' => array(
            'type' => 'int',
            'length' => 11,
            'default' => null,
        ),
    );

    /**
    * @var array
    */
    public $indexes = array(
            'PRIMARY' => array('unique' => true, 'columns' => 'id'),
    );

    /**
    * @var array
    */
    public $foreignKeys = array(
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

    /**
    * Get the value of DockerId / docker_id.
    *
    * @return int
    */
    public function getDockerId()
    {
        $rtn    = $this->data['docker_id'];

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

    /**
    * Set the value of DockerId / docker_id.
    *
    * Must not be null.
    * @param $value int
    */
    public function setDockerId($value)
    {
        $this->_validateNotNull('DockerId', $value);
        $this->_validateInt('DockerId', $value);

        if ($this->data['docker_id'] === $value) {
            return;
        }

        $this->data['docker_id'] = $value;

        $this->_setModified('docker_id');
    }
}
