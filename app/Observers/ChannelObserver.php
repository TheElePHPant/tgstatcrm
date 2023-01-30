<?php

namespace App\Observers;

use App\Models\Channel;
use App\Services\TgStatService;
use Symfony\Component\DomCrawler\Crawler;

class ChannelObserver
{
    public function __construct(
        private TgStatService $statService,
    )
    {

    }


    public function creating(Channel $channel)
    {
        //dd($channel);
        $channelName = $channel->channel_url;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://uk.tgstat.com/channels/global-search',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'query=' . urlencode($channelName),
            CURLOPT_HTTPHEADER => array(
                'content-type: application/x-www-form-urlencoded; charset=UTF-8',
                'x-requested-with: XMLHttpRequest'
            ),
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);
        if (isset($response['html'])) {
            $crawler = new Crawler($response['html']);
            $tgstatUrl = $crawler?->filter('a')?->first()?->attr('href');
            $channel->tgstat_url = $tgstatUrl;
        } else {
            admin_error('Не удалось получить ссылку TGStat');
            return false;
        }
    }

    public function created(Channel $channel)
    {
        $this->statService->parseChannel($channel->channel_url);
    }

    public function updated(Channel $channel)
    {
    }

    public function deleted(Channel $channel)
    {
    }

    public function restored(Channel $channel)
    {
    }

    public function forceDeleted(Channel $channel)
    {
    }
}
