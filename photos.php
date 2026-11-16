<?php
    $page_title = "Photos";
    $section = "Media";
    include ("shared/header.php");
?>
<script src="js/gallery.js"></script>
<main class="media-page photo-page">
	<header class="media-page-heading">
		<h1>Photos</h1>
	</header>

	<section class="photo-experience" aria-label="Cycling event photo gallery">
		<div class="gallery-stage">
			<img id="slider" src="images/gallery/img-1.jpg" alt="Cycling event photo" />
			<div class="gallery-controls">
				<button type="button" class="prev" aria-label="Previous photo" onclick="prev()">Previous</button>
				<button type="button" class="next" aria-label="Next photo" onclick="next()">Next</button>
			</div>
		</div>

		<div class="photos photo-gallery" aria-label="Photo thumbnails">
			<?php for ($image_number = 1; $image_number <= 21; $image_number++) { ?>
				<button type="button" class="photo-thumbnail" aria-label="Show photo <?php echo $image_number; ?>" onclick="showImg(this.querySelector('img'))">
					<img src="images/gallery/img-<?php echo $image_number; ?>.jpg" alt="" />
				</button>
			<?php } ?>
		</div>
	</section>
</main>

<?php
    include ("shared/footer.php");
?>