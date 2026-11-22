<?php
declare(strict_types=1);
require_once __DIR__ . '/exceptions.php';
class Validator {
    public static function required(array $data, array $fields): void {
        foreach ($fields as $field) {
            if (!array_key_exists($field,$data) || $data[$field] === null || (is_string($data[$field]) && trim($data[$field]) === '')) {
                throw new ValidationException("{$field} is required.");
            }
        }
    }
    public static function text(array $data, string $field, int $max, int $min = 1): void {
        if (!array_key_exists($field,$data)) return;
        if (!is_string($data[$field]) || strlen(trim($data[$field])) < $min || strlen($data[$field]) > $max) {
            throw new ValidationException("{$field} must contain between {$min} and {$max} characters.");
        }
    }
    public static function integer(array $data, string $field, int $min = 0): void {
        if (!array_key_exists($field,$data)) return;
        if (filter_var($data[$field], FILTER_VALIDATE_INT) === false || (int)$data[$field] < $min) {
            throw new ValidationException("{$field} must be an integer of at least {$min}.");
        }
    }
    public static function choice(array $data,string $field,array $values):void {
        if (array_key_exists($field,$data) && !in_array($data[$field],$values,true)) {
            throw new ValidationException("{$field} must be one of: ".implode(', ',$values));
        }
    }
    public static function date(array $data,string $field,bool $nullable=false):void {
        if (!array_key_exists($field,$data) || ($nullable && ($data[$field]===null || $data[$field]===''))) return;
        $value=$data[$field];
        if (!is_string($value) || !preg_match('/^\d{4}-\d{2}-\d{2}$/',$value)) throw new ValidationException("{$field} must be YYYY-MM-DD.");
        $d=DateTimeImmutable::createFromFormat('!Y-m-d',$value);
        if (!$d || $d->format('Y-m-d')!==$value) throw new ValidationException("{$field} is not a valid date.");
    }
    public static function email(array $data,string $field):void {
        if (array_key_exists($field,$data) && (!is_string($data[$field]) || !filter_var($data[$field], FILTER_VALIDATE_EMAIL))) throw new ValidationException("{$field} must be a valid email.");
    }
}
