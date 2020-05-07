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
	 * 主页
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
//		$data = app()->make('PostService')->view();
		$data  = $this->postService->view();
		return view('admin.post.index', ['posts' => $data]);
	}

	/**
	 * 新增页面
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function create()
	{
		$data  = $this->postService->create();
		return view('admin.post.create', $data);
	}

	/**
	 * 新建文章
	 * @param PostCreateRequest $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store(PostCreateRequest $request)
	{
		$this->postService->store($request);
		return redirect()
			->route('post.index')
			->with('success', '新文章创建成功.');
	}

	/**
	 * 修改页
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
	 * 删除文章
	 * @param PostUpdateRequest $request
	 * @param $id
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
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