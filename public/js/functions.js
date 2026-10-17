/**
 * Effectue une requête GET dynamique.
 * @param {string} url - L'URL de l'API.
 * @returns {Promise<any>} - Les données JSON de la réponse.
 */
async function get(url) {
    try {
        const response = await fetch(url);
        
        // Vérifie si la requête a échoué
        if (!response.ok) {
            throw new Error(`Erreur HTTP : ${response.status} ${response.statusText}`);
        }
        
        return await response.json();
    } catch (error) {
        console.error("Erreur lors de la requête GET :", error);
        throw error;
    }
}

/**
 * Effectue une requête POST dynamique.
 * @param {string} url - L'URL de l'API.
 * @param {Object|Array} [body=null] - Les données à envoyer dans le corps (converties automatiquement en JSON).
 * @returns {Promise<any>} - Les données JSON de la réponse.
 */
async function post(url, body = null) {
    try {
        const options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        };

        // Ajoute et convertit le body en JSON s'il est fourni
        if (body !== null) {
            options.body = JSON.stringify(body);
        }

        const response = await fetch(url, options);

        if (!response.ok) {
            throw new Error(`Erreur HTTP : ${response.status} ${response.statusText}`);
        }

        return await response.json();
    } catch (error) {
        console.error("Erreur lors de la requête POST :", error);
        throw error;
    }
}