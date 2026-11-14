<?php
    $page_title = "Photos";
    $section = "Media";
    include ("shared/header.php");
?>
<script src="js/gallery.js"></script>
<h1 align="center" style="font-style:oblique;">Photos</h1>

<div class="photos">
	<img src="images/gallery/img-1.jpg" width="160px" height="150px" alt="" onclick="showImg(this)"/>
	<img src="images/gallery/img-2.jpg" width="160px" height="150px" alt="" onclick="showImg(this)"/>
	<img src="images/gallery/img-3.jpg" width="160px" height="150px" alt="" onclick="showImg(this)"/>
	<img src="images/gallery/img-4.jpg" width="160px" height="150px" alt="" onclick="showImg(this)"/>
	<img src="images/gallery/img-5.jpg" width="160px" height="150px" alt="" onclick="showImg(this)"/>
	<img src="images/gallery/img-6.jpg" width="160px" height="150px" alt="" onclick="showImg(this)"/>
	<img src="images/gallery/img-7.jpg" width="160px" height="150px" alt="" onclick="showImg(this)"/>
	<img src="images/gallery/img-8.jpg" width="160px" height="150px" alt="" onclick="showImg(this)"/>
	<img src="images/gallery/img-9.jpg" width="160px" height="150px" alt="" onclick="showImg(this)"/>
	<img src="images/gallery/img-10.jpg" width="160px" height="150px" alt="" onclick="showImg(this)"/>
	<img src="images/gallery/img-11.jpg" width="160px" height="150px" alt="" onclick="showImg(this)"/>
	<img src="images/gallery/img-12.jpg" width="160px" height="150px" alt="" onclick="showImg(this)"/>
	<img src="images/gallery/img-13.jpg" width="160px" height="150px" alt="" onclick="showImg(this)"/>
	<img src="images/gallery/img-14.jpg" width="160px" height="150px" alt="" onclick="showImg(this)"/>
	<img src="images/gallery/img-15.jpg" width="160px" height="150px" alt="" onclick="showImg(this)"/>
	<img src="images/gallery/img-16.jpg" width="160px" height="150px" alt="" onclick="showImg(this)"/>
	<img src="images/gallery/img-17.jpg" width="160px" height="150px" alt="" onclick="showImg(this)"/>
	<img src="images/gallery/img-18.jpg" width="160px" height="150px" alt="" onclick="showImg(this)"/>
	<img src="images/gallery/img-19.jpg" width="160px" height="150px" alt="" onclick="showImg(this)"/>
	<img src="images/gallery/img-20.jpg" width="160px" height="150px" alt="" onclick="showImg(this)"/>
	<img src="images/gallery/img-21.jpg" width="160px" height="150px" alt="" onclick="showImg(this)"/>
</div>

<div>
	
	 <button class="prev" onclick="prev()" style="margin-left:25px;"> < Previous </button>
     <img id="slider" width="650px" height="650px"/>
    <button class="next" onclick="next()"> Next > </button>
</div>

<?php
    include ("shared/footer.php");
?>