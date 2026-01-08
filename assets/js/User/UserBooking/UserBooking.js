
$('#TBShowDataBooking').DataTable({
    responsive: true,
    order: [
        [1, 'desc']
    ],
});
$('#TBShowDataBookingAdmin').DataTable({
    responsive: true,
    'serverMethod': 'post',
    'ajax': {
        'url': '../../Booking/DB/DataTable/Approve/Admin'
    },
    order: [
        [0, 'desc']
    ],
    'columns': [
        { data: 'booking_order' },
        { data: 'booking_title' },
        {
            data: 'location_name',
            render: function(data, type, row) {
                return data + "<br><small>" + row.booking_dateStart + ' ถึง ' + row.booking_dateEnd + '</small>';
            }
        },
        {
            data: 'booker',
            render: function(data, type, row) {
                return data + '<br><small>' + row.booking_telephone + '</small>';
            }
        },
        {
            data: 'booking_admin_approve',
            render: function(data, type, row) {
                if (data === "อนุมัติ") {
                    return '<span class="badge bg-label-success me-1">' + data + '</span>';
                } else {
                    return '<span class="badge bg-label-danger me-1">' + data + '</span>';
                }

            }
        },
        { data: 'booking_admin_reason' },
        {
            data: 'booking_id',
            render: function(data, type, row) {
                return '<div class="btn-group" role="group" aria-label="Basic mixed styles example"> <button type="button" class="btn btn-success ' + (row.booking_admin_approve == "อนุมัติ" ? "disabled" : "") + '" id="BtnApproveBooking" booking-id="' + data + '">อนุมัติ </button> <button type="button" id="BtnNoApproveBooking" class="btn btn-danger" booking-id="' + data + '">ไม่อนุมัติ</button> </div>';
            }
        },
        {
            data: 'booking_id',
            render: function(data, type, row) {
                return '<a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalSignatureAdmin" id="FormSignatureAdmin" data-idBooking="'+data+'">ลายเซ็น</a>';
            }
        },
        {
            data: 'booking_id',
            render: function(data, type, row) {
                return '<a target="_blank" href="../../Booking/Approve/File/Requestform/'+ data +'" class="btn btn-primary">เอกสาร</a>';
            }
        },
    ]
});

$('#TBShowDataBookingExecutive').DataTable({
    responsive: true,
    'serverMethod': 'post',
    'ajax': {
        'url': '../../Booking/DB/DataTable/Approve/Executive'
    },
    order: [
        [0, 'desc']
    ],
    'columns': [
        { data: 'booking_order' },
        { data: 'booking_title' },
        {
            data: 'location_name',
            render: function(data, type, row) {
                return data + "<br><small>" + row.booking_dateStart + ' ถึง ' + row.booking_dateEnd + '</small>';
            }
        },
        {
            data: 'booker',
            render: function(data, type, row) {
                return data + '<br><small>' + row.booking_telephone + '</small>';
            }
        },
        {
            data: 'booking_admin_approve',
            render: function(data, type, row) {
                if (data === "อนุมัติ") {
                    return '<span class="badge bg-label-success me-1"><i class="bx bxs-check-circle"></i>' + data + '</span>';
                } else if (data === "ไม่อนุมัติ") {
                    return '<span class="badge bg-label-danger me-1"><i class="bx bx-x"></i>' + data + '</span> <div> <small>เหตุผล : ' + row.booking_admin_reason + '</small></div>';
                } else {
                    return "";
                }

            }
        },
        {
            data: 'booking_executive_approve',
            render: function(data, type, row) {
                if (data === "อนุมัติ") {
                    return '<span class="badge bg-label-success me-1"><i class="bx bxs-check-circle"></i>' + data + '</span>';
                } else if (data === "ไม่อนุมัติ") {
                    return '<span class="badge bg-label-danger me-1"><i class="bx bx-x"></i>' + data + '</span> <div> <small>เหตุผล : ' + row.booking_executive_reason + '</small></div>';
                } else {
                    return "";
                }

            }
        },
        {
            data: 'booking_id',
            render: function(data, type, row) {
                return '<div class="btn-group" role="group" aria-label="Basic mixed styles example"> <button type="button" class="btn btn-success ' + (row.booking_executive_approve == "อนุมัติ" ? "disabled" : "") + '" id="BtnApproveBooking" booking-id="' + data + '">อนุมัติ </button> <button type="button" id="BtnNoApproveBooking" class="btn btn-danger" booking-id="' + data + '">ไม่อนุมัติ</button> </div>';
            }
        },
        {
            data: 'booking_id',
            render: function(data, type, row) {
                return '<a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalSignatureExecutive" id="FormSignatureExecutive" data-idBooking="'+data+'">ลายเซ็น</a>';
            }
        },
        {
            data: 'booking_id',
            render: function(data, type, row) {
                return '<a target="_blank" href="../../Booking/Approve/File/Requestform/'+ data +'" class="btn btn-primary">เอกสาร</a>';
            }
        },
    ]
});

$(document).on('click', '#BtnApproveBooking', function() {
    let Booking_id = $(this).attr('booking-id');
    Swal.fire({
        title: 'แจ้งเตือน?',
        text: "คุณต้องการอนุมัตการจองนี้หรือไม่!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'ตกลง!'
    }).then((result) => {
        if (result.isConfirmed) {

            $.post('../../Booking/DB/BookingApproveAdmin', { BookingID: Booking_id }, function(data) {
                console.log(data);
                $('#TBShowDataBookingAdmin').DataTable().ajax.reload();
                //location.reload(true);
                Swal.fire("ดำเนินการ อนุมัติเรียบร้อยแล้ว!")
            });

        }
    })
});

$(document).on('click', '#BtnNoApproveBooking', function() {
    let Booking_id = $(this).attr('booking-id');
    Swal.fire({
        title: 'เหตุผลที่ไม่อนุมัติ!',
        input: 'textarea'
    }).then(function(result) {
        if (result.value) {
            $.post('../../Booking/DB/BookingNoApproveAdmin', {
                BookingID: Booking_id,
                booking_admin_reason: result.value
            }, function(data) {
                Swal.fire("ดำเนินการ ไม่อนุมัติ เรียบร้อยแล้ว!");
                $('#TBShowDataBookingAdmin').DataTable().ajax.reload();
            });
        }
    })
});




$(document).on('submit', '#FormAddBooking', function(e) {
    e.preventDefault();
    $.ajax({
        url: "../../Booking/DB/Insert",
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
                        window.location.href = "../../Booking/View/" + data;
                    }
                })
            }
            $('#BtnSubBooking').removeClass("disabled");
            $('#spinner').remove();
            $('#BtnSubBooking').html("จอง");
        }
    });
});

$(document).on('submit', '#FormEditBooking', function(e) {
    e.preventDefault();
    $.ajax({
        url: "../../Booking/DB/Update",
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
                    text: "แก้ไขการจองสำเร็จ!",
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'ตกลง!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "../../Booking/View/" + data;
                    }
                })
            }
            $('#BtnSubBooking').removeClass("disabled");
            $('#spinner').remove();
            $('#BtnSubBooking').html("จอง");
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
        booking_locationroom:$('#booking_locationroom').val(),
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
        booking_locationroom:$('#booking_locationroom').val(),
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

function formatThaiDate(date) {
    let thaiDate = new Intl.DateTimeFormat('th-TH', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        weekday: 'long'
    }).format(new Date(date));

    return thaiDate;
}

var calendarEl = document.getElementById('calendar');

var calendar = new FullCalendar.Calendar(calendarEl, {
    headerToolbar: {
        left: 'prevYear,prev,next,nextYear',
        center: 'title',
        right: 'dayGridMonth,dayGridWeek,dayGridDay'
    },
    navLinks: true, // can click day/week names to navigate views
    editable: false,
    locale: 'th',
    dayHeaderFormat: { weekday: 'long' },
    eventSources: [{
        events: function(fetchInfo, successCallback, failureCallback) {
            jQuery.ajax({
                url: "Booking/DB/ShowTimeBooking",
                type: "POST",
                success: function(res) {
                    var events = [];
                    res.forEach(evt => {
                        events.push({
                            id: evt.id,
                            title: evt.title,
                            start: evt.start,
                            end: evt.end,
                            allDay: true,
                            backgroundColor:evt.backgroundColor,
                            borderColor:evt.backgroundColor,                            
                            bookingApprove:evt.booking_admin_approve
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
        Swal.fire({
            title: `สถานะ : ${info.event.extendedProps.bookingApprove}`,
            html: `<b>วันเข้าใช้บริการ</b> : ${formatThaiDate(info.event.start)} <br>
                   <b>เวลา:</b> ${info.event.title || "ไม่มีรายละเอียด"}`,
            icon: 'info'
        });

       
    }
});
calendar.render();

