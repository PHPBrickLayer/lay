<?php
declare(strict_types=1);
namespace BrickLayer\Lay\core\view\tags;

use BrickLayer\Lay\core\LayConfig;
use BrickLayer\Lay\core\view\enums\DomainType;
use BrickLayer\Lay\core\view\ViewBuilder;
use BrickLayer\Lay\core\view\Domain;
use BrickLayer\Lay\core\view\ViewSrc;

final class Img {
    private const ATTRIBUTES = [
        "alt" => "Page Image"
    ];

    use \BrickLayer\Lay\core\view\tags\traits\Standard;

    public function class(string $class_name) : self {
        return $this->attr('class', $class_name);
    }

    public function width(int|string $width) : self {
        return $this->attr('width',(string)  $width);
    }
    
    public function height(int|string $height) : self {
        return $this->attr('height', (string) $height);
    }

    public function ratio(int|string $width, int|string $height) : self {
        $this->width((string) $width);
        $this->height((string) $height);
        return $this;
    }

    public function alt(string $alt_text) : self {
        return $this->attr('alt', $alt_text);
    }

    public function src(string $src, bool $lazy_load = true) : string {
        $src = ViewSrc::gen($src);
        $lazy_load = $lazy_load ? 'lazy' : 'eager';
        $attr = $this->get_attr();

        return <<<LNK
            <img src="$src" loading="$lazy_load" $attr />
        LNK;
    }

}
