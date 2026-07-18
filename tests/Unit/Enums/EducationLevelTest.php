<?php

namespace Tests\Unit\Enums;

use App\Enums\EducationLevel;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EducationLevelTest extends TestCase
{
    #[Test]
    public function valuesIncludeD4(): void
    {
        $this->assertSame(
            ['SMA', 'D3', 'D4', 'S1', 'S2', 'S3'],
            EducationLevel::values()
        );
    }

    #[Test]
    public function smaUsesIndonesianLabel(): void
    {
        $this->assertSame('SMA/SMK Sederajat', EducationLevel::SMA->label());
        $this->assertSame('SMA/SMK Sederajat', EducationLevel::labelOf('SMA'));
    }

    #[Test]
    public function d4HasExpectedLabelAndRank(): void
    {
        $this->assertSame('D4', EducationLevel::D4->label());
        $this->assertSame(3, EducationLevel::D4->rank());
        $this->assertSame(3, EducationLevel::rankOf('D4'));
    }

    #[Test]
    public function ranksFollowEducationProgression(): void
    {
        $this->assertLessThan(EducationLevel::D3->rank(), EducationLevel::SMA->rank());
        $this->assertLessThan(EducationLevel::D4->rank(), EducationLevel::D3->rank());
        $this->assertLessThan(EducationLevel::S1->rank(), EducationLevel::D4->rank());
        $this->assertLessThan(EducationLevel::S2->rank(), EducationLevel::S1->rank());
        $this->assertLessThan(EducationLevel::S3->rank(), EducationLevel::S2->rank());
    }

    #[Test]
    public function labelOfReturnsDashForNull(): void
    {
        $this->assertSame('-', EducationLevel::labelOf(null));
    }

    #[Test]
    public function rankOfReturnsZeroForUnknownLevel(): void
    {
        $this->assertSame(0, EducationLevel::rankOf('UNKNOWN'));
    }
}
