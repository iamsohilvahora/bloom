jQuery(document).ready(function($){
  $('.subscriber-left-block').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    fade: true,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 1500,
    speed: 1500,
    asNavFor: '.subscriber-right-block',
  });
  $('.subscriber-right-block').slick({
    slidesToShow: 4,
    slidesToScroll: 4,
    asNavFor: '.subscriber-left-block',
    dots: false,
    centerMode: false,
    focusOnSelect: true
  });

});