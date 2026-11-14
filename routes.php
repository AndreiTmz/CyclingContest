<?php
    $page_title = "Routes";
    $section = "Media";
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
<?php } else { ?>
<p>Route maps are disabled. Set GOOGLE_MAPS_API_KEY in your local .env file to enable them.</p>
<?php } ?>

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
         <button class="route" <?php echo $google_maps_api_key === '' ? 'disabled' : ''; ?> onclick="initialize(
            <?php echo $center_lat; ?>, <?php echo $center_lng; ?>,
            <?php echo $origin_lat; ?>, <?php echo $origin_lng; ?>,
            <?php echo $destination_lat; ?>, <?php echo $destination_lng; ?>,
            <?php echo $waypoints_json ?? '[]'; ?>)">
            <?php echo htmlspecialchars($category_name . " - Route " . $route_id, ENT_QUOTES, 'UTF-8'); ?>
        </button>
<?php
    }
?>

<div id="map_canvas" style="float:left;width:900px;height:700px; padding-left:20px;"></div>

<?php
    include ("shared/footer.php");
?>