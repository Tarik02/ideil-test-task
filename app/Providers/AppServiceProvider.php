<?php

namespace App\Providers;

use App\Services\PlaceLikesService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Blade::directive('redirect_to', function ($expression) {
            return <<<HERE
<?php if (Request::has('redirect_to')) { ?>
<input type="hidden" name="redirect_to" value="<?php echo e(Request::get('redirect_to')); ?>">
<?php }; ?>
HERE;
        });
    }
}
