<?php

namespace App\Controller;

use App\Entity\Defence;
use App\Entity\PlanetBuilding;
use App\Entity\PlanetDefence;
use App\Entity\PlanetResource;
use App\Entity\PlanetVessel;
use App\Entity\ResearchPlayer;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\BuildingRepository;
use App\Repository\DefenceRepository;
use App\Repository\PlanetRepository;
use App\Repository\ResearchRepository;
use App\Repository\VesselRepository;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, 
    AppCustomAuthenticator $authenticator, EntityManagerInterface $entityManager, PlanetRepository $planetRepository, BuildingRepository $buildingRepository,
    DefenceRepository $defenceRepository, VesselRepository $vesselRepository, ResearchRepository $researchRepository): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $isPlanetAwarded = false;
            
            do {
                $position = random_int(1, 15);
                $gSystem = random_int(1, 12);
                $userPlanet = $planetRepository->findOneBy(["galaxy" => 1, "gSystem" => $gSystem, "position" => $position]);
                $isPlanetOccupied = $userPlanet->getUser();
                if(!$isPlanetOccupied){
                    $userPlanet->setUser($user);
                    $userPlanet->setFields(175);
                    $userPlanet->setName("Planète mère");
                    $isPlanetAwarded = true;
                }
            } while (!$isPlanetAwarded);

            // on crée les bâtiments niveau 0

            $buildings = $buildingRepository->findAll();
            foreach($buildings as $building){
                $buildingUser = new PlanetBuilding();
                $buildingUser->setBuilding($building);
                $buildingUser->setPlanet($userPlanet);
                if(
                    $buildingUser->getBuilding()->getType() == "metal mine" ||
                    $buildingUser->getBuilding()->getType() == "crystal mine" ||
                    $buildingUser->getBuilding()->getType() == "deuterium refinery" ||
                    $buildingUser->getBuilding()->getType() == "metal mine" ||
                    $buildingUser->getBuilding()->getType() == "solar power plant" ||
                    $buildingUser->getBuilding()->getType() == "metal storage" ||
                    $buildingUser->getBuilding()->getType() == "crystal storage" ||
                    $buildingUser->getBuilding()->getType() == "deuterium tank" ||
                    $buildingUser->getBuilding()->getType() == "robot factory" ||
                    $buildingUser->getBuilding()->getType() == "research lab"
                ) {
                    $buildingUser->setIsUnlocked(true);
                }
                $entityManager->persist($buildingUser);
            }

            // on crée les flottes quantité 0

            $vessels = $vesselRepository->findAll();
            foreach($vessels as $vessel){
                $vesselUser = new PlanetVessel();
                $vesselUser->setVessel($vessel);
                $vesselUser->setPlanet($userPlanet);
                $entityManager->persist($vesselUser);
            }

            // on crée les défenses quantité 0

            $defences = $defenceRepository->findAll();
            foreach($defences as $defence){
                $defenceUser = new PlanetDefence();
                $defenceUser->setDefence($defence);
                $defenceUser->setPlanet($userPlanet);
                $entityManager->persist($defenceUser);
            }

            // on crée les recherches niveau 0

            $researches = $researchRepository->findAll();
            foreach($researches as $research){
                $researchUser = new ResearchPlayer();
                $researchUser->setResearch($research);
                $researchUser->setUser($user);
                $entityManager->persist($researchUser);
            }

            // on crée les ressources 

            $planetResourcesUser = new PlanetResource();
            $planetResourcesUser->setPlanet($userPlanet);
            $planetResourcesUser->setMetal(1000);
            $planetResourcesUser->setCrystal(1000);
        

            $entityManager->persist($user);
            $entityManager->persist($userPlanet);
            $entityManager->persist($planetResourcesUser);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
