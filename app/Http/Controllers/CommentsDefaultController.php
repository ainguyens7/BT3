<?php

namespace App\Http\Controllers;

use App\Factory\RepositoryFactory;
use App\Helpers\Helpers;
use App\Jobs\ImportDefaultReviews;
use App\Jobs\DeleteImportedReview;
use Illuminate\Http\Request;

/**
 * Class ChargedController
 * @package App\Http\Controllers
 */
class CommentsDefaultController extends Controller {

	protected $_commentDefaultRepo;
	protected $_commentDefaultAdminRepo;
	protected $_shopRepo;
	protected $_shopMetaRepo;
	protected $_commentBackendRepo;
	protected $_productRepo;

	public function __construct( RepositoryFactory $factory ) {
		$this->_shopRepo                = $factory::shopsReposity();
		$this->_shopMetaRepo            = $factory::shopsMetaReposity();
		$this->_commentDefaultRepo      = $factory::commentsDefaultRepository();
		$this->_commentDefaultAdminRepo = $factory::commentsDefaultAdminRepository();
		$this->_commentBackendRepo = $factory::commentBackEndRepository();
		$this->_productRepo = $factory::productsRepository();
	}

	public function manage() {
		$filters = [];
		$list    = $this->_commentDefaultRepo->all( session( 'shopId' ), $filters );
		if ( empty( $list->total() ) ) {
			$list = $this->_commentDefaultAdminRepo->all( $filters );
			if(!empty($list->total())){
				$filters_all = $filters;
				$filters_all['perPage'] = 'all';
				$list_all = $this->_commentDefaultAdminRepo->all( $filters_all );
				foreach ( $list_all as &$v ) {
					$v->country = !empty($v->counntry) ? $v->country : 'US';
					$this->_commentDefaultRepo->save( [
						'shop_id' => session( 'shopId' ),
						'id'      => 0,
						'star'    => $v->star,
						'content' => $v->content,
						'author'  => $v->author,
						'country'  =>  $v->country,
					] );
				}
			}

		}
		$allCountryCode = Helpers::getCountryCode();

		$settings = Helpers::getSettings( session( 'shopId' ) );

		$shopPlanInfo = $this->_shopRepo->shopPlansInfo( session( 'shopId' ) );
		if ( $shopPlanInfo['status'] ) {
			$shopPlanInfo = $shopPlanInfo['planInfo'];
		}

//		dd($settings);
		return view( 'commentsDefault.manage', compact( 'list', 'filters',
			'settings', 'args_rand', 'shopPlanInfo','allCountryCode' ) );
	}

	public function detail( Request $request ) {
		$data       = $request->all();
		$comment_id = $data['id'];
		if ( ! empty( $comment_id ) ) {
			$comment = $this->_commentDefaultRepo->detail( $comment_id );
			if ( ! empty( $comment ) ) {
				$allCountryCode = Helpers::getCountryCode();

				$selectHtml  = '<select name="country" class="select-multi" >
								<option value="">--- Select country ---</option>';
				if(!empty($allCountryCode)){
					foreach ($allCountryCode as $k => $c){
						$selected  = '';
						if($comment->country == $k){
							$selected = 'selected';
						}
						$selectHtml .= '<option value="'.$k.'" '.$selected.'>'.$c.'</option>';
					}
				}
				$selectHtml .='</select>';

				return [
					'status' => 'success',
					'data'   => $comment,
					'selectHtml'   => $selectHtml
				];
			}
		}

		return [
			'status'  => 'error',
			'message' => 'An error, please try again.'
		];
	}

	public function save( Request $request ) {
		$data            = $request->except( '_token' );
		$data['shop_id'] = session( 'shopId' );
		$save            = $this->_commentDefaultRepo->save( $data );
		if ( $save ) {
			return [
				'status'  => 'success',
				'message' => 'Save comment default successful.'
			];
		}

		return [
			'status'  => 'error',
			'message' => 'Save comment default fail.'
		];
	}


	public function delete( Request $request ) {
		$data = $request->all();
		unset( $data['_token'] );

		$save = $this->_commentDefaultRepo->delete( $data['id'] );
		if ( $save ) {
			return [
				'status'  => 'success',
				'message' => 'Delete comment default successful.'
			];
		}

		return [
			'status'  => 'error',
			'message' => 'Delete comment default fail.'
		];
	}

	/**
	 * Ajax change turn on/off comments default
	 *
	 * @param Request $request
	 */
	public function statusCommentsDefault( Request $request ) {
		$data   = $request->all();
		$result = array(
			'status'  => 'error',
			'message' => 'An error, please try again.'
		);

		$shop_id         = session( 'shopId' );
		$data['shop_id'] = $shop_id;

		$settings = $this->_shopMetaRepo->detail( $shop_id );

		if ( empty( $settings ) ) {
			$rs = $this->_shopMetaRepo->create( $data );
		} else {
			$rs = $this->_shopMetaRepo->update( $shop_id, $data );
		}

		if ( $rs['status'] ) {
			$result = array(
				'status'  => 'success',
				'message' => 'Save setting successful.'
			);
		}

		echo json_encode( $result );
		exit();
	}


	/*public function saveRandom( Request $request ) {
		$data   = $request->all();
		$result = array(
			'status'  => 'error',
			'message' => 'An error, please try again.'
		);

		$shop_id         = session( 'shopId' );
		$data['shop_id'] = $shop_id;

		$settings = $this->_shopMetaRepo->detail( $shop_id );
		if ( empty( $settings ) ) {
			$rs = $this->_shopMetaRepo->create( $data );
		} else {
			$rs = $this->_shopMetaRepo->update( $shop_id, $data );
		}

		if ( $rs['status'] ) {
			$result = array(
				'status'  => 'success',
				'message' => 'Save setting successful.'
			);
		}

		echo json_encode( $result );
		exit();
	}*/

	/**
	 * Ajax import random reviews to products
	 */
	public function importToProducts(Request $request){
		$result = array(
			'status'  => 'success',
			'message' => 'Import successful.'
		);

		$shopPlanInfo = $this->_shopRepo->shopPlansInfo( session( 'shopId' ) );
		if ( $shopPlanInfo['status'] ) {
			$shopPlanInfo = $shopPlanInfo['planInfo'];
		}
		if(empty($shopPlanInfo['sample_reviews'])){
			$result = array(
				'status'  => 'error',
				'message' => 'Only available in Premium version.'
			);
			echo json_encode( $result );
			exit();
		}

		$this->dispatch(new ImportDefaultReviews(session('shopId')));

		sleep(7);
		echo json_encode( $result );
		exit();
	}

	public function deleteImportedReviews(Request $request)
	{
		$shopId = session('shopId');
		$shopPlanInfo = $this->_shopRepo->shopPlansInfo($shopId);
		$this->dispatch(new DeleteImportedReview($shopId));
		return redirect()->back()->with(['success' => 'Delete all imported reviews successful']);
	}
}