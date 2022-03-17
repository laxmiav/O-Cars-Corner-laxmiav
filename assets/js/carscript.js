function reveal() {
      const reveals = document.querySelectorAll(".reveal");
    
      for (var i = 0; i < reveals.length; i++) {
        let windowHeight = window.innerHeight;
        let elementTop = reveals[i].getBoundingClientRect().top;
        let elementVisible = 150;
    
        if (elementTop < windowHeight - elementVisible) {
          reveals[i].classList.add("active");
        } else {
          reveals[i].classList.remove("active");
        }
      }
    }
    
    window.addEventListener("scroll", reveal);
  
  
  
    const details = document.querySelector("#viewdetail");
    details.addEventListener("click", detail);
  
    function detail(){
  
      let detailToView = document.querySelector(".canview");
      detailToView.classList.toggle("canview");
  
    
  
    }