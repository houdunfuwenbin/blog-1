<?php

namespace App\Providers;

use App\Article;
use App\Discussion;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Tools\FileManager\BaseManager;
use App\Tools\FileManager\UpyunManager;
use Illuminate\Database\Eloquent\Relations\Relation;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $lang = config('app.locale') != 'zh_cn' ? config('app.locale') : 'zh';
        Carbon::setLocale($lang);

        Relation::morphMap([
            'discussions' => Discussion::class,
            'articles'    => Article::class,
        ]);

        App::bind('url', function () {
            $generator = new UrlGenerator(App::make('router')->getRoutes(), App::make('request'));
            $generator->forceScheme('https');
            return $generator;
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('uploader', function ($app) {
            $config = config('filesystems.default', 'public');

            if ($config == 'upyun') {
                return new UpyunManager();
            }

            return new BaseManager();
        });
    }
}
