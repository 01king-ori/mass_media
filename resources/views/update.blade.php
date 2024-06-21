<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Files</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Update Files in Folder') }} ({{ $folder }})</div>

                    <div class="card-body">
                        <form action="{{ route('media.update', $folder) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                @foreach ($mediaFiles as $file)
                                    <div class="col-md-4 mb-4">
                                        <div class="card">
                                            @if ($file->type == 'photo')
                                                <p>File Name: {{ $file->file_name }}</p>
                                                <img src="{{ asset('storage/' . $file->file_path) }}" class="card-img-top" alt="{{ $file->file_name }}">
                                            @else
                                                <video controls class="card-img-top">
                                                    <source src="{{ asset('storage/' . $file->file_path) }}" type="{{ $file->file_path->getMimeType() }}">
                                                </video>
                                            @endif

                                            <div class="card-body">
                                                <h5 class="card-title">{{ $file->file_name }}</h5>
                                                <div class="mb-3">
                                                    <label for="new_file_{{ $file->id }}" class="form-label">Replace File</label>
                                                    <input class="form-control" type="file" name="new_file_{{ $file->id }}" id="new_file_{{ $file->id }}">
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="delete_files[]" value="{{ $file->id }}" id="delete_file_{{ $file->id }}">
                                                    <label class="form-check-label" for="delete_file_{{ $file->id }}">
                                                        Delete File
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-3">
                                <label for="new_files" class="form-label">Add New Files</label>
                                <input class="form-control" type="file" name="new_files[]" id="new_files" multiple>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Media Files</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
