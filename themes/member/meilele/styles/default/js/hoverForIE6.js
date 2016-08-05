var WebjxCom = (document.createElement() && document.getElementsByTagName()); 
window.onload = pinballEffect; 
function pinballEffect() 
{ 
    if (!WebjxCom) return; 
    var allElements = document.getElementsByTagName('*'); 
    var originalBackgrounds=new Array(); 
    for (var i=0; i<allElements.length; i++) 
    { 
        if (allElements[i].className.indexOf('item') >= 0) 
        { 
            allElements[i].onmouseover = mouseGoesOver; 
            allElements[i].onmouseout = mouseGoesOut; 
        } 
    } 
} 
function mouseGoesOver() 
{ 
    this.className += " hover"; 
} 
function mouseGoesOut() 
{  
    this.className = 'item';
} 
pinballEffect();
