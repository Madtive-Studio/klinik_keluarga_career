<?php

require_once 'asset_helpers.php';

function getRouteMiddlewares()
{
    return request()->route()?->middleware() ?? [];
}

function formatSalaryAmountShort(?int $amount): string
{
    if ($amount === null || $amount <= 0) {
        return '-';
    }

    if ($amount >= 1_000_000) {
        $value = $amount / 1_000_000;

        return rtrim(rtrim(number_format($value, 1, '.', ''), '0'), '.') . 'jt';
    }

    if ($amount >= 1_000) {
        $value = $amount / 1_000;

        return rtrim(rtrim(number_format($value, 1, '.', ''), '0'), '.') . 'k';
    }

    return (string) $amount;
}

function formatSalaryAmount(?int $amount): string
{
    if ($amount === null || $amount <= 0) {
        return '-';
    }

    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function formatSalaryRange(?int $min, ?int $max, bool $short = false): string
{
    if (($min === null || $min <= 0) && ($max === null || $max <= 0)) {
        return '-';
    }

    $format = $short ? 'formatSalaryAmountShort' : 'formatSalaryAmount';

    if ($min !== null && $min > 0 && $max !== null && $max > 0) {
        if ($min === $max) {
            return $format($min);
        }

        return $format($min) . ' - ' . $format($max);
    }

    $amount = ($min !== null && $min > 0) ? $min : $max;

    return $format($amount);
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

function parseJobExperienceYears(?string $experience): int
{
    if ($experience === null || trim($experience) === '') {
        return 0;
    }

    $normalized = strtolower(trim($experience));

    if (
        str_contains($normalized, 'fresh graduate')
        || str_contains($normalized, 'tanpa pengalaman')
        || str_contains($normalized, 'belum berpengalaman')
    ) {
        return 0;
    }

    if (preg_match('/(\d+)\s*\+/', $normalized, $matches)) {
        return (int) $matches[1];
    }

    if (preg_match('/(\d+)\s*(?:-\s*\d+)?\s*(?:tahun|thn|years?|year)/', $normalized, $matches)) {
        return (int) $matches[1];
    }

    if (preg_match('/(\d+)\s*[-–]\s*(\d+)/', $normalized, $matches)) {
        return (int) $matches[1];
    }

    if (preg_match('/(\d+)/', $normalized, $matches)) {
        return (int) $matches[1];
    }

    return 0;
}