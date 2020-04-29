<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Jobs\PostFormFields;
use App\Models\Post;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Events\PostEvent;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Repositories\PostRepository;
use App\Repositories\Criteria\Post\LengthOverTwoHours;
use App\Criteria\MyCriteria;

class PostController extends Controller
{
	private $postRepository;

	protected $fieldList = [
		'title' => '',
		'subtitle' => '',
		'page_image' => '',
		'content' => '',
		'meta_description' => '',
		'is_draft' => "0",
		'publish_date' => '',
		'publish_time' => '',
		'layout' => 'blog.layouts.post',
		'tags' => [],
		'user_id' => '',
	];

	public function __construct(PostRepository $postRepository)
	{
		$this->postRepository = $postRepository;
	}

	/**
	 * Display a listing of the posts.
	 */
	public function index()
	{
//		$this->postRepository->pushCriteria(new MyCriteria());
//		$this->postRepository->getByCriteria(new MyCriteria());
		$userId = Auth::user()->id;
		if ($userId == 1) {
			$post = Post::all();
		} else {
			$post = Post::all()->where(['user'=>$userId]);
		}
		return view('admin.post.index', ['posts' => $this->postRepository->all()]);
	}

	/**
	 * Show the new post form
	 */
	public function create()
	{
		$fields = $this->fieldList;

		$when = Carbon::now()->addHour();
		$fields['publish_date'] = $when->format('Y-m-d');
		$fields['publish_time'] = $when->format('g:i A');

		foreach ($fields as $fieldName => $fieldValue) {
			$fields[$fieldName] = old($fieldName, $fieldValue);
		}

		$data = array_merge(
			$fields,
			['allTags' => Tag::all()->pluck('tag')->all()]
		);

		return view('admin.post.create', $data);
	}

	/**
	 * Store a newly created Post
	 *
	 * @param PostCreateRequest $request
	 */
	public function store(PostCreateRequest $request)
	{
		$postArr= $request->postFillData();
		$postArr['user_id'] = Auth::user()->id;
		$post = Post::create($postArr);
		$post->syncTags($request->get('tags', []));
		event(new PostEvent($postArr['user_id'], $request->ip(), time()));
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
		$fields = $this->fieldsFromModel($id, $this->fieldList);

		foreach ($fields as $fieldName => $fieldValue) {
			$fields[$fieldName] = old($fieldName, $fieldValue);
		}

		$data = array_merge(
			$fields,
			['allTags' => Tag::all()->pluck('tag')->all()]
		);

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

		$post = Post::findOrFail($id);
		$this->authorize('update', $post);
		$post->fill($request->postFillData());
		$post->save();
		$post->syncTags($request->get('tags', []));

		if ($request->action === 'continue') {
			return redirect()
				->back()
				->with('success', '文章已保存.');
		}
		$userInfo = $request->user()->toArray();
		$after = $request->all();
		event(new PostEvent($userInfo, $request->ip(), time()), $after);

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
		$post = Post::findOrFail($id);
		$this->authorize('update', $post);
		$post->tags()->detach();
		$post->delete();
		event(new PostEvent($id, $request->ip(), time()));
		return redirect()
			->route('post.index')
			->with('success', '文章已删除.');
	}

	/**
	 * Return the field values from the model
	 *
	 * @param integer $id
	 * @param array $fields
	 * @return array
	 */
	private function fieldsFromModel($id, array $fields)
	{
		$post = Post::findOrFail($id);

		$fieldNames = array_keys(array_except($fields, ['tags']));

		$fields = ['id' => $id];
		foreach ($fieldNames as $field) {
			$fields[$field] = $post->{$field};
		}

		$fields['tags'] = $post->tags->pluck('tag')->all();

		return $fields;
	}
}