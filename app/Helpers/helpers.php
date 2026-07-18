<?php

require_once 'asset_helpers.php';

function getRouteMiddlewares()
{
    return request()->route()?->middleware() ?? [];
}

function formatSalaryShort(?string $salary): string
{
    if ($salary === null || trim($salary) === '') {
        return '-';
    }

    return preg_replace_callback('/\d[\d.]*/', function (array $matches): string {
        $digits = (int) preg_replace('/\D/', '', $matches[0]);

        if ($digits >= 1_000_000) {
            $value = $digits / 1_000_000;

            return rtrim(rtrim(number_format($value, 1, '.', ''), '0'), '.') . 'jt';
        }

        if ($digits >= 1_000) {
            $value = $digits / 1_000;

            return rtrim(rtrim(number_format($value, 1, '.', ''), '0'), '.') . 'k';
        }

        return (string) $digits;
    }, $salary);
}

function formatFlatpickrDatetime(mixed $value): string
{
    if ($value === null || $value === '') {
        return '';
    }

    return \Carbon\Carbon::parse($value)->format('d-m-Y H:i:s');
}

function parseFlatpickrDatetime(?string $value): ?string
{
    if ($value === null || trim($value) === '') {
        return null;
    }

    return \Carbon\Carbon::createFromFormat('d-m-Y H:i:s', trim($value))->format('Y-m-d H:i:s');
}