<?php


namespace Core\controller;


class FormEnums
{
    public const TEXT = [
        'type' => 'text',
        'attributes' => [
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
            'max',
            'min',
            'step',
            'value',
            'id',
            'class'
        ]
    ];
}