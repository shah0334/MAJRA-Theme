AOS.init()

$(document).ready(function() {
  $(".open-box").click(function() {
    $('.pupose-box').find(".close-box").css('display', 'none');
    $('.pupose-box').find(".open-box").css('display', 'block');
    $('.pupose-box').find(".to-show").css('display', 'none');

    console.log($(this).data('video'));

    const videoElement = $(this).closest('.purpose-sec').find('video')[0];
    const sourceElement = $(videoElement).find('source');
    // Change the video source
    sourceElement.attr('src', $(this).data('video'));
    videoElement.load();
    videoElement.play().catch((error) => {
      console.error('Autoplay failed:', error);
    });
    $(this).css('display', 'none');
    $(this).siblings(".close-box").css('display', 'block');
    $(this).siblings(".to-show").slideToggle();
  });
  $(".close-box").click(function() {
    $(this).css('display', 'none');
    $(this).siblings(".open-box").css('display', 'block');
    $(this).siblings(".to-show").slideToggle();
  });
});

var swiper = new Swiper(".mySwiper", {
      slidesPerView: 1,
      spaceBetween: 30,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      breakpoints: {
        640: {
          slidesPerView: 1,
          spaceBetween: 20,
        },
        768: {
          slidesPerView: 2,
          spaceBetween: 40,
        },
        1024: {
          slidesPerView: 5,
          spaceBetween: 50,
        },
      },
});

$('.menu-open').click(function(){
    $('.mobile-nav').addClass('active');
});

$('.menu-close').click(function(){
    $('.mobile-nav').removeClass('active');
});

$('.mobile-nav a').click(function(){
    $('.mobile-nav').removeClass('active');
});

var partners_logos = new Swiper(".winner-logos", {
  slidesPerView: 1,
  spaceBetween: 30,
  loop: true,
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  breakpoints: {
    640: {
      slidesPerView: 1,
      spaceBetween: 20,
    },
    768: {
      slidesPerView: 2,
      spaceBetween: 40,
    },
    1024: {
      slidesPerView: 5,
      spaceBetween: 50,
    },
  },
});


const progressCircle = document.querySelector(".autoplay-progress svg");
const progressContent = document.querySelector(".autoplay-progress span");
var single_slider = new Swiper(".single-swiper-slider", {
  slidesPerView: 1,
  spaceBetween: 30,
  loop: true,
  autoplay: {
    delay: 10000,
    //disableOnInteraction: true,
    pauseOnMouseEnter: true,
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  on: {
    autoplayTimeLeft(s, time, progress) {
      const clampedTime = Math.max(0, time); // Prevent negative values
      progressCircle.style.setProperty("--progress", 1 - progress);
      progressContent.textContent = `${Math.ceil(clampedTime / 1000)}s`;
    }
  }
});