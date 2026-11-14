 var images = [
 'images/gallery/img-1.jpg',
 'images/gallery/img-2.jpg',
 'images/gallery/img-3.jpg',
 'images/gallery/img-4.jpg',
 'images/gallery/img-5.jpg',
 'images/gallery/img-6.jpg',
 'images/gallery/img-7.jpg',
 'images/gallery/img-8.jpg',
 'images/gallery/img-9.jpg',
 'images/gallery/img-10.jpg',    
 'images/gallery/img-11.jpg',   
 'images/gallery/img-12.jpg',    
 'images/gallery/img-13.jpg',
 'images/gallery/img-14.jpg',
 'images/gallery/img-15.jpg',
 'images/gallery/img-16.jpg',
 'images/gallery/img-17.jpg',
 'images/gallery/img-18.jpg',
 'images/gallery/img-19.jpg',
 'images/gallery/img-20.jpg',
 'images/gallery/img-21.jpg',
];

var num = 0;

window.onload = function() {
    var firstImage = document.querySelector('.photos img');
    if (firstImage) {
        showImg(firstImage);
    }
}

function next() 
{
    var slider = document.getElementById('slider');  
    num++;

    if(num >= images.length) 
	{  
        num = 0; 
    }
	
    slider.src = images[num];
}

function prev()
{
    var slider = document.getElementById('slider');
    num--;
    
    if(num < 0) {
        num = images.length-1;
    }

    slider.src = images[num];
}

function showImg(imgPrev)
{
    document.getElementById('slider').src = imgPrev.src;
}