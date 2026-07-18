<?php

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ParseJobExperienceYearsTest extends TestCase
{
    #[Test]
    public function itReturnsZeroForFreshGraduate(): void
    {
        $this->assertSame(0, parseJobExperienceYears('Fresh Graduate'));
        $this->assertSame(0, parseJobExperienceYears('Tanpa pengalaman'));
    }

    #[Test]
    public function itParsesIndonesianYearFormats(): void
    {
        $this->assertSame(2, parseJobExperienceYears('2 Tahun'));
        $this->assertSame(1, parseJobExperienceYears('1-2 tahun'));
        $this->assertSame(5, parseJobExperienceYears('5 Tahun ke atas'));
    }

    #[Test]
    public function itParsesEnglishYearFormats(): void
    {
        $this->assertSame(1, parseJobExperienceYears('1-2 years of experience'));
        $this->assertSame(3, parseJobExperienceYears('3+ years'));
    }

    #[Test]
    public function itReturnsZeroForEmptyValue(): void
    {
        $this->assertSame(0, parseJobExperienceYears(null));
        $this->assertSame(0, parseJobExperienceYears(''));
    }
}
