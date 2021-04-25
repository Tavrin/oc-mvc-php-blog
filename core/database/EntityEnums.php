<?php


namespace Core\database;


class EntityEnums
{
    public const TABLE_NAME = 'table';
    public const ENTITY_REPOSITORY = 'repository';
    public const ENTITY_CLASS = 'entity';
    public const TYPE_ASSOCIATION = 'association';
    public const TYPE_DATE = "datetime";
    public const TYPE_JSON = 'json';
    public const TYPE_TINYINT = 'tinyInt';
    public const TYPE_BOOL = 'bool';
    public const TYPE_BOOLEAN = 'boolean';
    public const FIELD_NAME = 'fieldName';
    public const FIELD_TYPE = 'type';
    public const ID_FIELD_NAME = 'id';
    public const FIELDS_CATEGORY = 'fields';
    public const DEFAULT_DATE_FORMAT = 'Y-m-d H:i:s';

    public const TYPE_CONVERSION = [
        'int' => 'INT',
        'bool' => 'TINYINT(1)',
        'string' => 'VARCHAR(255)',
        'text' => 'LONGTEXT',
        'datetime' => 'DATETIME',
        'array' => 'JSON',
        'association' => 'INT'
    ];

    public const TYPE_COMPARISON = [
        'boolean' => 'boolean',
        'array' => 'json',
        'string' => 'string'
    ];
}