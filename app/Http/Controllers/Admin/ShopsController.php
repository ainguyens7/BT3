<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 11/8/17
 * Time: 09:40
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ShopsController extends Controller
{

	public function __construct() {

	}

	public function getManage(){

		return view('admin.shops.manage');
	}
}

?>