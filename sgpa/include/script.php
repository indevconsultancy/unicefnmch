 <!-- JAVASCRIPT -->
 <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
 <script src="assets/libs/simplebar/simplebar.min.js"></script>
 <script src="assets/libs/node-waves/waves.min.js"></script>
 <script src="assets/libs/feather-icons/feather.min.js"></script>
 <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
 <script src="assets/js/plugins.js"></script>

 <!-- apexcharts -->
 <script src="assets/libs/apexcharts/apexcharts.min.js"></script>

 <!-- Vector map-->
 <script src="assets/libs/jsvectormap/js/jsvectormap.min.js"></script>
 <script src="assets/libs/jsvectormap/maps/world-merc.js"></script>

 <!--Swiper slider js-->
 <!-- <script src="assets/libs/swiper/swiper-bundle.min.js"></script> -->

 <!-- Dashboard init -->
 <script src="assets/js/pages/dashboard-ecommerce.init.js"></script>

 <!-- App js -->
 <script src="assets/js/app.js"></script>
 <script src="https://excelwithtutors.indevconsultancy.in/v1/public/assets/js/jquery-3.5.1.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
 <script>
    $(document).ready(function() {
       $('.patner-carousel').owlCarousel({
          loop: true,
          margin: 10,
          nav: false,
          responsive: {
             0: {
                items: 1
             },
             600: {
                items: 2
             },
             1000: {
                items: 5
             },
             1280: {
                items: 7
             }
          }
       });
    });
 </script>
 <script>
    document.addEventListener("DOMContentLoaded", function() {
       function adjustSectionHeight() {
          // Get the header and footer elements
          var header = document.querySelector(".header");
          var footer = document.querySelector(".footer");
          var fullContent = document.querySelector(".full-content");

          if (header && footer && fullContent) {
             // Get the heights of header and footer
             var headerHeight = header.offsetHeight;
             var footerHeight = footer.offsetHeight;

             // Calculate the min-height for full-content
             var minHeight = "calc(100vh - " + (headerHeight + footerHeight) + "px)";
             fullContent.style.minHeight = minHeight;
          }
       }

       // Run on page load
       adjustSectionHeight();

       // Run on window resize
       window.addEventListener("resize", adjustSectionHeight);
    });
 </script>