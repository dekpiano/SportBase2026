$('.select2Personnel').select2({
    placeholder: "เลือกตัวเลือก",
    allowClear: true,
  });



//ฟังก์ชันหาอายุ
function calculateAge(thaiDate) {
   // แปลงจาก "02/05/2568" → [02, 05, 2568]
   const [day, month, year] = thaiDate.split('/').map(Number);

   // แปลง พ.ศ. → ค.ศ. (ลบ 543)
   const birthDate = new Date(year - 543, month - 1, day); // เดือนใน JS เริ่มจาก 0

   const today = new Date();
   let age = today.getFullYear() - birthDate.getFullYear();
   const m = today.getMonth() - birthDate.getMonth();

   if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
       age--;
   }

   return age;
}

$(document).on('submit', '#FormPersonnalAdd', function (e) {
    e.preventDefault();

    $.ajax({
        url: "../../../Admin/WorkPerson/Personnel/DB/Insert",
        method: "POST",
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
        success: function (res) {
            console.log(res);
            if (res == 1) {
                Swal.fire(
                    'แจ้งเตือน!', "บันทึกข้อมูลสำเร็จ!",
                    'success'
                )
                Swal.fire({
                    title: 'แจ้งเตือน?',
                    text: "บันทึกข้อมูลสำเร็จ!",
                    icon: 'success'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "../../../Admin/WorkPerson/Personnel";
                    }
                })

            } else if (res.success == false) {
                Swal.fire(
                    'แจ้งเตือน!', "บันทึกข้อมูลไม่สำเร็จ!",
                    'error'
                )
            }
        }
    });
});


$(document).on('submit', '#FormPersonnalUpdateDataPersonnel', function (e) {
    e.preventDefault();
    console.log('555');
    $.ajax({
        url: "../../../../Admin/WorkPerson/Personnel/DB/Update/DataPersonnel",
        method: "POST",
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
        success: function (res) {
            console.log(res);
            if (res == 1) {
                Swal.fire({
                    title: 'แจ้งเตือน?',
                    text: "อัพเดตข้อมูลสำเร็จ!",
                    icon: 'success'
                }).then((result) => {
                    if (result.isConfirmed) {

                    }
                })

            } else if (res.success == false) {
                Swal.fire(
                    'แจ้งเตือน!', "บันทึกข้อมูลไม่สำเร็จ!",
                    'error'
                )
            }
        },
        error: function () {
            alert('ไม่พบข้อมูลบุคลากร');
        }
    });
});

$(document).on('submit', '#FormPersonnalHistory', function (e) {
    e.preventDefault();
    $.ajax({
        url: "../../../../Admin/WorkPerson/Personnel/DB/Update/DataHistory",
        method: "POST",
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
        success: function (res) {
            console.log(res);
            // if (res == 1) {
            //     Swal.fire({
            //         title: 'แจ้งเตือน?',
            //         text: "อัพเดตข้อมูลสำเร็จ!",
            //         icon: 'success'
            //     }).then((result) => {
            //         if (result.isConfirmed) {

            //         }
            //     })

            // } else if (res.success == false) {
            //     Swal.fire(
            //         'แจ้งเตือน!', "บันทึกข้อมูลไม่สำเร็จ!",
            //         'error'
            //     )
            // }
        }
    });
});


$(document).on('change', '#pers_img', function () {

    var fileInput = $('#pers_img')[0].files[0];
    console.log(fileInput);
    
    var formData = new FormData();
    formData.append('file', fileInput);
    formData.append('KeyPresID', $(this).attr('key-persid'));
    //console.log(formData)
    $.ajax({
        url: "../../../../Admin/WorkPerson/Personnel/DB/Update/Img",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        success: function (res) {
            console.log(res);
            
            if (res == 1) {
                Swal.fire(
                    'แจ้งเตือน!', "เปลี่ยนรูปภาพสำเร็จ",
                    'success'
                )
            }
        }
    });
});


function loadPersonnelData(id) {
    $.ajax({
        url: '../../../../Admin/WorkPerson/Personnel/DB/Get/' + id,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#pers_position').val(data[0].pers_position); // ทำให้ "ผู้จัดการ" ถูกเลือก
          
             var index = $('#pers_position').prop('selectedIndex'); // รับลำดับที่เลือก (0-based)
   
             if($('#key_update').val() === 'Update'){

                if (index > 0 && index <= 6) {
                    // แสดง select ถัดไป
                    $('#show_learning').show();
                    $('#show_position').hide();
                    $('#pers_workother_id').removeAttr('required');
                    $('#pers_workother_id').val("");
                } else if (index >= 7) {
                    $('#show_position').show();
                    $('#show_learning').hide();
                    $('#pers_learning').val("");
                }
                
            }

            $('.pers_prefix').val(data[0].pers_prefix);
            $('.pers_firstname').val(data[0].pers_firstname);
            $('.pers_lastname').val(data[0].pers_lastname);
            $('.pers_id_card').val(data[0].pers_id_card);
            $('.pers_britday').val(data[0].pers_britday);
            $('.pers_nationality').val(data[0].pers_nationality);
            $('.pers_race').val(data[0].pers_race);
            $('.pers_religion').val(data[0].pers_religion);
            $('.pers_marital_status').val(data[0].pers_marital_status);
            $('.pers_blood_type').val(data[0].pers_blood_type);
            $('.pers_email').val(data[0].pers_username);
            $('.pers_phone').val(data[0].pers_phone);
            $('.pers_facebook').val(data[0].pers_facebook);
            $('.pers_instagram').val(data[0].pers_instagram);
            $('.pers_youtube').val(data[0].pers_youtube);
            $('.pers_social_links').val(data[0].pers_social_links);
            $('.pers_line').val(data[0].pers_line);
            $('.pers_military_service').val(data[0].pers_military_service);

            $('#pers_age').val(calculateAge(data[0].pers_britday));

            $('.pers_status').val(data[0].pers_status);
            $('.pers_username').val(data[0].pers_username);
            $('.pers_position').val(data[0].pers_position);
            $('.pers_department').val(data[0].pers_department);
            $('.pers_learning').val(data[0].pers_learning);
            $('.pers_academic').val(data[0].pers_academic);
            $('.pers_groupleade').val(data[0].pers_groupleade);
            $('#pers_workother_id').val(data[0].work_id).trigger('change');

             //$('#pers_workother_id').append(new Option(data[0].work_name, data[0].work_name, true, true)).trigger('change');
            //console.log(data[0].pers_workother_id);
           
            // ที่อยู่ตามทะเบียนบ้าน
            $('#addr_house_no').val(data[0].addr_house_no);
            $('#addr_moo').val(data[0].addr_moo); 
            $('#addr_village').val(data[0].addr_village);
            $('#addr_soi').val(data[0].addr_soi);
            $('#addr_road').val(data[0].addr_road);            
            $('#addr_postcode').val(data[0].addr_postcode);
            $('.province').append(new Option(data[0].addr_province, data[0].addr_province, true, true)).trigger('change');
            $('.district').append(new Option(data[0].addr_district, data[0].addr_district, true, true)).trigger('change');
            $('.subdistrict').append(new Option(data[0].addr_subdistrict, data[0].addr_subdistrict, true, true)).trigger('change');
 
            // ที่อยู่ปัจจุบัน
            if(data[1]){
            $('#curr_addr_house_no').val(data[1].addr_house_no);
            $('#curr_addr_moo').val(data[1].addr_moo);
            $('#curr_addr_village').val(data[1].addr_village);
            $('#curr_addr_soi').val(data[1].addr_soi);
            $('#curr_addr_road').val(data[1].addr_road);
            $('#curr_addr_postcode').val(data[1].addr_postcode);
            $('.curr_province').append(new Option(data[1].addr_province, data[1].addr_province, true, true)).trigger('change');
            $('.curr_district').append(new Option(data[1].addr_district, data[0].addr_district, true, true)).trigger('change');
            $('.curr_subdistrict').append(new Option(data[1].addr_subdistrict, data[1].addr_subdistrict, true, true)).trigger('change');
            }

            

                
        
        },
        error: function () {
            alert('ไม่พบข้อมูลบุคลากร');
        }
    });
}

function showSaveStatus(message, color = '#333') {
    const statusBox = document.getElementById('save-status');
    statusBox.style.color = color;
    statusBox.textContent = message;
    statusBox.style.display = 'block';

    // ซ่อนหลัง 2 วิ (ถ้าต้องการ)
    if (message !== 'กำลังบันทึก...') {
        setTimeout(() => statusBox.style.display = 'none', 2000);
    }
}

$('#pers_id_card').on('input', function () {
    const value = $(this).val();
    const raw = value.replace(/-/g, '');

    if (raw.length < 13) {
        $('#cid-error')
            .text('กรุณากรอกเลขบัตรให้ครบ 13 หลัก')
            .css('color', 'orange')
            .show();
            return;
    } else if (raw.length === 13) {
        if (isValidThaiID(value)) {
            $('#cid-error')
                .text('✅ เลขบัตรประชาชนถูกต้อง')
                .css('color', 'green')
                .show();
        } else {
            $('#cid-error')
                .text('❌ เลขบัตรประชาชนไม่ถูกต้อง')
                .css('color', 'red')
                .show();
                return;
        }
    }

});

$('.auto-save').on('blur change', function () {
    let PresID = $('#pers_id').val();
    let field = $(this).attr('name');
    let value = $(this).val();

    if(field === 'pers_id_card'){
        const raw = value.replace(/-/g, '');
        if (raw.length < 13) {
            $('#cid-error')
                .text('กรุณากรอกเลขบัตรให้ครบ 13 หลัก')
                .css('color', 'orange')
                .show();
                return;
        } else if (raw.length === 13) {
            if (isValidThaiID(value)) {
                $('#cid-error')
                    .text('✅ เลขบัตรประชาชนถูกต้อง')
                    .css('color', 'green')
                    .show();
            } else {
                $('#cid-error')
                    .text('❌ เลขบัตรประชาชนไม่ถูกต้อง')
                    .css('color', 'red')
                    .show();
                    return;
            }
        }
    }

    if("pers_britday" == $(this).attr('name')){
        $('#pers_age').val(calculateAge(value));  
    }
    $.ajax({
        url: '../../../../Admin/WorkPerson/Personnel/DB/Update/Alone',
        type: 'POST',
        data: {
            PresID: PresID,
            field: field,
            value: value
        },
        success: function (data) {
            
            if(data > 0){
                showSaveStatus('บันทึกแล้ว ✅', 'green')
            }else{
                showSaveStatus('❌ บันทึกล้มเหลว', 'red')
            }
            
        }
    });

});
const pathSegments = window.location.pathname.split('/');
const lastSegment = pathSegments[pathSegments.length - 1];
const matches = lastSegment.match(/^pers_(\d+)$/);
//console.log(matches[0]);
if (matches) {
    const id = matches[0];
    loadPersonnelData(id);
}


// ฟังก์ชันคัดลอกที่อยู่เมื่อ checkbox ถูกติ๊ก
$('#same_address').on('change', function () {
    const isChecked = $(this).is(':checked');
    const fields = [
        'house_no', 'moo', 'village', 'soi', 'road',
        'subdistrict', 'district', 'province', 'postcode'
    ];

    fields.forEach(f => {
        const $source = $('#addr_' + f);
        const $target = $('#curr_addr_' + f);

        if (isChecked) {
            const value = $source.is('select') ? $source.find(':selected').val() : $source.val();
            $target.val(value).trigger('change'); // ให้ select2 แสดงผลด้วย
        }
    });
});

// sync แบบ realtime เมื่อบ้านถูกแก้ และ checkbox ถูกติ๊ก
const addrFields = [
    'house_no', 'moo', 'village', 'soi', 'road',
    'subdistrict', 'district', 'province', 'postcode'
];

addrFields.forEach(f => {
    $('#addr_' + f).on('input change', function () {
        if ($('#same_address').is(':checked')) {
            const value = $(this).is('select') ? $(this).find(':selected').val() : $(this).val();
            $('#curr_addr_' + f).val(value).trigger('change');
        }
    });
});



//   ครอบครัว

let familyIndex = 1;

$('#add-family-member').on('click', function () {
    
    familyIndex++;
    const familyTemplate = `
      <div class="row g-3 family-member mb-3">
        <div class="col-md-4 form-floating">
          <input type="text" class="form-control" id="fam_name_${familyIndex}" name="fam_name[]" placeholder="ชื่อ">
          <label for="fam_name_${familyIndex}">ชื่อสมาชิกในครอบครัว</label>
        </div>
        <div class="col-md-4 form-floating">
          <select class="form-select" id="fam_relation_${familyIndex}" name="fam_relation[]" aria-label="ความสัมพันธ์">
            <option value="">เลือก...</option>
            <option value="บิดา">บิดา</option>
            <option value="มารดา">มารดา</option>
            <option value="คู่สมรส">คู่สมรส</option>
            <option value="บุตร">บุตร</option>
          </select>
          <label for="fam_relation_${familyIndex}">ความสัมพันธ์</label>
        </div>
        <div class="col-md-2 form-floating">
          <input type="date" class="form-control selectorEdit" id="fam_birth_date_${familyIndex}" name="fam_birth_date[]" placeholder="วันเกิด">
          <label for="fam_birth_date_${familyIndex}">วันเกิด</label>
        </div>
        <div class="col-md-2 form-floating">
          <div class="d-flex">
            <input type="text" class="form-control" id="fam_occupation_${familyIndex}" name="fam_occupation[]" placeholder="อาชีพ">
            <button type="button" class="btn btn-danger ms-2" onclick="this.closest('.family-member').remove()">ลบ</button>
          </div>
        </div>
      </div>`;
    $('#family-section').append(familyTemplate);
     selectorEdit();
});




 // เมื่อมีการเปลี่ยนแปลงค่าของ select แรก
 $('#pers_position').on('change', function() {
    // ถ้าเลือก "แสดง Select ถัดไป"
    var index = $(this).prop('selectedIndex'); // รับลำดับที่เลือก (0-based)
 
   
    if($('#key_update').val() === 'Update'){
        var urlUpdate = '../../../../Admin/WorkPerson/Personnel/DB/Select/GetPositionData';
       
    }else{
        var urlUpdate = '../../../Admin/WorkPerson/Personnel/DB/Select/GetPositionData';
    }

    if (index > 0 && index <= 6) {
        // แสดง select ถัดไป
        $('#show_learning').show();
        $('#show_position').hide();
        $('#pers_workother_id').removeAttr('required');
        $('#pers_workother_id').val("");
    } else if (index >= 7) {
        $('#show_position').show();
        $('#show_learning').hide();
        $('#pers_learning').val("");

        if (selectedPosition !== "") {
            var selectedPosition = $(this).val();
          // alert(selectedPosition);
            $.ajax({
                url: urlUpdate, // URL ที่จะส่งคำขอไปยัง controller
                type: 'POST',
                data: { position_id: selectedPosition },
                success: function(response) {
                   
                    var data = (response);
           
                    var secondSelect = $('#pers_workother_id');
                    secondSelect.empty(); // ล้างค่าเก่า

                    if (data.length > 0) {
                        // เพิ่มข้อมูลใน select
                        secondSelect.append('<option value="">--เลือกข้อมูล--</option>');
                        $.each(data, function(index, item) {
                            secondSelect.append('<option value="' + item.work_id + '">' + item.work_name + '</option>');
                        });
                    } else {
                        secondSelect.append('<option value="">ไม่มีข้อมูล</option>');
                    }
                }
            });
        } else {
            $('#pers_workother_id').empty().append('<option value="">--กรุณาเลือกตำแหน่งก่อน--</option>');
        }

     } else {
        // ซ่อน select ถัดไป
        $('#show_learning').hide();
        $('#show_position').hide();
    }
});


var items = document.querySelector('.sortable');
Sortable.create(items, {
    animation: 150,
    chosenClass: "selected",
    ghostClass: "ghost",
    dragClass: "drag",
    onEnd: () => {
        //console.log('an element was inserted');
    },
    group: "cards",
    store: {
        set: (sortable) => {
            const orden = sortable.toArray();
            localStorage.setItem(sortable.options.group.name, orden.join('|'));
            // console.log(orden);

            $.post("../../../../Admin/WorkPerson/Personnel/DB/SortableTeacher", {
                data: orden
            }, function (data, status) {
                console.log("Data: " + data + "\nStatus: " + status);
            });
        },
        //get list order       
        get: (sortable) => {
            const orden = localStorage.getItem(sortable.options.group.name);
            return orden ? orden.split('|') : [];
            console.log(orden);
        }

    }
});
