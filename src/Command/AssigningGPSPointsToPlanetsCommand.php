<?php

namespace App\Command;

use App\Entity\GpsPoint;
use App\Repository\PlanetRepository;
use App\Service\ConstantService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\Store\FlockStore;

#[AsCommand(
    name: 'app:assigning-GPS-Points',
    description: 'attribue aux planètes un point GPS',
)]
class AssigningGPSPointsToPlanetsCommand extends Command
{
     public function __construct(
        private ConstantService $constantService,
        // private PlanetRepository $planetRepository,
        private LoggerInterface $logger,
        private EntityManagerInterface $entityManager,
        private PlanetRepository $planetRepository,
        // private PlanetResourceRepository $planetResourceRepository,
        // private PlanetBuildingRepository $planetBuildingRepository
    ) {
        parent::__construct();
    }
    // protected function configure(): void
    // {
    //     $this
    //         ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
    //         ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
    //     ;
    // }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $store = new FlockStore('/tmp');
        $lockFactory = new LockFactory($store);

        $lock = $lockFactory->createLock('app:assigning-GPS-Points', 59);

        if (!$lock->acquire()) {
            $output->writeln('Le traitement est déjà en cours.');

            return Command::SUCCESS;
        }

        
        


        // switch($galaxiesNumber){
        //     case 1:
        //         $min = 1;
        //         $max = 180;
        //         break;
        //     case 2:
        //         $min = 1;
        //         $max = 180;
        //         break;
        //     case 3:
        //         $min = 1;
        //         $max = 180;
        //         break;
        //     case 4:
        //         $min = 1;
        //         $max = 180;
        //         break;
        //     case 5:
        //         $min = 1;
        //         $max = 180;
        //         break;
        //     case 6:
        //         $min = 1;
        //         $max = 180;
        //         break;
        //     case 7:
        //         $min = 1;
        //         $max = 180;
        //         break;
        //     case 8:
        //         $min = 1;
        //         $max = 180;
        //         break;
        //     case 9:
        //         $min = 1;
        //         $max = 180;
        //         break;
        //     case 10:
        //         $min = 1;
        //         $max = 180;
        //         break;
        // }
        
        
        // $excludedNumbers = $this->planetRepository->findExcludedNumbers();
        $allPlanets = $this->planetRepository->findPlanetsExceptUser();
        $planetsToExclude = $this->planetRepository->findPlanetsOfUser();
        $galaxiesNumber = $this->constantService->getGalaxiesNumber();
        $this->planetRepository->setGpsPointAsNull();

        // Indexer les entités par leur ID pour un accès direct en O(1)
        $planetsById = [];
        foreach ($allPlanets as $planet) {
            $planetsById[$planet->getId()] = $planet;
            $output->writeln("planète fields" . $planet->getFields());
        }

        // $excludedNumbers = [];

        // 1. Initialiser un tableau de 10 tableaux vides (indices 0 à 9)
        // Indice 0 -> gal 1, Indice 1 -> gal 2, ..., Indice 9 -> gal 10
        $excludedNumbers = array_fill(0, $galaxiesNumber, []);

        // 2. Récupérer toutes les entités où 'gal' est compris entre 1 et 10
        // $entities = $this->entityManager->getRepository(MonEntite::class)
        //     ->createQueryBuilder('e')
        //     ->where('e.gal BETWEEN 1 AND 10')
        //     ->getQuery()
        //     ->getResult();

        // 3. Répartir les IDs dans le tableau
        foreach ($planetsToExclude as $planet) {
            $galaxy = $planet->getGalaxy(); // Supposons getGal() retourne un entier de 1 à 10
            $index = $galaxy - 1;        // L'indice 0 correspond à gal 1

            // Sécurité au cas où la valeur est strictement comprise entre 1 et 10
            if ($index >= 0 && $index < $galaxiesNumber) {
                $excludedNumbers[$index][] = $planet->getId();
                // $output->writeln("planète id" . $planet->getId());
            }
        }
        

        try {
            $this->logger->info('Début de app:assigning-GPS-Points');
            $output->writeln('Début de l\'attribution des planètes...');

            $selectedNumbers = array_fill(0, $galaxiesNumber, []);

            $hasAsteroidAlea = random_int(1, 14);

            for($i = 1 ; $i <= $galaxiesNumber ; $i++){

                $min = 1 + 180 * ($i - 1);
                $max = 180 + 180 * ($i - 1);

                // 1. Générer tous les nombres de 1 à 180
                $allNumbers = range($min, $max);

                // 2. Exclure les nombres fournis dans le tableau
                // $output->writeln("count excludedNumbers " . count($excludedNumbers[$i - 1]));
                $output->writeln("min " . $allNumbers[0]);
                $allowedNumbers = array_diff($allNumbers, $excludedNumbers[$i - 1]);

                $output->writeln("count allowedNumbers " . count($allowedNumbers));

                // Sécurité : vérifier qu'il reste au moins 14 nombres disponibles
                if (count($allowedNumbers) < 14) {
                    throw new \InvalidArgumentException("Il n'y a pas assez de nombres disponibles après exclusion.");
                }

                // 3. Mélanger aléatoirement les nombres restants
                shuffle($allowedNumbers);

                // 4. Récupérer exactement les 14 premiers
                $selectedNumbers[$i] = array_slice($allowedNumbers, 0, 14);

                // Optionnel : trier le résultat par ordre croissant si besoin
                // sort($selectedNumbers);
            }

            for($i = 1 ; $i <= $galaxiesNumber ; $i++){

                foreach($selectedNumbers[$i] as $index => $selectedNumber){
                    $output->writeln("planète " . $selectedNumber);
                    // Vérifier si l'entité existe dans votre tableau en mémoire
                    if (isset($planetsById[$selectedNumber])) {
                        // on récupère la planète avec l'id sélectionné
                        $planet = $planetsById[$selectedNumber];
                        $output->writeln("planète fields" . $planet->getFields());
                        // on lui met un nouveau point gps
                        // $planet->setGpsPoint($index + 1);
                        $gpsPointId = $index + 1; // Votre ID calculé

                        // Crée une référence sans faire de requête SQL SELECT
                        $gpsPointProxy = $this->entityManager->getReference(GpsPoint::class, $gpsPointId);

                        // On assigne la référence à la planète
                        $planet->setGpsPoint($gpsPointProxy);
                        // on assigne l'astéroide
                        if($gpsPointId == $hasAsteroidAlea){
                            $planet->setHasAsteroid(true);
                        }
                    }
                }
                    
            }
            $this->entityManager->flush();

            $output->writeln('Processus d\'attribution terminé.');

            return Command::SUCCESS;
        } finally {
            $lock->release();
        }
        // $io = new SymfonyStyle($input, $output);
        // $arg1 = $input->getArgument('arg1');

        // if ($arg1) {
        //     $io->note(sprintf('You passed an argument: %s', $arg1));
        // }

        // if ($input->getOption('option1')) {
        //     // ...
        // }

        // $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        // return Command::SUCCESS;
    }
}
