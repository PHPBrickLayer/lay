<?php

namespace Oleonard\Lay\orm;

enum QueryStatus : string {
    case success = "Successful";
    case fail = "Failure";
}
