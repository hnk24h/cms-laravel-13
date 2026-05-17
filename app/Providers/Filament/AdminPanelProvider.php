<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Support\Facades\FilamentView;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Support\HtmlString;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->darkMode()
            ->maxContentWidth(Width::Full)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
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

    public function boot(): void
    {
        FilamentView::registerRenderHook(
            'panels::head.end',
            fn (): HtmlString => new HtmlString(<<<'HTML'
                <style>
                    /*
                     * ╔═══════════════════════════════════════════════════════════╗
                     * ║  WORKSPACE DESIGN SYSTEM — Developer-First CMS           ║
                     * ║  Linear · Vercel · Raycast · Sanity · Payload            ║
                     * ╠═══════════════════════════════════════════════════════════╣
                     * ║  PHILOSOPHY                                               ║
                     * ║  · Surfaces imply depth — borders are structural, not    ║
                     * ║    decorative                                             ║
                     * ║  · Motion confirms intent — not entertains               ║
                     * ║  · Density serves workflow — not fills space             ║
                     * ║  · Typography creates hierarchy — color reinforces it    ║
                     * ╚═══════════════════════════════════════════════════════════╝
                     */

                    /* ═══ 0. FORM BAR — HIDDEN (lives in sticky header) ═══ */
                    .fi-main .fi-sc-actions .fi-ac { display: none !important; }

                    /* ═══ 1. RENDERING FOUNDATION ═══ */
                    body {
                        -webkit-font-smoothing: antialiased;
                        -moz-osx-font-smoothing: grayscale;
                        font-feature-settings: 'cv11', 'ss01', 'tnum';
                        letter-spacing: -0.011em;
                    }

                    /* ═══════════════════════════════════════════════════════════
                     * APP SHELL — TOPBAR
                     * Minimal titlebar chrome. Nothing decorative, only functional.
                     * ═══════════════════════════════════════════════════════════ */
                    .fi-topbar nav {
                        background-color: #ffffff !important;
                        border-bottom: 1px solid rgba(0,0,0,0.07) !important;
                        box-shadow: none !important;
                    }
                    .dark .fi-topbar nav {
                        background-color: #0d0d0f !important;
                        border-bottom-color: rgba(255,255,255,0.055) !important;
                    }

                    /* ═══════════════════════════════════════════════════════════
                     * APP SHELL — SIDEBAR
                     * Dense Linear-style nav. 13px / 32px items.
                     * Sidebar ≠ menu. It is persistent context.
                     * ═══════════════════════════════════════════════════════════ */
                    .fi-sidebar {
                        background-color: #f8f8f9 !important;
                        border-right: 1px solid rgba(0,0,0,0.07) !important;
                        box-shadow: none !important;
                    }
                    .dark .fi-sidebar {
                        background-color: #0d0d0f !important;
                        border-right-color: rgba(255,255,255,0.055) !important;
                    }

                    /* Brand/header area */
                    .fi-sidebar-header {
                        border-bottom: 1px solid rgba(0,0,0,0.06) !important;
                        padding: 0.75rem 1rem !important;
                    }
                    .dark .fi-sidebar-header {
                        border-bottom-color: rgba(255,255,255,0.04) !important;
                    }

                    /* Group labels — structural, not decorative */
                    .fi-sidebar-nav-group-label {
                        font-size: 0.625rem !important;
                        font-weight: 700 !important;
                        letter-spacing: 0.1em !important;
                        text-transform: uppercase !important;
                        color: #c0c0c8 !important;
                        padding: 1rem 0.875rem 0.3rem !important;
                    }
                    .dark .fi-sidebar-nav-group-label {
                        color: #383840 !important;
                    }

                    /* Nav items — 32px density, Linear rhythm */
                    .fi-sidebar-item-button {
                        border-radius: 5px !important;
                        margin: 1px 6px !important;
                        padding: 0.33rem 0.5rem !important;
                        gap: 0.5rem !important;
                        font-size: 0.8125rem !important;
                        font-weight: 400;
                        letter-spacing: -0.006em;
                        color: #5c5c68 !important;
                        transition:
                            background-color 70ms ease,
                            color 70ms ease,
                            box-shadow 70ms ease !important;
                    }
                    .dark .fi-sidebar-item-button { color: #5e5e6e !important; }

                    /* Hover — intention, not celebration */
                    .fi-sidebar-item-button:hover {
                        background-color: rgba(0,0,0,0.05) !important;
                        color: #111118 !important;
                    }
                    .dark .fi-sidebar-item-button:hover {
                        background-color: rgba(255,255,255,0.06) !important;
                        color: #e0e0ea !important;
                    }

                    /* Active — amber left-rail (2.5px) + tinted fill */
                    .fi-sidebar-item-button[aria-current="page"],
                    .fi-sidebar-item-button.fi-active {
                        background-color: rgba(245,158,11,0.07) !important;
                        color: #a16207 !important;
                        font-weight: 500 !important;
                        box-shadow: inset 2.5px 0 0 #f59e0b !important;
                    }
                    .dark .fi-sidebar-item-button[aria-current="page"],
                    .dark .fi-sidebar-item-button.fi-active {
                        background-color: rgba(245,158,11,0.09) !important;
                        color: #fbbf24 !important;
                        font-weight: 500 !important;
                        box-shadow: inset 2.5px 0 0 #f59e0b !important;
                    }

                    /* Icons — restrained opacity. Active/hover = full presence */
                    .fi-sidebar-item-icon {
                        opacity: 0.38 !important;
                        width: 0.9375rem !important;
                        height: 0.9375rem !important;
                        flex-shrink: 0;
                        transition: opacity 70ms ease;
                    }
                    .fi-sidebar-item-button:hover .fi-sidebar-item-icon,
                    .fi-sidebar-item-button[aria-current="page"] .fi-sidebar-item-icon,
                    .fi-sidebar-item-button.fi-active .fi-sidebar-item-icon {
                        opacity: 0.9;
                    }

                    /* ═══════════════════════════════════════════════════════════
                     * APP SHELL — WORKSPACE (main content)
                     * Slightly recessed. Cards and tables float above it.
                     * ═══════════════════════════════════════════════════════════ */
                    .fi-main {
                        background-color: #f0f0f2 !important;
                    }
                    .dark .fi-main {
                        background-color: #111113 !important;
                    }

                    /* ═══════════════════════════════════════════════════════════
                     * STICKY PAGE HEADER
                     * Blends with workspace bg. Doesn't break reading flow.
                     * ═══════════════════════════════════════════════════════════ */
                    header.fi-header {
                        position: sticky;
                        top: 4rem;
                        z-index: 20;
                        background-color: #f0f0f2;
                        border-bottom: 1px solid rgba(0,0,0,0.06);
                        padding: 0.5rem 0 0.625rem;
                    }
                    .dark header.fi-header {
                        background-color: #111113;
                        border-bottom-color: rgba(255,255,255,0.055);
                    }

                    /* Page heading — not massive, not weak */
                    .fi-header h1,
                    .fi-page-heading {
                        font-size: 1.0625rem !important;
                        font-weight: 600 !important;
                        letter-spacing: -0.025em !important;
                    }

                    /* ═══════════════════════════════════════════════════════════
                     * SURFACE SYSTEM — SECTIONS & CARDS
                     *
                     * Z-axis:
                     *   Layer 0 = workspace (#f0f0f2 / #111113)
                     *   Layer 1 = card/section (white / #17171a)
                     *   Layer 2 = nested card (white / #1c1c20)
                     *
                     * Elevation = shadow, not border weight.
                     * ═══════════════════════════════════════════════════════════ */
                    .fi-section {
                        border-radius: 8px !important;
                        border: 1px solid rgba(0,0,0,0.07) !important;
                        background: #ffffff !important;
                        box-shadow:
                            0 1px 2px rgba(0,0,0,0.05),
                            0 2px 6px rgba(0,0,0,0.03) !important;
                        transition: box-shadow 180ms ease !important;
                    }
                    .dark .fi-section {
                        border-color: rgba(255,255,255,0.07) !important;
                        background: #17171a !important;
                        box-shadow:
                            0 1px 3px rgba(0,0,0,0.6),
                            0 2px 8px rgba(0,0,0,0.4) !important;
                    }
                    .fi-section-header {
                        border-bottom: 1px solid rgba(0,0,0,0.05) !important;
                        padding: 0.875rem 1.125rem 0.75rem !important;
                    }
                    .dark .fi-section-header {
                        border-bottom-color: rgba(255,255,255,0.05) !important;
                    }
                    .fi-section-content-ctn {
                        padding: 1.125rem !important;
                    }

                    /* ═══════════════════════════════════════════════════════════
                     * TABLE SYSTEM — Interactive List Views
                     *
                     * Philosophy:
                     *   Tables are object lists, not spreadsheets.
                     *   · No vertical cell dividers
                     *   · Row = one object, one action context
                     *   · Actions reveal on hover (opacity 0.25 → 1)
                     *   · Header = guide text (11px ALL CAPS), not chrome
                     *   · Last row has no bottom border
                     * ═══════════════════════════════════════════════════════════ */

                    /* Table shell — floats above workspace */
                    .fi-ta {
                        border-radius: 8px !important;
                        border: 1px solid rgba(0,0,0,0.07) !important;
                        background: #ffffff !important;
                        box-shadow:
                            0 1px 2px rgba(0,0,0,0.05),
                            0 2px 6px rgba(0,0,0,0.03) !important;
                    }
                    .dark .fi-ta {
                        border-color: rgba(255,255,255,0.07) !important;
                        background: #17171a !important;
                        box-shadow: 0 2px 10px rgba(0,0,0,0.5) !important;
                    }

                    /* Search/filter bar — same surface as table */
                    .fi-ta-header {
                        background: #ffffff !important;
                        border-bottom: 1px solid rgba(0,0,0,0.06) !important;
                        padding: 0.625rem 0.875rem !important;
                    }
                    .dark .fi-ta-header {
                        background: #17171a !important;
                        border-bottom-color: rgba(255,255,255,0.055) !important;
                    }

                    /* Column headers — guide text, not chrome */
                    .fi-ta-header-cell {
                        background: transparent !important;
                        font-size: 0.6875rem !important;
                        font-weight: 700 !important;
                        letter-spacing: 0.065em !important;
                        text-transform: uppercase !important;
                        color: #a0a0b0 !important;
                        padding: 0.5rem 0.875rem !important;
                        border-bottom: 1px solid rgba(0,0,0,0.06) !important;
                        white-space: nowrap;
                    }
                    .dark .fi-ta-header-cell {
                        color: #404050 !important;
                        border-bottom-color: rgba(255,255,255,0.055) !important;
                    }

                    /* Rows — objects, not spreadsheet cells */
                    .fi-ta-row {
                        border-bottom: 1px solid rgba(0,0,0,0.042) !important;
                        transition: background-color 60ms ease !important;
                    }
                    .dark .fi-ta-row {
                        border-bottom-color: rgba(255,255,255,0.032) !important;
                    }
                    .fi-ta-row:last-child {
                        border-bottom: none !important;
                    }
                    .fi-ta-row:hover {
                        background-color: rgba(0,0,0,0.018) !important;
                    }
                    .dark .fi-ta-row:hover {
                        background-color: rgba(255,255,255,0.02) !important;
                    }

                    /* Cells — comfortable reading density (13px, 10px v-pad) */
                    .fi-ta-cell {
                        padding: 0.625rem 0.875rem !important;
                        font-size: 0.8125rem !important;
                        color: #1c1c24 !important;
                        vertical-align: middle !important;
                        border-right: none !important;
                    }
                    .dark .fi-ta-cell {
                        color: #c4c4d0 !important;
                    }

                    /* Row actions — muted at rest, full presence on hover */
                    .fi-ta-row .fi-ac {
                        opacity: 0.25;
                        transition: opacity 100ms ease !important;
                    }
                    .fi-ta-row:hover .fi-ac,
                    .fi-ta-row:focus-within .fi-ac {
                        opacity: 1;
                    }

                    /* Pagination — small, informational */
                    .fi-ta-footer {
                        background: transparent !important;
                        border-top: 1px solid rgba(0,0,0,0.06) !important;
                        padding: 0.5rem 0.875rem !important;
                        font-size: 0.75rem !important;
                    }
                    .dark .fi-ta-footer {
                        border-top-color: rgba(255,255,255,0.055) !important;
                    }

                    /* Empty state — centered, atmospheric */
                    .fi-ta-empty-state {
                        padding: 3.5rem 1.5rem !important;
                        text-align: center !important;
                    }
                    .fi-ta-empty-state-icon {
                        opacity: 0.18 !important;
                        width: 2.75rem !important;
                        height: 2.75rem !important;
                        margin: 0 auto 0.875rem !important;
                    }
                    .fi-ta-empty-state-heading {
                        font-size: 0.9375rem !important;
                        font-weight: 600 !important;
                        letter-spacing: -0.02em !important;
                        margin-bottom: 0.3rem !important;
                    }
                    .fi-ta-empty-state-description {
                        font-size: 0.8125rem !important;
                        color: #a0a0b0 !important;
                    }

                    /* ═══════════════════════════════════════════════════════════
                     * DASHBOARD STAT WIDGETS
                     * Spring-based lift on hover. Feels alive, not animated.
                     * ═══════════════════════════════════════════════════════════ */
                    .fi-wi-stats-overview-stat {
                        border-radius: 8px !important;
                        border: 1px solid rgba(0,0,0,0.07) !important;
                        background: #ffffff !important;
                        box-shadow: 0 1px 3px rgba(0,0,0,0.05) !important;
                        padding: 1.25rem !important;
                        transition:
                            box-shadow 220ms cubic-bezier(0.34, 1.56, 0.64, 1),
                            transform 220ms cubic-bezier(0.34, 1.56, 0.64, 1) !important;
                    }
                    .fi-wi-stats-overview-stat:hover {
                        box-shadow: 0 6px 20px rgba(0,0,0,0.09) !important;
                        transform: translateY(-2px);
                    }
                    .dark .fi-wi-stats-overview-stat {
                        border-color: rgba(255,255,255,0.07) !important;
                        background: #17171a !important;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.5) !important;
                    }
                    .dark .fi-wi-stats-overview-stat:hover {
                        box-shadow: 0 8px 28px rgba(0,0,0,0.6) !important;
                        transform: translateY(-2px);
                    }

                    /* ═══════════════════════════════════════════════════════════
                     * BUTTON SYSTEM
                     * Spring micro-lift on hover. Active = snap back.
                     * ═══════════════════════════════════════════════════════════ */
                    .fi-btn {
                        border-radius: 6px !important;
                        font-size: 0.8125rem !important;
                        font-weight: 500 !important;
                        letter-spacing: -0.005em;
                        transition:
                            background-color 80ms ease,
                            box-shadow 80ms ease,
                            transform 140ms cubic-bezier(0.34, 1.56, 0.64, 1) !important;
                    }
                    .fi-btn:not(:disabled):hover {
                        transform: translateY(-1px);
                        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
                    }
                    .fi-btn:not(:disabled):active {
                        transform: translateY(0);
                        box-shadow: none;
                        transition-duration: 60ms !important;
                    }

                    /* ═══════════════════════════════════════════════════════════
                     * FORM / EDITING SURFACE
                     * Forms should feel like writing, not filling in a spreadsheet.
                     * ═══════════════════════════════════════════════════════════ */
                    .fi-input,
                    .fi-select-input,
                    .fi-textarea {
                        border-radius: 6px !important;
                        font-size: 0.875rem !important;
                        letter-spacing: -0.01em;
                        border-color: rgba(0,0,0,0.12) !important;
                        transition:
                            border-color 90ms ease,
                            box-shadow 90ms ease !important;
                    }
                    .dark .fi-input,
                    .dark .fi-select-input,
                    .dark .fi-textarea {
                        background-color: rgba(255,255,255,0.03) !important;
                        border-color: rgba(255,255,255,0.09) !important;
                        color: #e0e0ea !important;
                    }
                    .fi-input:focus,
                    .fi-select-input:focus,
                    .fi-textarea:focus {
                        border-color: #f59e0b !important;
                        box-shadow: 0 0 0 3px rgba(245,158,11,0.14) !important;
                        outline: none !important;
                    }

                    /* Field labels */
                    .fi-fo-field-wrp-label {
                        font-size: 0.75rem !important;
                        font-weight: 500 !important;
                        letter-spacing: -0.005em !important;
                        color: #3c3c48 !important;
                        margin-bottom: 0.25rem !important;
                    }
                    .dark .fi-fo-field-wrp-label { color: #88889a !important; }

                    /* Helper text */
                    .fi-fo-helper-text {
                        font-size: 0.7rem !important;
                        color: #aaaabc !important;
                        line-height: 1.4 !important;
                    }

                    /* ═══════════════════════════════════════════════════════════
                     * BADGE SYSTEM — Compact semantic tokens
                     * ═══════════════════════════════════════════════════════════ */
                    .fi-badge {
                        border-radius: 4px !important;
                        font-size: 0.6875rem !important;
                        font-weight: 600 !important;
                        letter-spacing: 0.03em !important;
                        padding: 0.1rem 0.4375rem !important;
                    }

                    /* ═══════════════════════════════════════════════════════════
                     * SCROLLBAR — Invisible until needed
                     * Appears only when the container is being scrolled/hovered.
                     * ═══════════════════════════════════════════════════════════ */
                    .fi-sidebar-nav,
                    .fi-main,
                    .fi-page {
                        scrollbar-width: thin;
                        scrollbar-color: transparent transparent;
                    }
                    .fi-sidebar-nav:hover,
                    .fi-main:hover,
                    .fi-page:hover {
                        scrollbar-color: rgba(0,0,0,0.15) transparent;
                    }
                    .dark .fi-sidebar-nav:hover,
                    .dark .fi-main:hover,
                    .dark .fi-page:hover {
                        scrollbar-color: rgba(255,255,255,0.1) transparent;
                    }
                    ::-webkit-scrollbar { width: 5px; height: 5px; }
                    ::-webkit-scrollbar-track { background: transparent; }
                    ::-webkit-scrollbar-thumb {
                        background: transparent;
                        border-radius: 99px;
                        transition: background 200ms ease;
                    }
                    *:hover::-webkit-scrollbar-thumb {
                        background: rgba(0,0,0,0.14);
                    }
                    .dark *:hover::-webkit-scrollbar-thumb {
                        background: rgba(255,255,255,0.1);
                    }

                    /* ══════════════════════════════════════════════════════════════
                     * POST EDITOR — Editorial Writing Experience
                     * Notion × Medium × Sanity — writing immersion
                     * ══════════════════════════════════════════════════════════════ */

                    /* Identity section (title + slug + excerpt): strip all chrome */
                    .post-identity-section {
                        border: none !important;
                        box-shadow: none !important;
                        background: transparent !important;
                        border-radius: 0 !important;
                        margin-bottom: 0 !important;
                    }
                    .post-identity-section .fi-section-header { display: none !important; }
                    .post-identity-section .fi-section-content-ctn {
                        padding: 0 0 0.25rem !important;
                    }
                    .post-identity-section .fi-fo-field-wrp { margin-bottom: 0 !important; }

                    /* TITLE — document heading, Notion/Medium style */
                    .post-title-input {
                        font-size: 1.875rem !important;
                        font-weight: 700 !important;
                        letter-spacing: -0.035em !important;
                        line-height: 1.2 !important;
                        color: #0c0c14 !important;
                        background: transparent !important;
                        border-color: transparent !important;
                        box-shadow: none !important;
                        padding: 0.375rem 0 !important;
                        height: auto !important;
                    }
                    .dark .post-title-input { color: #f0f0f8 !important; }
                    .post-title-input:focus {
                        border-color: transparent !important;
                        box-shadow: none !important;
                        outline: none !important;
                    }
                    .post-title-input::placeholder { color: rgba(120,120,150,0.35) !important; }

                    /* SLUG — monospace URL path hint, secondary */
                    .post-slug-input {
                        font-family: ui-monospace, 'SFMono-Regular', 'Cascadia Code', monospace !important;
                        font-size: 0.8125rem !important;
                        color: #b0b0c2 !important;
                        background: transparent !important;
                        border-color: transparent !important;
                        box-shadow: none !important;
                        letter-spacing: 0.01em !important;
                        padding: 0.1rem 0 !important;
                    }
                    .dark .post-slug-input { color: #444456 !important; }
                    .post-slug-input:focus {
                        color: #3a3a4e !important;
                        border-color: rgba(245,158,11,0.35) !important;
                        box-shadow: 0 0 0 2px rgba(245,158,11,0.1) !important;
                        background: rgba(245,158,11,0.025) !important;
                    }
                    .dark .post-slug-input:focus {
                        color: #d0d0e0 !important;
                        background: rgba(245,158,11,0.04) !important;
                    }

                    /* EXCERPT — subtitle tone, secondary voice */
                    .post-excerpt-area textarea {
                        font-size: 1.0625rem !important;
                        line-height: 1.65 !important;
                        color: #707080 !important;
                        background: transparent !important;
                        border-color: transparent !important;
                        box-shadow: none !important;
                        resize: none !important;
                    }
                    .dark .post-excerpt-area textarea { color: #606070 !important; }
                    .post-excerpt-area textarea:focus {
                        border-color: transparent !important;
                        box-shadow: none !important;
                    }
                    .post-excerpt-area textarea::placeholder { color: rgba(120,120,150,0.38) !important; }

                    /* BODY SECTION — clean white writing surface */
                    .post-body-section {
                        background: #ffffff !important;
                        border: 1px solid rgba(0,0,0,0.07) !important;
                        box-shadow: 0 1px 4px rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.025) !important;
                    }
                    .dark .post-body-section {
                        background: #17171a !important;
                        border-color: rgba(255,255,255,0.07) !important;
                    }
                    .post-body-section .fi-section-header { display: none !important; }
                    .post-body-section .fi-section-content-ctn { padding: 0 !important; }

                    /* Rich editor: remove inherited chrome, let the content breathe */
                    .post-body-section .fi-fo-field-wrp { margin-bottom: 0 !important; }
                    .post-body-section .fi-rich-editor {
                        border: none !important;
                        box-shadow: none !important;
                        border-radius: 0 !important;
                    }

                    /* Toolbar: subtle divider at top of writing surface */
                    .post-body-section .fi-rich-editor-toolbar {
                        border-bottom: 1px solid rgba(0,0,0,0.06) !important;
                        background: #fafafa !important;
                        padding: 0.375rem 0.625rem !important;
                        border-top-left-radius: 0.5rem !important;
                        border-top-right-radius: 0.5rem !important;
                    }
                    .dark .post-body-section .fi-rich-editor-toolbar {
                        border-bottom-color: rgba(255,255,255,0.06) !important;
                        background: #111114 !important;
                    }

                    /* Editor content: comfortable reading width, generous line height */
                    .post-body-section .ProseMirror,
                    .post-body-section .tiptap,
                    .post-body-section [contenteditable="true"] {
                        min-height: 480px !important;
                        padding: 1.75rem 2rem !important;
                        font-size: 1rem !important;
                        line-height: 1.8 !important;
                        color: #1a1a24 !important;
                        letter-spacing: -0.004em;
                        caret-color: #f59e0b;
                    }
                    .dark .post-body-section .ProseMirror,
                    .dark .post-body-section .tiptap,
                    .dark .post-body-section [contenteditable="true"] {
                        color: #d4d4e0 !important;
                    }

                    /* Editor typography inside the writing area */
                    .post-body-section h1, .post-body-section h2,
                    .post-body-section h3, .post-body-section h4 {
                        font-weight: 700;
                        letter-spacing: -0.025em;
                        line-height: 1.3;
                        margin-top: 1.6em;
                        margin-bottom: 0.4em;
                    }
                    .post-body-section p { margin-bottom: 1em; }
                    .post-body-section blockquote {
                        border-left: 3px solid #f59e0b;
                        margin-left: 0;
                        padding-left: 1rem;
                        color: #6e6e80;
                        font-style: italic;
                    }
                    .dark .post-body-section blockquote { color: #888899; }
                    .post-body-section code {
                        font-family: ui-monospace, 'SFMono-Regular', monospace;
                        font-size: 0.875em;
                        background: rgba(0,0,0,0.05);
                        padding: 0.1em 0.35em;
                        border-radius: 3px;
                    }
                    .dark .post-body-section code { background: rgba(255,255,255,0.07); }

                    /* SEO section — understated, doesn't distract during writing */
                    .post-seo-section .fi-section-header {
                        opacity: 0.55;
                        transition: opacity 120ms ease;
                    }
                    .post-seo-section:hover .fi-section-header,
                    .post-seo-section .fi-section-header:hover {
                        opacity: 1;
                    }
                </style>
            HTML),
        );
    }
}
