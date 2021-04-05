<?php


namespace Core\controller;


class FormEnums
{
    public const TEXT = [
        'type' => 'text',
        'attributes' => [
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
            'max',
            'min',
            'step',
            'value',
            'id',
            'class'
        ]
    ];
}