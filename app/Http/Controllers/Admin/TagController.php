<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag as TagMdl;
use App\Http\Requests\TagCreateRequest;
use App\Http\Requests\TagUpdateRequest;
use App\Services\TagService;

class TagController extends Controller
{
	private $tagService;

	public function __construct(TagService $tagService)
	{
		$this->tagService = $tagService;
	}

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $data = $this->tagService->getAll();
        return view('admin.tag.index')->withTags($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$data = $this->tagService->getField();
        return view('admin.tag.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TagCreateRequest $request)
    {
        $tag = new TagMdl();
        $this->tagService->store($request, $tag);
        return redirect('/admin/tag')
                        ->with('success', '标签「' . $tag->tag . '」创建成功.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->tagService->edit($id);
        return view('admin.tag.edit', $data);
    }

	// 替换 update() 方法如下
	/**
	 * Update the tag in storage
	 *
	 * @param TagUpdateRequest $request
	 * @param int $id
	 * @return Response
	 */
    public function update(TagUpdateRequest $request, $id)
    {
		$this->tagService->update($request,$id);
	    return redirect("/admin/tag/$id/edit")
		    ->with('success', '修改已保存.');
    }

	/**
	 * Delete the tag
	 *
	 * @param int $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->tagService->del($id);
		return redirect('/admin/tag')
			->with('success', '标签已经被删除.');
	}
}
