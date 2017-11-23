<?php
namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Wallpaper;
use QL\QueryList;

class WallpaperController extends Controller
{
    public function cron(){
        $query = QueryList::get('https://bing.ioliu.cn')->find('.item:first');
        $option = $query->find('.mark')->attr('href');
        $url = 'https://bing.ioliu.cn'.$option;

        $context = QueryList::get($url);

        $ret = $this->parse($context);

        $wallpaper = Wallpaper::create($ret);

        return response()->json($wallpaper);
    }

    public function parse($context){
        $view = $context->find('.options .eye')->text();
        $heart = $context->find('.options .heart')->text();
        $download = $context->find('.options .download')->text();

        $url = $context->find('.progressive__img')->attr('src');
        $title = $context->find('.description .title')->text();
        $content = $context->find('.description .sub')->text();;
        $calendar = $context->find('.description .calendari')->text();;
        $location = $context->find('.description .location')->text();;

        return compact('url', 'title', 'content', 'calendar', 'location', 'view', 'heart', 'download');
    }
}