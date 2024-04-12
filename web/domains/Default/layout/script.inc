<?php
\BrickLayer\Lay\Core\View\Tags\Script::new()->src("@js/another.js");
echo \BrickLayer\Lay\Core\View\DomainResource::plaster()->script;
