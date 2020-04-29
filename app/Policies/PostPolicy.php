<?php

namespace App\Policies;

use App\User;
use App\Models\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

	/**
	 * 判断给定文章是否可以被用户更新.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Post  $post
	 * @return bool
	 * @translator laravelacademy.org
	 */
	public function update(User $user, Post $post)
	{
		return $user->toArray()['id'] === $post->user_id;
	}
}
