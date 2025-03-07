<?php
declare(strict_types=1);

namespace Utils\Traits;

use BrickLayer\Lay\Core\LayConfig;
use BrickLayer\Lay\Libs\Aws\Bucket;
use BrickLayer\Lay\Libs\FileUpload\Enums\FileUploadExtension;
use BrickLayer\Lay\Libs\FileUpload\Enums\FileUploadStorage;
use BrickLayer\Lay\Libs\FileUpload\Enums\FileUploadType;
use BrickLayer\Lay\Libs\FileUpload\FileUpload;
use BrickLayer\Lay\Libs\String\Enum\EscapeType;
use BrickLayer\Lay\Libs\String\Escape;
use JetBrains\PhpStorm\ArrayShape;

trait FileUploadHelper
{
    private static function upload_dir(bool $with_root = false): string
    {
        $lay = LayConfig::server_data();
        $dir = $lay->uploads_no_root;

        if ($with_root)
            return $lay->uploads;

        return $dir;
    }

    #[ArrayShape([
        'uploaded' => 'bool',
        'dev_error' => '?string',
        'error' => '?string',
        'error_type' => "?BrickLayer\\Lay\\Libs\\FileUpload\\Enums\\FileUploadErrors",
        'upload_type' => "BrickLayer\\Lay\\Libs\\FileUpload\\Enums\\FileUploadType",
        'storage' => "BrickLayer\\Lay\\Libs\\FileUpload\\Enums\\FileUploadStorage",
        'url' => '?string',
        'size' => '?int',
        'width' => '?int',
        'height' => '?int',
    ])]
    public static function handle_upload(
        string $post_name,
        string $new_name,
        ?string $upload_sub_dir = null,
        ?int $file_limit = 2200000,
        ?FileUploadExtension $extension = null,
        ?array $extension_list = null,
        ?array $custom_mime = null,
        ?array $dimension = [800, 800],
        bool $copy_tmp_file = false,
        int $quality = 80,
        ?FileUploadType $upload_type = null
    ): array
    {
        $dir = self::upload_dir() . $upload_sub_dir;
        $root = LayConfig::server_data()->root . "web" . DIRECTORY_SEPARATOR;

        $file = (new FileUpload([
            "post_name" => $post_name,
            "new_name" => Escape::clean($new_name, EscapeType::P_URL),
            "directory" => $root . $dir,
            "permission" => 0755,
            "file_limit" => $file_limit,
            "storage" => FileUploadStorage::BUCKET,
            "bucket_path" => str_replace("uploads/", "", rtrim($dir, DIRECTORY_SEPARATOR . "/") . "/"),
            "extension" => $extension,
            "extension_list" => $extension_list,
            "custom_mime" => $custom_mime,
            "dimension" => $dimension,
            "quality" => $quality,
            "upload_type" => $upload_type,
            "copy_tmp_file" => $copy_tmp_file,
        ]))->response;

        if(!$file['uploaded'])
            return $file;

        if($file['storage'] == FileUploadStorage::BUCKET)
            $file['url'] = LayConfig::site_data()->others->bucket_domain . $file['url'];
        else
            $file['url'] = rtrim($dir, DIRECTORY_SEPARATOR . "/") . "/" . $file['url'];

        return $file;
    }

    public static function rm_upload(string $file_name) : void
    {
        if (LayConfig::$ENV_IS_DEV)
            return;

        $file_name = str_replace(LayConfig::site_data()->others->bucket_domain, "", $file_name);

        (new Bucket())->rm_file($file_name);
    }
}