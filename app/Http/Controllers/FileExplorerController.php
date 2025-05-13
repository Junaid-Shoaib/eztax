<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class FileExplorerController extends Controller
{
        public function index(Request $request, $folder = null)
        {
            $basePath = 'public/'.auth()->user()->id;
            $folderPath = $folder ? $basePath . '/' . $folder : $basePath;
            
            $fullPath = storage_path('app/' . $folderPath);

            if (!File::exists($fullPath)) {
                abort(404, 'Folder not found.');
            }

            $page = $request->get('page', 1);
            $perPage = 20;
            $offset = ($page - 1) * $perPage;

            $allFolders = File::directories($fullPath);
            $allFiles = File::files($fullPath);

            $folders = array_slice($allFolders, $offset, $perPage);
            $files = array_slice($allFiles, max(0, $offset - count($allFolders)), $perPage - count($folders));

            if ($request->ajax()) {
                
                    $current = $folder;
                return view('partials.file_items', compact('folders', 'files', 'folder','current'))->render();
            }

            return view('file_manager', [
                'folders' => $folders,
                'files' => $files,
                'current' => $folder,
                'parent' => $this->getParentFolder($folder),
            ]);
        }

    private function getParentFolder($folder)
    {
        if (!$folder) return null;

        $parts = explode('/', $folder);
        array_pop($parts);
        return implode('/', $parts);
    }

    public function download($path)
    {
        $filePath = storage_path('app/public/'. auth()->user()->id .'/'. $path);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath);
    }


    public function delete(Request $request)
    {
        $path = $request->input('path');
        $fullPath = storage_path('app/public/'. auth()->user()->id . '/' . $path);

        if (file_exists($fullPath)) {
            if (is_file($fullPath)) {
                unlink($fullPath);
            } elseif (is_dir($fullPath)) {
                // Only delete if empty
                if (count(scandir($fullPath)) <= 2) { // Only '.' and '..' means empty
                    rmdir($fullPath);
                } else {
                    return back()->with('error', 'Folder is not empty');
                }
            }
        }

        return back()->with('success', 'Deleted');
    }

}