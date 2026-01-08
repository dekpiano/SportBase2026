    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->
    <!-- Footer -->
    <footer class="content-footer footer bg-footer-theme">
        <div class="container-xxl d-flex flex-wrap justify-content-end py-2 flex-md-row flex-column">
            <div class="mb-2 mb-md-0">
                ©
                <script>
                document.write(new Date().getFullYear());
                </script>
                , made with ❤️ by
                <a href="https://facebook.com/dekpiano" target="_blank" class="footer-link fw-bolder">Dekpiano</a>
            </div>

        </div>
        </div>
    </footer>
    <!-- / Footer -->


    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <!-- <script src="<?=base_url()?>/assets/vendor/libs/jquery/jquery.js"></script> -->

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="<?=base_url()?>/assets/vendor/libs/popper/popper.js"></script>
    <script src="<?=base_url()?>/assets/vendor/js/bootstrap.js"></script>
    <script src="<?=base_url()?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="<?=base_url()?>/assets/vendor/js/menu.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    
    <!-- endbuild -->
    <script src="https://hcaptcha.com/1/api.js" async defer></script>

    
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
    <!-- moment -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment-with-locales.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Vendors JS -->
    <script src="<?=base_url()?>/assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="<?=base_url()?>/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="<?=base_url()?>/assets/js/dashboards-analytics.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    </body>

    </html>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.15/index.global.min.js"></script>

    <?php if($uri->getSegment(1) == 'Booking') : ?>       
    <script src="<?=base_url()?>/assets/js/User/UserBooking/UserBooking.js?v=22.5"></script>
    <script src="<?=base_url()?>/assets/js/User/UserBooking/UserBookingSignature.js?v=1.3"></script>
    
    <?php elseif($uri->getSegment(1) == 'Repair') : ?>
    <script src="<?=base_url()?>/assets/js/User/UserRepair/UserRepair.js?v=18"></script>
    <?php elseif($uri->getSegment(1) == 'CarBooking') : ?>
        <script src="<?=base_url()?>/assets/js/User/UserCarReservation/UserCarReservation.js?v=2"></script>
    <?php endif; ?>

    
    <?php if (session()->getFlashdata('Error')): ?>

<?= session()->getFlashdata('Error') ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: '<?= session()->getFlashdata('Error') ?>',
            showConfirmButton: false,
            timer: 3000
        })
    </script>
    <?php endif; ?>

    <script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
})()

$(function() {
    'use strict';

});
    </script>

    <script>
flatpickr.localize(flatpickr.l10ns.th);

$(".selector").flatpickr({
    dateFormat: "Y-m-d",
    altInput: true,
    onChange: (selectedDates, dateStr, instance) => {
        moment.locale('th');
        thai_DM = moment(selectedDates[0]).format('Do MMMM');
        thai_Y = parseInt(moment(selectedDates[0]).format('YYYY')) + 543;
        instance.altInput.value = thai_DM + " " + thai_Y;
    }
});

$(".selectorEdit").flatpickr({
    //dateFormat: "Y-m-d",
    altFormat: "j F Y",
    altInput: true,
    onReady: function (selectedDates, dateStr, instance) {
    // ปรับปีในวันที่ที่ถูกเลือก
    const selectedDate = instance.selectedDates[0];
    if (selectedDate) {
      selectedDate.setFullYear(selectedDate.getFullYear() + 543);
      instance.setDate(selectedDate);
    }
}
});

$(".selectorTime").flatpickr({
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true
});
    </script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
      var lazyLoadImages = document.querySelectorAll('.lazy-load');

      var lazyLoad = function () {
        lazyLoadImages.forEach(function (img) {
          if (img.getBoundingClientRect().top < window.innerHeight && img.dataset.src) {
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
          }
        });
      };

      // Initial load
      lazyLoad();

      // Lazy load on scroll
      document.addEventListener('scroll', lazyLoad);
    });

    
  </script>