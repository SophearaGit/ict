<?php

namespace App\Traites;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

trait HandlesBlogThumbnails
{
    private function extractUrlFromInput(string $input): ?string
    {
        if (filter_var(trim($input), FILTER_VALIDATE_URL)) {
            return trim($input);
        }

        if (preg_match('/[?&]href=([^&"]+)/', $input, $matches)) {
            return urldecode($matches[1]);
        }

        if (preg_match('/cite="([^"]+)"/', $input, $matches)) {
            return $matches[1];
        }

        if (preg_match('/data-href="([^"]+)"/', $input, $matches)) {
            return $matches[1];
        }

        if (preg_match('/src="(https?:\/\/(?:www\.)?youtube(?:-nocookie)?\.com\/embed\/[^"]+)"/', $input, $matches)) {
            return $matches[1];
        }

        if (preg_match('/https?:\/\/[^\s"\'<>]+/', $input, $matches)) {
            return $matches[0];
        }

        return null;
    }

    private function getYoutubeThumbnail(string $url): ?string
    {
        preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches);
        if (empty($matches[1])) {
            return null;
        }
        $videoId = $matches[1];
        $maxres = "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg";

        try {
            $headers = Http::timeout(5)->head($maxres);
            if ($headers->successful()) {
                return $maxres;
            }
        } catch (\Exception $e) {
            // fall through to hqdefault
        }

        return "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
    }

    private function getTiktokThumbnail(string $url): ?string
    {
        $response = Http::timeout(10)->get('https://www.tiktok.com/oembed', [
            'url' => $url,
        ]);
        return $response->successful() ? $response->json('thumbnail_url') : null;
    }

    private function getFacebookThumbnail(string $url): ?string
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (compatible; facebookexternalhit/1.1; +http://www.facebook.com/externalhit_uatext.php)',
            ])->timeout(10)->get($url);

            if (!$response->successful()) {
                return null;
            }

            if (preg_match('/<meta property="og:image" content="([^"]+)"/', $response->body(), $matches)) {
                return html_entity_decode($matches[1]);
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function downloadAndStoreBlogThumbnail(string $url): ?string
    {
        try {
            $response = Http::timeout(10)->get($url);
            if (!$response->successful()) {
                return null;
            }

            $contentType = $response->header('Content-Type');
            if (!str_starts_with($contentType, 'image/')) {
                return null; // reject non-image responses outright
            }

            $directory = $this->uploadDirectory ?? 'uploads/blog/thumbnails';
            $destination = public_path($directory);
            if (!File::isDirectory($destination)) {
                File::makeDirectory($destination, 0755, true, true);
            }

            $extension = str_contains($contentType, 'png') ? 'png' : 'jpg';
            $filename = 'ict_' . uniqid() . '.' . $extension;
            File::put($destination . '/' . $filename, $response->body());

            return '/' . $directory . '/' . $filename;
        } catch (\Exception $e) {
            return null;
        }
    }
}
