<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'appUninstalled',
        'deleteProduct',
        'createdProduct',
        'updatedProduct',
        'themesUpdate',
        'themesPublish',
        'review/uploadfile',
        'review/deletefile',
	    'comment/post_review',
	    'comment/delete_img',
	    'comment/upload_img',
	    'comment/unlike',
	    'comment/like',
	    'comment/get_summary_star_collection',
        'customers/redact',
        'shop/redact',
        'manage-reviews/add_google_rating'
    ];
}
