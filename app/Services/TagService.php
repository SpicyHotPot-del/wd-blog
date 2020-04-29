<?php


namespace App\Services;


use App\Models\Tag as TagMdl;

class TagService
{
	protected $fields = [
		'tag' => '',
		'title' => '',
		'subtitle' => '',
		'meta_description' => '',
		'page_image' => '',
		'layout' => 'blog.layouts.index',
		'reverse_direction' => 0,
	];

	public function getAll()
	{
		$tags = TagMdl::all();
		return $tags;
	}

	public function getField()
	{
		$data = [];
		foreach ($this->fields as $field => $default) {
			$data[$field] = old($field, $default);
		}
		return $data;
	}

	public function store($request, $tag)
	{
		$tag = new TagMdl();
		foreach (array_keys($this->fields) as $field) {
			$tag->$field = $request->get($field);
		}
		$tag->save();
	}

	public function edit($id)
	{
		$tag = TagMdl::findOrFail($id);
		$data = ['id' => $id];
		foreach (array_keys($this->fields) as $field) {
			$data[$field] = old($field, $tag->$field);
		}
		return $data;
	}

	public function update($request, $id)
	{
		$tag = TagMdl::findOrFail($id);
		foreach (array_keys(array_except($this->fields, ['tag'])) as $field) {
			$tag->$field = $request->get($field);
		}
		$tag->save();
	}

	public function del($id)
	{
		$tag = TagMdl::findOrFail($id);
		$tag->delete();
	}
}