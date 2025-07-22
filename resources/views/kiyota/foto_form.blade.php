<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ambil atau Upload Foto</title>
    <style>
        #preview {
            max-width: 100%;
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>Iseki Kiyota - Kiken Yochi Training</h2>
    <h4>Upload Foto KYT</h4>
    <form id="fotoForm" method="POST" action="{{ route('kiyota.hasil') }}">
        @csrf
        <!-- Input Kamera atau Upload -->
        <label>Pilih / Ambil Foto:</label><br>
        <input type="file" id="inputGambar" accept="image/*" capture="environment" required><br>
        <img id="preview" src="#" alt="Preview" style="display:none;" />
        <input type="hidden" name="foto" id="fotoInput">
        <!-- Input Teks -->
        <div>
            <label for="teks">Judul:</label>
            <input type="text" name="judul" id="judul" required>
        </div>
        <div>
            <label for="teks">PIC:</label>
            <input type="text" name="pic" id="pic" required>
        </div>
        <div>
            <label for="teks">Keterangan:</label>
            <input type="text" name="keterangan" id="keterangan" required>
        </div>
        <div>
            <label for="teks">Penanganan:</label>
            <input type="text" name="penanganan" id="penanganan" required>
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
