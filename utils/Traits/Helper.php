<?php
declare(strict_types=1);

namespace Utils\Traits;

use BrickLayer\Lay\Core\Api\Enums\ApiStatus;
use BrickLayer\Lay\Core\LayConfig;
use BrickLayer\Lay\Libs\Aws\Bucket;
use BrickLayer\Lay\Libs\Image\ImageLib;
use BrickLayer\Lay\Libs\LayDate;
use BrickLayer\Lay\Libs\LayObject;
use BrickLayer\Lay\Libs\String\Enum\EscapeType;
use BrickLayer\Lay\Libs\String\Escape;

trait Helper
{

    public static function resolve(int|ApiStatus $code = 409, ?string $message = null, ?array $data = null): array
    {
        $code = is_int($code) ? $code : $code->value;

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

    protected static function cleanse(mixed &$value, EscapeType $type = EscapeType::STRIP_TRIM_ESCAPE, bool $strict = true)
    {
        $value = $value ? Escape::clean($value, $type, ['strict' => $strict]) : "";
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

    public static function img_upload(string $post_name, string $img_name, ?string $upload_sub_dir = null, ?array $dimension = [800, 800], bool $copy_tmp_file = false, int $quality = 80, ?int $file_limit = 2200000): array
    {
        $dir = self::upload_dir() . $upload_sub_dir;
        $root = LayConfig::server_data()->root . "web" . DIRECTORY_SEPARATOR;

        $file = ImageLib::new()->move([
            "post_name" => $post_name,
            "new_name" => self::cleanse($img_name, EscapeType::P_URL),
            "directory" => $root . $dir,
            "permission" => 0755,
            "dimension" => $dimension,
            "quality" => $quality,
            "copy_tmp_file" => $copy_tmp_file,
            "file_limit" => $file_limit
        ]);

        if (!$file['uploaded'])
            return $file;

        $url = rtrim($dir, DIRECTORY_SEPARATOR . "/") . "/" . $file['url'];

        if (LayConfig::$ENV_IS_DEV) {
            $file['url'] = $url;
            return $file;
        }

        $url = str_replace("uploads/", "", $url);

        if ((new Bucket())->upload($root . $dir . DIRECTORY_SEPARATOR . $file['url'], $url)['statusCode'] != 200) {
            $file['url'] = $url;
            return $file;
        }

        // delete local copy
        unlink($root . $dir . DIRECTORY_SEPARATOR . $file['url']);

        // return s3 copy
        $file['url'] = LayConfig::site_data()->others->uploads_domain . $url;

        return $file;
    }

    public static function rm_img(string $file_name) : void
    {
        if (LayConfig::$ENV_IS_DEV)
            return;

        $file_name = str_replace(LayConfig::site_data()->others->uploads_domain, "", $file_name);
        (new Bucket())->rm_file($file_name);
    }
}