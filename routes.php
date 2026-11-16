<?php
    $page_title = "Routes";
    $section = "Media";
    $body_class = 'routes-page';
    include ("shared/db_connect.php");
    include ("shared/header.php");

    $routes_query = "SELECT r.*, r.id as route_id, c.name as category_name FROM routes r
                     JOIN category_route cr ON r.id = cr.route_id
                     JOIN categories c ON cr.category_id = c.id";

    $routes_result = mysqli_query($link, $routes_query);
    $google_maps_api_key = getenv('GOOGLE_MAPS_API_KEY') ?: '';
?>
<?php if ($google_maps_api_key !== '') { ?>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo htmlspecialchars(rawurlencode($google_maps_api_key), ENT_QUOTES, 'UTF-8'); ?>&amp;loading=async"></script>
<script src="js/routes.js"></script>
<?php } ?>

<main class="media-page routes-page">
    <header class="media-page-heading">
        <p class="media-eyebrow">Explore</p>
        <h1>Ride routes</h1>
        <p>Choose a course to see its route and checkpoints.</p>
    </header>

    <?php if ($google_maps_api_key === '') { ?>
        <div class="route-notice" role="status">Route maps are disabled. Set GOOGLE_MAPS_API_KEY in your local .env file to enable them.</div>
    <?php } ?>

    <section class="route-explorer" aria-label="Route explorer">
        <div class="route-selector">
            <h2>Available routes</h2>
            <div class="route-options">
                <?php
                    while ($route = mysqli_fetch_assoc($routes_result)) {
                        $route_id = $route['route_id'];
                        $category_name = $route['category_name'];
                        $center_lat = $route['center_lat'];
                        $center_lng = $route['center_lng'];
                        $origin_lat = $route['origin_lat'];
                        $origin_lng = $route['origin_lng'];
                        $destination_lat = $route['destination_lat'];
                        $destination_lng = $route['destination_lng'];
                        $waypoints = !empty($route['waypoints'])
                            ? json_decode($route['waypoints'], true) : [];

                        $waypoints_json = htmlspecialchars(
                            json_encode($waypoints),
                            ENT_QUOTES,
                            'UTF-8'
                        );
                ?>
                    <button class="route" type="button" <?php echo $google_maps_api_key === '' ? 'disabled' : ''; ?> onclick="initialize(
                        <?php echo $center_lat; ?>, <?php echo $center_lng; ?>,
                        <?php echo $origin_lat; ?>, <?php echo $origin_lng; ?>,
                        <?php echo $destination_lat; ?>, <?php echo $destination_lng; ?>,
                        <?php echo $waypoints_json ?? '[]'; ?>)">
                        <?php echo htmlspecialchars($category_name . " - Route " . $route_id, ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                <?php } ?>
            </div>
        </div>

        <div class="route-map-panel">
            <h2>Course map</h2>
            <div id="map_canvas" aria-label="Selected route map"></div>
        </div>
    </section>
</main>

<?php
    include ("shared/footer.php");
?>