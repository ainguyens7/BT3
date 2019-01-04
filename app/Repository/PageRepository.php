<?php

namespace App\Repository;

use App\Models\ShopMetaModel;
use App\Models\ShopsModel;
use App\Events\CreatedReviewPageEvent;

class PageRepository
{
    /**
     * @var \Illuminate\Foundation\Application|mixed
     */
    private $_shopsMetaModel;

    /**
     * ShopsRepository constructor.
     */
    function __construct()
    {
        $this->_shopsMetaModel = new ShopMetaModel();
    }

    public function savePageReviews($shopId,$pageId){
	    $result = $this->_shopsMetaModel->where('shop_id',$shopId)->update([
		    'page_reviews' => $pageId,
		    'updated_at' => date('Y-m-d H:i:s',time())
	    ]);
        event(new CreatedReviewPageEvent($shopId, $result));
        return $result;
    }
}
