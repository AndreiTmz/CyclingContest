
var directionsDisplay;
var map;
var customMarkers = [];

function initialize(centerLat, centerLng, originLat, originLng,
    destinationLat, destinationLng, waypoints)
{
    directionsDisplay = new google.maps.DirectionsRenderer({
        suppressMarkers: true
    });

    var myOptions = {
        zoom: 6,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        center: new google.maps.LatLng(centerLat, centerLng)
    };

    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

    directionsDisplay.setMap(map);

    calculateRoute(originLat, originLng, destinationLat, destinationLng, waypoints
    );
}

function calculateRoute(originLat, originLng, destinationLat, destinationLng, waypoints) {
    var mapWayPoints = [];

    waypoints.forEach(function(waypoint) {
        mapWayPoints.push({
            location: new google.maps.LatLng(
                waypoint.lat,
                waypoint.lng
            ),
            stopover: true
        });
    });

    var request = {
        origin: new google.maps.LatLng(originLat, originLng),
        destination: new google.maps.LatLng(destinationLat, destinationLng),
        waypoints: mapWayPoints,
        optimizeWaypoints: false,
        travelMode: google.maps.TravelMode.WALKING
    };

    var directionsService = new google.maps.DirectionsService();

    directionsService.route(request, function(response, status) {

        if (status == google.maps.DirectionsStatus.OK) {

            directionsDisplay.setDirections(response);

            customMarkers.forEach(function(marker) {
                marker.setMap(null);
            });

            customMarkers = [];

            var route = response.routes[0];

            var start = route.legs[0].start_location;
            var finish = route.legs[route.legs.length - 1].end_location;

            var isCircular = Math.abs(start.lat() - finish.lat()) < 0.000001 &&
                             Math.abs(start.lng() - finish.lng()) < 0.000001;

            addCustomMarker(start, isCircular ? "Start / Finish" : "Start"
            );

            route.legs.forEach(function(leg, index) {
                if (index < route.legs.length - 1) {
                    addCustomMarker(leg.end_location, "Checkpoint " + (index + 1)
                    );
                }
            });

            if (!isCircular) {
                addCustomMarker(finish, "Finish");
            }

        } else {
            alert("directions response " + status);
        }

    });
}

function addCustomMarker(position, label) {
    var marker = new google.maps.Marker({
        position: position,
        map: map,
        title: label,
        label: {
            text: label,
            color: "#000",
            fontWeight: "bold",
            fontSize: "12px"
        }
    });

    customMarkers.push(marker);
}
