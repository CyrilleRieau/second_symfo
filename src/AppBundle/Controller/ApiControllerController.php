<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class ApiControllerController extends Controller
{

/**
     * @Route("/api/article/all")  
     *
     */
     public function indexAction()
     {
        $data = $this->getDoctrine()
                      ->getManager()
                      ->getRepository('AppBundle:FirstEntity')
                      ->findAll();
     
         return new JsonResponse($data);
     }
}
