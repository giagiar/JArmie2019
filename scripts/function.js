var slideIndex = 1;
var timer;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n-1);
}

function currentSlide(n) {
  showSlides(slideIndex = n);

}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("top-img");
  var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
  }

  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  if (slideIndex > slides.length) {slideIndex = 1}
  slides[slideIndex-1].style.display = "block"; 
  dots[slideIndex-1].className += " active";
  autoplay();
}

function openlink(n){
    switch(n){
        case 1: 
        window.open("https://www.geeksforgeeks.org", "_top"); 
            break;
        case 2:
        window.open("../subpage.html");
              break;
    default:  break;}
        
}
function autoplay(){
    clearTimeout(timer);
    timer=setTimeout(showSlides, 3000);
    slideIndex++;
}

function closeWindow(id){
    document.getElementById(id).style.display = 'none';
}

function openWindow(id) {
    
    if (document.getElementById(id).style.display === 'none' ){
        document.getElementById(id).style.display = 'inline';
    }
    else {
        document.getElementById(id).style.display = 'none';
   }
        
} 


function change(TabID){
    var nodes = document.getElementById('subtitle').getElementsByTagName('a'); 
    for (var i = 0; i < nodes.length; i++) {
        nodes[i].className = 'btn-none';
    } 
    document.getElementById(TabID).className='btn-none-active';
} 

function Load(Tab){
    if (window.parent['change']){
  window.parent.change(Tab);
 }
}


function clearcolor(x, y){
    document.getElementById(x).style.opacity=1.0;
    document.getElementById(y).className='yellow-back';
}

function blurcolor(x, y){
    document.getElementById(x).style.opacity=0.7;
    document.getElementById(y).className='blue-back';
}


var page;

function contentChoose(pagenum){
    page=pagenum;
}

function content(){
    if (page===1)
        document.getElementById('subpageContent').src="../experience.html";
    else if (page===2)
        document.getElementById('subpageContent').src="../work.html";
    else if (page===3);
}


function changeUI(id,n){
    var tabNum = document.getElementById(id).getElementsByClassName('com');
    for (var i=0; i < tabNum.length; i++) {
      tabNum[i].style.display = 'none';  
    }
    document.getElementById(id + n).style.display = 'block';

}
 