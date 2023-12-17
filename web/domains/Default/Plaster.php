<?php

namespace web\domains\Default;

use BrickLayer\Lay\Core\View\DomainResource;
use BrickLayer\Lay\Core\View\ViewBuilder;
use BrickLayer\Lay\Core\View\ViewCast;

class Plaster extends ViewCast
{
    public function init_pages(): void
    {
        $this->builder->init_start()
            ->body_attr("dark")
            ->local("section", "app")
            ->local("logo", DomainResource::get()->shared->img_default->logo)
            ->init_end();
    }

    public function pages(): void
    {
        $this->builder->route("index")->bind(function (ViewBuilder $builder) {
            $builder
                ->page("title", "Homepage")
                ->page("desc", "This is the default homepage description")
                ->assets(
                    "@shared_css/colors2.css",
                    "@shared_js/colors.js",
                    "@shared_css/colors3.css",
                    "@shared_js/deee.js",
                    "@shared_css/colors.css",
                    "@shared_js/deee1.js",
                )
                ->body("homepage");
        });

        $this->builder->route("another-page")->bind(function (ViewBuilder $builder) {
            $builder->page("title", "Another Page")
                ->page("desc", "This is another page's description")
                ->body("another");
        });
    }
}