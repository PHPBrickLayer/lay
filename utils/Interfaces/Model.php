<?php

namespace Utils\Interfaces;

use BrickLayer\Lay\Orm\SQL;
use JetBrains\PhpStorm\ArrayShape;

interface Model {
    public function uuid() : string;

    public static function orm(?string $table = null) : SQL;

    #[ArrayShape(["code" => "int", "msg" => "string", "data" => "bool", "error" => "bool"])]
    public function add(array $columns) : array;

    #[ArrayShape(["code" => "int", "msg" => "string", "data" => "null", "error" => "bool"])]
    public function add_batch(string $values) : array;

    #[ArrayShape(["code" => "int", "msg" => "string", "data" => "array", "error" => "bool"])]
    public function get() : array;

    #[ArrayShape(["code" => "int", "msg" => "string", "data" => "array", "error" => "bool"])]
    public function get_by(string $where) : array;

    #[ArrayShape(["code" => "int", "msg" => "string", "data" => "array", "error" => "bool"])]
    public function get_by_list(string $where) : array;

    #[ArrayShape(["code" => "int", "msg" => "string", "data" => "bool", "error" => "bool"])]
    public function edit(string $id, array $columns) : array;

    #[ArrayShape(["code" => "int", "msg" => "string", "data" => "bool", "error" => "bool"])]
    public function delete(string $act_by, string $id = null) : array;

    #[ArrayShape(["code" => "int", "msg" => "string", "data" => "mixed", "error" => "bool"])]
    public function return_execution(mixed $data, array $info = []) : array;
}