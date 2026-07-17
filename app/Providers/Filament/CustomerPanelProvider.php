<?php

namespace App\Providers\Filament;

use App\Filament\Customer\Pages\Cart;
use App\Filament\Customer\Pages\Catalog;
use App\Filament\Customer\Pages\Checkout;
use App\Filament\Customer\Pages\Dashboard;
use App\Filament\Customer\Pages\History;
use App\Filament\Customer\Pages\Login;
use App\Filament\Customer\Pages\Profile;
use App\Filament\Customer\Pages\Register;
use App\Filament\Customer\Pages\TransactionDetail;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class CustomerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('customer')
            ->path('customer')
            ->login(Login::class)
            ->registration(Register::class)
            ->authGuard('pelanggan')
            ->homeUrl(fn () => route('filament.customer.pages.dashboard'))
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->topNavigation()
            ->breadcrumbs(false)
            ->maxContentWidth(Width::Full)
            ->pages([
                Dashboard::class,
                Catalog::class,
                Checkout::class,
                History::class,
                TransactionDetail::class,
                Profile::class,
                Cart::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Customer/Widgets'), for: 'App\Filament\Customer\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
