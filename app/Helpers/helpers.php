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

    return 'IDR ' . number_format($amount, 0, ',', '.');
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

function normalizePhoneNumber(?string $phone, ?string $countryCode = '+62'): ?string
{
    if (empty($phone)) {
        return null;
    }

    $cleaned = preg_replace('/[^\d]/', '', trim($phone));
    if ($cleaned === '') {
        return null;
    }

    $cleanCountryCode = preg_replace('/[^\d]/', '', $countryCode ?: '62');

    // If number starts with country code followed by a trunk prefix '0' (e.g. 620812...)
    if (!empty($cleanCountryCode) && str_starts_with($cleaned, $cleanCountryCode . '0')) {
        $cleaned = $cleanCountryCode . substr($cleaned, strlen($cleanCountryCode) + 1);
    }

    // If number already starts with the country code, return as is
    if (!empty($cleanCountryCode) && str_starts_with($cleaned, $cleanCountryCode)) {
        return $cleaned;
    }

    // Strip leading national trunk zero (e.g. 0812... -> 812...)
    if (str_starts_with($cleaned, '0')) {
        $cleaned = ltrim($cleaned, '0');
    }

    // Prepend country code if not already present
    if (!empty($cleanCountryCode)) {
        return $cleanCountryCode . $cleaned;
    }

    return $cleaned;
}