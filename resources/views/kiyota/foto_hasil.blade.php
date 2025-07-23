<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Foto & Input</title>
</head>
<body>
    <h2>Iseki Kiyota - Kiken Yochi Training</h2>
    <h4>Hasil KYT</h4>
    <div>
        <img src="{{ $foto }}" alt="Foto" width="320" height="240">
    </div>
    <div>
        <strong>Team:</strong> {{ $team }} <br>
        <strong>Judul:</strong> {{ $judul }} <br>
        <strong>PIC:</strong> {{ $pic }} <br>
        <strong>Keterangan:</strong> {{ $keterangan }} <br>
        <strong>Penanganan:</strong> {{ $penanganan }} <br>
    </div>
    <br>
    <div>
        <strong>Temuan KYT:</strong>
    </div>
    <form method="POST" action="{{ route('kiyota.unduh') }}">
        @csrf
        <input type="hidden" name="foto" value="{{ $foto }}">
        <input type="hidden" name="team" value="{{ $team }}">
        <input type="hidden" name="judul" value="{{ $judul }}">
        <input type="hidden" name="pic" value="{{ $pic }}">
        <input type="hidden" name="keterangan" value="{{ $keterangan }}">
        <input type="hidden" name="penanganan" value="{{ $penanganan }}">
        <button type="submit">Unduh sebagai PPT</button>
    </form>
</body>
</html>
