<?php

use App\Enums\DocumentType;

it('returns all document type values', function () {
    expect(DocumentType::getValues())
        ->toContain('CV')
        ->toContain('MCU')
        ->toContain('IJAZAH');
});

it('returns labels for each document type', function () {
    $labels = DocumentType::getWithLabels();

    expect($labels)->toBeArray()
        ->and($labels)->toHaveKey(DocumentType::CV->value);
});

it('maps document types to categories', function () {
    expect(DocumentType::CV->getCategory())->toBeInstanceOf(\App\Enums\DocumentCategory::class);
});
