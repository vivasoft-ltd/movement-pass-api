<?php
namespace App\Actions;

use Aws\S3\Exception\S3Exception;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use JetBrains\PhpStorm\ArrayShape;
use League\Flysystem\AwsS3v3\AwsS3Adapter;

class UploadAction
{
    public function __invoke(UploadedFile $file, $path, $options = []): bool|string
    {
        if ($this->isS3FileSystem()) {
            $path = $this->prefixS3Dir($path);
        }

        return Storage::putFile($path, $file, $options);
    }

    #[ArrayShape(['signedUrl' => "string", 'image' => "string"])]
    public function createS3SignedUrl($path): array
    {
        $output = [
            'signedUrl' => '',
            'image' => '',
        ];

        if ($this->isS3FileSystem()) {
            try {
                $awsS3Adapter = Storage::disk('s3')->getDriver()->getAdapter();

                /** @var AwsS3Adapter $awsS3Adapter */
                $s3Client = $awsS3Adapter->getClient();

                $command = $s3Client->getCommand('PutObject', [
                    'Bucket' => config('filesystems.disks.s3.bucket'),
                    'Key'    => $output['image'] = $this->prefixS3Dir($path),
                    'Expires'=> 300,
                ]);

                $request = $s3Client->createPresignedRequest($command, Carbon::now()->addMinutes(5));

                $output['signedUrl'] = (string) $request->getUri();

            } catch (Exception|S3Exception $exception) {
                Log::error($exception->getMessage());
            }
        }

        return $output;
    }

    protected function isS3FileSystem(): bool
    {
        return config('filesystems.default') === 's3';
    }

    protected function prefixS3Dir($path): ?string
    {
        if ($prefixDir = config('filesystems.disks.s3.directory_path')) {
            return rtrim($prefixDir, '/') . '/' . $path;
        }

        return $path;
    }
}
