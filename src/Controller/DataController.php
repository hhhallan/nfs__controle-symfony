<?php

namespace App\Controller;

use App\Repository\RestaurantRepository;
use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataController extends AbstractController
{
    /**
     * @Route("/", name="data_all", methods={"GET"})
     * @param VilleRepository $villeRepository
     * @return JsonResponse
     */
    public function index(VilleRepository $villeRepository): Response
    {
        $ville = $villeRepository->findAll();
        return $this->render('data/index.html.twig', ['villes' => $ville]);
    }

    /**
     * @Route("/ville/{nom}", name="data_ville", methods={"GET"})
     * @param $nom
     * @param VilleRepository $villeRepository
     * @return Response
     */
    public function villeByNom($nom, VilleRepository $villeRepository): Response
    {
        $villeNom = $villeRepository->findOneBy(['nom' => $nom]);

        if (is_null($villeNom)) {
            return $this->render('data/villeNotFound.html.twig');
        } else {
            return $this->render('data/ville.html.twig', ['ville' => $villeNom]);
        }
    }

    /**
     * @Route("/restaurant/{nom}", name="data_resto", methods={"GET"})
     * @param $nom
     * @param RestaurantRepository $restaurantRepository
     * @return Response
     */
    public function restoByNom($nom, RestaurantRepository $restaurantRepository): Response
    {
        $restoNom = $restaurantRepository->findOneBy(['nom' => $nom]);

        if (is_null($restoNom)) {
            return $this->render('data/restoNotFound.html.twig');
        } else {
            return $this->render('data/resto.html.twig', ['resto' => $restoNom]);
        }
    }

}
