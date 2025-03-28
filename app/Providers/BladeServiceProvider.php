<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::directive('formatDate', function ($expression) {
            return "<?php echo $expression ? \Carbon\Carbon::createFromFormat('Ymd', $expression)->format('Y/m/d') : '' ?>";
        });
    }
}
