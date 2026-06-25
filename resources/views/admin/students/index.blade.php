@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 py-3">
        <h6 class="mb-0 fw-bold text-dark">Daftar Siswa</h6>
        
        <div class="d-flex flex-wrap gap-2">
            <button type="button" id="btnExportBulk" class="btn btn-sm btn-info text-white fw-semibold d-none" data-bs-toggle="modal" data-bs-target="#exportBulkModal">
                <i class="bi bi-file-pdf-fill me-1"></i> Cetak PDF Terpilih
            </button>
            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-upload me-1"></i> Import Data Siswa
            </button>
            <a href="{{ route('admin.migrate_kelas') }}" class="btn btn-sm btn-warning text-dark fw-semibold">
                <i class="bi bi-arrow-up-circle-fill me-1"></i> Naik Kelas
            </a>
            <a href="{{ route('admin.students.create') }}" class="btn btn-sm btn-primary">+ Tambah Siswa</a>
        </div>
        
    </div>
    <div class="card-body"> 
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="table-responsive">
            <table id="studentTable" class="table table-hover w-100">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 40px; text-align: center;"><input type="checkbox" id="selectAll" style="cursor: pointer;"></th>
                        <th>NIS / NISN</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Jurusan</th> 
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td class="text-center align-middle">
                            <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="student-checkbox" style="cursor: pointer;">
                        </td>
                        <td class="align-middle">
                            <strong>{{ $student->nis }}</strong><br>
                            <small class="text-muted">{{ $student->nisn }}</small>
                        </td>
                        <td class="align-middle"><strong>{{ $student->user->name }}</strong></td>
                        <td class="align-middle">{{ $student->class_name }}</td>
                        <td class="align-middle">{{ $student->major->name_major ?? '-' }}</td>
                        
                        <td class="align-middle text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                
                                <a href="{{ route('admin.students.manage_courses', $student->id) }}" class="btn btn-sm btn-info text-white">Atur Mapel</a>
                                
                                <a href="{{ route('admin.students.scores', $student->id) }}" class="btn btn-sm btn-warning text-dark">Nilai</a>
    
                                <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini? Semua data nilai dan akun siswa juga akan ikut terhapus.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Import Data Siswa -->
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Import Data Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih File (.xlsx / .csv)</label>
                        <input type="file" name="file_excel" class="form-control" accept=".xlsx,.csv,.xls" required>
                    </div>
                    <div class="alert alert-info py-2">
                        <small>Pastikan format kolom (header) pada baris pertama Excel Anda sesuai dengan template sistem.</small>
                    </div>
                    <div class="mb-2">
                        <a href="{{ asset('templates/template_siswa.xlsx') }}" class="btn btn-sm btn-outline-secondary w-100 fw-semibold" download>
                            <i class="bi bi-download me-1"></i> Download Template .xlsx
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cetak Rapor Massal -->
<div class="modal fade" id="exportBulkModal" tabindex="-1" aria-labelledby="exportBulkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="exportBulkModalLabel">Cetak Rapor Massal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.students.export_pdf_bulk') }}" method="POST" id="exportBulkForm">
                @csrf
                <!-- Container inputs ID siswa yang dipilih -->
                <div id="bulkStudentIdsContainer"></div>

                <div class="modal-body p-4">
                    <p class="text-muted mb-3">
                        Anda akan mengunduh rapor untuk <strong id="selectedStudentsCount">0</strong> siswa terpilih. Silakan tentukan periode cetak.
                    </p>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary">Tahun Ajaran</label>
                        <select name="academic_year" class="form-select" style="border-radius: 8px;" required>
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold text-secondary">Semester</label>
                        <select name="semester" class="form-select" style="border-radius: 8px;" required>
                            <option value="">-- Pilih Semester --</option>
                            <option value="1">Semester 1 (Ganjil)</option>
                            <option value="2">Semester 2 (Genap)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 pb-4 px-4 d-flex gap-2">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn btn-primary px-4" style="background-color: #1b68cf; border-color: #1b68cf; border-radius: 8px;">
                        <i class="bi bi-file-pdf-fill me-1"></i> Cetak PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const table = $('#studentTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "pageLength": 10,
            "order": [[2, 'asc']], // Diurutkan berdasarkan kolom 2 (Nama Siswa) karena kolom 0 sekarang checkbox
            "columnDefs": [
                { "orderable": false, "targets": [0, 5] } // Kolom checkbox dan kolom Aksi tidak dapat diurutkan
            ]
        });

        // Klik checkbox Pilih Semua
        $('#selectAll').on('click', function() {
            const rows = table.rows({ 'search': 'applied' }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
            updateBulkExportButton();
        });

        // Klik checkbox individual
        $('#studentTable tbody').on('change', 'input[type="checkbox"]', function() {
            if (!this.checked) {
                const el = $('#selectAll').get(0);
                if (el && el.checked) {
                    el.checked = false;
                }
            }
            updateBulkExportButton();
        });

        // Fungsi memperbarui tombol cetak massal dan input modal
        function updateBulkExportButton() {
            const selectedIds = [];
            table.$('input[type="checkbox"]:checked').each(function() {
                selectedIds.push($(this).val());
            });

            const btn = $('#btnExportBulk');
            const countLabel = $('#selectedStudentsCount');
            const container = $('#bulkStudentIdsContainer');

            container.empty();

            if (selectedIds.length > 0) {
                btn.removeClass('d-none');
                countLabel.text(selectedIds.length);
                
                selectedIds.forEach(id => {
                    container.append(`<input type="hidden" name="student_ids[]" value="${id}">`);
                });
            } else {
                btn.addClass('d-none');
            }
        }

        // Reset state checkbox saat tabel draw/pindah halaman/search
        table.on('draw', function() {
            $('#selectAll').prop('checked', false);
            updateBulkExportButton();
        });
    });
</script>
@endpush