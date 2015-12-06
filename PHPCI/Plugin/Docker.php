<?php
namespace PHPCI\Plugin;

class Docker implements \PHPCI\Plugin
{
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;
        $this->message = $options['message'];

        $buildSettings = $phpci->getConfig('build_settings');


        if (isset($buildSettings['docker'])) {
//            $docker = $buildSettings['docker'];
        }
    }

    public function execute()
    {
        // TODO: Implement execute() method.
    }
}
