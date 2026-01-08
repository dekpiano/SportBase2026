$('.select2Rloes').select2({
    placeholder: "เลือกตัวเลือก",
    allowClear: true
  });

$(document).on("change", ".SettingPersonnelRloes", function() {

    console.log($(this).val());
    console.log($(this).attr('rloes-id'));

    $.post("../../Admin/Rloes/RloesSettingManager", {
        TeachID: $(this).val(),
        RloesID: $(this).attr('rloes-id'),
        Keytype: $(this).attr('Key-nanetype')
    }, function(data, status) {
        if (data == 1) {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'เป็นผู้ใช้งานเรียบร้อย',
                showConfirmButton: false,
                timer: 1500
            })
        } else {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'เปลี่ยนแปลงข้อมูลไม่สำเร็จ',
                showConfirmButton: false,
                timer: 1500
            })
        }
    });
});