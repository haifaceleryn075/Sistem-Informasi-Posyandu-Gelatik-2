$(document).ready(function () {
    $('.datatables-default').DataTable({
        dom: '<"row mb-2"<"col-sm-6"l><"col-sm-6 text-end"B>>' +
             '<"row"<"col-12"f>>' +
             '<"row"<"col-12"tr>>' +
             '<"row mt-2"<"col-sm-5"i><"col-sm-7"p>>',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export Excel',
                className: 'btn btn-success btn-sm'
            },
            {
                extend: 'pdfHtml5',
                text: 'Export PDF',
                className: 'btn btn-danger btn-sm',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'print',
                text: 'Print',
                className: 'btn btn-secondary btn-sm'
            }
        ],
        responsive: true,
        lengthMenu: [
            [10, 100, -1],
            ['10', '100', 'Tampilkan Semua']
        ],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ entri",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
            zeroRecords: "Tidak ditemukan data yang sesuai",
            paginate: {
                first: "Awal",
                last: "Akhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }
        }
    });
});


function toggleUserForm() {
    const form = document.getElementById('userForm');
    // Toggle tampilan form: jika tersembunyi tampilkan, jika tampil sembunyikan
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

function toggleUkurForm() {
    const form = document.getElementById('ukurForm');
    // Toggle tampilan form: jika tersembunyi tampilkan, jika tampil sembunyikan
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

function toggleKembangForm() {
    const form = document.getElementById('kembangForm');
    // Toggle tampilan form: jika tersembunyi tampilkan, jika tampil sembunyikan
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

function toggleImunisasiForm() {
    const form = document.getElementById('imunisasiForm');
    // Toggle tampilan form: jika tersembunyi tampilkan, jika tampil sembunyikan
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

function toggleGuestForm() {
    const form = document.getElementById('guestForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}