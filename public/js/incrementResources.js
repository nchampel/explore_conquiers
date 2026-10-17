//////////////////////////// partie venant de la BDD //////////////////////////////////

// Variable globale pour stocker les données
let resourcesIncrementedDB = null;

/**
 * Met à jour l'affichage HTML en front
 */
function updateResourcesUI(resources) {
    if (!resources) return;

    const metalElement = document.getElementById('resources_metal');
    if (metalElement) {
        metalElement.textContent = resources.metal ?? 0;
    }

    const crystalElement = document.getElementById('resources_crystal');
    if (crystalElement) {
        crystalElement.textContent = resources.crystal ?? 0;
    }
    const deuteriumElement = document.getElementById('resources_deuterium');
    if (deuteriumElement) {
        deuteriumElement.textContent = resources.deuterium ?? 0;
    }
}

/**
 * Fonction : récupère et met à jour
 */
async function updateResourcesByDB(planetId) {
    try {
        // 1. On récupère les données via le service dédié
        resourcesIncrementedDB = await fetchResources(planetId);
        
        // 2. On met à jour le front-end
        updateResourcesUI(resourcesIncrementedDB);
        
        // console.log("Ressources chargées avec succès :", resourcesIncrementedDB);
    } catch (error) {
        console.error("Erreur lors de l'initialisation des ressources :", error);
    }
}

function startResourceAutoUpdate(planetId, intervalMs = 60000) {
    // Premier appel direct
    updateResourcesByDB(planetId);

    // Puis répétition
    setInterval(() => {
        updateResourcesByDB(planetId);
    }, intervalMs);
}

/////////////////////// partie pour l'incrémentation sur le front uniquement ////////////////////

function incrementResources(productions){
    console.log(productions);
    const resourceConfig = {
        metal: {
            interval: Math.trunc(productions.metal / 60),
            selector: '#resources_metal'
        },
        crystal: {
            interval: 20,
            selector: '#resources_crystal'
        },
        deuterium: {
            interval: 30,
            selector: '#resources_deuterium'
        },
        // satellites: {
        //     interval: 60,
        //     selector: '#satellites'
        // }
    };
    console.log(resourceConfig);
}

async function incrementResourcesByDB(planetId) {
    try {
        // 1. On récupère les données via le service dédié
        resourcesProductionsDB = await fetchResourcesProductions(planetId);
        
        // 2. 
        incrementResources(resourcesProductionsDB);
        
        console.log("Productions chargées avec succès :", resourcesProductionsDB);
    } catch (error) {
        console.error("Erreur lors de l'initialisation des productions :", error);
    }
}

// let productions = await fetchResourcesProductions(planetId);

// console.log(productions);

