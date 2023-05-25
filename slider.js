var script = document.createElement('script');
script.src = 'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js';
document.head.appendChild(script);

var swiper = new Swiper(".mySwiper", {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });