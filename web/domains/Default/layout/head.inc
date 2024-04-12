<?php

\BrickLayer\Lay\Core\View\Tags\Link::new()->rel("stylesheet")->href("@css/style.css");

echo \BrickLayer\Lay\Core\View\DomainResource::plaster()->head;
