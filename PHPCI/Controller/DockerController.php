<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Controller;

use b8;
use b8\Exception\HttpException\NotFoundException;
use b8\Http\Response\JsonResponse;
use PHPCI\BuildFactory;
use PHPCI\Helper\AnsiConverter;
use PHPCI\Helper\Lang;
use PHPCI\Model\Docker;
use PHPCI\Service\DockerService;

/**
* Build Controller - Allows users to run and view builds.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Web
*/
class DockerController extends \PHPCI\Controller
{

    /**
     * @var \PHPCI\Store\DockerStore
     */
    protected $dockerStore;

    /**
     * @var \PHPCI\Service\DockerService
     */
    protected $dockerService;

    /**
     * Initialise the controller, set up stores and services.
     */
    public function init()
    {
        $this->dockerStore = b8\Store\Factory::getStore('Docker');
        $this->dockerService = new DockerService($this->dockerStore);
    }


    public function index()
    {
        $this->view->total = $this->dockerStore->getCount();

        $this->view->images = $this->dockerStore->getAll();
        $this->view->render();
    }

    /**
    * View a specific docker image.
    */
    public function view($imageId)
    {
        try {
            $image = $this->dockerStore->getByPrimaryKey($imageId);
        } catch (\Exception $ex) {
            $image = null;
        }

        if (empty($image)) {
            throw new NotFoundException(Lang::get('docker_image_x_not_found', $image));
        }

        $this->view->image    = $image;

        $this->layout->title = Lang::get('docker_image_n', $imageId);
        $this->layout->subtitle = $image->getName();

        $this->layout->skin = 'green';

        $build = Lang::get('docker_image_build');
        $buildLink = PHPCI_URL . 'docker/build/' . $image->getId();
        $delete = Lang::get('docker_delete_image');
        $deleteLink = PHPCI_URL . 'docker/delete/' . $image->getId();

        $this->view->logs = $this->getLogsForOutput($image);

        $actions = '';
        if ($this->currentUserIsAdmin()) {
            $actions .= " <a class=\"btn btn-normal\" href=\"{$buildLink}\">{$build}</a>";
            $actions .= " <a class=\"btn btn-danger\" href=\"{$deleteLink}\">{$delete}</a>";
        }

        $this->layout->actions = $actions;

    }

    /**
    * Delete a docker image.
    */
    public function delete($imageId)
    {
        $this->requireAdmin();

        /** @var Docker $image */
        $image = b8\Store\Factory::getStore('Docker')->getByPrimaryKey($imageId);
        if (empty($image)) {
            throw new NotFoundException(Lang::get('docker_image_x_not_found', $imageId));
        }

        $this->dockerService->deleteDockerImage($image);

        $response = new b8\Http\Response\RedirectResponse();
        $response->setHeader('Location', PHPCI_URL.'docker');
        return $response;
    }


    public function build($imageId)
    {
//        echo $imageId;die();

        $image = $this->dockerStore->getByPrimaryKey($imageId);

        file_put_contents('/tmp/testDockerFile', $image->getDockerfile());

//        echo 'docker build -t ' . $image->getDockerImage() . ' - < /tmp/testDockerFile';die();

        $log = '[!] Running: \'docker build  -t ' . $image->getDockerImage() . ' - < /tmp/testDockerFile\'' . "\n";
        $log .= shell_exec('docker build  -t ' . $image->getDockerImage() . ' - < /tmp/testDockerFile');


        $logsToSave = [];
        if($image->getLogs()) {
            $logsToSave = unserialize($image->getLogs());
        }

         $logsToSave[] = [
            'build_date' => date('Y-m-d H:i:s'),
            'data' => $log
        ];

        $image->setLogs(serialize($logsToSave));
        $this->dockerStore->save($image);

        $response = new b8\Http\Response\RedirectResponse();
        $response->setHeader('Location', PHPCI_URL.'docker/view/' .$image->getId());
        return $response;
    }

    protected function getLogsForOutput($image)
    {
        $output = '';
        if($image->getLogs()) {
            foreach (unserialize($image->getLogs()) as $log) {
                $output .= "*********** BUILD DATE: " . $log['build_date'] . " ***********\n" .
                    $log['data'] . "\n*********** END OF BUILD LOG ***********\n\n\n";
            }
        }

        return $output;
    }
    /**
    * Parse log for unix colours and replace with HTML.
    */
    protected function cleanLog($log)
    {
        return AnsiConverter::convert($log);
    }
}