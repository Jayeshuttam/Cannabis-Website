<?php

class Gallery
{
    public function GalleryPage()
    {
        $displayGallery=new Webpage();
        $displayGallery->title="Gallery";
        $displayGallery->activeMenu='gallery';
        $displayGallery->extraCss='css/galary.css';
        $displayGallery->content=<<<HTML
        <!--   Gallery HTML Starts Here   --> 
        <div class="wrapper">
            <div class="section-contact">
                <div class="container">
                    <div class="gallery">
                        <figure class="gallery_item gallery_item--1">
                            <img src="img/galary/image-1.png" alt="Gallery image 1" class="gallery__img">
                        </figure>
                        <figure class="gallery_item gallery_item--2">
                            <img src="img/galary/image-2.png" alt="Gallery image 2" class="gallery__img">
                        </figure>
                        <figure class="gallery_item gallery_item--3">
                            <img src="img/galary/image-3.png" alt="Gallery image 3" class="gallery__img">
                        </figure>
                        <figure class="gallery_item gallery_item--4">
                            <img src="img/galary/image-4.png" alt="Gallery image 4" class="gallery__img">
                        </figure>
                        <figure class="gallery_item gallery_item--5">
                            <img src="img/galary/image-5.png" alt="Gallery image 5" class="gallery__img">
                        </figure>
                        <figure class="gallery_item gallery_item--6">
                            <img src="img/galary/image-6.png" alt="Gallery image 6" class="gallery__img">
                        </figure>
                        <figure class="gallery_item gallery_item--7">
                            <img src="img/galary/image-7.png" alt="Gallery image 7" class="gallery__img">
                        </figure>
                        <figure class="gallery_item gallery_item--8">
                            <img src="img/galary/image-8.png" alt="Gallery image 8" class="gallery__img">
                        </figure>
                        <figure class="gallery_item gallery_item--9">
                            <img src="img/galary/image-9.png" alt="Gallery image 9" class="gallery__img">
                        </figure>
                        <figure class="gallery_item gallery_item--10">
                            <img src="img/galary/image-10.jpeg" alt="Gallery image 10" class="gallery__img">
                        </figure>
                        <figure class="gallery_item gallery_item--11">
                            <img src="img/galary/image-11.jpg" alt="Gallery image 11" class="gallery__img">
                        </figure>
                        <figure class="gallery_item gallery_item--12">
                            <img src="img/galary/image-12.png" alt="Gallery image 12" class="gallery__img">
                        </figure>
                        <figure class="gallery_item gallery_item--13">
                            <img src="img/galary/image-13.jpeg" alt="Gallery image 13" class="gallery__img">
                        </figure>
                        <figure class="gallery_item gallery_item--14">
                            <img src="img/galary/image-14.png" alt="Gallery image 14" class="gallery__img">
                        </figure>
                        <figure class="gallery_item gallery_item--15">
                            <img src="img/galary/image-15.jpg" alt="Gallery image 15" class="gallery__img">
                        </figure>
                        <figure class="gallery_item gallery_item--16">
                            <img src="img/galary/image-16.jpg" alt="Gallery image 16" class="gallery__img">
                        </figure>
                        <figure class="gallery_item gallery_item--17">
                            <img src="img/galary/image-17.png" alt="Gallery image 17" class="gallery__img">
                        </figure>
                        <figure class="gallery_item gallery_item--18">
                            <img src="img/galary/image-18.png" alt="Gallery image 18" class="gallery__img">
                        </figure>
                    </div>
                </div>
            </div>
        </div>
        HTML;
        $displayGallery->render();
    }
}