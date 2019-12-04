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
        $mesRegions = file_get_contents('https://geo.api.gouv.fr/regions');

        /* on transforme les donnees recus en tableau (cad) Json to Array */
        $mesRegionsTab = $serializer->decode($mesRegions, 'json'); // json to array

        /* dd($mesRegionsTab); */
        /* $serializer->denormalize($mesRegionsTab, 'App\Entity\Region');  on obtient un object */

        /* transform mon tableau en Object Region , [] pour dire qu'on obtient un tableau d'objects de regions */
        $mesRegionObject = $serializer->denormalize($mesRegionsTab, 'App\Entity\Region[]');

        /* dd($mesRegionObject); */

        /* on rend la vue avec ses donnees */
        return $this->render('api/index.html.twig', [
            /* 'mesRegions' => $mesRegionsTab */
            'mesRegions' => $mesRegionObject
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
