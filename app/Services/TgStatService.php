<?php

namespace App\Services;

use App\Models\Channel;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class TgStatService
{
    public function parseChannel($channelUrl) {

        $channel = Channel::where([
            'channel_url' => $channelUrl,
        ])->first();
        if (null !== $channel->tgstat_url) {
            $tgStatHtml = Http::get($channel->tgstat_url);
            if ($tgStatHtml->ok()) {
                $html = $tgStatHtml->body();
                $crawler = new Crawler($html);
                $todaySubscribers = $crawler->filterXPath('//*[@id="sticky-center-column"]/div/div/div[1]/div/div[1]/div[1]/table/tr[1]/td[1]/b')->first()->text();
                $totalSubscribers = $crawler->filterXPath('//*[@id="sticky-center-column"]/div/div/div[1]/div/h2')->first()->text();
                $totalSubscribers = (int)preg_replace('/[^0-9]/', '', $totalSubscribers);
                $todaySubscribers = (int)preg_replace('/[^0-9-+]/', '', $todaySubscribers);
                $channel->stats()->create([
                    'total_subscribers' => $totalSubscribers,
                    'day_subscribers' => $todaySubscribers,
                ]);
            }
        }
    }
}
