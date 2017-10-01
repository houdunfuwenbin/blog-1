<?php

namespace App\Console\Commands;

use App\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use QL\QueryList;

class Wallpaper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wallpaper';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'move the blog';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        for ($page = 10; $page > 9; $page--){
            $this->info('正在抓取页码: '. $page);
            $url = 'https://bing.ioliu.cn/?p='.$page;
            try{
                $items = QueryList::get($url)->find('.item');

                $index = 0;
                $items->reverse()->map(function ($item) use (&$index){
                    $this->info('正在解析: '.$index);
                    $contextUrl = 'https://bing.ioliu.cn'.$item->find('.mark')->attr('href');
                    $context = QueryList::get($contextUrl);

                    $view = $context->find('.options .eye')->text();
                    $heart = $context->find('.options .heart')->text();
                    $download = $context->find('.options .download')->text();

                    $url = $context->find('.progressive__img')->attr('src');
                    $title = $context->find('.description .title')->text();
                    $content = $context->find('.description .sub')->text();;
                    $calendar = $context->find('.description .calendari')->text();;
                    $location = $context->find('.description .location')->text();;

                    $ret = compact('view', 'heart', 'download', 'url', 'title', 'content', 'calendar', 'location');
                    $this->info('解析完成!');
                    \App\Wallpaper::create($ret);
                    $index++;
                });

            }catch (\Exception $e){
                $this->error('异常: '.$e->getMessage());
            }


        }
    }

}
