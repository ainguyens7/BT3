<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Repository\CommentBackEndRepository;

class DeleteImportedReview implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times job may be attempted
     */
    public $tries = 3;

    /**
     * Timeout of job
     */
    public $timeout = 1200;

    private $shopId;
    private $commentBackendRepo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($shopId = '')
    {
        $this->shopId = $shopId;
        $this->commentBackendRepo = new CommentBackEndRepository();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // silent check
        $this->commentBackendRepo->deleteCommentBySource($this->shopId);
    }
}