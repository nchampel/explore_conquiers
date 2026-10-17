let baseUrl = 'http://localhost:8000'
async function fetchResources(planet) {
    try {
        const url = baseUrl + '/game/api/resources/get';
        const data = await post(url, {"planet": planet});
        // console.log("Ressources récupérées :", data);
        return data;
    } catch (error) {
        // Gestion de l'erreur si besoin
    }
}
async function fetchResourcesProductions(planet) {
    try {
        const url = baseUrl + '/game/api/resources/production/get';
        const data = await post(url, {"planet": planet});
        // console.log("Productions récupérées :", data);
        return data.resourcesProductions;
    } catch (error) {
        // Gestion de l'erreur si besoin
    }
}