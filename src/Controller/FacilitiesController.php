<?php

namespace App\Controller;

use App\Repository\PlanetRepository;
use App\Repository\PlanetResourceRepository;
use App\Service\ConstantService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/game')]
class FacilitiesController extends AbstractController
{
    #[Route('/facilities', name: 'app_facilities')]
    public function index(PlanetRepository $planetRepository, PlanetResourceRepository $planetResourceRepository, ConstantService $constantService): Response
    {
        // Récupérer le paramètre 'component' (renvoie null si absent, avec une valeur par défaut facultative)
        // $component = $request->query->get('component');

        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $planet = $planetRepository->findOneBy(["user" => $user]);
        // $resources = $planetResourceRepository->findOneBy(["planet" => $planet]);
        
        return $this->render('facilities/index.html.twig', [
            'user' => $user,
            'planet' => $planet,
            // 'resources' => $resources,
            'resources' => $planet->getPlanetResource(),
            // 'buildings' => $planet->getPlanetBuildings(),
            // 'defences' => $planet->getPlanetDefences(),
            // 'vessels' => $planet->getPlanetVessels(),
            // 'researches' => $user->getResearchesPlayer(),
            'domain' => $constantService->getDomain(),
            'component' => 'facilities',
        ]);
    }
}
