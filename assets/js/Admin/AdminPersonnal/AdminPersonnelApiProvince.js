

const apiUrl = "../../../assets/js/api_province_with_amphure_tambon.json";
let data = {};

$(document).ready(function() {
    $('.province, .district, .subdistrict').select2();

    fetch(apiUrl)
        .then(res => res.json())
        .then(json => {
            data = json;
            const provinces = data.map(p => p.name_th);
            provinces.sort().forEach(p => {
                $('.province').append(`<option value="${p}">${p}</option>`);
            });
        });

    $('.province').on('change', function() {
        const selectedProvince = $(this).val();
        $('.district').html('<option selected disabled>เลือกอำเภอ</option>').prop('disabled', false);
        $('.subdistrict').html('<option selected disabled>เลือกตำบล</option>').prop('disabled', true);
        $('.zipcode').val('');

        const provinceData = data.find(p => p.name_th === selectedProvince);
        if (!provinceData) return;

        const amphures = provinceData.amphure || [];
        amphures.forEach(a => {
            $('.district').append(`<option value="${a.name_th}">${a.name_th}</option>`);
        });
    });

    $('.district').on('change', function() {
        const selectedProvince = $('.province').val();
        const selectedDistrict = $(this).val();
        $('.subdistrict').html('<option selected disabled>เลือกตำบล</option>').prop('disabled', false);
        $('.zipcode').val('');

        const provinceData = data.find(p => p.name_th === selectedProvince);
        if (!provinceData) return;

        const amphure = provinceData.amphure.find(a => a.name_th === selectedDistrict);
        if (!amphure) return;

        amphure.tambon.forEach(t => {
            $('.subdistrict').append(
                `<option value="${t.name_th}" data-zipcode="${t.zip_code}">${t.name_th}</option>`
                );
        });
    });

    $('.subdistrict').on('change', function() {
        const zipcode = $(this).find(':selected').data('zipcode') || '';
        $('.zipcode').val(zipcode);
    });

});


$(document).ready(function() {
    $('.curr_province, .curr_district, .curr_subdistrict').select2();

    fetch(apiUrl)
        .then(res => res.json())
        .then(json => {
            data = json;
            const provinces = data.map(p => p.name_th);
            provinces.sort().forEach(p => {
                $('.curr_province').append(`<option value="${p}">${p}</option>`);
            });
        });

    $('.curr_province').on('change', function() {
        const selectedProvince = $(this).val();
        $('.curr_district').html('<option selected disabled>เลือกอำเภอ</option>').prop('disabled', false);
        $('.curr_subdistrict').html('<option selected disabled>เลือกตำบล</option>').prop('disabled', true);
        $('.curr_zipcode').val('');

        const provinceData = data.find(p => p.name_th === selectedProvince);
        if (!provinceData) return;

        const amphures = provinceData.amphure || [];
        amphures.forEach(a => {
            $('.curr_district').append(`<option value="${a.name_th}">${a.name_th}</option>`);
        });
    });

    $('.curr_district').on('change', function() {
        const selectedProvince = $('.curr_province').val();
        const selectedDistrict = $(this).val();
        $('.curr_subdistrict').html('<option selected disabled>เลือกตำบล</option>').prop('disabled', false);
        $('.curr_zipcode').val('');

        const provinceData = data.find(p => p.name_th === selectedProvince);
        if (!provinceData) return;

        const amphure = provinceData.amphure.find(a => a.name_th === selectedDistrict);
        if (!amphure) return;

        amphure.tambon.forEach(t => {
            $('.curr_subdistrict').append(
                `<option value="${t.name_th}" data-zipcode="${t.zip_code}">${t.name_th}</option>`
                );
        });
    });

    $('.curr_subdistrict').on('change', function() {
        const zipcode = $(this).find(':selected').data('zipcode') || '';
        $('.curr_zipcode').val(zipcode);
    });

});

function isValidThaiID(id) {
    id = id.replace(/-/g, '');
    if (!/^[0-9]{13}$/.test(id)) return false;

    let sum = 0;
    for (let i = 0; i < 12; i++) {
        sum += parseInt(id.charAt(i)) * (13 - i);
    }
    const checkDigit = (11 - (sum % 11)) % 10;
    return checkDigit === parseInt(id.charAt(12));
}

