<?php

namespace Burgess\FileTransfer;

use Illuminate\Support\ServiceProvider;

class FileTransferServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make(FileTransferController::class);
        include(__DIR__ . '/routes.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'FileTransfer');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
