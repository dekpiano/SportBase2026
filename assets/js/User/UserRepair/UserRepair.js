function toThaiDateString(date) {
    let monthNames = [
        "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน",
        "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม.",
        "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
    ];

    let year = date.getFullYear() + 543;
    let month = monthNames[date.getMonth()];
    let numOfDay = date.getDate();

    let hour = date.getHours().toString().padStart(2, "0");
    let minutes = date.getMinutes().toString().padStart(2, "0");
    let second = date.getSeconds().toString().padStart(2, "0");

    return `${numOfDay} ${month} ${year} ` +
        `${hour}:${minutes}:${second} น.`;
}

$(document).on('change', '#repair_caselist', function() {
    if ($(this).val() === "งานอาคารสถานที่") {
        Swal.fire({
            title: 'แจ้งเตือน?',
            text: "สำหรับงานอาคารสถานที่!... ให้ทำบันทึกข้อความ (ตามนโยบายงานอาคารสถานที่) ที่กลุ่มงานบริหารทั่วไป",
            icon: 'warning',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'ตกลง!'
        }).then((result) => {
            if (result.isConfirmed) {
                // window.location.href = "../Repair/Add";
            }
        })
    }

});

ShowDataLocationRoom();

function ShowDataLocationRoom() {
    $('#TbDataRepair').DataTable({
        responsive: true,
        'processing': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'Repair/DataTable/ShowRepari'
        },
        order: [
            [2, 'desc']
        ],
        'columns': [
            { data: 'repair_datetime' },
            {
                data: 'repair_status',
                render: function(data, type, row) {
                    if (data == "รอดำเนินการ") {
                        return '<span class="badge  bg-label-warning ">' + data + '</span>';
                    } else if (data == "กำลังดำเนินการ") {
                        return '<span class="badge  bg-label-primary loading-text">' + data + '</span>';
                    } else if (data == "ดำเนินการเรียบร้อย") {
                        return '<span class="badge  bg-label-success">' + data + '</span>';
                    } else {
                        return '<span class="badge  bg-label-danger">' + data + '</span>';
                    }

                }
            },
            { data: 'repair_order' },
            { data: 'UserFullname' },
            { data: 'repair_caselist' },

            {
                data: 'repair_ID',
                render: function(data, type, row) {
                    return '<a href="Repair/View/'+row.repair_order+'" data-id="' + row.repair_ID + '" id="BtnRepairFullDetail" class="btn btn-sm btn-outline-primary" >รายละเอียด</a>';
                }
            }
        ]
    });
}

$(document).on('click', '#BtnRepairFullDetail1', function() {
    $('#ModalShowRepair').modal('show');
    $.post('Repair/DB/CheckRepairFullDetail', {
        RepairId: $(this).attr('data-id')
    }, function(data) {

        let datetime = new Date(data[0][0].repair_datetime);
        let dateworIf = new Date(data[0][0].repair_datework);
        let datework;

        if (isNaN(dateworIf)) {
            datework = "";
        } else {
            datework = toThaiDateString(dateworIf);
        }

        $('#show_repair_order').text(data[0][0].repair_order);
        $('#show_repair_caselist').text(data[0][0].repair_caselist);
        $('#show_repair_datetime').text(toThaiDateString(datetime));
        $('#show_repair_detail').text(data[0][0].repair_detail);
        $('#show_repair_location').text(data[0][0].repair_building + ' ชั้น ' + data[0][0].repair_class + ' ห้อง ' + data[0][0].repair_room);
        $('#show_repair_userID').text(data[0][0].pers_prefix + data[0][0].pers_firstname + ' ' + data[0][0].pers_lastname);
        $('#show_repair_posi').text(data[0][0].posi_name);
        $('#show_repair_datework').text(datework);
        $('#show_repair_Repairman').text(data[1][0].pers_prefix + data[1][0].pers_firstname + ' ' + data[1][0].pers_lastname);
        $('#show_repair_cause').text(data[0][0].repair_cause);
        $('#show_repair_status').text(data[0][0].repair_status);

        $('#show_repair_imguser').html('<img src="uploads/admin/Repair/User/' + data[0][0].repair_imguser + '" class="img-fluid" alt="รูปที่ผู้ใช้งานส่งมา">');

        $('#show_repair_imgwork').html('<img src="uploads/admin/Repair/' + data[0][0].repair_imgwork + '" class="img-fluid" alt="รูปที่ทำงาน">');
        $('#show_repair_usersignature').html('<img src="' + data[0][0].repair_usersignature + '" class="img-fluid" alt="รูปลายมือชื่อ">');

        $('#repair_order').val(data[0][0].repair_order);
        $('#repair_cause').val(data[0][0].repair_cause);
        $('#repair_status').val(data[0][0].repair_status);

        $('.PrintOrder').attr('href', "Repair/PrintOrder/" + data[0][0].repair_order);
    }, 'json');

});

$(document).on('change', '#repair_posi', function() {
    $.post('../Repair/DB/CheckPosiUser', {
        repair_posi: $('#repair_posi').val()
    }, function(data) {
        $('#repair_userID > option').remove();
        $.each(data, function(key, val) {
            //console.log(val.pers_firstname);
            var optionElement = $('<option>').attr('value', val.pers_id).text(val.pers_prefix + val.pers_firstname + ' ' + val.pers_lastname);
            // Append the option element to the select element
            //console.log(optionElement);
            $('#repair_userID').append(optionElement);
        });

    }, 'json');
});

$(document).on('submit', '#FormAddRepair', function(e) {
    e.preventDefault();

    
    if (signaturePad.isEmpty()) {
        Swal.fire(
            'แจ้งเตือน!', 'กรุณาลงลายมือชื่อก่อนบันทึก!',
            'warning'
        )
        return;
    }

    var dataURL = signaturePad.toDataURL('image/svg+xml');
    var formData = new FormData(this);
    formData.append('Signature', dataURL); // เพิ่มคีย์และค่าที่ต้องการส่ง

    $.ajax({
        url: "../Repair/DB/Insert",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function() {
            $('#BtnSubRepair').html('<div id="spinner" class="spinner-border spinner-border-sm text-white" role="status"></div> <span class="">กำลังบันทึก...</span>');
            $('#BtnSubRepair').addClass("disabled");
        },
        success: function(data) {
            console.log(data);
            if (data == 1) {
                Swal.fire({
                    title: 'แจ้งเตือน?',
                    text: "บันทึกแจ้งซ่อมสำเร็จ!",
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'ตกลง!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "../Repair";
                    }
                })
            } else if (data == "ErrorSendEmail") {
                Swal.fire(
                    'แจ้งเตือน!', 'ส่ง Email ผืดพลาด!',
                    'error'
                )
            } else if (data == "ErrorhCaptcha") {
                Swal.fire(
                    'แจ้งเตือน!', 'ยืนยันความเป็นมนุษย์ด้วย!',
                    'warning'
                )
            }
            $('#BtnSubRepair').removeClass("disabled");
            $('#spinner').remove();
            $('#BtnSubRepair').html("บันทึกแจ้งซ่อม");
        }
    });
});


$(document).on('click', '#ModalFormAdmin', function() {
    //$(this).css('display', 'none');
    $('#ModalRepairSaveAdmin').modal('show');
    //$('#ModalShowRepair').modal('hide');
});

const canvas = document.getElementById("signature-pad");
const clearBtn = document.getElementById("clear");

const signaturePad = new SignaturePad(canvas, {
    backgroundColor: 'rgb(250,250,250)',
    penColor: 'rgb(0,0,250)'
});

// ปุ่มล้างลายเซ็น
clearBtn.addEventListener("click", () => {
    signaturePad.clear();
});

$(document).on('submit', '#FormSaveRepairAdmin', function(e) {
    e.preventDefault();

    if (signaturePad.isEmpty()) {
        alert("กรุณาเซ็นก่อนบันทึก!");
        return;
    }

    var dataURL = signaturePad.toDataURL('image/svg+xml');
    var formData = new FormData(this);
    formData.append('Signature', dataURL); // เพิ่มคีย์และค่าที่ต้องการส่ง

    $.ajax({
        url: "../../Repair/DB/UpdateWork",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        success: function(res) {
            console.log(res);
            $('#ModalRepairSaveAdmin').hide();
            $('.modal-backdrop').hide();
            if (res == 1) {
                Swal.fire({
                    title: 'แจ้งเตือน?',
                    text: "บันทึกซ่อมสำเร็จ!",
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'ตกลง!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload(true);
                    }
                })

            } else {
                Swal.fire(
                    'แจ้งเตือน!', 'บันทึกขั้อมูลช่างซ่อมไม่สำเร็จ!',
                    'error'
                )
            }
           
        }
    });
});