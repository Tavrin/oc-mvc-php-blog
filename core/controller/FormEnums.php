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
            'required',
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
            'required',
            'size',
            'class',
            'inputmode'
        ]
    ];
}