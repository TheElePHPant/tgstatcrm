<?php

namespace App\Console\Commands;

use App\Models\Channel;
use App\Services\TgStatService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class TgstatParserCommand extends Command
{
    protected $signature = 'tgstat:parser {channel}';

    protected $description = 'Command description';

    public function handle(TgStatService $tgStatService)
    {
        $tgStatService->parseChannel($this->argument('channel'));
    }

}
