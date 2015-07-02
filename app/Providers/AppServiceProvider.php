<?php namespace App\Providers;

use App\Http\Middleware\CorsMiddleware;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    var $corsMiddleware;

    function __construct(CorsMiddleware $corsMiddleware)
    {
        $this->corsMiddleware = $corsMiddleware;
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /** @var \Illuminate\Http\Request $request */
        $request = $this->app->make('request');

        if($request->isMethod('OPTIONS'))
        {
            $this->app->options($request->path(), function()
            {
                return response('OK', 200, [
                    "Access-Control-Allow-Origins" => "*",
                    "Access-Control-Allow-Headers" => "Accept, Content-Type",
                    "Access-Control-Allow-Methods" => "GET,HEAD,PUT,POST,DELETE",
                ]);
            });
        }
    }
}
