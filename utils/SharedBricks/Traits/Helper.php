<?php
declare(strict_types=1);

namespace utils\SharedBricks\Traits;

use BrickLayer\Lay\Core\LayConfig;
use BrickLayer\Lay\Libs\Aws\Bucket;
use BrickLayer\Lay\Libs\LayDate;
use BrickLayer\Lay\Libs\LayImage;
use BrickLayer\Lay\Libs\LayObject;

trait Helper
{
    public static function resolve(int $code = 0, ?string $message = null, ?array $data = null): array
    {
        return [
            "code" => $code,
            "msg" => $message ?? "Request could not be processed at the moment, please try again later",
            "data" => $data
        ];
    }

    protected static function date(?string $datetime = null): string
    {
        return LayDate::date($datetime);
    }

    protected static function required_post_missing(object $post_object, array $post_names): ?string
    {
        foreach ($post_names as $p) {
            if (empty(@$post_object->{$p})) {
                $p = "<b>" . ucwords(str_replace("_", " ", $p)) . "</b>";
                return "$p cannot be empty. $p is required!";
            }
        }

        return null;
    }

    private static function upload_dir(bool $with_root = false): string
    {
        $lay = LayConfig::server_data();
        $dir = $lay->uploads_no_root;

        if ($with_root)
            return $lay->uploads;

        return $dir;
    }

    protected static function cleanse(mixed &$value, float $level = 16, bool $strict = true)
    {
        $strict = $strict ? "!" : "";
        $value = $value ? LayConfig::get_orm()->clean($value, $level, $strict) : "";
        return $value;
    }

    private function validate_captcha(): bool
    {
        if (!isset($_SESSION['CAPTCHA_CODE']))
            return false;

        if (self::get_json(false)->captcha == $_SESSION['CAPTCHA_CODE'])
            return true;

        return false;
    }

    protected static function get_json(bool $throw_error = true): bool|null|object
    {
        return LayObject::new()->get_json($throw_error);
    }

    private static function img_upload(string $post_name, string $img_name, ?string $upload_sub_dir = null, ?array $dimension = [800, 800], bool $copy_tmp_file = false, int $quality = 80): ?string
    {
        $dir = self::upload_dir() . $upload_sub_dir;
        $root = LayConfig::server_data()->root . "web" . DIRECTORY_SEPARATOR;

        $x = LayImage::new()->move([
            "post_name" => $post_name,
            "new_name" => self::cleanse($img_name, 6, false),
            "directory" => $root . $dir,
            "permission" => 0755,
            "dimension" => $dimension,
            "quality" => $quality,
            "copy_tmp_file" => $copy_tmp_file
        ]);

        if (!$x)
            return null;

        $url = rtrim($dir, DIRECTORY_SEPARATOR . "/") . "/" . $x;

        if (LayConfig::$ENV_IS_DEV)
            return $url;

        if ((new Bucket())->upload($root . $dir . DIRECTORY_SEPARATOR . $x, $url)['statusCode'] != 200)
            return $x;

        // delete local copy
        unlink($root . $dir . DIRECTORY_SEPARATOR . $x);

        // return s3 copy
        return LayConfig::site_data()->others->uploads_domain . $url;
    }
}