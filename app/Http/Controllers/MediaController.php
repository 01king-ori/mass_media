<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class MediaController extends Controller
{
    public function index()
    {
        $folders = Storage::disk('public')->allDirectories('media');
        return view('media.index', compact('folders'));
    }
    public function edit($folder)
    {
        $folderPath = trim($folder);
        $mediaFiles = Media::where('folder_name', $folderPath)->get();
        return view('media.edit', compact('mediaFiles', 'folder'));
    }

    public function update(Request $request, $folder)
    {
        \Log::info('Update method called for folder: ' . $folder);
        $folderPath = trim($folder);
        $mediaFiles = Media::where('folder_name', $folderPath)->get();

        foreach ($mediaFiles as $mediaFile) {
            if ($request->hasFile("new_file_{$mediaFile->id}")) {
                $request->validate([
                    "new_file_{$mediaFile->id}" => 'required|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480',
                ]);

                $newFilePath = $request->file("new_file_{$mediaFile->id}")->store($folderPath, 'public');

                Storage::disk('public')->delete($mediaFile->file_path);

                $mediaFile->file_name = $request->file("new_file_{$mediaFile->id}")->getClientOriginalName();
                $mediaFile->file_path = $newFilePath;
                $mediaFile->type = strpos($request->file("new_file_{$mediaFile->id}")->getMimeType(), 'video') !== false ? 'video' : 'photo';
                $mediaFile->save();
            }
        }

        if ($request->hasFile('new_files')) {
            $newFiles = $request->file('new_files');

            foreach ($newFiles as $newFile) {
                $request->validate([
                    'new_files.*' => 'required|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480',
                ]);

                $newFilePath = $newFile->store($folderPath, 'public');

                Media::create([
                    'file_name' => $newFile->getClientOriginalName(),
                    'file_path' => $newFilePath,
                    'type' => strpos($newFile->getMimeType(), 'video') !== false ? 'video' : 'photo',
                    'folder_name' => $folderPath,
                ]);
            }
        }


        if ($request->has('delete_files')) {
            $deleteFileIds = $request->input('delete_files');

            foreach ($deleteFileIds as $fileId) {
                $mediaFile = Media::findOrFail($fileId);
                Storage::disk('public')->delete($mediaFile->file_path);
                $mediaFile->delete();
            }
        }

        return redirect()->route('media.index', $folder)->with('success', 'Media files updated successfully.');
    }


    public function store(Request $request)
    {
        $request->validate([
            'files.*' => 'required|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480',
            'folder_name' => 'required|string|max:255',
        ]);

        $folderName = 'media/' . $request->folder_name;

        if ($request->hasfile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store($folderName, 'public');
                Media::create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'type' => $file->getMimeType() == 'video/mp4' ? 'video' : 'photo',
                    'folder_name' => $request->folder_name,
                ]);
            }
        }

        return back()->with('success', 'Files uploaded successfully.');
    }
}
