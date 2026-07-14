<?php
namespace App\Traites;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
trait FileUpload
{
    public function uploadFile(UploadedFile $file, string $directory = 'uploads'): string
    {
        $destination = public_path($directory);
        if (!File::isDirectory($destination)) {
            File::makeDirectory($destination, 0755, true, true);
        }
        $filename = 'ict_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($destination, $filename);
        return '/' . $directory . '/' . $filename;
    }
    public function deleteIfImageExist(?string $path): bool
    {
        if (empty($path) || trim($path, '/') === '') {
            return false;
        }
        $fullPath = public_path($path);
        if (is_file($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }
}
