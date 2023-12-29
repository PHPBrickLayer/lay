<?php
use BrickLayer\Lay\Core\View\Domain;

if(!defined("SAFE_TO_INIT_LAY"))
    define("SAFE_TO_INIT_LAY", true);

include_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "foundation.php";

Domain::new()->create(
    id: "api-endpoint",
    builder: new \web\domains\Api\Plaster(),
    patterns: ["api"],
);

Domain::new()->create(
    id: "default",
    builder: new \web\domains\Default\Plaster(),
    patterns: ["*"],
);


