let leaveByPositionTable = null;

function loadLeaveSummaryByDay() {
     const date = document.getElementById('leaveSummaryDate').value;

    fetch(`../Admin/SaveAttendance/DB/Select/leaveSummaryByPositionDay?date=${date}`)
        .then(res => res.json())
        .then(data => {
            if (leaveByPositionTable) {
                leaveByPositionTable.destroy();
                $('#LeaveSummaryByPositionTable tbody').empty();
            }
            let rows = '';
            data.forEach(row => {
                rows += `
          <tr>
            <td>${row.posi_name}</td>
            <td  class="text-center">${row.total_person}</td>
            <td  class="text-center">${row.attend_person}</td>
            <td class="text-center">${row.sick_leave}</td>
            <td class="text-center">${row.personal_leave}</td>
            <td class="text-center">${row.official_leave}</td>
            <td class="text-center">${row.other_leave}</td>
          </tr>
        `;
            });
            rows += `      
            <tr class="text-center">
            <th>รวมทั้งหมด</th>
            <th id="sum_total_person"></th>
            <th id="sum_attend_person"></th>
            <th id="sum_personal_leave"></th>
            <th id="sum_sick_leave"></th>
            <th id="sum_official_leave"></th>
            <th id="sum_other_leave"></th>
            </tr>
      `;
            $('#LeaveSummaryByPositionTable tbody').html(rows);

            leaveByPositionTable = $('#LeaveSummaryByPositionTable').DataTable({
                ordering: false,
               

            });
            calcTableSummary();
            zeroToDashInTable('LeaveSummaryByPositionTable');
            // Fix header ให้ตรง modal
            //setTimeout(() => leaveByPositionTable.fixedHeader.adjust(), 200);
        });
}

document.getElementById('btnSearchLeaveByDay').addEventListener('click', loadLeaveSummaryByDay);

// โหลดผลสรุปวันแรก (ถ้าต้องการ)
window.addEventListener('DOMContentLoaded', loadLeaveSummaryByDay);


function calcTableSummary() {
    // เอา tbody ทั้งหมดมา
    const tbody = document.querySelector('#LeaveSummaryByPositionTable tbody');
    // แยกช่องรวม
    let sum_total_person = 0;
    let sum_attend_person = 0;
    let sum_personal_leave = 0;
    let sum_sick_leave = 0;
    let sum_official_leave = 0;
    let sum_other_leave = 0;

    // loop ทีละแถว
    for (let row of tbody.rows) {
        sum_total_person += Number(row.cells[1].textContent) || 0;
        sum_attend_person += Number(row.cells[2].textContent) || 0;
        sum_personal_leave += Number(row.cells[3].textContent) || 0;
        sum_sick_leave += Number(row.cells[4].textContent) || 0;
        sum_official_leave += Number(row.cells[5].textContent) || 0;
        sum_other_leave += Number(row.cells[6].textContent) || 0;
    }

    // ใส่ค่าใน tfoot
    document.getElementById('sum_total_person').textContent = sum_total_person;
    document.getElementById('sum_attend_person').textContent = sum_attend_person;
    document.getElementById('sum_personal_leave').textContent = sum_personal_leave;
    document.getElementById('sum_sick_leave').textContent = sum_sick_leave;
    document.getElementById('sum_official_leave').textContent = sum_official_leave;
    document.getElementById('sum_other_leave').textContent = sum_other_leave;
}
calcTableSummary();

function zeroToDashInTable(tableId) {
  const tbody = document.querySelector(`#${tableId} tbody`);
  for (let row of tbody.rows) {
    for (let i = 1; i < row.cells.length; i++) { // ข้าม column ที่ 0 (เช่น "ตำแหน่ง" หรือ "ชื่อ")
      if (row.cells[i].textContent.trim() === '0') {
        row.cells[i].textContent = '-';
      }
    }
  }
}
