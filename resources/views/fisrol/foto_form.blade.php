<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambil Foto & Input</title>
    <style>
        #preview {
            max-width: 100%;
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>Iseki Fisrol - Five S Patrol</h2>
    <h4>Ambil Foto Temuan 5S</h4>
    <form id="fotoForm" method="POST" action="{{ route('fisrol.hasil') }}">
        @csrf
        <label>Pilih / Ambil Foto:</label><br>
        <input type="file" id="inputGambar" accept="image/*" capture="environment" required><br>
        <img id="preview" src="#" alt="Preview" style="display:none;" />
        <input type="hidden" name="foto" id="fotoInput">
        <div>
            <label for="teks">Temuan:</label>
            <input type="text" name="teks" id="teks" required>
        </div>
        <button type="submit">Submit</button>
    </form>
    <script>
        const inputGambar = document.getElementById('inputGambar');
        const preview = document.getElementById('preview');
        const fotoInput = document.getElementById('fotoInput');

        inputGambar.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    preview.src = event.target.result;
                    preview.style.display = 'block';
                    fotoInput.value = event.target.result; // simpan base64
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
