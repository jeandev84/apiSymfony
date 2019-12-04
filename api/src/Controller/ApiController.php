<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        // on recupere les regions
        $mesRegionJson = file_get_contents('https://geo.api.gouv.fr/regions');

        // on recupere une collection de Region [ Array ]
        $mesRegionsArray = $serializer->deserialize($mesRegionJson, 'App\Entity\Region[]', 'json');

        return $this->render('api/index.html.twig', [
            'mesRegions' => $mesRegionsArray
        ]);
    }

    /**
     * @Route("/listeDepsParRegions", name="listeDepsParRegions")
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listeDepsParRegions(Request $request, SerializerInterface $serializer)
    {
        // on recupere la region selectionnee dans le formulaire
        $codeRegion = $request->query->get('region');

        // on recupere les regions
        $mesRegionJson = file_get_contents('https://geo.api.gouv.fr/regions');
        $mesRegionsArray = $serializer->deserialize($mesRegionJson, 'App\Entity\Region[]', 'json');

        // on recupere la liste des departements
        if($codeRegion == null || $codeRegion == "Toutes")
        {
            // on fait une recherche integrale de mon departement
            $mesDepsJson = file_get_contents('https://geo.api.gouv.fr/departements');

        } else {

            // on fait la recherche qui correspond a la recheche de departement dont selectionne
            $mesDepsJson = file_get_contents('https://geo.api.gouv.fr/regions/'. $codeRegion .'/departements');
        }

        // decodage du format json en tableau
        // on precise qu'on passe au decode() un format de type json
        $mesDepsArray = $serializer->decode($mesDepsJson, 'json');

        return $this->render('api/listDepsParRegion.html.twig', [
            'mesRegions' => $mesRegionsArray,
            'mesDeps' => $mesDepsArray
        ]);
    }

}
