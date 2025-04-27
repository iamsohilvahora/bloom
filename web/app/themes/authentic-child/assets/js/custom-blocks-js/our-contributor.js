jQuery(document).ready(function($){
    $('.contributor-slide-main').slick({
      dots: true,
      arrows: false,
      infinite: false,
      slidesToShow: 3,
      slidesToScroll: 3,
    //   autoplay: true,
    //   autoplaySpeed: 1000,
      responsive: [
          {
          breakpoint: 991,
          settings: {
              slidesToShow: 2,
              slidesToScroll: 2
          }
          },
          {
          breakpoint: 767,
          settings: {
              slidesToShow: 1,
              slidesToScroll: 1
          }
          }
      ]
  });
});