<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Word to PDF Converter</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold text-center mb-8 text-gray-800">Word to PDF Converter</h1>
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('convert.word-to-pdf') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                <input type="file" name="word_file" id="word_file" accept=".doc,.docx" class="hidden" required onchange="showFileName()">
                <label for="word_file" class="cursor-pointer">
                    <div class="text-gray-600">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="mt-1 text-sm">Click to upload</p>
                        <p class="text-xs text-gray-500">DOC or DOCX (MAX. 10MB)</p>
                    </div>
                </label>
                <div id="file-name" class="mt-4 text-sm text-green-800 hidden"></div>
            </div>
            
            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200">
                Convert to PDF
            </button>
        </form>
    </div>

    <script>
        function showFileName() {
            const input = document.getElementById('word_file');
            const fileNameDiv = document.getElementById('file-name');
            
            if (input.files.length > 0) {
                fileNameDiv.textContent = 'Selected file: ' + input.files[0].name;
                fileNameDiv.classList.remove('hidden');
            } else {
                fileNameDiv.classList.add('hidden');
            }
        }
    </script>
</body>
</html> 