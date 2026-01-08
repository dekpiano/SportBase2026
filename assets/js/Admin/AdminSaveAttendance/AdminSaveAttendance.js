
document.addEventListener("DOMContentLoaded", function () {
    // โหลดรายชื่อเมื่อเปิด modal
    let TbSaveAttendance = null;
    function loadAttendanceTable() {
        const tbody = document.getElementById('personnel-tbody');
        const date = document.getElementById('att_date').value;

        fetch('../Admin/SaveAttendance/DB/Select/GetPersonnalData') // <<< เปลี่ยน endpoint ตามหลังบ้านของพี่บอส
            .then(res => res.json())
            .then(personnel => {
                fetch('../Admin/SaveAttendance/DB/Select/GetAttendanceToDate?date=' + date)
                    .then(res => res.json())
                    .then(attendance => {
                        if (TbSaveAttendance) {
                            TbSaveAttendance.destroy();
                            $('#TbSaveAttendance tbody').empty();
                        }

                        let html = '';
                        personnel.forEach(function (p, idx) {
                            const att = attendance.find(a => a.person_id == p.pers_id) || {};
                            const status = att.status || 'มา';
                            const remark = att.remark || '';
                            html += `<tr>
            <td class="td-name">${p.pers_prefix + p.pers_firstname} ${p.pers_lastname}</td>
            <td class="td-name">${p.posi_name}</td>
            <td><input class="form-check-input" type="radio" name="status[${p.pers_id}]" value="มา" ${status == 'มา' ? 'checked' : ''}></td>
                <td><input class="form-check-input" type="radio" name="status[${p.pers_id}]" value="สาย" ${status == 'สาย' ? 'checked' : ''}></td>
                <td><input class="form-check-input" type="radio" name="status[${p.pers_id}]" value="ขาด" ${status == 'ขาด' ? 'checked' : ''}></td>
                <td><input class="form-check-input" type="radio" name="status[${p.pers_id}]" value="ลากิจ" ${status == 'ลากิจ' ? 'checked' : ''}></td>
                <td><input class="form-check-input" type="radio" name="status[${p.pers_id}]" value="ลาป่วย" ${status == 'ลาป่วย' ? 'checked' : ''}></td>
                <td><input class="form-check-input" type="radio" name="status[${p.pers_id}]" value="ไปราชการ" ${status == 'ไปราชการ' ? 'checked' : ''}></td>
                <td><input class="form-check-input" type="radio" name="status[${p.pers_id}]" value="อื่นๆ" ${status == 'อื่นๆ' ? 'checked' : ''}></td>
                <td><input type="text" class="form-control" name="remark[${p.pers_id}]" value="${remark}"></td>
          </tr>`;
                        });
                        tbody.innerHTML = html;
                        TbSaveAttendance = $('#TbSaveAttendance').DataTable({
                            ordering: false,
                            fixedHeader: true, // เปิดหัวตารางลอย!
                        });

                    })
                    .catch(err => {
                        console.log(`เกิดข้อผิดพลาด: ${err.message}`);
                    });
            });
    }




    // โหลดเมื่อ modal เปิด
    document.getElementById('attendanceModal').addEventListener('show.bs.modal', loadAttendanceTable);

    // โหลดเมื่อเปลี่ยนวันใน modal (input date)
    document.getElementById('att_date').addEventListener('change', loadAttendanceTable);


    document.getElementById('attendance-form').addEventListener('submit', function (e) {
        e.preventDefault();
         let table = $('#TbSaveAttendance').DataTable();
        const form = e.target;
        let allRows = table.rows().nodes().toArray();
        const formData = new FormData(form);

         allRows.forEach(function(row) {
        // สมมติ input radio ชื่อ status[xxx], input remark[xxx]
        let radios = row.querySelectorAll('input[type="radio"]:checked');
        radios.forEach(function(input) {
            formData.append(input.name, input.value);
        });
        let remark = row.querySelector('input[name^="remark"]');
        if (remark) {
            formData.append(remark.name, remark.value);
        }
    });

        const btn = form.querySelector('button[type=submit]');
        if (btn) btn.disabled = true;

        fetch('../Admin/SaveAttendance/DB/Select/SaveAttendanceToDB', {
            method: 'POST',
            body: formData,
        })
            .then(res => {
                if (!res.ok) throw new Error('บันทึกข้อมูลล้มเหลว');
                return res.json();
            })
            .then(result => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('attendanceModal'));
                modal.hide();

                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกข้อมูลสำเร็จ!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: result.message || 'ไม่สามารถบันทึกข้อมูลได้'
                    });
                }
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: err.message
                });
            })
            .finally(() => {
                if (btn) btn.disabled = false;
            });
    });






    const dateType = document.getElementById('dateType');
    const container = document.getElementById('datePickerContainer');
    dateType.addEventListener('change', function () {
        let html = '';
        if (this.value === 'day') {
            html = `
        <label for="dateInput" class="form-label">เลือกวันที่</label>
        <input type="date" id="dateInput" class="form-control" value="${new Date().toISOString().split('T')[0]}">
      `;
        } else if (this.value === 'month') {
            html = `
        <label for="dateInput" class="form-label">เลือกเดือน</label>
        <input type="month" id="dateInput" class="form-control" value="${new Date().toISOString().split('T')[0].slice(0, 7)}">
      `;
        } else if (this.value === 'year') {
            html = `
        <label for="dateInput" class="form-label">เลือกปี</label>
        <input type="number" id="dateInput" class="form-control" min="2020" max="2100" value="2025">
      `;
        }
        container.innerHTML = html;
        // โหลดข้อมูลใหม่ทุกครั้งที่เปลี่ยน input
        setTimeout(updateDashboard, 100); // wait for DOM
        document.getElementById('dateInput').addEventListener('change', updateDashboard);
    });


    let chart; // กราฟ donut
    let datatable = null;
    function updateDashboard() {
        // 1. get type & value
        const type = document.getElementById('dateType').value;
        const dateInput = document.getElementById('dateInput').value;
        let params = '';
        if (type === 'day') {
            params = `type=day&value=${dateInput}`;
        } else if (type === 'month') {
            params = `type=month&value=${dateInput}`;
        } else if (type === 'year') {
            params = `type=year&value=${dateInput}`;
        }

        // แสดง loading
        // document.getElementById('attendance-tbody').innerHTML = '<tr><td colspan="4" class="text-center">กำลังโหลด...</td></tr>';
        document.getElementById('summaryBox').innerHTML = '';

        // 2. fetch ข้อมูล
        fetch('../Admin/SaveAttendance/DB/Select/DashboardAttendance?' + params)
            .then(res => res.json())
            .then(data => {
                // สถิติกราฟ donut
                const series = [
                    data.stats.present,    // มา
                    data.stats.absent,     // ขาด
                    data.stats.sick,       // ลาป่วย
                    data.stats.official,    // ไปราชการ
                    data.stats.personal,    // ลากิจ
                    data.stats.other,
                    data.stats.late       // ลาอื่น ๆ
                ];
                const labels = ['มาทำงาน', 'ขาดงาน', 'ลาป่วย', 'ไปราชการ', 'ลากิจ', 'ลาอื่น ๆ','มาสาย'];
                const colors = ['#198754', '#dc3545', '#ffc107', '#0d6efd', '#fd7e14', '#adb5bd', '#6c757d'];
                if (chart) { chart.destroy(); }
                chart = new ApexCharts(document.querySelector("#chart"), {
                    chart: { type: 'donut' },
                    series, labels, colors
                });
                chart.render();

                // summary boxes
                document.getElementById('summaryBox').innerHTML = `
       
          <div class="col-4">
            <div class="bg-success text-white rounded p-3 text-center">
              <div class="fs-2 fw-bold">${data.stats.present_percent}%</div>
              <div>มาทำงาน</div>
            </div>
          </div>
          <div class="col-4">
            <div class="bg-danger text-white rounded p-3 text-center">
              <div class="fs-2 fw-bold">${data.stats.absent_percent}%</div>
              <div>ขาดงาน</div>
            </div>
          </div>
          <div class="col-4">
            <div class="bg-warning text-dark rounded p-3 text-center">
              <div class="fs-2 fw-bold">${data.stats.sick_percent}%</div>
              <div>ลาป่วย</div>
            </div>
          </div>
          <div class="col-4">
            <div class="bg-primary text-white rounded p-3 text-center">
              <div class="fs-2 fw-bold">${data.stats.official_percent}%</div>
              <div>ไปราชการ</div>
            </div>
          </div>
           <div class="col-4">
            <div class="bg-orange text-white rounded p-3 text-center" style="background:#fd7e14;">
            <div class="fs-2 fw-bold">${data.stats.personal_percent}%</div>
            <div>ลากิจ</div>
            </div>
        </div>
        <div class="col-4">
            <div class="bg-secondary text-white rounded p-3 text-center">
            <div class="fs-2 fw-bold">${data.stats.other_percent}%</div>
            <div>ลาอื่น ๆ</div>
            </div>
        </div>
        <div class="col-4">
            <div class="bg-info text-white rounded p-3 text-center">
            <div class="fs-2 fw-bold">${data.stats.late_percent}%</div>
            <div>มาสาย</div>
            </div>
        </div>
      `;

                if (datatable) {
                    datatable.destroy();
                    datatable = null; // สำคัญ!
                }
                $('#TbDashboradAttendance tbody').empty();
                // ตาราง
                let rows = '';
                if (data.table && data.table.length) {

                    data.table.forEach(row => {
                        let badge = 'bg-secondary';
                        if (row.status === 'มา') badge = 'bg-success';
                        else if (row.status === 'ขาด') badge = 'bg-danger';
                        else if (row.status === 'ลาป่วย') badge = 'bg-warning text-dark';
                        else if (row.status === 'ไปราชการ') badge = 'bg-primary';
                        else if (row.status === 'ลากิจ') badge = 'bg-secondary';
                        else if (row.status === 'อื่นๆ') badge = 'bg-secondary';
                        else if (row.status === 'สาย') badge = 'bg-danger';
                        rows += `
                                <tr>
                                <td>${row.date}</td>
                                <td>${row.name}</td>
                                <td><span class="badge ${badge}">${row.status}</span></td>
                                <td>${row.remark || '-'}</td>
                                </tr>
                            `;
                    });
                    $('#TbDashboradAttendance tbody').html(rows);

                } else {
                    $('#TbDashboradAttendance tbody').html(`<tr><td class="text-center">รอ...</td>
                        <td class="text-center">รอ...</td>
                        <td class="text-center">รอ...</td>
                        <td class="text-center">รอ...</td>
                        </tr>`);

                }
                datatable = $('#TbDashboradAttendance').DataTable();
                // เปลี่ยน title
                const title = type === 'day' ? 'สถิติการมาทำงาน (วัน)' : (type === 'year' ? 'สถิติการมาทำงาน (ปี)' : 'สถิติการมาทำงาน (เดือน)');
                document.getElementById('statTitle').textContent = title;
                document.getElementById('tableTitle').textContent = title.replace('สถิติ', 'ตาราง');
            })
            .catch(err => {
                document.getElementById('TbDashboradAttendance tbody').innerHTML = '<tr><td colspan="4" class="text-danger text-center">เกิดข้อผิดพลาดในการโหลดข้อมูล</td></tr>';
                document.getElementById('summaryBox').innerHTML = '';
                if (chart) chart.destroy();
                console.error('Error fetching data:', err);
            });
    }


    // initial
    document.getElementById('dateInput').addEventListener('change', updateDashboard);
    updateDashboard();
});



