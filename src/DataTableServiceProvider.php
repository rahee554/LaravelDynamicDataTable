<?php

namespace ArtFlowStudio\DynamicDatatable;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;

class DataTableServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        Blade::directive('AF_dtable', function ($expression) {
            return "<?php echo \$__env->make('AF_dtable::source', $expression, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>";
        });

        Blade::directive('AF_dtable_btns', function ($expression) {
            return "<?php echo \$__env->make('AF_dtable::header', $expression, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>";
        });

     
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/views', 'ArtFlowStudio\\DynamicDatatable');

        // Add a namespace for vendor views with the 'AF_dtable' namespace
        View::addNamespace('AF_dtable', __DIR__ . '/views');

        // Add a namespace for vendor assets with the 'AF_DataTables' namespace
        $this->publishes([
            __DIR__ . '/../public' => public_path('/'),
        ], 'artflow-studio/laravel-dynamic-datatable');
    }
}
