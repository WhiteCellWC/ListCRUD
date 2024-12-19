<?php

namespace Modules\Common\FileUpload;

use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    protected string $disk;

    public function __construct()
    {
        $this->disk = env("DEFAULT_FILE_STORAGE");
    }

    public function changeDisk(string $disk)
    {
        $this->disk = $disk;
    }

    public function upload(string $path, int $maxSize = 0, $file)
    {

        $this->isValidFile($file);
        if ($maxSize) $this->isValidSize($file, $maxSize);

        $name = now()->unix() . "_" . $file->getClientOriginalName();
        $fullFilePath = rtrim($path, '/') . '/' . ltrim($name, '/');

        Storage::disk($this->disk)->put($fullFilePath, file_get_contents($file));

        if ($this->disk === "public") {
            return env("APP_URL") . "/storage/" . ltrim($fullFilePath, "/");
        }
    }

    public function delete(array|string $filePaths): bool
    {
        $filePaths = (array) $filePaths;

        $filePaths = array_map(function ($filePath) {
            $parsedPath = parse_url($filePath, PHP_URL_PATH);
            return str_starts_with($parsedPath, '/storage/')
                ? substr($parsedPath, strlen('/storage/'))
                : $filePath;
        }, $filePaths);

        return collect($filePaths)->every(fn($filePath) => Storage::disk($this->disk)->delete($filePath));
    }

    public function isValidFile($file)
    {
        if (!$file || !is_file($file)) {
            throw new \InvalidArgumentException('Invalid file provided for upload.');
        }
    }

    public function isValidSize($file, $size)
    {
        if (filesize($file) > $size) {
            throw new \InvalidArgumentException('File size exceeds the maximum allowed size of ' . $size . ' bytes.');
        }
    }
}
