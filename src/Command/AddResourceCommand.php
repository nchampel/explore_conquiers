<?php

namespace App\Command;

use App\Repository\PlanetBuildingRepository;
use App\Repository\PlanetRepository;
use App\Repository\PlanetResourceRepository;
use App\Service\ResourceService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\Store\FlockStore;

#[AsCommand(
    name: 'app:add-resource',
    description: 'incrémente les ressources'
)]
class AddResourceCommand extends Command
{
    public function __construct(
        private ResourceService $resourceService,
        private PlanetRepository $planetRepository,
        private LoggerInterface $logger,
        private EntityManagerInterface $entityManager,
        private PlanetResourceRepository $planetResourceRepository,
        private PlanetBuildingRepository $planetBuildingRepository
    ) {
        parent::__construct();
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $store = new FlockStore('/tmp');
        $lockFactory = new LockFactory($store);

        $lock = $lockFactory->createLock('app-add-resource', 59);

        if (!$lock->acquire()) {
            $output->writeln('Le traitement est déjà en cours.');

            return Command::SUCCESS;
        }

        $resources = ["metal", "crystal", "deuterium"];
        $planets = $this->planetRepository->findPlanetsByUserAndNPC();

        try {
            $this->logger->info('Début de app:add-resource');
            $output->writeln('Début de l\'incrémentation des ressources...');

            foreach($planets as $planet){
                $resourcesData = $this->planetResourceRepository->findOneBy(["planet" => $planet]);
                $productionBuildings = $this->planetBuildingRepository->findBuildingProductionByPlanet($planet);
                // echo count($buildingLevels);
                foreach($resources as $resource){

                    $this->resourceService->addResource($resource, $planet, $resourcesData, $productionBuildings);
                }
            }

            // Un seul flush pour toutes les planètes et toutes les ressources
            $this->entityManager->flush();

            $output->writeln('Processus d\'incrémentation terminé.');

            return Command::SUCCESS;
        } finally {
            $lock->release();
        }
    }
}