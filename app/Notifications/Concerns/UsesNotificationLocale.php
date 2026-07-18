<?php

namespace App\Notifications\Concerns;

trait UsesNotificationLocale
{
    protected function withNotificationLocale(): void
    {
        app()->setLocale(session('locale', config('app.locale')));
    }
}
