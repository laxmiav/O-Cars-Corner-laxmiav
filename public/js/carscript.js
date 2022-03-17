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
  
 //this is for owner-details 
  
    const details = document.querySelector("#viewdetail");

    
    details.addEventListener("click", detail);
  
    function detail(){
  
      let detailToView = document.querySelector(".canview");
      console.log(detailToView);
      detailToView.classList.toggle("canview-on");
      
      return detailToView;
     
    }

   //this is for uploadfile
                $('.custom-file-input').on('change', function(event) {
                    var inputFile = event.currentTarget;
                    $(inputFile).parent()
                        .find('.custom-file-label')
                        .html(inputFile.files[0].name);
                });