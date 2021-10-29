<?php

namespace App\Controller;

use App\Entity\Benne;
use App\Repository\BenneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class Exemple extends AbstractController
{
    private $serializer;

//    private $em;

    public function __construct()
    {
        $this->serializer = new Serializer([new ObjectNormalizer(null, null, null, null, null, null, [AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {return $object->getId();},])], [new JsonEncoder()]);
//        $this->em = $this->getDoctrine()->getManager();
    }

    /**
     * @Route("/bennes", name="api_benne", methods={"GET"})
     * @param BenneRepository $benneRepository
     * @return JsonResponse
     */
    public function apiBenne(BenneRepository $benneRepository): JsonResponse
    {
        $benne = $benneRepository->findAll();
        $benneSerialized = $this->serializer->serialize($benne, 'json');

        return JsonResponse::fromJsonString($benneSerialized, Response::HTTP_OK, ['Content-Type', 'application/json']);
    }

    /**
     * @Route("/bennes/{data}", name="api_benne_ville", methods={"GET"})
     * @param BenneRepository $benneRepository
     * @param $data
     * @return JsonResponse
     */
    public function apiBenneVille(BenneRepository $benneRepository, $data): JsonResponse
    {
        $benne = $benneRepository->findBenneByVilleOrCode($data);

        if (empty($benne)) {
            $jsonResponse = new JsonResponse('Désolé, votre ville n\'est pas encore enregistrée notre application...', Response::HTTP_NOT_FOUND, ['Content-Type', 'application/json']);
        } else {
            $benneSerialized = $this->serializer->serialize($benne, 'json');
            $jsonResponse =  JsonResponse::fromJsonString($benneSerialized, Response::HTTP_OK, ['Content-Type', 'application/json']);
        }

        return $jsonResponse;
    }

    /**
     * @Route("/benne/{id}", name="api_benne_edit", methods={"PUT"})
     * @param Benne $benne
     * @param Request $request
     * @return JsonResponse
     */
    public function apiBenneEdit(Benne $benne, Request $request): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);

        if (!empty($data['adresse'])) $benne->setAdresse($data['adresse']);
        if (!empty($data['type'])) $benne->setAdresse($data['type']);
        if (!empty($data['ville'])) $benne->setAdresse($data['ville']);
        if (!empty($data['code_postal'])) $benne->setAdresse($data['code_postal']);
        if (!empty($data['frequence_collecte'])) $benne->setAdresse($data['frequence_collecte']);
        if (!empty($data['jour_collecte'])) $benne->setAdresse($data['jour_collecte']);
        if (!empty($data['lat'])) $benne->setAdresse($data['lat']);
        if (!empty($data['lon'])) $benne->setAdresse($data['lon']);

        $em->persist($benne);
        $em->flush();

        return new JsonResponse('Les informations de la benne ont bien été modifées !', Response::HTTP_OK);
    }


}
