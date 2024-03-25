<?php

namespace utils\Traits;

use BrickLayer\Lay\Libs\LayDate;
use BrickLayer\Lay\Orm\SQL;
use JetBrains\PhpStorm\ArrayShape;

trait ModelDefault {

    public function uuid() : string {
        return self::orm()->uuid();
    }

    protected static function exists(string $where) : int {
        return self::orm(self::$table)
            ->where("deleted=0 AND $where")
            ->count_row("id");
    }

    public static function orm(?string $table = null) : SQL {
        if($table)
            return SQL::instance()->open($table);

        return SQL::instance();
    }

    #[ArrayShape(["code" => "int", "msg" => "string", "data" => "bool"])]
    public function add(array $columns) : array {
        $columns['id'] = $columns['id'] ?? 'UUID()';

        return $this->return_execution(
            self::orm(self::$table)->insert($columns)
        );
    }

    #[ArrayShape(["code" => "int", "msg" => "string", "data" => "null"])]
    public function add_batch(string $values) : array {
        return $this->return_execution(null);
    }

    #[ArrayShape(["code" => "int", "msg" => "string", "data" => "array"])]
    public function get() : array {
        return $this->return_execution(
            self::orm(self::$table)->loop()
                ->where("deleted=0")
                ->sort("name")
                ->limit(100)
                ->then_select()
        );
    }

    #[ArrayShape(["code" => "int", "msg" => "string", "data" => "array"])]
    public function get_by(string $where) : array {
        return $this->return_execution(
            self::orm(self::$table)
                ->where("deleted=0 AND ($where)")
                ->then_select()
        );
    }

    #[ArrayShape(["code" => "int", "msg" => "string", "data" => "array"])]
    public function get_by_list(string $where) : array {
        return $this->return_execution(
            self::orm(self::$table)
                ->where("deleted=0 AND ($where)")
                ->loop()->then_select()
        );
    }

    #[ArrayShape(["code" => "int", "msg" => "string", "data" => "bool", "error" => "bool"])]
    public function edit(string $id, array $columns) : array {
        return $this->return_execution(
            self::orm(self::$table)->column($columns)
                ->no_false()
                ->where("id='$id'")
                ->edit()
        );
    }

    #[ArrayShape(["code" => "int", "msg" => "string", "data" => "bool", "error" => "bool"])]
    public function delete(string $act_by, string $id = null) : array {
        return $this->return_execution(
            self::orm(self::$table)->column([
                "deleted" => 1,
                "deleted_by" => $act_by,
                "deleted_at" => LayDate::date(),
            ])->where("id='$id'")->edit()
        );
    }

    #[ArrayShape(["code" => "int", "msg" => "string", "data" => "mixed", "error" => "bool"])]
    public function return_execution(mixed $data, array $info = []) : array {
        return [
            "code" => 1,
            "msg" => "Execution complete",
            "data" => $data,
            "error" => !$data
        ];
    }
}