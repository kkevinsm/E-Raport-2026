@extends('layouts.app')

@section('content')

{{-- Header --}}
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-arrow-up-circle-fill me-2 text-primary"></i>Naik Kelas / Lulus (Migrate)</h5>
        <p class="text-muted mb-0" style="font-size: 0.9rem;">
            Kelas <strong>10 → 11</strong>, <strong>11 → 12</strong>, dan <strong>12 → Telah Lulus</strong>.
            Nilai & mata pelajaran semester sebelumnya tetap tersimpan.
        </p>
    </div>
    <a href="{{ route('admin.students') }}" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

@if($students->isEmpty())
    {{-- Empty State --}}
    <div class="card shadow-sm border-0">
        <div class="card-body text-center py-5">
            <i class="bi bi-people fs-1 text-secondary opacity-50 mb-3 d-block"></i>
            <h6 class="fw-bold text-dark mb-1">Tidak Ada Siswa yang Dapat Dimigrate</h6>
            <p class="text-muted mb-0">Belum ada data siswa kelas 10, 11, atau 12.</p>
        </div>
    </div>
@else

{{-- Legend --}}
<div class="d-flex flex-wrap gap-2 mb-3">
    <span class="badge bg-info rounded-pill px-3 py-2">Kelas 10 → 11</span>
    <span class="badge bg-warning text-dark rounded-pill px-3 py-2">Kelas 11 → 12</span>
    <span class="badge bg-success rounded-pill px-3 py-2">Kelas 12 → Telah Lulus</span>
</div>

<form action="{{ route('admin.migrate_kelas.process') }}" method="POST" id="migrateForm">
    @csrf

    <div class="card shadow-sm border-0 mb-5">
        <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
            <div class="d-flex align-items-center gap-3">
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" id="selectAll" style="width: 18px; height: 18px; cursor: pointer;">
                    <label class="form-check-label fw-semibold ms-1" for="selectAll" style="cursor: pointer;">Pilih Semua</label>
                </div>
                <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold px-3 py-2 rounded-pill" id="selectedCount">
                    0 siswa dipilih
                </span>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <select id="filterKelas" class="form-select form-select-sm" style="width: auto; border-radius: 8px;">
                    <option value="">Semua Kelas</option>
                    <option value="10">Kelas 10</option>
                    <option value="11">Kelas 11</option>
                    <option value="12">Kelas 12</option>
                </select>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="migrateTable">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 52px;">#</th>
                            <th style="width: 130px;">NIS / NISN</th>
                            <th>Nama Siswa</th>
                            <th>Kelas Saat Ini</th>
                            <th>Status Setelah Migrate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            @php
                                $parts  = explode(' ', $student->class_name, 2);
                                $grade  = (int) ($parts[0] ?? 0);
                                $suffix = trim($parts[1] ?? '');

                                if ($grade === 10) {
                                    $currentBadge = 'bg-info';
                                    $newLabel     = '11 ' . $suffix;
                                    $newBadge     = 'bg-warning text-dark';
                                    $newIcon      = 'bi-arrow-up-circle-fill text-warning';
                                } elseif ($grade === 11) {
                                    $currentBadge = 'bg-warning text-dark';
                                    $newLabel     = '12 ' . $suffix;
                                    $newBadge     = 'bg-danger';
                                    $newIcon      = 'bi-arrow-up-circle-fill text-danger';
                                } else {
                                    // Kelas 12 → Lulus
                                    $currentBadge = 'bg-danger';
                                    $newLabel     = 'Telah Lulus';
                                    $newBadge     = 'bg-success';
                                    $newIcon      = 'bi-patch-check-fill text-success';
                                }
                            @endphp
                            <tr class="student-row" data-grade="{{ $grade }}">
                                <td class="ps-4">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input student-checkbox"
                                               type="checkbox"
                                               name="student_ids[]"
                                               value="{{ $student->id }}"
                                               id="student_{{ $student->id }}"
                                               style="width: 18px; height: 18px; cursor: pointer;">
                                    </div>
                                </td>
                                <td>
                                    <label for="student_{{ $student->id }}" class="d-block mb-0 fw-semibold" style="cursor: pointer;">{{ $student->nis }}</label>
                                    <small class="text-muted">{{ $student->nisn ?? '-' }}</small>
                                </td>
                                <td>
                                    <label for="student_{{ $student->id }}" class="d-block mb-0 fw-semibold text-dark" style="cursor: pointer;">
                                        {{ $student->user->name }}
                                    </label>
                                    <small class="text-muted">{{ $student->major->name_major ?? '-' }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $currentBadge }} rounded-pill px-3 py-2 fw-semibold" style="font-size: 0.8rem;">
                                        {{ $student->class_name }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi {{ $newIcon }} fs-5"></i>
                                        <span class="badge {{ $newBadge }} rounded-pill px-3 py-2 fw-semibold" style="font-size: 0.8rem;">
                                            {{ $newLabel }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Action Bar (sticky bottom) --}}
    <div class="card shadow border-0" style="position: sticky; bottom: 16px; z-index: 100;">
        <div class="card-body py-3 px-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
            <div>
                <p class="mb-0 fw-semibold text-dark">
                    <i class="bi bi-info-circle text-primary me-1"></i>
                    Siswa terpilih: <span id="actionCount" class="text-primary fw-bold">0</span> dari <strong>{{ $students->count() }}</strong> siswa
                </p>
                <small class="text-muted">Nilai & mapel lama <strong>tidak akan terhapus</strong>.</small>
            </div>
            <button type="button" class="btn btn-primary fw-semibold px-4 d-flex align-items-center gap-2"
                    id="btnProses" disabled
                    data-bs-toggle="modal" data-bs-target="#confirmModal">
                <i class="bi bi-arrow-up-circle-fill fs-5"></i>
                Proses Migrate
            </button>
        </div>
    </div>
</form>
@endif

{{-- Confirmation Modal --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bi bi-arrow-up-circle-fill text-primary me-2"></i>
                    Konfirmasi Migrate Kelas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2 pb-4">
                <p class="text-muted mb-3">
                    Anda akan memproses migrate untuk <strong id="modalCount" class="text-primary">0</strong> siswa yang dipilih.
                </p>

                <div class="rounded-3 border p-3 mb-3" style="background: #f8f9fa; font-size: 0.88rem;">
                    <div class="d-flex gap-2 mb-1"><span class="badge bg-info rounded-pill px-2">10 → 11</span> Naik ke kelas 11</div>
                    <div class="d-flex gap-2 mb-1"><span class="badge bg-warning text-dark rounded-pill px-2">11 → 12</span> Naik ke kelas 12</div>
                    <div class="d-flex gap-2"><span class="badge bg-success rounded-pill px-2">12 → Lulus</span> Ditandai sebagai Telah Lulus</div>
                </div>

                <div class="alert alert-info border-0 rounded-3 py-2 mb-2">
                    <i class="bi bi-shield-check me-2"></i>
                    <strong>Data aman:</strong> Nilai dan mata pelajaran lama <strong>tidak akan dihapus</strong>.
                </div>
                <div class="alert alert-warning border-0 rounded-3 py-2 mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Tindakan ini <strong>tidak dapat dibatalkan</strong>. Pastikan daftar siswa sudah benar.
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary fw-semibold px-4" id="btnConfirm">
                    <i class="bi bi-check-lg me-1"></i> Ya, Proses!
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll     = document.getElementById('selectAll');
    const checkboxes    = document.querySelectorAll('.student-checkbox');
    const btnProses     = document.getElementById('btnProses');
    const selectedCount = document.getElementById('selectedCount');
    const actionCount   = document.getElementById('actionCount');
    const modalCount    = document.getElementById('modalCount');
    const btnConfirm    = document.getElementById('btnConfirm');
    const migrateForm   = document.getElementById('migrateForm');
    const filterKelas   = document.getElementById('filterKelas');
    const rows          = document.querySelectorAll('.student-row');

    function visibleCheckboxes() {
        return document.querySelectorAll('.student-row:not([style*="display: none"]) .student-checkbox');
    }

    function updateCount() {
        const checked = document.querySelectorAll('.student-checkbox:checked').length;
        selectedCount.textContent = checked + ' siswa dipilih';
        if (actionCount) actionCount.textContent = checked;
        if (modalCount)  modalCount.textContent  = checked;
        btnProses.disabled = checked === 0;

        // Update indeterminate / checked state for selectAll
        const vis = visibleCheckboxes();
        const visChecked = [...vis].filter(c => c.checked).length;
        selectAll.indeterminate = visChecked > 0 && visChecked < vis.length;
        selectAll.checked       = vis.length > 0 && visChecked === vis.length;
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updateCount));

    // Klik baris juga toggle checkbox
    rows.forEach(row => {
        row.addEventListener('click', function (e) {
            if (e.target.type === 'checkbox' || e.target.tagName === 'LABEL') return;
            const cb = row.querySelector('.student-checkbox');
            if (cb) { cb.checked = !cb.checked; updateCount(); }
        });
        row.style.cursor = 'pointer';
    });

    selectAll.addEventListener('change', function () {
        visibleCheckboxes().forEach(cb => { cb.checked = this.checked; });
        updateCount();
    });

    // Filter by grade
    filterKelas.addEventListener('change', function () {
        const val = this.value;
        rows.forEach(row => {
            const show = !val || row.dataset.grade === val;
            row.style.display = show ? '' : 'none';
            if (!show) row.querySelector('.student-checkbox').checked = false;
        });
        updateCount();
    });

    btnConfirm.addEventListener('click', function () {
        migrateForm.submit();
    });

    updateCount();
});
</script>
@endpush
