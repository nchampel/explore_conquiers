<?php

namespace App\Controller;

use App\Entity\Planet;
use App\Service\TechonologyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/game')]
class ApiController extends AbstractController
{
    public function __construct(private TechonologyService $techonologyService, private Planet $planet) {
    }
    #[Route('/api/ajax', name: 'app_api', methods:["GET"])]
    public function index(Request $request): Response
    {
        // var_dump($request->getContent());
        // $technologyId = json_decode($request->getContent(), true)["technology"];
        // data-target=\"https://s274-fr.ogame.gameforge.com/game/index.php?page=ajax&amp;component=technologytree&amp;ajax=1&amp;technologyId=2&amp;tab=technologytree\"\n 
        // enlever overlay si souci
        $technologyId = $request->query->get('technology');
        $technologiesData = $this->techonologyService->getTechonologiesSupplies($technologyId);

        // il faut récupérer le niveau du bâtiment sur cette planète
        // $planet = 

        return $this->json([
    "content" => [
        "technologydetails" => "    <div id=\"technologydetails\" data-technology-id=\"" . $technologyId . "\">\n
            <div class=\"sprite sprite_large building " . $technologiesData["buildingNameEn"] . "\">\n
             <button 
             class=\"technology_tree no_prerequisites tooltip js_hideTipOnMobile overlay ipiHintable\"\n 
              aria-label=\"Afficher arbre tech\"\n   
              title=\"Aucune condition requise\"\n  
              
              data-target=\"http://localhost:8000/game/api/tree/ajax\"\n 
               data-ipi-hint=\"ipiTechnologyTree" . $technologiesData["buildingNameEn"] . "\"\n  
                >\n  
                 Arbre tech\n      
                  </button>\n  
                  </div>\n   
                     <div class=\"content\">\n        
                         <button class=\"close\">✖</button>\n\n         
                            <h3>" . $technologiesData["buildingName"] . "</h3>\n\n         
                               <div class=\"information\">\n     
                                    <span class=\"level\" data-value=\"1\">\n       Niveau 0\n        </span>\n \n   
                                    <ul class=\"narrow\">\n  
                                        <li class=\"build_duration\">\n       
                                            <strong>Temps de production :</strong>  \n 
                                            <time class=\"value tooltip\"\n  datetime=\"PT3S\"\n  title=\"\"\n  >3s   
                                            <span class=\"bonus\" data-value=\"0\">\n            (-0%)\n        </span>\n    \n   
                                            </time>\n    \n                            
                                        </li>\n           
                                        <li class=\"additional_energy_consumption\">\n   
                                            <strong>Énergie nécessaire:</strong>     \n 
                                               <span class=\"value tooltip\"\n   data-value=\"11\"\n     title=\"\"\n    >11  \n   </span>\n    \n   
                                        </li>\n       
                                    </ul>\n     \n    
                                    <div class=\"costs\">\n 
                                     <p>Nécessaire pour le niveau 1 :</p>\n   
                                      <ul class=\"ipiHintable\" data-ipi-hint=\"\">\n       
                                        <li class=\"resource metal icon sufficient tooltip js_hideTipOnMobile\"\n      data-value=\"48\"\n   aria-label=\"48 Métal\"\n     title=\"48 Métal\"\n     >\n     48\n        </li>
                                        <li class=\"resource crystal icon sufficient tooltip js_hideTipOnMobile\"\n      data-value=\"24\"\n      aria-label=\"24 Cristal\"\n       title=\"24 Cristal\"\n     >\n      24\n    </li>  
                                     </ul>\n   
                                    </div>\n\n                \n      
                                    <div class=\"build-it_wrap\">\n         
                                    <div class=\"ipiHintable\" data-ipi-hint=\"ipiTechnologyUpgrade" . $technologiesData["buildingNameEn"] . "\">\n     
                                         <button class=\"upgrade\"  data-technology=\"2\">\n     
                                           <span class=\"tooltip\" title=\"\">Développer</span>\n     
                                       </button>\n       
                                    </div>\n     
                                    </div>\n     
                                        </div>\n     \n   
                                          </div>\n\n
                                          <div class=\"required\"></div>
                                  <div class=\"description\">\n     \n      \n  
                                    <div class=\"txt_box\">\n   
                                       <button class=\"details tooltip js_hideTipOnMobile overlay\"\n aria-label=\"Plus de détails\"\n    title=\"Plus de détails\"\n data-target=\"https://s274-fr.ogame.gameforge.com/game/index.php?page=ajax&amp;component=technologytree&amp;ajax=1&amp;technologyId=2&amp;tab=technologyinformation\"\n   data-overlay-title=\"Mine de cristal\"\n  >\n      
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
