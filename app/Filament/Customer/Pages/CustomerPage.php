<?php

namespace App\Filament\Customer\Pages;

use Filament\Pages\Page;

class CustomerPage extends Page
{
    protected static string $layout = 'components.customer-layout';

    public function getLayout(): string
    {
        return static::$layout;
    }
}
