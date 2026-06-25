<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; line-height: 1.4; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; font-size: 11px; word-wrap: break-word; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .header-info { margin-bottom: 4px; }
        .text-left { text-align: left; }
        .text-justify { text-align: justify; }
    </style>
</head>
<body>
    <div style="text-align: center;">
        <h2 style="margin-bottom: 4px;">LAPORAN HASIL BELAJAR SISWA</h2>
        <p class="header-info">Nama: <strong>{{ $student->user->name }}</strong> &nbsp;|&nbsp; NIS: <strong>{{ $student->nis }}</strong></p>
        <p class="header-info">Tahun Ajaran: <strong>{{ $academic_year }}</strong> &nbsp;|&nbsp; {{ $semester_label }}</p>
        <p style="font-size:11px; color:#555; margin-top: 4px;">Dicetak pada: {{ $date }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30%;">Mata Pelajaran</th>
                <th style="width: 15%;">Nilai Akhir</th>
                <th style="width: 55%;">Capaian Pembelajaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($courses as $course)
                @php
                    $total = 0;
                    $count = 0;
                    foreach($categories as $cat) {
                        $score = $student->scores->where('course_id', $course->id)
                                                ->where('score_category_id', $cat->id)->first();
                        $val = $score ? $score->score : null;
                        if ($val !== null) {
                            $total += $val;
                            $count++;
                        }
                    }
                    $finalScore = $count > 0 ? $total / $count : 0;
                    $cp = $student->capaianPembelajaran->where('course_id', $course->id)->first();
                @endphp
                <tr>
                    <td class="text-left" style="font-weight: bold;">{{ $course->name }}</td>
                    <td><strong>{{ $count > 0 ? number_format($finalScore, 1) : '-' }}</strong></td>
                    <td class="text-justify">
                        @if($cp && !empty($cp->description))
                            {{ $cp->description }}
                        @else
                            <span style="color: #777; font-style: italic;">Belum ada deskripsi capaian pembelajaran.</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align:center; color:#888; padding: 20px;">
                        Tidak ada nilai untuk periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>