$(document).ready(function () {
  // Header Sticky
  $(window).on('scroll', function () {
    if ($(this).scrollTop() > 120) {
      $('.navbar-area').addClass("is-sticky");
    } else {
      $('.navbar-area').removeClass("is-sticky");
    }
  });
  // // side Menu
  // ma5menu({
  //   menu: '.site-menu',
  //   activeClass: 'active',
  //   position: 'right',
  //   closeOnBodyClick: true
  // });
  // Button Hover JS
  $('.default-btn')
    .on('mouseenter', function (e) {
      var parentOffset = $(this).offset(),
        relX = e.pageX - parentOffset.left,
        relY = e.pageY - parentOffset.top;
      $(this).find('span').css({
        top: relY,
        left: relX
      })
    })
    .on('mouseout', function (e) {
      var parentOffset = $(this).offset(),
        relX = e.pageX - parentOffset.left,
        relY = e.pageY - parentOffset.top;
      $(this).find('span').css({
        top: relY,
        left: relX
      })
    });
  // Input Plus & Minus Number JS
  $('.input-counter').each(function () {
    var spinner = jQuery(this),
      input = spinner.find('input[type="number"]'),
      btnUp = spinner.find('.plus-btn'),
      btnDown = spinner.find('.minus-btn'),
      min = input.attr('min'),
      max = input.attr('max');
    btnUp.on('click', function () {
      var oldValue = parseFloat(input.val());
      if (oldValue >= max) {
        var newVal = oldValue;
      } else {
        var newVal = oldValue + 1;
      }
      spinner.find("input").val(newVal);
      spinner.find("input").trigger("change");
    });
    btnDown.on('click', function () {
      var oldValue = parseFloat(input.val());
      if (oldValue <= min) {
        var newVal = oldValue;
      } else {
        var newVal = oldValue - 1;
      }
      spinner.find("input").val(newVal);
      spinner.find("input").trigger("change");
    });
  });
  // Go to Top JS
  $(window).on('scroll', function () {
    var scrolled = $(window).scrollTop();
    if (scrolled > 600) $('.go-top').addClass('active');
    if (scrolled < 600) $('.go-top').removeClass('active');
  });
  // Products Filter Options
  $(".icon-view-two").on("click", function (e) {
    e.preventDefault();
    document.getElementById("products-collections-filter").classList.add('products-col-two')
    document.getElementById("products-collections-filter").classList.remove('products-col-one', 'products-col-three', 'products-col-four', 'products-row-view');
  });
  $(".icon-view-three").on("click", function (e) {
    e.preventDefault();
    document.getElementById("products-collections-filter").classList.add('products-col-three')
    document.getElementById("products-collections-filter").classList.remove('products-col-one', 'products-col-two', 'products-col-four', 'products-row-view');
  });
  $('.products-filter-options .view-column a').on('click', function () {
    $('.view-column a').removeClass("active");
    $(this).addClass("active");
  });
  // spinner
  jQuery(window).on('load', function () {
    $('.spinner').fadeOut()
  })
  //MainSlider
  var swiper = new Swiper('.MainSlider-container', {
    spaceBetween: 0,
    centeredSlides: true,
    loop: true,
    // effect: 'fade',
    speed: 1000,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    keyboard: {
      enabled: true,
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
  });
  //Categories Slider
  var swiper = new Swiper('.CategoriesSlider', {
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    slidesPerView: 'auto',
    spaceBetween: 5,
    speed: 1000,
    // autoplay: {
    //   delay: 1500,
    //   disableOnInteraction: false,
    // },
  });
  //gallery-thumbs
  var galleryThumbs = new Swiper('.gallery-thumbs', {
    spaceBetween: 10,
    slidesPerView: 4,
    freeMode: true,
    watchSlidesVisibility: true,
    watchSlidesProgress: true,
  });
  var galleryTop = new Swiper('.gallery-top', {
    spaceBetween: 10,
    speed: 1000,
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
    },
    thumbs: {
      swiper: galleryThumbs
    }
  });
  //product details
  var galleryThumbs = new Swiper('.product-details-thumbs', {
    spaceBetween: 10,
    slidesPerView: 4,
    freeMode: true,
    watchSlidesVisibility: true,
    watchSlidesProgress: true,
  });
  var galleryTop = new Swiper('.product-details-top', {
    spaceBetween: 10,
    speed: 1000,
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
    },
    thumbs: {
      swiper: galleryThumbs
    }
  });
  //product slider
  var swiper = new Swiper('.productsSlider', {
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    speed: 1000,
    // autoplay: {
    //   delay: 2500,
    //   disableOnInteraction: false,
    // },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    slidesPerView: 'auto',
    breakpoints: {
      0: {
        slidesPerView: 2,
      },
      576: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 3,
      },
      991: {
        slidesPerView: 4,
      },
    },
    observer: true,
    observeParents: true,
  });
  // New product slider
  var swiper = new Swiper('.newProductsSlider', {
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    speed: 1000,
    // autoplay: {
    //   delay: 2500,
    //   disableOnInteraction: false,
    // },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    slidesPerView: 'auto',
    breakpoints: {
      0: {
        slidesPerView: 2,
      },
      576: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2,
      },
      991: {
        slidesPerView: 3,
      },
    },
    observer: true,
    observeParents: true,
  });
  // partners slider
  var swiper = new Swiper('.partners', {
    slidesPerView: 'auto',
    spaceBetween: 10,
    speed: 2000,
    loop: true,
    autoplay: {
      delay: 3500,
      disableOnInteraction: false,
    },
  });
  // Testimonials 
  var swiper = new Swiper('.TestimonialsSliderContainer', {
    speed: 1000,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
    breakpoints: {
      0: {
        slidesPerView: 1,
      },
      640: {
        slidesPerView: 1,
      },
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
      },
    }
  });
  // // change language  
  // var open = false;
  // $('#language').click(function () {
  //   open = !open;
  //   if (open) {
  //     $('#StyleLink').attr('href', 'css/styleEN.css');
  //     $('#BootstrapLink').attr('href', 'css/bootstrap.css');
  //     $('#MDBlink').attr('href', 'css/mdb.min.css');
  //     $('#language').html('<a href="#" >عربي</a>');
  //   } else {
  //     $('#StyleLink').attr('href', 'css/style.css');
  //     $('#BootstrapLink').attr('href', 'css/bootstrap.rtl.css');
  //     $('#MDBlink').attr('href', 'css/mdb.rtl.min.css');
  //     $('#language').html('<a href="#" >English</a>');
  //   }
  // });
  // odometer
  $('.odometer').appear(function (e) {
    var odo = $(".odometer");
    odo.each(function () {
      var countNumber = $(this).attr("data-count");
      $(this).html(countNumber);
    });
  });
  // category filter 
  $(".filterControl").on('click', function () {
    $(".filter").removeClass("hideFilter").addClass("showFilter");
  });
  $(".closeFilter").on('click', function () {
    $(".filter").removeClass("showFilter").addClass("hideFilter");
  });


    // category Users 
    $(".UsersControl").on('click', function () {
      $(".Users").removeClass("hideUsers").addClass("showUsers");
    });
    $(".closeUsers").on('click', function () {
      $(".Users").removeClass("showUsers").addClass("hideUsers");
    });


});
////////////////////////////////////////////////
////////////////////////////////////////////////
////////////////////////////////////////////////
////////////////////////////////////////////////
//////////////////  main  //////////////////////
////////////////////////////////////////////////
////////////////////////////////////////////////
////////////////////////////////////////////////
////////////////////////////////////////////////
$(document).ready(function () {
  // Tooltips Initialization
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })
  // select2
  $('.select2').select2({
    minimumResultsForSearch: -1
  });
  $('.select2-search').select2({});
  // img gallery
  $('[data-fancybox]').fancybox({
    buttons: [
      "zoom",
      // "share",
      "slideShow",
      "fullScreen",
      // "download",
      "thumbs",
      "close"
    ],
    transitionEffect: "slide",
  });
  //wow
  new WOW().init();
  //dropify
  $('.dropify').dropify();
});


