<?php
use BrickLayer\Lay\Core\View\Domain;

if(!defined("SAFE_TO_INIT_LAY"))
    define("SAFE_TO_INIT_LAY", true);

include_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "foundation.php";

Domain::new()->create(
    id: "api-endpoint",
    builder: \web\domains\Api\Plaster::class,
    patterns: ["api"],
);

Domain::new()->create(
    id: "default",
    builder: \web\domains\Default\Plaster::class,
    patterns: ["*"],
);
