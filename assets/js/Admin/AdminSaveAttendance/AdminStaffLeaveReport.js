let leaveTable = null;

function loadLeaveSummary() {
    const start = document.getElementById('leaveDateStart').value;
    const end = document.getElementById('leaveDateEnd').value;
    fetch(`../Admin/SaveAttendance/DB/Select/LeaveSummary?start=${start}&end=${end}`)
        .then(res => res.json())
        .then(data => {
            if (leaveTable) {
                leaveTable.destroy();
                $('#LeaveSummaryTable tbody').empty();
            }
            let rows = '';
            data.forEach(row => {
                rows += `
          <tr>
            <td class="td-name">${row.pers_prefix}${row.pers_firstname} ${row.pers_lastname}</td>
            <td class="td-name">${row.posi_name}</td>
            <td>${row.total_days}</td>
            <td>${row.present}</td>
            <td>${row.late}</td>
            <td>${row.absent}</td>
            <td>${row.personal_leave}</td>
            <td>${row.sick_leave}</td>
            <td>${row.official_leave}</td>
            <td>${row.other_leave}</td>
          </tr>
        `;
            });


            $('#LeaveSummaryTable tbody').html(rows);
            leaveTable = $('#LeaveSummaryTable').DataTable({
                ordering: false
            });
        }).catch(err => {
            console.error('Error fetching data:', err);
        });
}

// เปิด modal ครั้งแรกให้โหลดข้อมูลด้วยช่วงวันที่ default
document.getElementById('leaveSummaryModal').addEventListener('show.bs.modal', function () {
    loadLeaveSummary();
});

// เปลี่ยนช่วงวันที่แล้วกดค้นหา
document.getElementById('btnSearchLeave').addEventListener('click', loadLeaveSummary);
