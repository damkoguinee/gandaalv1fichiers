$(document).ready(function(){

    // $(window).scroll(function(){
    //     if ($(window).scrollTop()>200) {             
    //         $("#myBtnScroll").css({display: "block"});             
    //         $("#navjs").css({position: "absolute"});
    //         $(".navmilieu").css({height: "60px"});

    //     }else{
    //         $("#myBtnScroll").css({display: "none"}); 
    //         $("#navjs").css({position: "fixed"});
    //         $(".navmilieu").css({height: "50px"});
    //     }

    // });

    // $("#closPanier").on("click", function(event){            
    //     // $("#panier").css({
    //     //     "width":"30vw",
    //     //     "display":"none",
    //     // }).show().animate({width:"0"}, 1500)          
    //     $("#panier").css({display: "none"}); 

    // });

    
     
        let device=$(window).width();
        
        //let widtHours=$('.hours').width();
        let widtHours=0;
        let width=((device-100)/7);
        let widthTd=width-4;
        $(".wDevice").css({
            "width":width,
        })
        $(".wTd").css({
            "width":widthTd,
        })

        $(window).resize(function () {
            let device=$(window).width();        
            //let widtHours=$('.hours').width();
            let widtHours=0;
            let width=((device-100)/7);
            let widthTd=width-4;
            $(".wDevice").css({
                "width":width,
            })
            $(".wTd").css({
                "width":widthTd,
            })
        });
   

        
     

    // $('.scroll').on('click', function() { // Au clic sur un élément
    //     var page = $("#header"); // Page cible
    //     var speed = 900; // Durée de l'animation (en ms)
    //     $('html, body').animate( { scrollTop: $(page).offset().top }, speed ); // Go
    //     return false;
    // });

        
})

// window.addEventListener('scroll', () => {  
//     let scrollTop = document.documentElement.scrollTop;        
//     document.querySelector('#header').style.width = 100 + scrollTop / 5 + '%';
// });

