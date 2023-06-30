popupWhatsApp = () => {
  
    let btnOpenPopup = document.querySelector('.whatsapp-button');
    
    btnOpenPopup.addEventListener("click",  () => {
      let num = '933301844';
       
     window.open('https://wa.me/51'+num+'?_blank'); 
    
    })
  }
  
  popupWhatsApp();