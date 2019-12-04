<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * Class ApiController
 * @package App\Controller
*/
class ApiController extends AbstractController
{
    /**
     * @Route("/listeRegions", name="listeRegions")
     * @param SerializerInterface $serializer [ jsonToArray(normalise), ArrayToJson(denormalise) ]
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listeRegion(SerializerInterface $serializer)
    {
        $mesRegions = file_get_contents('https://geo.api.gouv.fr/regions');
        $mesRegionsTab = $serializer->decode($mesRegions, 'json'); // json to array

        /* dd($mesRegionsTab); */

        return $this->render('api/index.html.twig', [
            'mesRegions' => $mesRegionsTab
        ]);
    }


    /*
     * @Route("/api", name="api")
    public function index()
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
    */
}
