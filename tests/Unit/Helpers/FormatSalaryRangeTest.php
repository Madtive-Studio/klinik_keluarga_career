<?php

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FormatSalaryRangeTest extends TestCase
{
    #[Test]
    public function itFormatsFullSalaryRange(): void
    {
        $this->assertSame('IDR 2.000.000 - IDR 5.000.000', formatSalaryRange(2_000_000, 5_000_000));
    }

    #[Test]
    public function itFormatsFixedSalaryWithoutRangeSeparator(): void
    {
        $this->assertSame('IDR 3.000.000', formatSalaryRange(3_000_000, 3_000_000));
    }

    #[Test]
    public function itFormatsShortSalaryRangeForDatatable(): void
    {
        $this->assertSame('2jt - 5jt', formatSalaryRange(2_000_000, 5_000_000, true));
    }

    #[Test]
    public function itReturnsDashWhenSalaryIsMissing(): void
    {
        $this->assertSame('-', formatSalaryRange(null, null));
    }
}
