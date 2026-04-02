<!-- Google Identity Services Script -->
<script src="https://accounts.google.com/gsi/client" async defer></script>

<!-- SweetAlert2 for Error handling -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Layout container -->
<div class="layout-page align-self-center text-center">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y demo">

            <div class="mb-4">
                <div class="w-20 h-20 bg-primary rounded-3xl flex items-center justify-center text-white shadow-lg mx-auto mb-4" style="width: 80px; height: 80px; background-color: #696cff; border-radius: 20px; display: flex; align-items: center; justify-center; color: white;">
                    <i class="bx bx-shield-quarter" style="font-size: 40px;"></i>
                </div>
                <h4 class="mb-2">ยินดีต้อนรับเข้าใช้งานระบบ 👋</h4>
                <p class="text-muted">โปรดเข้าสู่ระบบด้วยบัญชี Google ขององค์กร</p>
            </div>

            <?php 
                $client_id = env('google.clientId') ?: env('GOOGLE_CLIENT_ID');
                $client_id = trim($client_id, "'\""); 
            ?>

            <!-- Google Sign-In Button Container -->
            <div class="d-flex justify-content-center py-3">
                <div id="g_id_onload"
                    data-client_id="<?= $client_id ?>"
                    data-context="signin"
                    data-ux_mode="popup"
                    data-callback="handleCredentialResponse"
                    data-auto_prompt="false">
                </div>
                <div class="g_id_signin"
                    data-type="standard"
                    data-shape="pill"
                    data-theme="outline"
                    data-text="signin_with"
                    data-size="large"
                    data-logo_alignment="left">
                </div>
            </div>

            <div id="error-msg" style="display:none;">
                <?= session()->getFlashdata('Error') ?>
            </div>

        </div>
    </div>
</div>

<script>
    function handleCredentialResponse(response) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= base_url('LoginOfficerSportBase') ?>';

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'credential';
        input.value = response.credential;

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }

    // Handle Flashdata Error
    const errorText = document.getElementById('error-msg').innerText.trim();
    if (errorText) {
        Swal.fire({
            icon: 'error',
            title: 'เข้าสู่ระบบล้มเหลว',
            text: errorText,
            confirmButtonColor: '#696cff'
        });
    }
</script>