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
     * @param SerializerInterface $serializer [ jsonToArray(normalise), ArrayToJson(denormalise) , ArrayToObject, ..]
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listeRegion(SerializerInterface $serializer)
    {
        /*
         on recupere toutes les regions depuis le lien d'une api externe : https://geo.api.gouv.fr/regions
         qui est au format json
        */
        $mesRegionJson = file_get_contents('https://geo.api.gouv.fr/regions');
        $mesRegions = $serializer->deserialize($mesRegionJson, 'App\Entity\Region[]', 'json');

        return $this->render('api/index.html.twig', [
            'mesRegions' => $mesRegions
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
