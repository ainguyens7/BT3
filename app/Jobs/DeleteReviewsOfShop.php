<?php

namespace App\Jobs;

use App\Events\DeleteAllReviewsPusherEvent;
use App\Events\UpdateCache;
use App\Listeners\UpdateDatabaseSubscriber;
use App\Repository\CommentBackEndRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DeleteReviewsOfShop implements ShouldQueue
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
	private $page;
	private $total;
	private $source;
	private $commentBackendRepo;

	const limitDelete = 200;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($shopId,$page,$total,$source)
    {
        //
	    $this->shopId = $shopId;
	    $this->page = $page;
	    $this->total = $total;
	    $this->source = $source;
	    $this->commentBackendRepo = new CommentBackEndRepository();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
	    $sentry = app('sentry');
	    $sentry->user_context([
		    'shopId' => $this->shopId,
	    ]);
	    try {
		    Cache::forever('deletingReviews_'.$this->shopId,TRUE);
		    $totalPages = $this->total / $this::limitDelete;

		    $table = $this->commentBackendRepo->getTableComment($this->shopId);

		    if(is_array($this->source)){
			    $query = DB::table($table)->whereIn('source', $this->source)->take($this::limitDelete);
		    }else{
			    $query = DB::table($table)->where('source', $this->source)->take($this::limitDelete);
		    }

		    $actionDelete = $query->delete();

		    if($actionDelete){
			    echo($this->page .'/'.$totalPages);

			    if($this->page < $totalPages ){
				    dispatch(new DeleteReviewsOfShop($this->shopId,$this->page + 1,$this->total,$this->source));
			    }else{
				    sleep(2);
				    $this->eventAfterDelete();
				    dispatch(new UpdateProductAfterDeleteReviewsOfShop($this->shopId));
			    }
		    }else{
			    Cache::forget('deletingReviews_'.$this->shopId,TRUE);
			    event(new UpdateCache($this->shopId));
		    }
	    }catch (\Exception $exception){
		    $sentry->captureException($exception);
		    Cache::forget('deletingReviews_'.$this->shopId,TRUE);
		    event(new UpdateCache($this->shopId));
	    }
    }

    public function eventAfterDelete(){
	    $sentry = app('sentry');
	    $sentry->user_context([
		    'shopId' => $this->shopId,
	    ]);
	    try {
		    Cache::forget('deletingReviews_'.$this->shopId,TRUE);

		    event(new UpdateCache($this->shopId));
		    event(new DeleteAllReviewsPusherEvent($this->shopId,'Delete all reviews successful!'));
	    }catch (\Exception $exception){
		    $sentry->captureException($exception);
	    }
    }
}
