<?php

declare(strict_types=1);

namespace Shared\Services\S3;

use Aws\S3\S3Client;
use Exception;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class S3UploadService
{
    private static ?S3Client $s3Client = null;

    private static string $bucket;

    private static string $region;

    public function __construct()
    {
        $this->initializeClient();
    }

    private function initializeClient(): void
    {
        if (self::$s3Client) {
            return;
        }

        self::$region = config('filesystems.disks.s3.region');

        self::$s3Client = new S3Client([
            'version' => 'latest',
            'region' => self::$region,
            'endpoint' => config('filesystems.disks.s3.endpoint'),
            'credentials' => [
                'key' => config('filesystems.disks.s3.key'),
                'secret' => config('filesystems.disks.s3.secret'),
            ],
            'use_path_style_endpoint' => false,
            'http' => [
                'verify' => false,
            ],
        ]);

        self::$bucket = config('filesystems.disks.s3.bucket');
    }

    public function executeStartMultipartUpload(string $key, string $mime_type): string
    {
        try {
            $result = self::$s3Client->createMultipartUpload([
                'Bucket' => self::$bucket,
                'Key' => $key,
                'ACL' => 'public-read',
                'ContentType' => $mime_type,
            ]);

            Log::info('Started multipart upload with ID: '.$result['UploadId']);

            return $result['UploadId'];
        } catch (Exception $e) {
            Log::error('Failed to start multipart upload: '.$e->getMessage());
            throw new RuntimeException('Failed to start multipart upload: '.$e->getMessage());
        }
    }

    public function executeUploadPartFromString(string $contents, int $partNumber, Media $uploadMedia): void
    {
        $contentLength = strlen($contents);
        Log::info("Uploading part {$partNumber} with size: {$contentLength} bytes");

        // S3 requires each part (except the last) to be at least 5MB
        $minPartSize = 5 * 1024 * 1024; // 5MB in bytes
        $totalParts = (int) request()->input('resumableTotalChunks', 1);

        if ($partNumber < $totalParts && $contentLength < $minPartSize) {
            throw new RuntimeException(
                "Part {$partNumber} size ({$contentLength} bytes) is too small. ".
                'S3 requires each part except the last to be at least 5MB.'
            );
        }

        try {
            $key = $uploadMedia->getCustomProperty('key');
            $uploadId = $uploadMedia->getCustomProperty('uploadId');

            $result = self::$s3Client->uploadPart([
                'Bucket' => self::$bucket,
                'Key' => $key,
                'UploadId' => $uploadId,
                'PartNumber' => $partNumber,
                'Body' => $contents,
                'ContentLength' => $contentLength,
            ]);

            if (! isset($result['ETag'])) {
                throw new RuntimeException('No ETag received from S3');
            }

            // Get existing parts or initialize empty array
            $parts = $uploadMedia->getCustomProperty('parts', []);
            $parts[] = ['PartNumber' => $partNumber, 'ETag' => trim($result['ETag'], '"')];

            $uploadMedia->update(['custom_properties->parts' => $parts]);

            Log::info("Successfully uploaded part {$partNumber} with ETag: {$result['ETag']}");
        } catch (Exception $e) {
            Log::error("Failed to upload part {$partNumber}: ".$e->getMessage());
            throw new RuntimeException('Failed to upload part: '.$e->getMessage());
        }
    }

    public function executeCompleteMultipartUpload(Media $uploadMedia): string
    {
        $key = $uploadMedia->getCustomProperty('key');
        $uploadId = $uploadMedia->getCustomProperty('uploadId');
        $s3Parts = $uploadMedia->getCustomProperty('parts');

        try {
            $result = self::$s3Client->completeMultipartUpload([
                'Bucket' => self::$bucket,
                'Key' => $key,
                'UploadId' => $uploadId,
                'MultipartUpload' => ['Parts' => $s3Parts],
            ]);
            Log::info('Upload completed successfully. Location: '.($result['Location'] ?? 'Not provided'));

            return $result['Location'] ?? 'https://'.self::$bucket.'.s3.'.self::$region.'.arvanstorage.ir/'.$key;
        } catch (Exception $e) {
            Log::error('Failed to complete multipart upload: '.$e->getMessage());
            throw new RuntimeException('Failed to complete multipart upload: '.$e->getMessage());
        }
    }
}
