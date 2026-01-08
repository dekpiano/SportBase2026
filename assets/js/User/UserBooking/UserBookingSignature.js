//---------------------------------ลายเซ็น  Admin -----------------------------------
var signaturePad = new SignaturePad(document.getElementById('SignatureAdmin'));
signaturePad.penColor = '#0066CC';

//ถึงลายเซ็นกลับมาโชว์ในฟอร์ม
signaturePad.fromDataURL('');

 // โค้ด JavaScript สำหรับล้างลายเซ็น
 document.getElementById('clear').addEventListener('click', function () {
    signaturePad.clear();
});

$(document).on('click', '#FormSignatureAdmin', function() {
    var BookingID = $(this).attr('data-idBooking');

    fetch("../../Booking/DB/BookingSignatureAdmin/Show/" + BookingID)
        .then(response => response.json())
        .then(data => {
           // console.log(data);
            if (data.booking_admin_signature) {
                signaturePad.fromDataURL(data.booking_admin_signature);
            }
        })
        .catch(error => console.error('Error:', error));
});

$(document).on('click', '#SaveSignatureAdmin', function() {
    var BookingID = $('#FormSignatureAdmin').attr('data-idBooking');
    var data = signaturePad.toDataURL('image/png');

    // ส่งข้อมูลลายเซ็นผ่าน AJAX
    fetch("../../Booking/DB/BookingSignatureAdmin/Save", {
        method: "POST",
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: "BookingID=" + BookingID + "&signature=" + encodeURIComponent(data)
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            alert('ลายเซ็นถูกบันทึกแล้ว');
            $('#ModalSignatureAdmin').hide();
            $('.modal-backdrop').remove();
        } else {
            alert('เกิดข้อผิดพลาด');
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });
});

//---------------------------------ลายเซ็น  ผู้บริหาร -----------------------------------
//var signaturePad = new SignaturePad(document.getElementById('SignatureExecutive'));
//signaturePad.penColor = '#0066CC';

 // โค้ด JavaScript สำหรับล้างลายเซ็น
 document.getElementById('clear').addEventListener('click', function () {
    signaturePad.clear();
});

$(document).on('click', '#FormSignatureExecutive', function() {
    var BookingID = $(this).attr('data-idBooking');
    console.log(BookingID);
    fetch("../../Booking/DB/BookingSignatureExecutive/Show/" + BookingID)
        .then(response => response.json())
        .then(data => {
           
            if (data.booking_executive_signature) {
                signaturePad.fromDataURL(data.booking_executive_signature);
            }
        })
        .catch(error => console.error('Error:', error));
});

$(document).on('click', '#SaveSignatureExecutive', function() {
    var BookingID = $('#FormSignatureExecutive').attr('data-idBooking');
    var data = signaturePad.toDataURL('image/png');

    // ส่งข้อมูลลายเซ็นผ่าน AJAX
    fetch("../../Booking/DB/BookingSignatureExecutive/Save", {
        method: "POST",
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: "BookingID=" + BookingID + "&signature=" + encodeURIComponent(data)
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            alert('ลายเซ็นถูกบันทึกแล้ว');
            $('#ModalSignatureExecutive').hide();
            $('.modal-backdrop').remove();
        } else {
            alert('เกิดข้อผิดพลาด');
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });
});
