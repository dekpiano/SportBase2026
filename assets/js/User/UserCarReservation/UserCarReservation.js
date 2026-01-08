
$('#TBShowDataBooking').DataTable({
    responsive: true,
    order: [
        [0, 'desc']
    ],
});
$('#TBShowDataCarBooking').DataTable({
    responsive: true,
    'serverMethod': 'post',
    'ajax': {
        'url': '../CarBooking/DB/DataTable/View'
    },
    order: [
        [1, 'desc']
    ],
    'columns': [
        {
            data: 'car_reserv_status',
            render: function(data, type, row) {
                if(data == "อนุมัติ"){
                    return '<span class="badge rounded-pill bg-success">'+data+'</span>';
                }else if(data == "ไม่อนุมัติ"){
                    return '<span class="badge rounded-pill bg-danger">'+data+'</span>';
                }else{
                    return '<span class="badge rounded-pill bg-warning">'+data+'</span>';
                }
            }
        },
        { data: 'car_reserv_order' },
        { data: 'car_reserv_carID',
            render: function(data, type, row) {
                return '<img class="img-fluid" style="width:150px;" src="../uploads/admin/Car/'+row.car_img+'">' ;
            }
        },
        {
            data: 'car_reserv_carID',
            render: function(data, type, row) {
                return row.car_category+'<br>'+row.car_registration+' '+row.car_province ;
            }
        },
        { data: 'car_reserv_driver',
            render: function(data, type, row) {
                if(data == ''){
                    return '<span class="badge bg-danger">รอเลือกคนขับรถ</span>';
                }else{
                    return data;
                }
                
            }
         },
        {
            data: 'car_reserv_location',
            render: function(data, type, row) {
                return data ;
            }
        },
        { data: 'Date' },
        { data: 'car_reserv_detail' },
        {
            data: 'car_reserv_memberID',
            render: function(data, type, row) {
                return row.Member;
            }
        }
        
    ]
});

$('#TBShowDataCarBookingAdmin').DataTable({
    responsive: true,
    'serverMethod': 'post',
    'ajax': {
        'url': '../../CarBooking/DB/DataTable/Approve/Admin'
    },
    "columnDefs": [
            { "width": "20%", "targets": 4 } 
    ],
    order: [
        [1, 'desc']
    ],
    'columns': [
        { data: 'car_reserv_status',
            render: function(data, type, row) {
                if(data == 'รอตรวจสอบ'){
                    return '<span class="badge bg-warning">'+data+'</span>';
                }else if(data == 'ไม่อนุมัติ'){
                    return '<span class="badge bg-danger">'+data+'</span>';
                }else{
                    return '<span class="badge bg-success">'+data+'</span>';;
                }
                
            }
         },
        { data: 'car_reserv_order' },
        { data: 'car_reserv_carID',
            render: function(data, type, row) {
                return '<img class="img-fluid" style="width:150px;" src="../../uploads/admin/Car/'+row.car_img+'">' ;
            }
        },
        {
            data: 'car_reserv_carID',
            render: function(data, type, row) {
                return row.car_category+'<br>'+row.car_registration+' '+row.car_province ;
            }
        },
        { data: 'car_reserv_driver',
            render: function(data, type, row) {
                if(data == ''){
                    return '<span class="badge bg-warning">รอเลือกคนขับรถ</span>';
                }else{
                    return data;
                }
                
            }
         },
        {
            data: 'car_reserv_location',
            render: function(data, type, row) {
                return data ;
            }
        },
        { data: 'Date' },
        { data: 'car_reserv_detail' },
        {
            data: 'car_reserv_memberID',
            render: function(data, type, row) {
                return row.Member;
            }
        },
        {
            data: 'car_reserv_status',
            render: function(data, type, row) {
                if(row.car_reserv_status != "อนุมัติ"){ var disab = "disabled";}
                return '<div class="btn-group" role="group" aria-label="Basic mixed styles example"> <button type="button" data-bs-toggle="modal" data-bs-target="#ModalApproveAdmin" class="btn btn-primary" id="BtnApproveCarBooking" carbooking-id="' + row.car_reserv_id + '"><i class="bx bx-edit-alt"></i> ดำเนินการ </button> <button type="button" id="BtnClaseBooking" class="btn btn-danger" booking-id="' + row.car_reserv_id + '"><i class="bx bx-x"></i> ยกเลิก</button> <a href="Admin/Print/' + row.car_reserv_id + '" target="_blank"  id="BtnClaseBooking" class="btn btn-info '+disab+'"><i class="bx bxs-printer" ></i> พิมพ์ใบอนุญาต</a>  </div>';
            }
        }
    ]
});

$(document).on('submit', '#FormAddCarReservation', function(e) {
    e.preventDefault();
    $.ajax({
        url: "../../CarBooking/DB/Insert",
        method: "POST",
        data: $(this).serialize(),
        beforeSend: function() {
            $('#BtnSubBooking').html('<div id="spinner" class="spinner-border spinner-border-sm text-white" role="status"></div> <span class="">กำลังบันทึก...</span>');
            $('#BtnSubBooking').addClass("disabled");
        },
        success: function(data) {
            console.log(data);
            if (data > 0) {
                Swal.fire({
                    title: 'แจ้งเตือน?',
                    text: "บันทึกการจองสำเร็จ!",
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'ตกลง!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "../../CarBooking/View";
                    }
                })
            }
            $('#BtnSubBooking').removeClass("disabled");
            $('#spinner').remove();
            $('#BtnSubBooking').html("จอง");
        }
    });
});


$(document).on('click', '#BtnApproveCarBooking', function() {
    $('#carbookingID').val($(this).attr('carbooking-id'));
});

$(document).on('submit', '#FormAppoveCarReservation', function(e) {
    e.preventDefault();
    $.ajax({
        url: "../../CarBooking/DB/AppoveCarReservationAdmin",
        method: "POST",
        data: $(this).serialize(),
        beforeSend: function() {
            $('#BtnSubBooking').html('<div id="spinner" class="spinner-border spinner-border-sm text-white" role="status"></div> <span class="">กำลังบันทึก...</span>');
            $('#BtnSubBooking').addClass("disabled");
        },
        success: function(data) {
            console.log(data);
            if (data > 0) {
                Swal.fire({
                    title: 'แจ้งเตือน?',
                    text: "อนุมัติการจองรถสำเร็จ!",
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'ตกลง!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "../../CarBooking/Approve/Admin";
                    }
                })
            }
            //$('.modal-backdrop').removeClass
            $('#ModalApproveAdmin').modal('hide');
            $('#BtnSubBooking').removeClass("disabled");
            $('#spinner').remove();
            
        }
    });
});



$(document).on('click', '#BtnNoAppoveCarBooking', function(e) {
    e.preventDefault();
    //console.log($('#carbookingID').val());

    $.ajax({
        url: "../../CarBooking/DB/NoAppoveCarReservationAdmin",
        method: "POST",
        data: {carbookingID:$('#carbookingID').val()},
        beforeSend: function() {
            $('#BtnNoAppoveCarBooking').html('<div id="spinner" class="spinner-border spinner-border-sm text-white" role="status"></div> <span class="">กำลังบันทึก...</span>');
            $('#BtnNoAppoveCarBooking').addClass("disabled");
        },
        success: function(data) {
            console.log(data);
            if (data > 0) {
                Swal.fire({
                    title: 'แจ้งเตือน?',
                    text: "ไม่อนุมัติการจองรถสำเร็จ!",
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'ตกลง!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "../../CarBooking/Approve/Admin";
                    }
                })
            }
            $('#ModalApproveAdmin').modal('hide');
            $('#BtnNoAppoveCarBooking').removeClass("disabled");
            $('#spinner').remove();
            $('#BtnNoAppoveCarBooking').html("ไม่อนุมัติ");
        }
    });
});

$(document).on('click', '#BtnCancelBooking', function() {
    //alert($(this).attr('key-id'));
    Swal.fire({
        title: 'ต้องการยกเลิกการจองหรือไม่?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ตกลง'
    }).then((result) => {
        if (result.isConfirmed) {

            $.post('../../Booking/DB/Cancel', { KeyID: $(this).attr('key-id') }, function(data) {
                console.log(data);
                // $('#TBShowDataBooking').DataTable().ajax.reload();
                location.reload(true);
            });
        }
    })
});


$(document).on('change', '#booking_timeStart', function() {
    $.post('../../Booking/DB/CheckDateBooking', {
        booking_dateStart: $('#booking_dateStart').val(),
        booking_timeStart: $('#booking_timeStart').val()
    }, function(data) {
        console.log(data);
        if (data > 0) {
            Swal.fire(
                'กรุณาเลือกใหม่',
                'ช่วงวัน หรือ เวลา มีผู้จองแล้ว!',
                'warning'
            )
            $('#booking_timeStart').val('');
        }

    });
});

$(document).on('change', '#booking_timeEnd', function() {
    $.post('../../Booking/DB/CheckTimeBooking', {
        booking_dateEnd: $('#booking_dateEnd').val(),
        booking_timeEnd: $('#booking_timeEnd').val()
    }, function(data) {
        console.log(data);
        if (data > 0) {
            Swal.fire(
                'กรุณาเลือกใหม่',
                'ช่วงวัน หรือ เวลา มีผู้จองแล้ว!',
                'warning'
            )
            $('#booking_timeEnd').val('');
        }
    });
});


var calendarEl = document.getElementById('calendar');

var calendar = new FullCalendar.Calendar(calendarEl, {
    headerToolbar: {
        left: 'prevYear,prev,next,nextYear today',
        center: 'title',
        right: 'dayGridMonth,dayGridWeek,dayGridDay'
    },
    navLinks: true, // can click day/week names to navigate views
    editable: false,
    locale: 'th',
    eventSources: [{
        events: function(fetchInfo, successCallback, failureCallback) {
            jQuery.ajax({
                url: "Booking/DB/ShowTimeCarBooking",
                type: "POST",
                success: function(res) {
                    var events = [];
                    res.forEach(evt => {
                        if(evt.approved == "รอตรวจสอบ"){
                            var Color = '#ffab00';
                        }else if(evt.approved == "อนุมัติ"){
                            var Color = '#71dd37';
                        }else{
                            var Color = '#ff3e1d';
                        }
                        events.push({
                            id: evt.id,
                            title: evt.title,
                            start: evt.start,
                            end: evt.end,
                            backgroundColor: Color
                        });
                    });
                    successCallback(events);
                },
            });
        },
        eventColor: '#378006',


    }, ],
    initialView: 'dayGridMonth',
    eventClick: function(info) {
        alert("วันที่: " + info.event.start.toLocaleDateString() + "\n"+
                "เวลา: " + info.event.title + "\n");
    }
});
calendar.render();

