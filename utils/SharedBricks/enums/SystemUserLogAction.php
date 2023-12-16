<?php

namespace utils\SharedBricks\enums;

enum SystemUserLogAction : string {
    case Create = "CREATION";
    case Update = "MODIFICATION";
    case Delete = "DELETION";
    case Login = "LOGIN";
    case Other = "OTHER ACTION";
    case InvalidAccess = "UNAUTHORIZED ACCESS";
}