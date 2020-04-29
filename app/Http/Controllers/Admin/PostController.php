<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Services\PostService;

class PostController extends Controller
{
	private $postService;

	public function __construct(PostService $postService)
	{
		$this->postService = $postService;
	}

	/**
	 * @param PostService $postService
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$data  = $this->postService->view();
		return view('admin.post.index', ['posts' => $data]);
	}

	/**
	 * Show the new post form
	 */
	public function create()
	{
		$data  = $this->postService->create();
		return view('admin.post.create', $data);
	}

	/**
	 * Store a newly created Post
	 *
	 * @param PostCreateRequest $request
	 */
	public function store(PostCreateRequest $request)
	{
		$this->postService->store($request);
		return redirect()
			->route('post.index')
			->with('success', '新文章创建成功.');
	}

	/**
	 * Show the post edit form
	 *
	 * @param int $id
	 * @return Response
	 */
	public function edit($id)
	{
		$data = $this->postService->edit($id);

		return view('admin.post.edit', $data);
	}

	/**
	 * 更新文章
	 * @param PostUpdateRequest $request
	 * @param $id
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function update(PostUpdateRequest $request, $id)
	{
		$post = $this->postService->findOrFail($id);
		$this->authorize('update', $post);
		$this->postService->update($request, $post);
		return redirect()
			->route('post.index')
			->with('success', '文章已保存.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function destroy(PostUpdateRequest $request, $id)
	{
		$post = $this->postService->findOrFail($id);
		$this->authorize('update', $post);
		$this->postService->destroy($request, $id, $post);
		return redirect()
			->route('post.index')
			->with('success', '文章已删除.');
	}
}