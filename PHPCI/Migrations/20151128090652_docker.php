<?php

use Phinx\Migration\AbstractMigration;

class Docker extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('docker');

        if (!$this->hasTable('docker')) {
            $table->create();
        }

        $table->addColumn('project_id', 'integer', array('signed' => false));
        $table->addColumn('name', 'string', array('limit' => 255, 'null' => true));
        $table->addColumn('docker_image', 'string', ['limit' => 255, 'null' => true]);
        $table->addColumn('created_date', 'datetime');
        if (!$table->hasIndex(array('id'))) {
            $table->addIndex(array('id'));
        }
        if (!$table->hasIndex(array('project_id'))) {
            $table->addIndex(array('project_id'));
        }
        $table->addForeignKey('project_id', 'project', 'id', array('delete'=> 'CASCADE', 'update' => 'CASCADE'));
        $table->save();

    }
}
