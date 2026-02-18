<h1>Halo, {{ $submission->student->name }}</h1>
<p>Nilai untuk tugas "{{ $submission->assignment->title }}" telah diberikan.</p>
<p>Nilai Anda: {{ $submission->score }}</p>