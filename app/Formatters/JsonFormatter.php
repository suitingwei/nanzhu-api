<?php
namespace App\Formatters;

abstract class JsonFormatter
{
    protected $originalArray = [];

    public static function getListFormatter()
    {
        return function () {
        };
    }

    public static function getDetailFormatter()
    {
        return function () {
        };
    }

    public static function getDeatailFormatterForOp()
    {
        return function () {
        };
    }

    public static function getListFormatterForOp()
    {
        return function () {
        };
    }
}
