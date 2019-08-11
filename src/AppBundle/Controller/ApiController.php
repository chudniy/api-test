<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 8/11/19
 * Time: 12:41
 */

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends AbstractFOSRestController
{
    /**
     * @Route("/api")
     */
    public function indexAction()
    {
        $data = array("hello" => "world");
        return $this->json($data, Response::HTTP_OK);
    }
}