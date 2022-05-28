/* Slideshow per scorrere le foto nei post */

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



function showSlides(n,slideclass) {
    let i;
    let slides = document.getElementsByClassName(slideclass);
    
    if (n > slides.length) {slideIndexes[slideclass] = 1;}
    if(n < 1) {slideIndexes[slideclass] = slides.length;}
    
    for (i = 0; i < slides.length; i++){
        slides[i].style.display = "none";
    }
    
 
    

    slides[slideIndexes[slideclass]-1].style.display = "block";
    
}