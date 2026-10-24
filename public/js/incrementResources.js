//////////////////////////// partie venant de la BDD //////////////////////////////////

// Variable globale pour stocker les données
// let resourcesIncrementedDB = null;

// /**
//  * Met à jour l'affichage HTML en front
//  */
// function updateResourcesUI(resources) {
//     if (!resources) return;

//     const metalElement = document.getElementById('resources_metal');
//     if (metalElement) {
//         metalElement.textContent = resources.metal ?? 0;
//     }

//     const crystalElement = document.getElementById('resources_crystal');
//     if (crystalElement) {
//         crystalElement.textContent = resources.crystal ?? 0;
//     }
//     const deuteriumElement = document.getElementById('resources_deuterium');
//     if (deuteriumElement) {
//         deuteriumElement.textContent = resources.deuterium ?? 0;
//     }
// }

// /**
//  * Fonction : récupère et met à jour
//  */
// async function updateResourcesByDB(planetId) {
//     try {
//         // 1. On récupère les données via le service dédié
//         resourcesIncrementedDB = await fetchResources(planetId);
        
//         // 2. On met à jour le front-end
//         updateResourcesUI(resourcesIncrementedDB);
        
//         // console.log("Ressources chargées avec succès :", resourcesIncrementedDB);
//     } catch (error) {
//         console.error("Erreur lors de l'initialisation des ressources :", error);
//     }
// }

// function startResourceAutoUpdate(planetId, intervalMs = 60000) {
//     // Premier appel direct
//     updateResourcesByDB(planetId);

//     // Puis répétition
//     setInterval(() => {
//         updateResourcesByDB(planetId);
//     }, intervalMs);
// }

// /////////////////////// partie pour l'incrémentation sur le front uniquement ////////////////////

//  function incrementResources(productions) {
//     // let productions = await fetchResourcesProductions(planetId);

//     const resourceConfig = {
//         metal: {
//             currentValue : metal,
//             acc: 0,
//             productionByMinute: productions.metal,
//             selector: 'resources_metal'
//         },
//         crystal: {
//             currentValue : crystal,
//             acc: 0,
//             productionByMinute: productions.crystal,
//             selector: 'resources_crystal'
//         },
//         deuterium: {
//             currentValue : deuterium,
//             acc: 0,
//             productionByMinute: productions.deuterium,
//             selector: 'resources_deuterium'
//         },
//         // satellites: {
//         //     productionByMinute: 60,
//         //     selector: '#satellites'
//         // }
//     };
//     let lastTime = performance.now();

// // Boucle de simulation fluide basée sur le temps réel
//     function loopSimulation() {
//         const currentTime = performance.now();
//         const elapsedTimeMs = currentTime - lastTime;
//         lastTime = currentTime;

//         // Sécurité : si l'onglet est resté en arrière-plan très longtemps, 
//         // on évite de calculer un delta gigantesque d'un coup (laissez l'API s'en charger).
//         if (elapsedTimeMs < 5000) {
//             for (const key in resourceConfig) {
//                 const res = resourceConfig[key];
                
//                 // Calcul par milliseconde : (prod/min / 60 secondes / 1000 ms)
//                 const incrementByMs = (res.productionByMinute / 60) / 1000;
                
//                 // On ajoute la production correspondant au temps exact écoulé
//                 res.acc += elapsedTimeMs * incrementByMs;
                
//                 const toBeAdded = Math.floor(res.acc);
//                 if (toBeAdded > 0) {
//                     res.currentValue += toBeAdded;
//                     res.acc -= toBeAdded; // On garde le reste des décimales
                    
//                     // Mise à jour du DOM
//                     const element = document.getElementById(res.selector);
//                     if (element) {
//                         element.textContent = Math.floor(res.currentValue);
//                     }
//                 }
//             }
//         }

//         // On relance la loop pour la frame suivante
//         // requestAnimationFrame(loopSimulation);
//         setTimeout(loopSimulation, 1000); // si vous préférez une mise à jour par seconde
//     }
//     // setTimeout(loopSimulation, 1000);
//         // (Ou setTimeout(boucleDeProduction, 1000) si vous préférez une mise à jour par seconde)
//     loopSimulation();
// }

// async function incrementResourcesByDB(planetId) {
//     try {
//         // 1. On récupère les données via le service dédié
//         resourcesProductionsDB = await fetchResourcesProductions(planetId);
        
//         // 2. 
//         incrementResources(resourcesProductionsDB);
        
//         console.log("Productions chargées avec succès :", resourcesProductionsDB);
//     } catch (error) {
//         console.error("Erreur lors de l'initialisation des productions :", error);
//     }
// }

// let productions = await fetchResourcesProductions(planetId);

// console.log(productions);

// Variable globale pour stocker la configuration et l'état en cours
let resourceConfig = null;
let simulationIntervalId = null;

/**
 * Met à jour l'affichage HTML en front
 */
function updateResourcesUI() {
    if (!resourceConfig) return;

    for (const key in resourceConfig) {
        // console.log('updateResourcesUI');
        // console.log(resourceConfig);
        const res = resourceConfig[key];
        const element = document.getElementById(res.selector);
        if (element) {
            element.textContent = Math.floor(res.currentValue).toLocaleString('fr-FR').replace(/\s/g, ' ');
        }
    }
}

/**
 * Lance la boucle de simulation fluide (toutes les secondes)
 */
function startSimulationLoop() {
    if (simulationIntervalId) clearInterval(simulationIntervalId);

    let lastTime = performance.now();

    simulationIntervalId = setInterval(() => {
        if (!resourceConfig) return;

        const currentTime = performance.now();
        const elapsedTimeMs = currentTime - lastTime;
        lastTime = currentTime;

        if (elapsedTimeMs < 5000 && elapsedTimeMs > 0) {
            for (const key in resourceConfig) {
                const res = resourceConfig[key];
                
                // Calcul par milliseconde (prod/min -> /60 -> /1000)
                const incrementByMs = (res.productionByMinute / 60) / 1000;
                // console.log('incrementByMs');
                // console.log(incrementByMs);

                
                res.acc += elapsedTimeMs * incrementByMs;
                // console.log('res.acc');
                // console.log(res.acc);
                
                const toBeAdded = Math.floor(res.acc);
                // console.log('toBeAdded');
                // console.log(toBeAdded);

                if (toBeAdded > 0) {
                    // console.log('res.currentValue');
                    // console.log(res.currentValue);
                    res.currentValue += toBeAdded;
                    res.acc -= toBeAdded; // On garde le reste des décimales
                }
            }
            updateResourcesUI();
        }
    }, 1000);
}

/**
 * Appel périodique : met juste à jour les valeurs actuelles depuis la BDD
 */
async function syncResourcesFromDB(planetId) {
    try {
        const resourcesFromDB = await fetchResources(planetId);
        if (!resourcesFromDB) return;
        // console.log('resourcesFromDB');
        // console.log(resourcesFromDB);
        // console.log(resourcesFromDB.metal);

        // Si c'est le tout premier appel, on initialise tout (valeurs ET productions fixes)
        if (!resourceConfig) {
            const resourcesProductionsDB = await fetchResourcesProductions(planetId);
            resourceConfig = {
                metal: {
                    currentValue: resourcesFromDB.metal,
                    productionByMinute: resourcesProductionsDB.metal, // Fixé une fois pour toutes
                    acc: 0,
                    selector: 'resources_metal'
                },
                crystal: {
                    currentValue: resourcesFromDB.crystal,
                    productionByMinute: resourcesProductionsDB.crystal,
                    acc: 0,
                    selector: 'resources_crystal'
                },
                deuterium: {
                    currentValue: resourcesFromDB.deuterium,
                    productionByMinute: resourcesProductionsDB.deuterium,
                    acc: 0,
                    selector: 'resources_deuterium'
                }
            };
            // console.log('if (!resourceConfig)');
            // console.log(resourceConfig);
            
            // On lance la simulation front pour la première fois
            startSimulationLoop();
        } else {
            // Pour les appels suivants (toutes les 60s), on met juste à jour le stock 
            // et on reset l'accumulateur pour recaler le front sur le back.
            for (const key in resourcesFromDB) {
                if (key != 'satellites' && resourceConfig[key]) {
                    resourceConfig[key].currentValue = resourcesFromDB[key];
                    resourceConfig[key].acc = 0; 
                }
            }
        }

        updateResourcesUI();

    } catch (error) {
        console.error("Erreur lors de la synchronisation des ressources :", error);
    }
}

/**
 * Point d'entrée pour démarrer l'auto-update
 */
function startResourceAutoUpdate(planetId, intervalMs = 60000) {
    // Premier appel direct (initialisation complète)
    syncResourcesFromDB(planetId);

    // Puis répétition uniquement pour resynchroniser les quantités
    setInterval(() => {
        syncResourcesFromDB(planetId);
    }, intervalMs);
}

