<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\Register;
use BezhanSalleh\FilamentShield\Resources\RoleResource;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;
use Filament\Pages;
use Filament\View\PanelsRenderHook;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\View\View;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('SIMPLE BPSDM KALTIM')
            ->login(Login::class)
            ->registration(Register::class)
            ->maxContentWidth('10xl')
            ->renderHook(
                PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE, 
                fn(): View => view('components.total')
            )
            ->sidebarWidth(300)
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->resources([
                RoleResource::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    public function boot(): void
    {
        // Register render hook untuk year selector di topbar
        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_END,
            fn (): View => view('filament.hooks.year-selector'),
        );
    }
}