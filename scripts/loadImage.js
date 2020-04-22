var jsonurl = document.getElementById("json").value;
let requestURL = jsonurl;
let request = new XMLHttpRequest();
request.open('GET', requestURL);
request.responseType = 'json';
request.send();

request.onload = function() { 
  const imageSequance = request.response;
  showImage(imageSequance);
}

const section = document.getElementById("slides");

function showImage(jsonObj) {
  const pages = jsonObj['slide'];

  for(let i = 0; i < pages.length; i++) {
    const myppt = document.createElement('img');
    myppt.src = pages[i].src;

    section.appendChild(myppt);
  }
}