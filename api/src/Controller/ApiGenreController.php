<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * Class ApiGenreController
 * @package App\Controller
*/
class ApiGenreController extends AbstractController
{
    /**
     * @Route("/api/genres", name="api_genres", methods={"GET"})
     * @param GenreRepository $repo
     * @param SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list(GenreRepository $repo, SerializerInterface $serializer)
    {
        $genres = $repo->findAll();

        $genresJson = $serializer->serialize($genres, 'json', [
            'groups' => ['listGenreFull'] // on indique le groupe a serialiser
        ]);

        # json = true  (si true alors cela veut dire que la donnee est du json donc pas besoin de le transformer)
        # json = false (si false alors cela veut dire que la donnee n'est du json et a besoin d'etre transforme)
        return new JsonResponse($genresJson, 200, [], true);
    }


    /**
     * @Route("/api/genres/{id}", name="api_genres_show", methods={"GET"})
     * @param Genre $genre
     * @param SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Genre $genre, SerializerInterface $serializer)
    {
        $genresJson = $serializer->serialize($genre, 'json', [
            'groups' => ['listGenreSimple'] // on indique le groupe a serialiser
        ]);

        // Response::HTTP_OK = 200
        return new JsonResponse($genresJson, Response::HTTP_OK, [], true);
    }


    /**
     * @Route("/api/genres", name="api_genres_create, methods={"POST"})
     * @param Request $request
     * @param ObjectManager $manager
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function create(Request $request, ObjectManager $manager, SerializerInterface $serializer)
    {
        // on obtient le contenu de la requette
        $data = $request->getContent();

        /*
        1 ere Methode
        on creer un nouveau object genre
        $genre = new Genre();

        remplit l'object genre avec les donnees que tu auras deserialise
        $serializer->deserialize($data, Genre::class, 'json', ['object_to_populate' => $genre]);
        */

        /* 2 ieme Methode */
        $genre = $serializer->deserialize($data, Genre::class, 'json');

        $manager->persist($genre);
        $manager->flush();

        // Response::HTTP_CREATED = 201
        // UrlGeneratorInterface::ABSOLUTE_PATH (genere l'url avec l'url absolu)
        return new JsonResponse(
            'Le genre a bien ete cree', // ou indiquer null
      Response::HTTP_CREATED,
            [
            # redirige vers le lien de l'object qui a ete cree
            /* "location" => "api/genres/". $genre->getId() ou encore */
            "location" => $this->generateUrl('api_genres_show', ['id' => $genre->getId()], UrlGeneratorInterface::ABSOLUTE_PATH)
           ],
            true
        );
    }


    /**
     * @Route("/api/genres/{id}", name="api_genres_update", methods={"PUT"})
     * @param Genre $genre
     * @param Request $request
     * @param ObjectManager $manager
     * @param SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Genre $genre, Request $request, ObjectManager $manager,SerializerInterface $serializer)
    {
        $data = $request->getContent();

        $genresJson = $serializer->deserialize($data, Genre::class, 'json', [
            'object_to_populate' => $genre
        ]);

        $manager->persist($genre);
        $manager->flush();

        return new JsonResponse('Le genre a bien ete modifie', Response::HTTP_OK, [], true);
    }


    /**
     * @Route("/api/genres/{id}", name="api_genres_delete", methods={"DELETE"})
     * @param Genre $genre
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Genre $genre, ObjectManager $manager)
    {
        $manager->remove($genre);
        $manager->flush();

        return new JsonResponse('Le genre a bien ete supprime', Response::HTTP_OK, []);
    }
}
