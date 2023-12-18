<?php
use BrickLayer\Lay\Core\View\Domain;

const SAFE_TO_INIT_LAY = true;
include_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "foundation.php";

if(defined("DOMAIN_SET"))
    return;

Domain::new()->create(
    id: "api-endpoint",
    builder: new \web\domains\Api\Plaster(),
    patterns: ["api"],
);

Domain::new()->create(
    id: "default",
    builder: new \web\domains\Default\Plaster()
);
