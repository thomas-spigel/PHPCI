<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Command;

use b8\Store\Factory;
use Monolog\Logger;
use PHPCI\Store;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
* Daemon that loops and call the run-command.
* @author       Gabriel Baker <gabriel.baker@autonomicpilot.co.uk>
* @package      PHPCI
* @subpackage   Console
*/
class DockerDaemoniseCommand extends Command
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var boolean
     */
    protected $run;

    /**
     * @var int
     */
    protected $sleep;

    /**
     * @param \Monolog\Logger $logger
     * @param string $name
     */
    public function __construct(Logger $logger, $name = null)
    {
        parent::__construct($name);
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this
            ->setName('phpci:docker-daemonise')
            ->setDescription('Starts the daemon to run commands with experimental docker support.');
    }

    /**
    * Loops through running.
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cmd = "echo %s > '%s/daemon/daemon.pid'";
        $command = sprintf($cmd, getmypid(), PHPCI_DIR);
        exec($command);

        $this->output = $output;
        $this->run   = true;
        $this->sleep = 0;
        $runner      = new DockerRunCommand($this->logger);
        $runner->setMaxBuilds(1);
        $runner->setDaemon(true);

        $emptyInput = new ArgvInput(array());

        while ($this->run) {

            $buildCount = 0;

            $options = '';
            try {

                $this->logger->addInfo('Finding next build and checking if it\'s a docker build...');
                $store = Factory::getStore('Build');
                $result = $store->getByStatus(0, 1);
                if(count($result['items']) > 0) {
                    /** @var \PHPCI\Store\BuildStore $build */
                    $build = array_shift($result['items']);
                    if(!$build) continue;
                    if($build->getExtra('docker')) {
                        $docker = Factory::getStore('Docker')->getByPrimaryKey($build->getExtra('docker'));

                        $this->output->writeln('Project type: ' . $build->getProject()->getType());
                        if($build->getProject()->getType() == 'local') {
                            $options = ' -v ' . $build->getProject()->getReference() . ':' . $build->getProject()->getReference();
                            $this->output->writeln('Adding option for link on the docker image: ' . $options);
                        }
                        $command = 'docker run --net=host ' . $options . ' -v ' . getcwd() . ':/var/www/phpci -w /var/www/phpci -v /var/run/mysqld:/var/run/mysqld ' . $docker->getDockerImage() . ' ./console phpci:docker-run ' . $build->getId();
//                        echo $command;

                        $this->logger->addInfo(sprintf('Running build on docker instance %s', $docker->getDockerImage()));

                        passthru($command);
                    } else {
                        $buildCount = $runner->run($emptyInput, $output);
                    }
                }
            } catch (\Exception $e) {
                $output->writeln('<error>Exception: ' . $e->getMessage() . '</error>');
                $output->writeln('<error>Line: ' . $e->getLine() . ' - File: ' . $e->getFile() . '</error>');
            }

            if (0 == $buildCount && $this->sleep < 15) {
                $this->sleep++;
            } elseif (1 < $this->sleep) {
                $this->sleep--;
            }
            echo '.'.(0 === $buildCount?'':'build');
            sleep($this->sleep);
        }
    }

    /**
    * Called when log entries are made in Builder / the plugins.
    * @see \PHPCI\Builder::log()
    */
    public function logCallback($log)
    {
        $this->output->writeln($log);
    }
}
