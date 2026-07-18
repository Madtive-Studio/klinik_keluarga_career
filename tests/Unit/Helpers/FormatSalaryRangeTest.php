<?php

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FormatSalaryRangeTest extends TestCase
{
    #[Test]
    public function itFormatsFullSalaryRange(): void
    {
        $this->assertSame('Rp 2.000.000 - Rp 5.000.000', formatSalaryRange(2_000_000, 5_000_000));
    }

    #[Test]
    public function itFormatsFixedSalaryWithoutRangeSeparator(): void
    {
        $this->assertSame('Rp 3.000.000', formatSalaryRange(3_000_000, 3_000_000));
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
