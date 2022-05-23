let slideIndexes = {};

function initSlideShow(s){
    slideIndexes[s] = 1;
    showSlides(1,s);
}
//next/previous controls
function plusSlides(n,s) {
    slideIndexes[s] += n;
    showSlides(slideIndexes[s],s);
}

//Thumbnail images controls
/*function currentSlide(n,s,d) {
    slideIndexes[s] = n;
    showSlides(n,s,d);
}*/

function showSlides(n,slideclass) {
    let i;
    let slides = document.getElementsByClassName(slideclass);
    //let dots = document.getElementsByClassName(dotclass);
    if (n > slides.length) {slideIndexes[slideclass] = 1;}
    if(n < 1) {slideIndexes[slideclass] = slides.length;}
    
    for (i = 0; i < slides.length; i++){
        slides[i].style.display = "none";
    }
    
    /*for(i = 0; i < dots.length; i++){
        dots[i].className = dots[i].className.replace(" active", "");
    }*/
    

    slides[slideIndexes[slideclass]-1].style.display = "block";
    //dots[slideIndexes[slideclass]-1].className += " active";
}