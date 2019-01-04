<?php

namespace App\Repository;

use App\Events\UpdateAssetMetaFieldEvent;
use App\Events\UpdateProductMetaField;
use App\Models\ShopMetaModel;
use App\Contracts\Repository\ShopMetaRepositoryInterface;
use Illuminate\Support\Facades\Lang;
use App\Events\SavedSettingsEvent;
use App\Events\ChangedThemeSettingEvent;
use App\Events\UpdateCache;

/**
 * Class ShopMetaRepository
 * @package App\Repository
 */
class ShopMetaRepository implements ShopMetaRepositoryInterface {
	/**
	 * @var \Illuminate\Foundation\Application|mixed
	 */
	private $_model;

	/**
	 * ShopMetaRepository constructor.
	 */
	function __construct() {
		$this->_model = new ShopMetaModel();
	}

	/**
	 * Get shop meta detail by shop_id
	 *
	 * @param $shop_id
	 *
	 * @return \Illuminate\Database\Eloquent\Model|null|static
	 */
	public function detail( $shop_id ) {
		return $this->_model->where( 'shop_id', $shop_id )->first();
	}


	/**
	 * Convert data from form to db
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function convertDataImport( $data ) {
		if(!empty($data['setting']) && is_array($data['setting'])){
			$data['setting'] = json_encode($data['setting']);
		}

		if(!empty($data['translate']) && is_array($data['translate'])){
			$data['translate'] = json_encode($data['translate']);
		}

		if(!empty($data['rand_comment_default']) && is_array($data['rand_comment_default'])){
			$data['rand_comment_default'] = json_encode($data['rand_comment_default']);
		}

		if(!empty($data['style_customize']) && is_array($data['style_customize'])){
			$data['style_customize'] = json_encode($data['style_customize']);
		}
		return $data;
	}

	/**
	 * Create shop meta
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function create( array $data = array() ) {
		$client =new \Raven_Client(env('SENTRY_DSN'));
		$client->user_context(array(
			'shop_id' => isset($data['shop_id']) ? $data['shop_id'] : null,
		));
		try{
			$result = array(
				'status'  => false,
				'message' => Lang::get( 'settings.failed' ),
			);
			if(isset($data['shop_id' ]) and $this->_model->find($data['shop_id']))
				return $result;

			$data_save = $this->convertDataImport( $data );
			$insert    = $this->_model->create( $data_save );
			if ( $insert ) {
				$result = array(
					'status'  => true,
					'message' => Lang::get( 'settings.create_success' ),
				);
			}

			return $result;
		} catch (\Exception $exception)
		{
			$client->captureException($exception);

			return array(
				'status'  => false,
				'message' => $exception->getMessage(),
			);
		}
	}

    /**
     * Update general settings
     *
     * @param int $shop_id
     * @param array $data
     *
     * @return array
     */
    public function updateGeneralSettings($shop_id, array $data = array()) {
        $result = $this->update($shop_id, $data);
		event(new SavedSettingsEvent($shop_id, $data, $result));
		event(new UpdateCache($shop_id));		
        return $result;
    }

    /**
     * Update theme settings
     *
     * @param int $shop_id
     * @param array $data
     *
     * @return array
     */
    public function updateThemeSettings($shop_id, array $data = array()) {
		$result = $this->update($shop_id, $data);
		event(new ChangedThemeSettingEvent($shop_id));
		event(new UpdateCache($shop_id));		
        // Update Asset Meta Filed

        return $result;
    }

	/**
	 * Update shop meta
	 *
	 * @param int $shop_id
	 * @param array $data
	 *
	 * @return array
	 */
	public function update( $shop_id, array $data = array() ) {
		$client =new \Raven_Client(env('SENTRY_DSN'));
		$client->user_context(array(
			'shop_id' => $shop_id,
		));
		try{
			$result = array(
				'status'  => false,
				'message' => Lang::get( 'settings.failed' ),
			);

			$settings = $this->_model->find($shop_id);
			if ( $settings ) {
				$data_save = $this->convertDataImport( $data );
				$save = $settings->update( $data_save );
				if ( $save ) {
					$result = array(
						'status'  => true,
						'message' => Lang::get( 'settings.update_success' ),
					);
				}
			}else{
				$result = $this->create($data);
			}

			return $result;
		} catch (\Exception $exception)
		{
			$client->captureException($exception);

			return array(
				'status'  => false,
				'message' => $exception->getMessage(),
			);
		}
	}
}
