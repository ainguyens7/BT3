<?php

namespace App\ShopifyApi;

use App\Services\GuzzleHttp;


class PageApi extends GuzzleHttp
{

	public function detail( string $page) : array
	{
		try{
			$page = $this->get('pages/'.$page.'.json');
			return ['status' => true, 'page' => $page->page];
		} catch (\Exception $exception)
		{
			return ['status' => false, 'message' => $exception->getMessage()];
		}
	}


	public function create(array $data) : array
	{
		try{
			$page = $this->post('pages.json',['page' => $data]);

			return ['status' => true, 'page' => $page->page];
		} catch (\Exception $exception)
		{
			return ['status' => false, 'message' => $exception->getMessage()];
		}
	}

}
