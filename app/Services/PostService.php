<?php


namespace App\Services;


use App\Events\PostEvent;
use App\Models\Post;
use App\Models\Tag;
use App\Repositories\PostRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PostService
{
	private $postRepository;

	private $uid;

	private $request;

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

	public function __construct(PostRepository $postRepository, Request $request)
	{
		$this->uid = Auth::id();
		$this->postRepository = $postRepository;
		$this->request = $request;
	}

	public function view()
	{
		$uid = Auth::id();
		if ($uid != 1) {
			$search = $this->request->get(config('repository.criteria.params.search', 'search'), null);
			$param = $search . "&user_id:{$this->uid}";
			$this->request->merge(['search'=>$param]);
		}
		return $this->postRepository->all();
	}

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

		return $data;
	}

	public function store($request)
	{
		$postArr= $request->postFillData();
		$postArr['user_id'] = Auth::id();
		$post = Post::create($postArr);
		$post->syncTags($request->get('tags', []));
		event(new PostEvent($postArr['user_id'], $request->ip(), time()));
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

		return $data;
	}

	public function update($request, $post)
	{
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

	public function findOrFail($id)
	{
		return Post::findOrFail($id);;
	}

	public function destroy($request, $id, $post)
	{
		$this->authorize('update', $post);
		$post->tags()->detach();
		$post->delete();
		event(new PostEvent($id, $request->ip(), time()));
	}
}