<?php


namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

class PostRepository extends BaseRepository
{
	protected $fieldSearchable = [
		'user_id',
		'title'=>'like',
		'created_at'
	];

	public function boot(){
		$this->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
	}

	public function model()
	{
		return 'App\Models\Post';
	}
}