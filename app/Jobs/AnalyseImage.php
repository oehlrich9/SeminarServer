<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\Image;
use App\Models\Emotions;
use GuzzleHttp\Client;


class AnalyseImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $image;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $queryUrl = "http://api.kairos.com/v2/media";
        $APP_ID = $_ENV['KAIROS_APP_ID'];
        $APP_KEY = $_ENV['KAIR0S_KEY'];
        $client = new Client(['headers' => ['Content-type' => 'application/json', 'app_id' => $APP_ID, 'app_key' => $APP_KEY]]);
        $response = $client->request('POST', $queryUrl, [
            'multipart' => [
                [
                    'name'     => 'source',
                    'contents' => fopen(__DIR__.'/../../storage/app/'.$this->image->path, 'r')
                ]
            ]
        ]);
        //$response = $request->send();
        // show the API response
        $object = json_decode($response->getBody());
        $emo = $object->frames[0]->people[0]->emotions;
        error_log(json_encode($emo));
        $emotion = new Emotions();
        $emotion->anger = $emo->anger;
        $emotion->disgust = $emo->disgust;
        $emotion->fear = $emo->fear;
        $emotion->joy = $emo->joy;
        $emotion->sadness = $emo->sadness;
        $emotion->surprise = $emo->surprise;
        $this->image->emotion()->save($emotion);
        $this->image->analized = true;
        $this->image->save();
        // close the session
        //curl_close($request);
    }
}
