<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Foto & Input</title>
</head>
<body>
    <h2>Iseki Fisrol - Five S Patrol</h2>
    <h4>Hasil Temuan 5S</h4>
    <div>
        <img src="{{ $foto }}" alt="Foto" width="320" height="240">
    </div>
    <div>
        <strong>Temuan:</strong> {{ $teks }}
    </div>
    <form method="POST" action="{{ route('fisrol.unduh') }}">
        @csrf
        <input type="hidden" name="foto" value="{{ $foto }}">
        <input type="hidden" name="teks" value="{{ $teks }}">
        <button type="submit">Unduh sebagai PPT</button>
    </form>
</body>
</html>
