<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

/**
 * CloudinaryService
 *
 * Central service for all file uploads in ServeDavao.
 * Replaces local `public` disk storage so files persist
 * across Azure App Service restarts and deployments.
 *
 * Free tier limits (more than enough for thesis/testing):
 *   - 25 GB storage
 *   - 25 GB monthly bandwidth
 */
class CloudinaryService
{
    private Cloudinary $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('cloudinary.cloud_name', env('CLOUDINARY_CLOUD_NAME')),
                'api_key'    => config('cloudinary.api_key',    env('CLOUDINARY_API_KEY')),
                'api_secret' => config('cloudinary.api_secret', env('CLOUDINARY_API_SECRET')),
            ],
            'url' => [
                'secure' => true,
            ],
        ]);
    }

    /**
     * Upload any file to Cloudinary and return the secure HTTPS URL.
     *
     * @param  UploadedFile|string  $file     Uploaded file instance or raw file contents (string)
     * @param  string               $folder   Cloudinary folder (e.g. 'events', 'avatars')
     * @param  array                $options  Extra Cloudinary upload options
     * @return string               The secure HTTPS URL of the uploaded file
     * @throws \Exception           If upload fails
     */
    public function upload(UploadedFile|string $file, string $folder = 'uploads', array $options = []): string
    {
        try {
            $uploadOptions = array_merge([
                'folder'          => 'servedavao/' . $folder,
                'resource_type'   => 'auto',   // handles images, videos, raw files
                'use_filename'    => true,
                'unique_filename' => true,
                'overwrite'       => false,
            ], $options);

            $filePath = null;
            $isTmp    = false;

            if ($file instanceof UploadedFile) {
                $filePath = $file->getRealPath();
            } else {
                // Raw string content (e.g. downloaded Google avatar bytes)
                $filePath = tempnam(sys_get_temp_dir(), 'cld_') . '.jpg';
                file_put_contents($filePath, $file);
                $isTmp = true;
            }

            $result = $this->cloudinary->uploadApi()->upload($filePath, $uploadOptions);

            if ($isTmp && file_exists($filePath)) {
                @unlink($filePath);
            }

            return $result['secure_url'];

        } catch (\Exception $e) {
            Log::error('Cloudinary upload failed', [
                'folder' => $folder,
                'error'  => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Delete a file from Cloudinary by its secure URL or public_id.
     *
     * @param  string  $publicIdOrUrl  Either a full Cloudinary URL or a public_id
     */
    public function delete(string $publicIdOrUrl): void
    {
        try {
            $publicId = $this->extractPublicId($publicIdOrUrl);
            if ($publicId) {
                $this->cloudinary->uploadApi()->destroy($publicId);
            }
        } catch (\Exception $e) {
            Log::warning('Cloudinary delete failed', [
                'input' => $publicIdOrUrl,
                'error' => $e->getMessage(),
            ]);
            // Non-fatal: log and continue
        }
    }

    /**
     * Extract Cloudinary public_id from a full secure URL.
     * e.g. https://res.cloudinary.com/demo/image/upload/v123/servedavao/events/abc.jpg
     *      → servedavao/events/abc
     */
    private function extractPublicId(string $url): ?string
    {
        if (!str_starts_with($url, 'http')) {
            return $url; // Already a public_id
        }

        // Match path after /upload/v{digits}/ or /upload/
        if (preg_match('/\/upload\/(?:v\d+\/)?(.+?)(?:\.\w+)?$/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
