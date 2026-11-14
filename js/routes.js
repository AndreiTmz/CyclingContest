
var map;
var mapPolylines = [];
var mapMarkers = [];

async function initialize(
    centerLat, centerLng,
    originLat, originLng,
    destinationLat, destinationLng,
    waypoints
) {
    try {
        // Load the required Google Maps libraries
        const { Map } = await google.maps.importLibrary("maps");
        const { Route } = await google.maps.importLibrary("routes");
        const { AdvancedMarkerElement } = 
            await google.maps.importLibrary("marker");

        // Initialize the map
        map = new Map(document.getElementById("map_canvas"), {
            zoom: 10,
            center: {
                lat: centerLat,
                lng: centerLng
            },
            mapTypeId: "roadmap",
            mapId: "DEMO_MAP_ID"
        });

        // Remove the previous route if one exists
        mapPolylines.forEach(function(polyline) {
            polyline.setMap(null);
        });

        mapPolylines = [];

        // Remove the previous markers if they exist
        mapMarkers.forEach(function(marker) {
            marker.map = null;
        });

        mapMarkers = [];

        // Convert waypoints
        const intermediates = (waypoints || []).map(function(waypoint) {
            return {
                location: {
                    lat: Number(waypoint.lat),
                    lng: Number(waypoint.lng)
                }
            };
        });

        // Create the route request
        const request = {
            origin: {
                lat: originLat,
                lng: originLng
            },
            destination: {
                lat: destinationLat,
                lng: destinationLng
            },
            travelMode: "WALKING",
            intermediates: intermediates,
            optimizeWaypointOrder: false,
            fields: [
                "path",
                "viewport"
            ]
        };

        // Calculate the route
        const { routes } = await Route.computeRoutes(request);

        if (!routes || routes.length === 0) {
            alert("No routes found.");
            return;
        }

        const route = routes[0];

        // Draw the route on the map
        mapPolylines = route.createPolylines();

        mapPolylines.forEach(function(polyline) {
            polyline.setMap(map);
        });

        // Check if route is circular
        const isCircular = Math.abs(Number(originLat) - Number(destinationLat)) < 0.000001 &&
                           Math.abs(Number(originLng) - Number(destinationLng)) < 0.000001;

        // Start marker
        addCustomMarker(
            AdvancedMarkerElement,
            {
                lat: Number(originLat),
                lng: Number(originLng)
            },
            isCircular ? "Start / Finish" : "Start"
        );

        // Checkpoint markers
        intermediates.forEach(function(waypoint, index) {
            addCustomMarker(
                AdvancedMarkerElement,
                waypoint.location,
                "Checkpoint " + (index + 1)
            );
        });

        if (!isCircular) {
            addCustomMarker(
                AdvancedMarkerElement,
                {
                    lat: Number(destinationLat),
                    lng: Number(destinationLng)
                },
                "Finish"
            );
        }

        // Adjust map to show the entire route
        if (route.viewport) {
            map.fitBounds(route.viewport);
        }

    } catch (error) {
        console.error("Google Maps route error:", error);
        alert("Could not calculate the route. Check the console.");
    }
}

function addCustomMarker(AdvancedMarkerElement, position, label) {

    var markerContent = document.createElement("div");

    markerContent.textContent = label;

    markerContent.style.backgroundColor = "#FFFFFF";
    markerContent.style.color = "#222222";
    markerContent.style.fontSize = "12px";
    markerContent.style.fontWeight = "600";
    markerContent.style.fontFamily = "Arial, sans-serif";
    markerContent.style.padding = "7px 12px";
    markerContent.style.borderRadius = "6px";
    markerContent.style.border = "1px solid #CCCCCC";
    markerContent.style.boxShadow = "0 2px 5px rgba(0,0,0,0.25)";
    markerContent.style.whiteSpace = "nowrap";

    var marker = new AdvancedMarkerElement({
        map: map,
        position: position,
        title: label,
        zIndex: 100
    });

    marker.append(markerContent);
    mapMarkers.push(marker);
}