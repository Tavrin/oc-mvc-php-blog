<?php


namespace Core\controller;


class FormEnums
{
    public const TEXT = [
        'type' => 'text',
        'attributes' => [
            'value',
            'dataAttributes',
            'placeholder',
            'id',
            'minlength',
            'maxlength',
            'pattern',
            'readonly',
            'size',
            'class',
            ]
    ];

    public const PASSWORD = [
        'type' => 'password',
        'attributes' => [
            'dataAttributes',
            'placeholder',
            'id',
            'minlength',
            'maxlength',
            'pattern',
            'readonly',
            'size',
            'class',
            'value',
            'inputmode',
        ]
    ];

    public const EMAIL = [
        'type' => 'email',
        'attributes' => [
            'dataAttributes',
            'placeholder',
            'id',
            'minlength',
            'maxlength',
            'pattern',
            'readonly',
            'value',
            'size',
            'class',
        ]
    ];

    public const DATETIME = [
        'type' => 'datetime-local',
        'attributes' => [
            'dataAttributes',
            'max',
            'min',
            'step',
            'value',
            'id',
            'class',
        ]
    ];

    public const SELECT = [
        'type' => 'select',
        'attributes' => [
            'dataAttributes',
            'max',
            'min',
            'step',
            'value',
            'id',
            'class',
        ]
    ];

    public const HIDDEN = [
        'type' => 'hidden',
        'attributes' => [
            'dataAttributes',
            'required',
            'value',
            'id',
            'class',
        ]
    ];

    public const FILE = [
        'type' => 'file',
        'attributes' => [
            'dataAttributes',
            'required',
            'value',
            'id',
            'class',
            'accept',
            'capture',
            'files',
            'multiple',
        ]
    ];

    public const BOOL_FIELDS = [
        'readonly',
        'multiple',
        'autofocus',
        'checked',
        'disabled',
    ];

    public const WHITELISTS = [
        'WHITELIST_IMAGE' => self::WHITELIST_IMAGE,
        'WHITELIST_VIDEO' => self::WHITELIST_VIDEO,
        'WHITELIST_AUDIO' => self::WHITELIST_AUDIO,
    ];

    public const WHITELIST_IMAGE = [
        'image/jpeg',
        'image/gif',
        'image/png',
        'image/bmp',
        'image/svg+xml',
        'image/tiff',
        'image/webp',
    ];

    public const WHITELIST_VIDEO = [
        'video/mp4',
        'video/mpeg',
        'video/ogg',
        'video/mp2t',
        'video/webm',
        'video/3gpp',
        'video/3gpp2',
    ];

    public const WHITELIST_AUDIO = [
        'audio/midi',
        'video/mpeg',
        'video/ogg',
        'video/mp2t',
        'video/webm',
        'video/3gpp',
        'video/3gpp2',
    ];

    public static function getAllWhitelists(): array
    {
        return self::WHITELISTS;
    }

    public static function getWhiteList($whiteList): array
    {
        return self::WHITELISTS[$whiteList];
    }

    public static function hasWhiteList(string $whitelist): bool
    {
        return (key_exists($whitelist, self::WHITELISTS));
    }
}