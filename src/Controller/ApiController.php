<?php

namespace App\Controller;

// use App\Entity\Planet;
use App\Repository\PlanetRepository;
use App\Service\BuildingService;
use App\Service\TechnologyService;
use App\Service\VesselService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/game')]
class ApiController extends AbstractController
{
    public function __construct(private BuildingService $buildingService, private TechnologyService $technologyService, private VesselService $vesselService){
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
                $lisElement .= "<li class=\"resource " . $type . " icon sufficient tooltip js_hideTipOnMobile\"\n      data-value=\"" . $resource . "\"\n   aria-label=\"" . $resource . " " . $typeFr . "\"\n     title=\"" . $resource . " " . $typeFr . "\"\n     >\n     " . $resource . "\n        </li>";
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
    #[Route('/api/ajax', name: 'app_api', methods:["GET"])]
    public function index(Request $request, PlanetRepository $planetRepository): Response
    {
        // var_dump($request->getContent());
        // $technologyId = json_decode($request->getContent(), true)["technology"];
        // data-target=\"https://s274-fr.ogame.gameforge.com/game/index.php?page=ajax&amp;component=technologytree&amp;ajax=1&amp;technologyId=2&amp;tab=technologytree\"\n 
        // enlever overlay si souci
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $planetId = $request->query->get('planetId');
        // $type = $request->query->get('type');
        $planet = $planetRepository->findOneBy(["id" => $planetId]);
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
        $energyText = "<li class=\"additional_energy_consumption\">\n   
                                            <strong>" . $energyOrProductionOrNot . "</strong>     \n 
                                               <span class=\"value tooltip\"\n   data-value=\"11\"\n     title=\"\"\n    >11  \n   </span>\n    \n   
                                        </li>\n   ";

        $necesaryText = "Nécessaire pour le niveau " . $technologyDatas["buildingLevel"] + 1; 
        $costText = "Coûts par unité "; 
        $necesaryOrCost = $type == "building" ? $necesaryText : $costText;

        $energyOrNot = $type == "building" ? $energyText : "";

        $dataValueInformation = $type == "building" ? $technologyDatas["buildingLevel"] + 1: $technologyDatas["buildingLevel"]; // pour def et recherche faudra voir les valeurs data-value dans information
        $classInformation = $type == "building" ? "level": "amount";

        // il faut récupérer le niveau du bâtiment sur cette planète
        // $planet = 

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
                                    <div class=\"txt_box\">\n   
                                       <button class=\"details tooltip js_hideTipOnMobile overlay\"\n aria-label=\"Plus de détails\"\n    title=\"Plus de détails\"\n data-target=\"https://s274-fr.ogame.gameforge.com/game/index.php?page=ajax&amp;component=technologytree&amp;ajax=1&amp;technologyId=2&amp;tab=technologyinformation\"\n   data-overlay-title=\"" . $technologyDatas["buildingName"] . "\"\n  >\n      
                                         ?\n     
                                         </button>\n    
                                          <span class=\"text\">\n        Principale ressource pour les installations électroniques et pour les alliages.                 </span>\n            </div>\n            \n\n                    </div>\n    </div>\n\n"
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
}
