<?php namespace App\Providers;

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
        /** @var \Illuminate\Http\Request $request */
        $request = $this->app->make('request');

        if ($request->isMethod('OPTIONS')) {
            $this->app->options($request->path(), function () {
                return response('OK', 200, [
                    "Access-Control-Allow-Origins" => "*",
                    "Access-Control-Allow-Headers" => "Accept, Content-Type",
                    "Access-Control-Allow-Methods" => "GET,HEAD,PUT,POST,DELETE",
                ]);
            });
        }
    }
}
