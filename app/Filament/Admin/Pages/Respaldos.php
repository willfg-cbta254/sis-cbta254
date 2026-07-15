<?php

namespace App\Filament\Admin\Pages;

use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use UnitEnum;
use ZipArchive;

class Respaldos extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?string $title = 'Respaldos';

    protected static ?string $navigationLabel = 'Respaldos';

    protected static string|UnitEnum|null $navigationGroup = 'Usuarios';

    protected static ?int $navigationSort = 50;

    protected string $view = 'filament.admin.pages.respaldos';

    public static function can(): bool
    {
        return auth()->user()?->can('ver usuarios') ?? false;
    }

    public function getBreadcrumbs(): array
    {
        return [
            url()->route('filament.admin.pages.dashboard') => 'Dashboard',
            'Respaldos',
        ];
    }

    public function getBackups(): array
    {
        $disk = Storage::disk('backups');

        if (! $disk->exists('')) {
            return [];
        }

        $files = collect($disk->files())
            ->filter(fn (string $path): bool => Str::endsWith($path, '.zip'))
            ->map(fn (string $path): array => [
                'path' => $path,
                'name' => basename($path),
                'size' => $disk->size($path),
                'size_for_humans' => $this->formatBytes($disk->size($path)),
                'last_modified' => $disk->lastModified($path),
            ])
            ->sortByDesc('last_modified')
            ->values()
            ->toArray();

        return $files;
    }

    public function createBackupAction(): Action
    {
        return Action::make('createBackup')
            ->label('Crear respaldo')
            ->icon('heroicon-o-play')
            ->color('success')
            ->action(function (): void {
                try {
                    $this->runMysqldump();

                    Notification::make()
                        ->title('Respaldo creado exitosamente')
                        ->success()
                        ->send();
                } catch (\Throwable $e) {
                    Notification::make()
                        ->title('Error al crear el respaldo')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();

                    report($e);
                }
            });
    }

    protected function runMysqldump(): void
    {
        $dbHost = config('database.connections.mysql.host', '127.0.0.1');
        $dbPort = config('database.connections.mysql.port', '3306');
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');
        $dumpBinPath = config('database.connections.mysql.dump.dump_binary_path', '');

        $mysqldump = $dumpBinPath
            ? rtrim($dumpBinPath, '\\/').DIRECTORY_SEPARATOR.'mysqldump.exe'
            : 'mysqldump';

        $tempDir = storage_path('app'.DIRECTORY_SEPARATOR.'backup-temp');
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $timestamp = Carbon::now()->format('Y-m-d-H-i-s');
        $sqlFile = $tempDir.DIRECTORY_SEPARATOR."{$dbName}-{$timestamp}.sql";
        $zipFile = "{$dbName}-{$timestamp}.zip";

        $credFile = $tempDir.DIRECTORY_SEPARATOR."cred-{$timestamp}.cnf";
        $credContent = "[client]\n";
        $credContent .= "user = '{$dbUser}'\n";
        $credContent .= "password = '{$dbPass}'\n";
        $credContent .= "port = '{$dbPort}'\n";
        $credContent .= "host = '{$dbHost}'\n";
        $credContent .= "ssl-mode=DISABLED\n";
        file_put_contents($credFile, $credContent);

        $command = sprintf(
            '"%s" --defaults-extra-file="%s" --skip-comments --extended-insert --single-transaction --protocol=TCP %s > "%s"',
            $mysqldump,
            $credFile,
            $dbName,
            $sqlFile
        );

        $output = [];
        $exitCode = 0;
        exec($command.' 2>&1', $output, $exitCode);

        unlink($credFile);

        if ($exitCode !== 0) {
            if (file_exists($sqlFile)) {
                unlink($sqlFile);
            }
            throw new \RuntimeException(
                'mysqldump falló (código '.$exitCode.'): '.implode(PHP_EOL, $output)
            );
        }

        if (! file_exists($sqlFile) || filesize($sqlFile) === 0) {
            throw new \RuntimeException('El archivo SQL generado está vacío o no existe.');
        }

        $zip = new ZipArchive;
        $zipPath = $tempDir.DIRECTORY_SEPARATOR.$zipFile;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('No se pudo crear el archivo ZIP.');
        }
        $zip->addFile($sqlFile, basename($sqlFile));
        $zip->close();

        unlink($sqlFile);

        $disk = Storage::disk('backups');
        $disk->put($zipFile, file_get_contents($zipPath));

        unlink($zipPath);
    }

    public function downloadBackupAction(): Action
    {
        return Action::make('downloadBackup')
            ->label('Descargar')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('gray')
            ->action(function (array $arguments): ?StreamedResponse {
                $path = $arguments['path'];

                $disk = Storage::disk('backups');

                if (! $disk->exists($path)) {
                    Notification::make()
                        ->title('El archivo ya no existe')
                        ->danger()
                        ->send();

                    return null;
                }

                return $disk->download($path);
            });
    }

    public function deleteBackupAction(): Action
    {
        return Action::make('deleteBackup')
            ->label('Eliminar')
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->action(function (array $arguments): void {
                $path = $arguments['path'];

                $disk = Storage::disk('backups');

                if ($disk->exists($path)) {
                    $disk->delete($path);
                }

                Notification::make()
                    ->title('Respaldo eliminado')
                    ->success()
                    ->send();
            });
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        return round($bytes / pow(1024, $pow), $precision).' '.$units[$pow];
    }
}
