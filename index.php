<?php
/**
 * Created by PhpStorm.
 * User: DONG
 * Date: 15/10/2018
 * Time: 14:05
 * AIzaSyA972MxvD7WSQxQyfrY_zmgsVf_KtDpq4M
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Simple Polylines</title>
    <style>
        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            height: 100%;
        }
        /* Optional: Makes the sample page fill the window. */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
<div id="map"></div>
<script>

    // This example creates a 2-pixel-wide red polyline showing the path of
    // the first trans-Pacific flight between Oakland, CA, and Brisbane,
    // Australia which was made by Charles Kingsford Smith.

    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 14,
            center: {lat: 21.015726666666666666666666667, lng: 105.77689777777777777777777778}
        });

        var flightPlanCoordinates = [
            {lat: 21.015726666666666666666666667, lng: 105.77689777777777777777777778},
            {lat: 21.016435555555555555555555556, lng: 105.77407111111111111111111111},
            {lat: 21.015208888888888888888888889, lng: 105.77355555555555555555555556},
            {lat: 21.015015555555555555555555556, lng: 105.7736},
            {lat: 21.014448888888888888888888889, lng: 105.77428444444444444444444444},
            {lat: 21.014702222222222222222222222, lng: 105.77438222222222222222222222},
            {lat: 21.017764444444444444444444444, lng: 105.77859555555555555555555556},
            {lat: 21.018293333333333333333333333, lng: 105.77953777777777777777777778},
            {lat: 21.017724444444444444444444444, lng: 105.78064888888888888888888889}
        ];
        var flightPath = new google.maps.Polyline({
            path: flightPlanCoordinates,
            geodesic: true,
            strokeColor: '#005792',
            strokeOpacity: 1.0,
            strokeWeight: 2
        });
        flightPath.setMap(map);

        var beachMarker = new google.maps.Marker({
            position: {lat: 21.017724444444444444444444444, lng: 105.78064888888888888888888889},
            map: map,
            icon: 'images/car.png'
        });


    }
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA972MxvD7WSQxQyfrY_zmgsVf_KtDpq4M&callback=initMap">
</script>
</body>
</html>