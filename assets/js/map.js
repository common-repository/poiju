(function () {
    // Hack-ish way to decode HTML entities by creating a textarea, setting
    // its content to the encoded string, then getting its value.
    function decodeEntities(encoded) {
        var textarea = document.createElement('textarea');
        textarea.innerHTML = encoded;
        return textarea.value
    }

    // Check if a point of interest (feature) is shown on this page
    // wp_localize_script() turns poijuMapData.currentPage into a string,
    // convert back.
    function isOnCurrentPage(feature) {
        return feature.properties.page === parseInt(poijuMapData.currentPage);
    }

    // Two points constituting the southwest corner and the northeast corner
    var mapBounds = [];
    var points = poijuMapData.geojson.features;
    var map;

    mapboxgl.accessToken = poijuMapData.accessToken;    
    // Only do map setup if the map container (#poiju-map) is present and
    // Mapbox GL is supported
    if (document.getElementById('poiju-map') !== null) {
        if (mapboxgl.supported()) {
            // Initialize mapBounds
            if (points.length > 0) {
                mapBounds = [
                    // wp_localize_script() turns numbers into strings, convert back
                    {lat: parseFloat(points[0].geometry.coordinates[1]), lng: parseFloat(points[0].geometry.coordinates[0])},
                    {lat: parseFloat(points[0].geometry.coordinates[1]), lng: parseFloat(points[0].geometry.coordinates[0])}
                ];
            }

            // Calculate the southwest and the northeast corner of the map
            points.forEach(function (point) {
                // wp_localize_script() turns numbers into strings, convert back
                point.geometry.coordinates[1] = parseFloat(point.geometry.coordinates[1]);
                point.geometry.coordinates[0] = parseFloat(point.geometry.coordinates[0]);

                if (point.geometry.coordinates[1] < mapBounds[0].lat) {
                    mapBounds[0].lat = point.geometry.coordinates[1];
                }
                if (point.geometry.coordinates[1] > mapBounds[1].lat) {
                    mapBounds[1].lat = point.geometry.coordinates[1];
                }
                if (point.geometry.coordinates[0] < mapBounds[0].lng) {
                    mapBounds[0].lng = point.geometry.coordinates[0];
                }
                if (point.geometry.coordinates[0] > mapBounds[1].lng) {
                    mapBounds[1].lng = point.geometry.coordinates[0];
                }
            });

            // Create map
            map = new mapboxgl.Map({
                container: 'poiju-map',
                style: 'mapbox://styles/klumme/cjlbwwd6u5uv82snyfr5ld7a0'})
                .fitBounds(mapBounds, { padding: 40, animate: false, maxZoom: 18 });

            // Add controls if setting selected
            if (poijuMapData.showControls) {
                map.addControl(new mapboxgl.NavigationControl());
            }

            map.on('load', function () {
                map.addSource('points', {
                    type: 'geojson',
                    data: poijuMapData.geojson,
                    cluster: true
                });

                map.addLayer({
                    id: 'clusters',
                    type: 'circle',
                    source: 'points',
                    filter: ['has', 'point_count'],
                    paint: {
                        'circle-color': '#70b0e9',
                        'circle-radius': 18,
                        'circle-stroke-width': 1,
                        'circle-stroke-color': '#ffffff'
                    }
                });

                map.addLayer({
                    id: 'cluster-count',
                    type: 'symbol',
                    source: 'points',
                    filter: ['has', 'point_count'],
                    layout: {
                        'text-field': '{point_count_abbreviated}',
                        'text-size': 12
                    }
                });

                map.addLayer({
                    id: 'markers',
                    type: 'symbol',
                    source: 'points',
                    filter: ['!', ['has', 'point_count']],
                    layout: {
                        'icon-allow-overlap': true,
                        'icon-image': '{icon}',
                        'icon-anchor': 'bottom',
                        'text-field': poijuMapData.showLabels ? '{name}' : '',
                        'text-size': 14,
                        'text-anchor': 'top',
                        'text-offset': [0, 0.25],
                        'text-optional': true
                    },
                    paint: {
                        'text-halo-color': 'white',
                        'text-halo-width': 1
                    }
                });

                map.on('click', 'clusters', function (event) {
                    var feature = map.queryRenderedFeatures(event.point, {layers: ['clusters']})[0];
                    var clusterId = feature.properties.cluster_id;
                    map.getSource('points').getClusterExpansionZoom(clusterId, function (error, zoom) {
                        if (error) { return; }

                        map.easeTo({
                            center: feature.geometry.coordinates,
                            zoom: zoom
                        });
                    });
                });

                map.on('mouseenter', 'clusters', function () {
                    map.getCanvas().style.cursor = 'pointer';
                });
                map.on('mouseleave', 'clusters', function () {
                    map.getCanvas().style.cursor = null;
                });

                map.on('click', 'markers', function (event) {
                    var feature = event.features[0];
                    var coordinates = feature.geometry.coordinates.slice();
                    var url;

                    // Ensure that the popup appears over the copy of the marker
                    // that was clicked. See
                    // https://www.mapbox.com/mapbox-gl-js/example/popup-on-click/.
                    while (Math.abs(event.lngLat.lng - coordinates[0]) > 180) {
                        coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
                    }

                    a = document.createElement('a');
                    a.textContent = decodeEntities(feature.properties.name);

                    // If the feature is on this page, just scroll (smoothly)
                    // down to it.
                    if (isOnCurrentPage(feature)) {
                        a.setAttribute('href', '#' + feature.properties.slug);

                        a.addEventListener('click', function (event) {
                            event.preventDefault();
                            smoothScroll(this);
                        });
                    }
                    else {
                        /**
                         * Using URL is not very backwards-compatible, but it
                         * should be okay here, as map is not supported by old
                         * browsers anyway.
                         */
                        url = new URL(window.location.href);
                        url.hash = feature.properties.slug;
                        url.searchParams.set(
                            poijuMapData.paginationQueryVar,
                            feature.properties.page
                        );

                        a.setAttribute('href', url.href);
                    }

                    new mapboxgl.Popup({closeButton: false})
                        .setLngLat(coordinates)
                        .setDOMContent(a)
                        .addTo(map);
                });

                map.on('mouseenter', 'markers', function () {
                    map.getCanvas().style.cursor = 'pointer';
                });
                map.on('mouseleave', 'markers', function () {
                    map.getCanvas().style.cursor = null;
                });
            });
        }
        else {
            // Hide map container
            document.getElementById('poiju-map').style.display = 'none';
        }
    }

    function getOffsetTop(element) {
        return element.getBoundingClientRect().top + document.body.scrollTop;
    }

    // Duration is given in milliseconds
    function animateScroll(destination, duration) {
        var currentTime = performance.now();
        var elapsed = 0;
        var startingPosition = window.pageYOffset;
        var distance = destination - startingPosition;

        function animationStep(timestamp) {
            if (elapsed > duration) {
                // Make sure we finish the animation in the right position
                window.scrollTo(window.pageXOffset, startingPosition + distance);
                return;
            }

            // Fraction of the duration which has passed
            var fraction = elapsed / duration;
            var factor = (Math.sin(fraction * Math.PI - Math.PI / 2) + 1) / 2;

            window.scrollTo(window.pageXOffset, startingPosition + factor * distance);

            elapsed = elapsed + timestamp - currentTime;
            currentTime = timestamp;

            requestAnimationFrame(animationStep);
        }

        requestAnimationFrame(animationStep);
    }

    function smoothScroll(link) {
        var destination = 0;
        // Cut of the intial "#"
        var hash = link.hash.slice(1);
        var targetElement = document.getElementById(hash);
        var documentHeight = document.documentElement.scrollHeight;
        var windowHeight = window.innerHeight;

        if (getOffsetTop(targetElement) > documentHeight - windowHeight) {
            destination = documentHeight - windowHeight;
        }
     
        else {
            destination = getOffsetTop(targetElement);
        }
     
        animateScroll(destination, 500);
        // Try to focus target, if it fails, set tabindex and try again
        targetElement.focus();
        if (document.activeElement !== targetElement) {
            targetElement.setAttribute('tabindex', '-1');
            targetElement.focus();
        }
        window.history.pushState({}, '', '#' + hash);
    }
})();
