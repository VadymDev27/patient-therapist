<?php

if (!function_exists('appendToEach')) {
    function appendStringToEach(array $array, string $prefix, string $suffix = '')
    {
        return array_map(fn ($i): string => $prefix . $i . $suffix, $array);
    }
}

if (!function_exists('isRadioChecked')) {
    function isRadioChecked(string $questionName, string $optionValue)
    {
        return old($questionName) === $optionValue ? 'checked' : '';
    }
}

if (!function_exists('isCheckboxChecked')) {
    function isCheckboxChecked(string $questionName, string $optionValue)
    {
        if (!is_null(old($questionName))) {
            return in_array($optionValue, old($questionName)) ? 'checked' : '';
        }
        return '';
    }
}

if (!function_exists('isOptionSelected')) {
    function isOptionSelected(string $questionName, string $optionValue)
    {
        return old($questionName) === $optionValue ? 'selected' : '';
    }
}

if (!function_exists('json_old')) {
    function json_old(string $questionName, mixed $default = '')
    {
        return json_encode(old($questionName) ?? $default);
    }
}

if (!function_exists('x_data_string')) {
    function x_data_string(string $questionName, string $modelName, bool $hasModel, mixed $default = '') {
        return $hasModel
            ? '{}'
            : "{ {$modelName}: ".json_old($questionName, $default).' }';
    }
}
