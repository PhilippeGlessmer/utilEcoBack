async function displayMap(elements, datas) {
    const { geocoder, addresses, directionsService, directionsRenderer } = await init(datas.addresses);

    // Creating MAP
    const mapOptions = {
        zoom: 15,
        center: addresses.end
    };
    const map = new google.maps.Map(elements.map, mapOptions);

    createRoutes(directionsService, directionsRenderer, addresses, map);
}

async function init(addresses){
    const geocoder = new google.maps.Geocoder();

    return new Promise(async (resolve, reject) => {
        try {
            const startAddress = await latLngFromAddress(geocoder, addresses.start);
            document.getElementById('LatLngdepart').innerHTML = 'Lat: '+startAddress.lat+' - long: '+startAddress.lng;
            const endAddress = await latLngFromAddress(geocoder, addresses.end);
            document.getElementById('LatLngArrivee').innerHTML = 'Lat: '+endAddress.lat+' - long: '+endAddress.lng;
            // Creating routes service & renderer
            const directionsService = new google.maps.DirectionsService();
            const rendererOptions = {}
            const directionsRenderer = new google.maps.DirectionsRenderer(rendererOptions);

            resolve({
                map,
                geocoder,
                directionsService,
                directionsRenderer,
                addresses: {
                    start: startAddress,
                    end: endAddress
                }
            })
        } catch(e) {
            reject(e)
        }
    })
}

async function latLngFromAddress(geocoder, address) {
    return new Promise((resolve, reject) => {
        if (typeof address === "object"){
            if (address.hasOwnProperty("lat") && address.hasOwnProperty("lng"))
                resolve(address)
            else
                reject("Invalid address object structure, needs at least 'lat' & 'lng'")
        }else{
            geocoder.geocode({address: encodeURI(address)}, (results, status) => {
                if (status !== "OK")
                    reject(status);
                else
                    resolve({lat: results[0].geometry.location.lat(), lng: results[0].geometry.location.lng()});
            })
        }
    });
}

async function createRoute(directionsService, request) {
    return new Promise((resolve, reject) => {
        directionsService.route(request, (result, status) => {
            if (status !== "OK")
                reject(status)
            else{
                resolve(result)
                document.getElementById('distance').innerHTML = result.routes[0].legs[0].distance.text;
                let distance2 = Math.floor((result.routes[0].legs[0].distance.value * 2) /1000);
                document.getElementById('distanceAr').innerHTML = distance2+ ' Km allé et retour';

                document.getElementById('temps').innerHTML = result.routes[0].legs[0].duration.text;
                duree = result.routes[0].legs[0].duration.value * 2;
                temp2 = conversion_seconde_heure(duree)
                document.getElementById('tempsAr').innerHTML = temp2;

                let km = result.routes[0].legs[0].distance.value/1000;
                let cout = km *0.29;
                //https://calculis.net/cout-km
                document.getElementById('couts').innerHTML = cout.toFixed(2)+' €';
                document.getElementById('coutsAr').innerHTML = (cout*2).toFixed(2)+' € allé et retour';
            }
        })
    })
}

function displayRoute(renderer, route, map) {
    renderer.setDirections(route)
    renderer.setMap(map)
}

async function createRoutes(service, renderer, addresses, map) {
    // Set route from start to end
    try {
        const route = await createRoute(service, {
            origin: addresses.start,
            destination: addresses.end,
            travelMode: 'DRIVING', // Can also be 'BICYCLING', 'TRANSIT' or 'WALKING'
            // drivingOptions: {}, // There's also a 'transitOptions' for transit mode
            unitSystem: google.maps.UnitSystem.METRIC, // Can be 'IMPERIAL'
            provideRouteAlternatives: true, // Display more than ONE route for given origin/destination pair
            avoidFerries: false, // Éviter les bateaux
            avoidHighways: false, // Éviter les autoroutes
            avoidTolls: false, // Éviter les péages
            region: 'fr' // domain extension used for google maps API (https://maps.google.{region})
        })
        displayRoute(renderer, route, map)
    } catch(e) {
        alert(`Getting route failed with error: ${e}`)
    }
}
function conversion_seconde_heure(time) {
    var reste=time;
    var result='';

    var nbJours=Math.floor(reste/(3600*24));
    reste -= nbJours*24*3600;

    var nbHours=Math.floor(reste/3600);
    reste -= nbHours*3600;

    var nbMinutes=Math.floor(reste/60);
    reste -= nbMinutes*60;

    var nbSeconds=reste;

    if (nbJours>0)
        result=result+nbJours+' jour ';

    if (nbHours>0)
        result=result+nbHours+' heure(s) ';

    if (nbMinutes>0)
        result=result+nbMinutes+' minute(s) ';

    if (nbSeconds>0)
        //result=result+nbSeconds+'s ';
        return result;
}
