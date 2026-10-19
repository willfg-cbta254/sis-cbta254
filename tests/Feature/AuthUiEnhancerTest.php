<?php

use DiogoGPinto\AuthUIEnhancer\Pages\Auth\AuthUiEnhancerLogin;
use DiogoGPinto\AuthUIEnhancer\Pages\Auth\EmailVerification\AuthUiEnhancerEmailVerificationPrompt;
use DiogoGPinto\AuthUIEnhancer\Pages\Auth\PasswordReset\AuthUiEnhancerRequestPasswordReset;
use DiogoGPinto\AuthUIEnhancer\Pages\Auth\PasswordReset\AuthUiEnhancerResetPassword;
use Filament\Facades\Filament;

it('registers the enhanced login page on every panel', function (string $panel, string $path) {
    $routeAction = Filament::getPanel($panel)->getLoginRouteAction();

    expect($routeAction)->toBe(AuthUiEnhancerLogin::class)
        ->and($this->get($path)->assertSuccessful()->assertSee('custom-auth-wrapper'));
})->with([
    'admin' => ['admin', '/admin/login'],
    'alumno' => ['alumno', '/alumno/login'],
    'financiero' => ['financiero', '/financiero/login'],
    'profesor' => ['profesor', '/profesor/login'],
    'servicios-escolares' => ['servicios-escolares', '/servicios-escolares/login'],
]);

it('registers the enhanced password reset pages on every panel', function (string $panel, string $path) {
    $requestAction = Filament::getPanel($panel)->getRequestPasswordResetRouteAction();
    $resetAction = Filament::getPanel($panel)->getResetPasswordRouteAction();

    expect($requestAction)->toBe(AuthUiEnhancerRequestPasswordReset::class)
        ->and($resetAction)->toBe(AuthUiEnhancerResetPassword::class)
        ->and($this->get($path)->assertSuccessful()->assertSee('custom-auth-wrapper'));
})->with([
    'admin' => ['admin', '/admin/password-reset/request'],
    'alumno' => ['alumno', '/alumno/password-reset/request'],
    'financiero' => ['financiero', '/financiero/password-reset/request'],
    'profesor' => ['profesor', '/profesor/password-reset/request'],
    'servicios-escolares' => ['servicios-escolares', '/servicios-escolares/password-reset/request'],
]);

it('registers the enhanced email verification page', function () {
    $action = Filament::getPanel('admin')->getEmailVerificationPromptRouteAction();

    expect($action)->toBe(AuthUiEnhancerEmailVerificationPrompt::class);
});

it('injects the form panel width on every panel', function (string $path, string $formPanelWidth) {
    $this->get($path)
        ->assertSuccessful()
        ->assertSee("--form-panel-width: {$formPanelWidth}");
})->with([
    ['/admin/login', '46%'],
    ['/alumno/login', '46%'],
    ['/financiero/login', '46%'],
    ['/profesor/login', '46%'],
    ['/servicios-escolares/login', '46%'],
]);

it('renders the shared custom empty panel view on every panel', function (string $path, string $badge, string $image) {
    $this->get($path)
        ->assertSuccessful()
        ->assertSee($badge)
        ->assertSee($image, false)
        ->assertSee('images/logo.png', false);
})->with([
    ['/admin/login', 'Panel Administrativo', 'images/admin.jpg'],
    ['/alumno/login', 'Panel Alumnos', 'images/Alumnos.jpg'],
    ['/profesor/login', 'Panel Docentes', 'images/Teacher.jpg'],
    ['/financiero/login', 'Panel Financiero', 'images/Financieros.jpg'],
    ['/servicios-escolares/login', 'Panel Servicios Escolares', 'images/ControlEscolar.jpg'],
]);
