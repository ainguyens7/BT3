<?php

namespace App\Http\Controllers;

use App\Jobs\SendMailFreeToUnlimited;
use App\Jobs\SendMailUnuseReviewPage;
use Illuminate\Http\Request;

class EmailsController extends Controller
{
    public function unUSeReviewPage()
    {
        $jobSendMailUnuseReviewPage = (new SendMailUnuseReviewPage(session('shopId')));
        $this->dispatch($jobSendMailUnuseReviewPage);
        return response()->json(['status' => true, 'message' => 'Ding-dong, we have sent to your store email,  please check!']);
    }

    public function freeToUnlimitedPlan()
    {
        $jobSendMailFreeToUnlimited = (new SendMailFreeToUnlimited(session('shopId')));
        $this->dispatch($jobSendMailFreeToUnlimited);
        return response()->json(['status' => true, 'message' => 'Already send Free to try Unlimited Plan with Ali Reviews']);
    }
}
