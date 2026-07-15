<?php
namespace App\Traites;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
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
    private function downloadAndStoreThumbnail(string $url, string $directory = 'blog/thumbnails'): ?string
    {
        try {
            $response = Http::timeout(10)->get($url);

            if (!$response->successful()) {
                return null;
            }

            $destination = public_path($directory);

            if (!File::isDirectory($destination)) {
                File::makeDirectory($destination, 0755, true, true);
            }

            $extension = str_contains($response->header('Content-Type'), 'png') ? 'png' : 'jpg';
            $filename = 'ict_' . uniqid() . '.' . $extension;

            File::put($destination . '/' . $filename, $response->body());

            return '/' . $directory . '/' . $filename;
        } catch (\Exception $e) {
            return null;
        }
    }
}
