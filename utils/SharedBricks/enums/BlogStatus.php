<?php

namespace utils\SharedBricks\enums;

enum BlogStatus : string {
    case Draft = "is_draft";
    case Published = "is_published";
    case Scheduled = "is_scheduled";

}