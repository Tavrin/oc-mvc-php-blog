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
            'class'
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
            'inputmode'
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
            'class'
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
            'class'
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
            'class'
        ]
    ];

    public const HIDDEN = [
        'type' => 'hidden',
        'attributes' => [
            'dataAttributes',
            'required',
            'value',
            'id',
            'class'
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
            'multiple'
        ]
    ];

    public const BOOL_FIELDS = [
        'readonly',
        'multiple',
        'autofocus',
        'checked',
        'disabled'
    ];
}