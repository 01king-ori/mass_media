<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Upload</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Upload Photos or Videos</h2>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="folder_name" class="form-label">Folder Name</label>
            <input type="text" class="form-control" name="folder_name" id="folder_name" required>
        </div>
        <div class="mb-3">
            <label for="files" class="form-label">Select Files</label>
            <input class="form-control" type="file" name="files[]" id="files" multiple required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>

    <hr>

    <h3 class="mb-3">Existing Folders</h3>
    <ul>
        @foreach ($folders as $folder)
            <li>
                <a href="{{ route('media.edit', ['folder' => str_replace('media/', '', $folder)]) }}">
                    {{ str_replace('media/', '', $folder) }}
                </a><br><br>

            </li>
        @endforeach
    </ul>
</div>
</body>
</html>
