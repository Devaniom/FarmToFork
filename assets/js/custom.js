window.onload = function() {
  AOS.init({
    disable:'mobile',
        easing: 'ease',
         duration: 1000,
      }); 

    // breadcum parallax and sectioon parallax 

    var width=$(window).width();
    if (width > 767) {
      $('.breadcum').parallax_content(); 
      $('.parallax').parallax(); 
    }
  }

$(document).ready(function(argument) {
//active menu navigation
var loc = window.location.pathname;
var page = location.pathname.split('index.html').pop();
$('.mobile-navbar ul.navbar-active li  a[href*="'+page+'"]').addClass('active');
$('.mobile-navbar ul.navbar-nav li  a.project-active[href*="'+page+'"]').addClass('active');

var loc = window.location.pathname;
var page = location.pathname.split('index.html').pop();
if(page=='') {
  page = 'index-2.html';
}

if($('a[href*="'+page+'"]').parent().parent().hasClass('dropdown-menu')) {
  $('a[href*="'+page+'"]').parents('.dropdown').find('a:first').addClass('active');
}
else {
  $('a[href*="'+page+'"]').addClass('active');
}
});


// //cross icon header navigation 

$('#mobile-menu-action').click(function() {
  if($('.mobile-navbar').hasClass('open')) {
    $('.mobile-navbar').removeClass('open');
    $(this).removeClass('active');
  }
  else {
    $('.mobile-navbar').addClass('open');
    $(this).addClass('active');
  }
});

$(document).on('click','.mobile-navbar',function() {
      $('#mobile-menu-action').trigger('click');
    });

// // header shrink js

$(document).on("scroll", function(){
  var width=$(window).width();
  if (width > 767) {
     if($(document).scrollTop() > 100) {
      $("header").addClass("shrink");
    }
    else 
    {
      $("header").removeClass("shrink");
    }

  }
   
});

// <--------------- top arrow --------------------->

    $(document).on("scroll", function() {

        if ($(document).scrollTop() > 2000) {
            $(".top-arrow").show();
        } else {
            $(".top-arrow").hide();
        }
    });

    $(document).on('click','.top-arrow', function() {
    $("html, body").animate({ scrollTop: 0 }, 1000);
    });


// smooth scroll hash click
   var hash=window.location.hash;
    var ele=$('.nav-tabs a[href="'+hash+'"][data-toggle="tab"]');
    var ele_top=$('.tab-content');
    if(ele.length > 0) {
      $(ele).trigger('click');
      $("html, body").animate({ scrollTop: $(ele_top).offset().top - ($('.breadcum').height() - 200) }, 1000);
    }
    

    // home page 

    $(".home-slider").owlCarousel({
  items:1,
  loop:false,
  margin:0,
  nav:true,
   navText: ['<span class="icon-left-arrow2"></span>','<span class="icon-right-arrow1"></span>'],
  dots:false,
  autoplay:true,
  smartSpeed:700,
  responsive:{
    0:{
      items:1,
    },
    600:{
      items:1,
    },
    1000:{
      items:1
    }
  }
});

// background change on mouse hover 
      $('.background-changer').on('mouseover', 'a', function () {
        var background = "url('" + $(this).attr('data-background') + "')";
        setTimeout(function () {
          $('.background-changer').css('background-image', background);
        }, 500);
      });

        // hash change scroll top on same page
        var hash = window.location.hash;
        if(hash!='') {
          $('html, body').animate({
            scrollTop: $(hash).offset().top - ($('header').height() + 200)
          }, 1000);
        }

        // // scroll smooth on same page
        // $( window ).on( 'hashchange', function( e ) { 
        //   var hash = window.location.hash;
        //   if(hash!='') {
        //     $('html, body').animate({
        //       scrollTop: $(hash).offset().top - ($('header').height() - 0)
        //     }, 800);
        //   }
        // });
$('.counter-value').each(function() {
  var $this = $(this),
      countTo = $this.attr('data-count');
  
  $({ countNum: $this.text()}).animate({
    countNum: countTo
  },

  {

    duration: 1000,
    easing:'linear',
    step: function() {
      $this.text(Math.floor(this.countNum));
    },
    complete: function() {
      $this.text(this.countNum);
      
    }

  });  

});

$(".testimonials-slider").owlCarousel({
  items:6,
  loop:false,
  margin:0,
  nav:true,
   navText: ['<span class="icon-left-arrow2"></span>','<span class="icon-right-arrow1"></span>'],
  dots:true,
  autoplay:false,
  smartSpeed:700,
  responsive:{
    0:{
      items:1,
    },
    600:{
      items:1,
    },
    1000:{
      items:1,
    }
  }
});

// 
$(".collaboration-slider").owlCarousel({
  items:6,
  loop:false,
  margin:30,
  nav:true,
   navText: ['<span class="icon-left-arrow2"></span>','<span class="icon-right-arrow1"></span>'],
  dots:false,
  autoplay:false,
  smartSpeed:700,
  responsive:{
    0:{
      items:2,
       margin:10,
    },
    600:{
      items:2,
    },
    1000:{
      items:5,
    }
  }
});

var width=$(window).width();
    if (width > 767) {
      $('.breadcum1').parallax_content();
      $('.parallax').parallax(); 
    };

            
// footer  script

    // mobile tab script
// if ($(window).width() < 767) {
// var hash = window.location.hash;
//     if (hash!='') {
//         $('html, body').animate({
          
//             scrollTop:$(hash).offset().top - ($('header').height() +20)}, 1000);
//     }       
// };

    // mobile tab script
// if ($(window).width() < 992) {
// var hash = window.location.hash;
//     if (hash!='') {
//         $('html, body').animate({
          
//             scrollTop:$(hash).offset().top - ($('header').height() + 50)}, 1000);
//     }       
// };

    jQuery(document).ready(function($) {
    $(".lazy-image ").click(function(event){     
     
        $('html,body').animate({scrollTop:$(this.hash).offset().top - 10}, 1000);
    });
});

// jQuery(document).ready(function($) {
// $(".column a").click(function(event){

// $('html,body').animate({scrollTop:$(this.hash).offset().top - 100}, 1000);
// });
// });

// $('.collapse').on('shown.bs.collapse', function(e) {
//   var $card = $(this).closest('.card');
//   $('html,body').animate({
//     scrollTop: $card.offset().top - 80
//   }, 500);
// });


// career

   $('.infrastructure-slider').owlCarousel({
           loop:true,
           margin:50,
           dots:false,
           stagePadding:130,
           center:true,
            nav:true,
          navText: ['<span class="icon-left-arrow2"></span>','<span class="icon-right-arrow1"></span>'],
            autoplay:true,
            smartSpeed:400,
         // autoplayTimeout:5000,
           responsive:{
            0:{
             items:1,
               stagePadding:0,
             margin:30,
             center:false,
         },
         576:{
             items:1,
              stagePadding:0,
             margin:30,
             center:false,
         },
         768:{
             items:3,
             stagePadding:0,
             margin:30,
             center:false,
         },
         992:{
             items:3,
         },
         1200:{
             items:2,
            }
           }
         });
// partners-slider

         $(".partners-slider").owlCarousel({
  items:5,
  loop:false,
  margin:20,
  nav:true,
   navText: ['<span class="icon-left-arrow2"></span>','<span class="icon-right-arrow1"></span>'],
  dots:false,
  autoplay:true,
  smartSpeed:700,
  responsive:{
    0:{
      items:2,
    },
    600:{
      items:3,
    },
    1000:{
      items:5,
    }
  }
});

// testimonials-slider
$(".testimonials-slider").owlCarousel({
  items:6,
  loop:false,
  margin:0,
  nav:true,
   navText: ['<span class="icon-left-arrow2"></span>','<span class="icon-right-arrow1"></span>'],
  dots:true,
  autoplay:false,
  smartSpeed:700,
  responsive:{
    0:{
      items:1,
    },
    600:{
      items:1,
    },
    1000:{
      items:1,
    }
  }
});

// collaboration-slider

$(".collaboration-slider").owlCarousel({
  items:6,
  loop:false,
  margin:30,
  nav:true,
   navText: ['<span class="icon-left-arrow2"></span>','<span class="icon-right-arrow1"></span>'],
  dots:false,
  autoplay:false,
  smartSpeed:700,
  responsive:{
    0:{
      items:2,
       margin:10,
    },
    600:{
      items:2,
    },
    1000:{
      items:5,
    }
  }
});

// cookies 

 // Create cookie
function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// Delete cookie
function deleteCookie(cname) {
    const d = new Date();
    d.setTime(d.getTime() + (24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=;" + expires + ";path=/";
}

// Read cookie
function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

// Set cookie consent
function acceptCookieConsent(){
    deleteCookie('user_cookie_consent');
    setCookie('user_cookie_consent', 1, 30);
    document.getElementById("cookieNotice").style.display = "none";
}
    

let cookie_consent = getCookie("user_cookie_consent");
if(cookie_consent != ""){
    document.getElementById("cookieNotice").style.display = "none";
}else{
    document.getElementById("cookieNotice").style.display = "block";
}