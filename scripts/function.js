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


function clearcolor(x){
    document.getElementById(x).className='folderF';
}

function blurcolor(x){
    document.getElementById(x).className='folder';
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
    var tabNum = document.getElementsByClassName(id);
    for (var i=0; i < tabNum.length; i++) {
      tabNum[i].style.display = 'none';  
        var j=i+1;
       document.getElementById('btn' + id + j).removeAttribute("disabled"); 
    }
    document.getElementById(id + n).style.display = 'block';
    document.getElementById('btn' + id + n).setAttribute("disabled", true);

}

function topFunction() {
 //   document.body.scrollTop = 0; // For Safari
//    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    const scrollHeight = window.scrollY,
          scrollStep = Math.PI / ( 20 ),
          cosParameter = scrollHeight / 2;
    var   scrollCount = 0,
          scrollMargin,
          scrollInterval = setInterval( function() {
            if ( window.scrollY != 0 ) {
                scrollCount = scrollCount + 1;  
                scrollMargin = cosParameter - cosParameter * Math.cos( scrollCount * scrollStep);
                window.scrollTo( 0, ( scrollHeight - scrollMargin ) );
            } 
            else clearInterval(scrollInterval); 
        }, 15 );
}

function topiFunction() {
    document.article.scrollTop = 0; // For Safari
    document.article.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}
 
function loadpage(id,pageurl) {
    document.getElementById(id).src = pageurl;
}


function scrollFunction() {
  if (document.body.scrollTop > 40 || document.documentElement.scrollTop > 40) {
    document.getElementById('floatingbutton').style.display = "block";
  } else {
    document.getElementById('floatingbutton').style.display = "none";
  }
}

