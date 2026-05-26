<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div style="text-align: center;">
        <h2>LAPORAN HASIL BELAJAR SISWA</h2>
        <p>Nama: {{ $student->user->name }} | NIS: {{ $student->nis }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Mata Pelajaran</th>
                @foreach($categories as $cat)
                    <th>{{ $cat->name }}</th>
                @endforeach
                <th>Rata-rata</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses as $course)
            <tr>
                <td style="text-align: left;">{{ $course->name }}</td>
                @php $total = 0; $count = 0; @endphp
                
                @foreach($categories as $cat)
                    @php 
                        $score = $student->scores->where('course_id', $course->id)
                                                ->where('score_category_id', $cat->id)->first();
                        $val = $score ? $score->score : 0;
                        $total += $val;
                        if($score) $count++;
                    @endphp
                    <td>{{ $score ? $score->score : '-' }}</td>
                @endforeach
                
                <td><strong>{{ $count > 0 ? number_format($total / $count, 1) : 0 }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>