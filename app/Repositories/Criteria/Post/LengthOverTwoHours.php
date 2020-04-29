<?php


namespace App\Repositories\Criteria\Post;

use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;


class LengthOverTwoHours extends Criteria {

	/**
	 * @param $model
	 * @param RepositoryInterface $repository
	 * @return mixed
	 */
	public function apply($model, Repository $repository)
	{
		$model = $model->where('title', 'like', '%1%');
//		$model = $model->where('user_id', '=', 2);
		return $model;
	}
}