# Walkthrough - Fitur Cetak Rapor PDF Massal (Bulk Export) & Capaian Pembelajaran

Kami telah berhasil menerapkan seluruh fitur input **Capaian Pembelajaran (CP)** oleh Guru, menyederhanakan **Dashboard Siswa**, memformat ulang cetakan **PDF Rapor Siswa**, menambahkan fitur **Cetak Rapor PDF Massal (Bulk Export)** untuk Admin, serta memperbarui **Template & Fitur Import Data Siswa** agar memuat data profil siswa secara lengkap.

---

## Ringkasan Perubahan

### 1. Database & Model Baru
- **[2026_06_25_142240_create_capaian_pembelajaran_table.php](file:///Users/mac/Documents/Codes/E-Raport-2026/e-raport-2026/database/migrations/2026_06_25_142240_create_capaian_pembelajaran_table.php)**: Migration untuk membuat tabel `capaian_pembelajaran` yang menghubungkan `student_id` × `course_id` dengan teks `description` capaian pembelajaran.
- **[CapaianPembelajaran.php](file:///Users/mac/Documents/Codes/E-Raport-2026/e-raport-2026/app/Models/CapaianPembelajaran.php)**: Model baru untuk merepresentasikan data capaian pembelajaran.
- **[Student.php](file:///Users/mac/Documents/Codes/E-Raport-2026/e-raport-2026/app/Models/Student.php)** & **[Course.php](file:///Users/mac/Documents/Codes/E-Raport-2026/e-raport-2026/app/Models/Course.php)**: Menambahkan relasi `capaianPembelajaran()` agar relasi data terpaut secara dinamis.

### 2. Logika Controller & Import
- **[AdminController.php](file:///Users/mac/Documents/Codes/E-Raport-2026/e-raport-2026/app/Http/Controllers/AdminController.php)**:
  - `indexStudents()`: Memuat daftar Tahun Ajaran secara dinamis dari tabel `courses` untuk dropdown modal cetak rapor massal.
- **[StudentsImport.php](file:///Users/mac/Documents/Codes/E-Raport-2026/e-raport-2026/app/Imports/StudentsImport.php)**:
  - Diperbarui agar memparsing semua field profil siswa dari baris Excel (seperti `tingkat_kelas`, `nomor_kelas`, `id_jurusan`, `jenis_kelamin`, `tanggal_lahir`, `nama_orang_tua`, `no_telepon`, `alamat`).
  - Mengonversi data jenis kelamin secara cerdas (L/P -> Laki-laki/Perempuan).
  - Menyusun `class_name` secara otomatis dengan relasi model Major (contoh: "10 Teknik Komputer dan Jaringan 1").
- **[GuruController.php](file:///Users/mac/Documents/Codes/E-Raport-2026/e-raport-2026/app/Http/Controllers/GuruController.php)**:
  - `inputNilai()`: Memuat relasi `capaianPembelajaran` menggunakan eager loading terfilter.
  - `saveNilai()`: Menyimpan/mengupdate deskripsi CP secara massal menggunakan metode `updateOrCreate` pada input `capaian.*`.
- **[StudentController.php](file:///Users/mac/Documents/Codes/E-Raport-2026/e-raport-2026/app/Http/Controllers/StudentController.php)**:
  - `dashboard()`: Memuat relasi `capaianPembelajaran` agar data capaian pembelajaran dapat diakses di dashboard siswa.
  - `exportPdf()`: Memuat relasi `capaianPembelajaran` agar teks deskripsi CP dapat dicetak ke dalam PDF rapor.
  - `exportPdfBulk()`: Memvalidasi ID siswa yang dipilih beserta tahun ajaran & semester, mengambil data nilai & CP masing-masing siswa tersebut, lalu mengunduh satu file PDF gabungan rapor massal.

### 3. Tampilan Interface & Cetak Rapor (UI/UX)
- **[index.blade.php](file:///Users/mac/Documents/Codes/E-Raport-2026/e-raport-2026/resources/views/admin/students/index.blade.php)** (Daftar Siswa Admin):
  - Ditambahkan kolom checkbox paling kiri beserta checkbox "Pilih Semua" (`#selectAll`).
  - Ditambahkan tombol **"Cetak PDF Terpilih"** di samping tombol impor. Tombol ini otomatis muncul jika ada siswa yang dicentang.
  - Modal **"Cetak Rapor Massal"** ditambahkan untuk memilih Tahun Ajaran & Semester sebelum mengunduh PDF massal.
  - Link **"Download Template .xlsx"** ditambahkan di dalam modal impor agar admin dapat mengunduh template yang sesuai.
- **[input_nilai.blade.php](file:///Users/mac/Documents/Codes/E-Raport-2026/e-raport-2026/resources/views/guru/input_nilai.blade.php)**:
  - Ditambahkan kolom **CP** tepat di sebelah kanan kolom **Nilai Akhir**.
  - Menyediakan tombol **CP** yang jika diklik akan membuka modal input deskripsi capaian pembelajaran siswa.
  - Ditambahkan feedback visual: tombol berwarna biru outline (`btn-outline-primary` dengan ikon 💬) jika CP kosong, dan berwarna hijau (`btn-success` dengan ikon ☑️) jika CP sudah terisi.
  - Dilengkapi dynamic JavaScript agar warna tombol berubah seketika ketika guru mengetik atau mengubah deskripsi di textarea.
- **[dashboard.blade.php](file:///Users/mac/Documents/Codes/E-Raport-2026/e-raport-2026/resources/views/student/dashboard.blade.php)**:
  - Menyederhanakan tampilan tabel hasil belajar agar hanya memuat **No, Mata Pelajaran, Nilai Akhir, dan Capaian Pembelajaran**.
  - Rincian kolom nilai per kategori serta kolom keterangan ketuntasan telah disembunyikan agar tampilan bersih dan sesuai referensi gambar.
- **[rapor.blade.php](file:///Users/mac/Documents/Codes/E-Raport-2026/e-raport-2026/resources/views/pdf/rapor.blade.php)** (Cetak PDF Tunggal) & **[rapor_bulk.blade.php](file:///Users/mac/Documents/Codes/E-Raport-2026/e-raport-2026/resources/views/pdf/rapor_bulk.blade.php)** (Cetak PDF Massal):
  - Diformat ulang agar tabel nilai rapor hanya menampilkan 3 kolom: **Mata Pelajaran**, **Nilai Akhir**, dan **Capaian Pembelajaran**.
  - Rincian nilai per kategori dilepas/disembunyikan agar cetakan PDF rapor ringkas, bersih, dan sesuai dengan dokumen referensi yang Anda berikan.
  - Menggunakan CSS `.page-break { page-break-after: always; }` untuk memisahkan rapor antarsiswa secara otomatis pada cetak massal.

---

## Hasil Verifikasi

1. **Migrasi Database**: Sukses dijalankan dan tabel `capaian_pembelajaran` telah dibuat.
2. **PHP Syntax Check**: Lulus verifikasi tanpa error syntax di semua controller, model, import, dan view yang diubah.
