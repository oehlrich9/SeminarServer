<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\TextSnippet;

class AnalyseTextSnippet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $textSnippet;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(TextSnippet $snippet)
    {
        $this->textSnippet = $snippet;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Nothing yet
    }
}
