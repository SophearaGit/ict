<?php

namespace App\Traites;

use Illuminate\Http\UploadedFile;

trait FileUpload
{
    public function uploadFile(UploadedFile $file, string $directory = 'uploads'): string
    {
        $filename = 'ict_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($directory), $filename);
        return '/' . $directory . '/' . $filename;
    }

    public function deleteIfImageExist(?string $path): bool
    {
        if (file_exists(public_path($path))) {
            return unlink(public_path($path));
        }
        return false;
    }

}

