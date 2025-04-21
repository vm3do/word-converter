<!DOCTYPE html>
<html>
<head>
    <title>Word to PDF Converter</title>
</head>
<body>
    <h1>Upload Word File to Convert to PDF</h1>

    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('upload.convert') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="word_file" accept=".docx" required>
        <button type="submit">Convert to PDF</button>
    </form>
</body>
</html>
