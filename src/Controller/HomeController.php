<?php

namespace App\Controller;

use App\Repository\PlanetRepository;
use App\Repository\PlanetResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home_symfony')]
    public function home(): Response
    {
        return $this->redirectToRoute('app_login');
    }
    #[Route('/game/home', name: 'app_home_game')]
    public function homeGame(): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if(!$user){
            return $this->redirectToRoute('app_login');
        }
        return $this->render('home/homeGame.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/game/home/og', name: 'app_home_game_og')]
    public function homeGameOg(PlanetResourceRepository $planetResourceRepository, PlanetRepository $planetRepository): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if(!$user){
            return $this->redirectToRoute('app_login');
        }
        $planet = $planetRepository->findOneBy(["user" => $user]);
        $resources = $planetResourceRepository->findOneBy(["planet" => $planet]);
        return $this->render('home/homeGameOg.html.twig', [
            'user' => $user,
            'planet' => $planet,
            'resources' => $resources
        ]);
    }
}
