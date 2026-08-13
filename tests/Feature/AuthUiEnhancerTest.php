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

it('injects the plugin configuration into the auth pages', function () {
    $response = $this->get('/admin/login');

    $response->assertSuccessful()
        ->assertSee('--form-panel-width: 45%')
        ->assertSee('images/emprendimiento-social.png', false)
        ->assertSee('opacity: 80%');
});
