<?php

/**
 * Docker base model for table: docker
 */

namespace PHPCI\Model\Base;

use PHPCI\Model;
use b8\Store\Factory;

/**
 * Docker Base Model
 */
class DockerBase extends Model
{
    /**
    * @var array
    */
    public static $sleepable = array();

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
        'name' => null,
        'docker_image' => null,
        'dockerfile' => null,
        'created_date' => null,
        'logs' => null,
    );

    /**
    * @var array
    */
    protected $getters = array(
        // Direct property getters:
        'id' => 'getId',
        'name' => 'getName',
        'docker_image' => 'getDockerImage',
        'dockerfile' => 'getDockerfile',
        'created_date' => 'getCreatedDate',
        'logs' => 'getLogs',

        // Foreign key getters:
    );

    /**
    * @var array
    */
    protected $setters = array(
        // Direct property setters:
        'id' => 'setId',
        'name' => 'setName',
        'docker_image' => 'setDockerImage',
        'dockerfile' => 'setDockerfile',
        'created_date' => 'setCreatedDate',
        'logs' => 'setLogs',

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
        'name' => array(
            'type' => 'varchar',
            'length' => 255,
            'nullable' => true,
            'default' => null,
        ),
        'docker_image' => array(
            'type' => 'varchar',
            'length' => 255,
            'nullable' => true,
            'default' => null,
        ),
        'dockerfile' => array(
            'type' => 'text',
            'nullable' => true,
            'default' => null,
        ),
        'created_date' => array(
            'type' => 'datetime',
            'default' => null,
        ),
        'logs' => array(
            'type' => 'text',
            'nullable' => true,
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
    * Get the value of Name / name.
    *
    * @return string
    */
    public function getName()
    {
        $rtn    = $this->data['name'];

        return $rtn;
    }

    /**
    * Get the value of DockerImage / docker_image.
    *
    * @return string
    */
    public function getDockerImage()
    {
        $rtn    = $this->data['docker_image'];

        return $rtn;
    }

    /**
    * Get the value of Dockerfile / dockerfile.
    *
    * @return string
    */
    public function getDockerfile()
    {
        $rtn    = $this->data['dockerfile'];

        return $rtn;
    }

    /**
    * Get the value of CreatedDate / created_date.
    *
    * @return \DateTime
    */
    public function getCreatedDate()
    {
        $rtn    = $this->data['created_date'];

        if (!empty($rtn)) {
            $rtn    = new \DateTime($rtn);
        }
        
        return $rtn;
    }

    /**
    * Get the value of Logs / logs.
    *
    * @return string
    */
    public function getLogs()
    {
        $rtn    = $this->data['logs'];

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
    * Set the value of Name / name.
    *
    * @param $value string
    */
    public function setName($value)
    {
        $this->_validateString('Name', $value);

        if ($this->data['name'] === $value) {
            return;
        }

        $this->data['name'] = $value;

        $this->_setModified('name');
    }

    /**
    * Set the value of DockerImage / docker_image.
    *
    * @param $value string
    */
    public function setDockerImage($value)
    {
        $this->_validateString('DockerImage', $value);

        if ($this->data['docker_image'] === $value) {
            return;
        }

        $this->data['docker_image'] = $value;

        $this->_setModified('docker_image');
    }

    /**
    * Set the value of Dockerfile / dockerfile.
    *
    * @param $value string
    */
    public function setDockerfile($value)
    {
        $this->_validateString('Dockerfile', $value);

        if ($this->data['dockerfile'] === $value) {
            return;
        }

        $this->data['dockerfile'] = $value;

        $this->_setModified('dockerfile');
    }

    /**
    * Set the value of CreatedDate / created_date.
    *
    * Must not be null.
    * @param $value \DateTime
    */
    public function setCreatedDate($value)
    {
        $this->_validateNotNull('CreatedDate', $value);
        $this->_validateDate('CreatedDate', $value);

        if ($this->data['created_date'] === $value) {
            return;
        }

        $this->data['created_date'] = $value;

        $this->_setModified('created_date');
    }

    /**
    * Set the value of Logs / logs.
    *
    * @param $value string
    */
    public function setLogs($value)
    {
        $this->_validateString('Logs', $value);

        if ($this->data['logs'] === $value) {
            return;
        }

        $this->data['logs'] = $value;

        $this->_setModified('logs');
    }
}
