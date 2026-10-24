<?php

namespace App\Controller;

// use App\Entity\Planet;

use App\Entity\Planet;
use App\Entity\PlanetBuilding;
use App\Repository\PlanetRepository;
use App\Service\BuildingService;
use App\Service\TechnologyService;
use App\Service\VesselService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/game')]
class ApiController extends AbstractController
{
    public function __construct(private BuildingService $buildingService, private TechnologyService $technologyService, private VesselService $vesselService, private LoggerInterface $logger){
    }
    private function getLiResourceForBuild(array $buildingNeededResources): string{
        $lisElement = "";
        $typeFr = "";
        foreach($buildingNeededResources as $type => $resource){
            switch($type){
                case "metal":
                    $typeFr = "Métal";
                    break;
                case "crystal":
                    $typeFr = "Cristal";
                    break;
                case "deuterium":
                    $typeFr = "Deutérium";
                    break;
            }
            if($resource != 0){
                $lisElement .= "<li class=\"resource " . $type . " icon sufficient tooltip js_hideTipOnMobile\"\n      data-value=\"" . $resource . "\"\n   aria-label=\"" . $resource . " " . $typeFr . "\"\n     title=\"" . $resource . " " . $typeFr . "\"\n     >\n     " . number_format($resource, 0, '', ' ') . "\n        </li>";
            }
        }
        return $lisElement;
    }
    private function getTechnologyType(int $technologyId): string{
        // switch($this->buildingService->getTechnoIDCorrespondence($technologyId)){
        switch($technologyId){
            case 1:
            case 2:
            case 3:
            case 4:
            case 12:
            case 22:
            case 23:
            case 24:
                return "building";
            case 212:
                return "vessel";
            default:
                return "";
        }
    }
    #[Route('/api/ajax/{planet}', name: 'app_api', methods:["GET"])]
    public function index(Request $request, Planet $planet): Response
    {
        // var_dump($request->getContent());
        // $technologyId = json_decode($request->getContent(), true)["technology"];
        // data-target=\"https://s274-fr.ogame.gameforge.com/game/index.php?page=ajax&amp;component=technologytree&amp;ajax=1&amp;technologyId=2&amp;tab=technologytree\"\n 
        // enlever overlay si souci
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        // $planetId = $request->query->get('planetId');
        // $type = $request->query->get('type');
        // $planet = $planetRepository->findOneBy(["id" => $planetId]);
        $technologyId = $request->query->get('technology');
        $type = $this->getTechnologyType($technologyId);
        $idBuildingForMyDB = $this->buildingService->getTechnoIDCorrespondence($technologyId);
        switch($type){
            case "building":
                $technologyDatas = $this->buildingService->getTechnologiesSupplies($technologyId, $planet);
                $entity = $planet->getBuildingById($idBuildingForMyDB)->getBuilding();
                break;
            case "vessel":
                $technologyDatas = $this->vesselService->getTechnologiesSupplies($technologyId, $planet);
                $entity = $planet->getVesselById($this->vesselService->getTechnoIDCorrespondence($technologyId))->getVessel();
                break;
            default:
                $technologyDatas = [];
                $entity = "";
        }
        $lisElement = $this->getLiResourceForBuild($technologyDatas["buildingNeededResources"]);

        // on vérifie si le bâtiment a les prérequis remplis

        $isPrerequisitesMet = $this->technologyService->isPrerequisitesMet($user, $planet, $entity, $type);
        $activeTechTree = $isPrerequisitesMet["hasPrerequisites"] ? "" : "no_prerequisites";

        // niveau ou nombre

        $levelOrNumber = $type == "building" ? "Niveau" : "Nombre";

        // énergie ou pas

        $energyType = $this->buildingService->getEnergyType($idBuildingForMyDB);
        $energyOrProductionOrNot = $energyType == "prod" ? "Production :" : ($energyType == "consumption" ? "Énergie nécessaire :" : "");

        // différence énergie

        $energy = "";
        $energyText = "";

        
        
        $necesaryText = "Nécessaire pour le niveau " . $technologyDatas["buildingLevel"] + 1; 
        $costText = "Coûts par unité "; 
        $necesaryOrCost = $type == "building" ? $necesaryText : $costText;
        
        $energyOrNot = $type == "building" ? $energyText : "";
        
        $dataValueInformation = $type == "building" ? $technologyDatas["buildingLevel"] + 1: $technologyDatas["buildingLevel"]; // pour def et recherche faudra voir les valeurs data-value dans information
        $classInformation = $type == "building" ? "level": "amount";

        $displayEnergy = false;
        if($type == "building"){
            $displayEnergy = true;
        }
        
        // good critical full
        
        $storage = "";
        $storageValue = $technologyDatas["storageCapacity"];
        
        $this->logger->info($idBuildingForMyDB);
        
        if($idBuildingForMyDB == 6 || $idBuildingForMyDB == 7 || $idBuildingForMyDB == 8) {
            $displayEnergy = false;
            switch($idBuildingForMyDB){
                case 6:
                    $resourceFormatted = number_format($planet->getPlanetResource()->getMetal(), 0, '', ' ');
                    $resource = $planet->getPlanetResource()->getMetal();
                    break;
                case 7:
                    $resourceFormatted = number_format($planet->getPlanetResource()->getCrystal(), 0, '', ' ');
                    $resource = $planet->getPlanetResource()->getCrystal();
                    break;
                case 8:
                    $resourceFormatted = number_format($planet->getPlanetResource()->getDeuterium(), 0, '', ' ');
                    $resource = $planet->getPlanetResource()->getDeuterium();
                    break;
                default:
                    $resource = 0;
                    $resourceFormatted = 0;
                    break;
            }

            if($resource >= $storageValue){
                $colorFill = "full";
            } else if($resource >= $storageValue * 0.9){
                $colorFill = "critical";
            } else {
                // original 90%
                $colorFill = "good";
            }

            // low chgt de couleur
            // high rouge

            $storage = '<div class="capacity">
                        <span class="label">Capacité de stockage:</span>
                        <div class="progressBar">
                                                                <meter min="0" max="' . $storageValue . '" low="' . $storageValue * 0.9 . '" high="' . $storageValue - 0.1 . '" optimum="0" value="' . $resource . '"></meter>
                                                                </div>
                        <span class="description">
                            <span class="' . $colorFill . '">' . $resourceFormatted . '</span> / ' . number_format($storageValue, 0, '', ' ') . '
                        </span>
                    </div>' . 
                    '<div class="fill_capacity_info">
                        <div class="arrow_description"></div>
                        
                    </div>';
        }

        if($type == "building" && $displayEnergy){
    
                $energy = $technologyDatas["buildingNextConsumption"] - $technologyDatas["buildingConsumption"];
        
                $energyText = "<li class=\"additional_energy_consumption\">\n   
                                                    <strong>" . $energyOrProductionOrNot . "</strong>     \n 
                                                       <span class=\"value tooltip\"\n   data-value=\"" . $energy . "\"\n     title=\"\"\n    >" . $energy . "  \n   </span>\n    \n   
                                                </li>\n   ";
            }



        return $this->json([
    "content" => [
        "technologydetails" => "    <div id=\"technologydetails\" data-technology-id=\"" . $technologyId . "\">\n
            <div class=\"sprite sprite_large building " . $technologyDatas["buildingNameEn"] . "\">\n
             <button 
             class=\"technology_tree " . $activeTechTree . " tooltip js_hideTipOnMobile overlay ipiHintable\"\n 
              aria-label=\"Afficher arbre tech\"\n   
              title=\"Aucune condition requise\"\n  
              
              data-target=\"http://localhost:8000/game/api/tree/ajax\"\n 
               data-ipi-hint=\"ipiTechnologyTree" . $technologyDatas["buildingNameEn"] . "\"\n  
                >\n  
                 Arbre tech\n      
                  </button>\n  
                  </div>\n   
                     <div class=\"content\">\n        
                         <button class=\"close\">✖</button>\n\n         
                            <h3>" . $technologyDatas["buildingName"] . "</h3>\n\n         
                               <div class=\"information\">\n     
                                    <span class=\"" . $classInformation . "\" data-value=\"" . $dataValueInformation . "\">\n       " . $levelOrNumber . " " . $technologyDatas["buildingLevel"] . "\n        </span>\n \n   
                                    <ul class=\"narrow\">\n  
                                        <li class=\"build_duration\">\n       
                                            <strong>Temps de production :</strong>  \n 
                                            <time class=\"value tooltip\"\n  datetime=\"PT3S\"\n  title=\"\"\n  >" . $technologyDatas["buildingTimeFormatted"] . "  
                                            <span class=\"bonus\" data-value=\"0\">\n            (-0%)\n        </span>\n    \n   
                                            </time>\n    \n                            
                                        </li>\n           
                                        " . $energyOrNot . "     
                                    </ul>\n     \n    
                                    <div class=\"costs\">\n 
                                     <p>" . $necesaryOrCost . " :</p>\n   
                                      <ul class=\"ipiHintable\" data-ipi-hint=\"\">\n       
                                        " . $lisElement . "  
                                      </ul>\n   
                                    </div>\n\n                \n      
                                    <div class=\"build-it_wrap\">\n         
                                    <div class=\"ipiHintable\" data-ipi-hint=\"ipiTechnologyUpgrade" . $technologyDatas["buildingNameEn"] . "\">\n     
                                         <button class=\"upgrade\"  data-technology=\"" . $technologyId . "\">\n     
                                           <span class=\"tooltip\" title=\"\">Développer</span>\n     
                                       </button>\n       
                                    </div>\n     
                                    </div>\n     
                                        </div>\n     \n   
                                          </div>\n\n
                                          <div class=\"required\"></div>
                                  <div class=\"description\">\n     \n      \n  
                                  " . $storage . "
                                    <div class=\"txt_box\">\n   
                                       <button class=\"details tooltip js_hideTipOnMobile overlay\"\n aria-label=\"Plus de détails\"\n    title=\"Plus de détails\"\n data-target=\"https://s274-fr.ogame.gameforge.com/game/index.php?page=ajax&amp;component=technologytree&amp;ajax=1&amp;technologyId=2&amp;tab=technologyinformation\"\n   data-overlay-title=\"" . $technologyDatas["buildingName"] . "\"\n  >\n      
                                         ?\n     
                                         </button>\n    
                                          <span class=\"text\">\n        " . $technologyDatas["description"] . "                 </span>\n            </div>\n            \n\n                    </div>\n    </div>\n\n"
],
    "target" => "technologydetails",
            ], 200);
    }
    #[Route('/api/tree/ajax', name: 'app_api_tree', methods:["GET"])]
    public function technology(Request $request): Response
    {
        return $this->render('api/index.html.twig', [
            // 'buildings' => $buildingRepository->findAll(),
        ]);
    }
    #[Route('/api/building/upgrade/{planetBuilding}', name: 'api_building_upgrade', methods: ['POST'])]
    public function upgrade(PlanetBuilding $planetBuilding, EntityManagerInterface $entityManager): Response
    {
        // $id->getBuildingByName('')->get
        $user = $this->getUser();
        $planet = $planetBuilding->getPlanet();
        // $building = $buildingRepository->find($id);


        // if (!$building || $building->getUser() !== $user) {
        //     return $this->json(['success' => false, 'message' => 'Bâtiment introuvable.'], 404);
        // }

        // 1. Calculer le coût du prochain niveau
        // $cost = $building->getNextLevelCost(); // Méthode à créer dans votre entité

        // 2. Vérifier si le joueur a les ressources
        // if (!$user->hasEnoughResources($cost)) {
        //     return $this->json(['success' => false, 'message' => 'Ressources insuffisantes.'], 400);
        // }

        // 3. Déduire les ressources et lancer la construction
        // $user->deductResources($cost);
        
        // $durationInSeconds = $building->calculateUpgradeDuration(); // Ex: 60 secondes
        // $finConstruction = new \DateTime("+{$durationInSeconds} seconds");
        
        // $building->setConstructionEndsAt($finConstruction);
        // $building->setIsBuilding(true);

        // il faudra vérifier si le bâtiment a été débloqué
        $result = $this->buildingService->buildBuilding($planetBuilding, $planet);
        $durationInSeconds = $result["buildingTime"]["buildingTime"];
        $parisTimezone = new \DateTimeZone('Europe/Paris');
        $endTime = new \DateTime("+$durationInSeconds seconds", $parisTimezone);

        $planetBuilding->setConstructionEndsAt($endTime);
        if($result != "pas assez de ressources pour construire"){

            $entityManager->flush();
        }

        return $this->json([
            'success' => $result["msg"] == "construction en cours" ? true : false,
            // 'duration' => $durationInSeconds,
            // 'endsAt' => $finConstruction->getTimestamp() * 1000 // Timestamp en millisecondes pour JS
        ]);
    }
}
