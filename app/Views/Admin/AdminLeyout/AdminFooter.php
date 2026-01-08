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
    <script src="<?=base_url()?>/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="<?=base_url()?>/assets/vendor/libs/popper/popper.js"></script>
    <script src="<?=base_url()?>/assets/vendor/js/bootstrap.js"></script>
    <script src="<?=base_url()?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?=base_url()?>/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->
    <script src="<?=base_url()?>/assets/js/select2.js"></script>

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Vendors JS -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
    <!-- moment -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment-with-locales.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <!-- Main JS -->
    <script src="<?=base_url()?>/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="<?=base_url()?>/assets/js/dashboards-analytics.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/jquery.inputmask.min.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    </body>

    </html>

    <?php if($uri->getSegment(2) == 'WorkPerson') : ?>
    <script src="<?=base_url()?>/assets/js/Admin/AdminPersonnal/AdminPersonnalMain.js?v=16.2"></script>
    <script src="<?=base_url()?>/assets/js/Admin/AdminPersonnal/AdminPersonnelApiProvince.js?v=1.2"></script>
    <?php endif;?>
    <?php if($uri->getSegment(2) == 'SaveAttendance') : ?>
    <script src="<?=base_url()?>/assets/js/Admin/AdminSaveAttendance/AdminSaveAttendance.js?v=1.7"></script>
     <script src="<?=base_url()?>/assets/js/Admin/AdminSaveAttendance/AdminStaffLeaveReport.js?v=1"></script>
     <script src="<?=base_url()?>/assets/js/Admin/AdminSaveAttendance/AdminReportPositionLeave.js?v=1.1"></script>
    <?php endif;?>

    <?php if($uri->getSegment(2) == 'Rloes') : ?>
    <script src="<?=base_url()?>/assets/js/Admin/AdminRoles/AdminRolesMain.js?v=3"></script>
    <?php endif;?>



    <script>
$('#pers_id_card').inputmask('9-9999-99999-99-9');
$('.pers_phone').inputmask('99-9999-9999');



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

function setBuddhistYear(instance) {
    const yearEl = instance.currentYearElement;
    const buddhistYear = parseInt(yearEl.value);
    if (buddhistYear < 2500) {
        yearEl.value = buddhistYear + 543;
    }
}


function selectorEdit() {
    $('.selectorEdit').flatpickr({
        locale: "th",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d/m/Y",
        parseDate: function(dateStr, format) {
            const parts = dateStr.split('/');
            parts[2] = parseInt(parts[2]) - 543;
            return new Date(parts[2], parts[1] - 1, parts[0]);
        },
        formatDate: function(date, format) {
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear() + 543;
            return `${day}/${month}/${year}`;
        },
        onReady: function(selectedDates, dateStr, instance) {
            setTimeout(() => setBuddhistYear(instance), 5);
        },
        onYearChange: function(selectedDates, dateStr, instance) {
            setTimeout(() => setBuddhistYear(instance), 5);
        },
        onMonthChange: function(selectedDates, dateStr, instance) {
            setTimeout(() => setBuddhistYear(instance), 5);
        },
        onOpen: function(selectedDates, dateStr, instance) {
            setTimeout(() => setBuddhistYear(instance), 5);
        }
    });
}
selectorEdit();
    </script>