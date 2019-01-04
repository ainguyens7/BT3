<?php

namespace App\Repository;


use App\Models\FeedbackModel;


class FeedbackRepository
{
    /**
     * @var \Illuminate\Foundation\Application|mixed
     */
    private $_model;

    function __construct()
    {
        $this->_model = new FeedbackModel();
    }

	public function insert(  $data) {
		return $this->_model->create($data);
	}
}