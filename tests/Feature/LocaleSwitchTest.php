<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LocaleSwitchTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function itStoresLocaleInSessionAndSetsAppLocale(): void
    {
        $this->from('/candidate')
            ->get(route('locale.switch', 'en'))
            ->assertRedirect('/candidate')
            ->assertSessionHas('locale', 'en');

        $this->get('/candidate');
        $this->assertSame('en', app()->getLocale());
    }

    #[Test]
    public function itRejectsUnsupportedLocale(): void
    {
        $this->get(route('locale.switch', 'fr'))->assertNotFound();
    }
}
