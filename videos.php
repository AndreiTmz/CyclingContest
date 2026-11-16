<?php
    $page_title= "Videos";
    $section = "Media";
    include ("shared/header.php");
?>
<main class="media-page video-page">
    <header class="media-page-heading">
        <h1>Riding films</h1>
        <p>Mountain bike, downhill, BMX, and trial from the course.</p>
    </header>

    <section class="video-grid" aria-label="Cycling videos">
        <article class="video-card">
            <h2>Mountain bike</h2>
            <video controls preload="metadata" playsinline aria-label="Mountain bike video">
                <source src="videos/MTB.mp4" type="video/mp4">
                Your browser does not support this video format.
            </video>
        </article>
        <article class="video-card">
            <h2>Downhill</h2>
            <video controls preload="metadata" playsinline aria-label="Downhill video">
                <source src="videos/Downhill.mp4" type="video/mp4">
                Your browser does not support this video format.
            </video>
        </article>
        <article class="video-card">
            <h2>BMX</h2>
            <video controls preload="metadata" playsinline aria-label="BMX video">
                <source src="videos/BMX.mp4" type="video/mp4">
                Your browser does not support this video format.
            </video>
        </article>
        <article class="video-card">
            <h2>Trial</h2>
            <video controls preload="metadata" playsinline aria-label="Trial video">
                <source src="videos/Trial.mp4" type="video/mp4">
                Your browser does not support this video format.
            </video>
        </article>
    </section>
</main>

<?php
    include ("shared/footer.php");
?>