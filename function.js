function openWindow(id) { 
    if(document.getElementById(id).style.display == 'none' ){
        
        document.getElementById(id).style.display = 'inline'; 
    }
    else {
        document.getElementById(id).style.display = 'none';
   }
} 


function change(TabID) 
{ 
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
    if (page==1)
        document.getElementById('subpageContent').src="experience.html";
    else if (page==2)
        document.getElementById('subpageContent').src="work.html";
    else if (page==3)
        document.getElementById('subpageContent').src="life.html";
}

 