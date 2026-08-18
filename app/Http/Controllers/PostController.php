<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Post\ViewClass;
use App\Services\Post\SaveClass;
use App\Services\Post\PrintClass;
use App\Http\Resources\PostCommentResource;

class PostController extends Controller
{
    protected $view, $save, $print;

    public function __construct(ViewClass $view, SaveClass $save, PrintClass $print)
    {
        $this->view = $view;
        $this->save = $save;
        $this->print = $print;
    }

    public function show($id)
    {
        return $this->view->show($id);
    }

    public function print($id)
    {
        return $this->print->order($id);
    }

    public function react(Request $request, $id)
    {
        $request->validate([
            'reaction_id' => ['required', 'integer', 'exists:list_data,id'],
        ]);

        return $this->save->react($request, $id);
    }

    public function comment(Request $request, $id)
    {
        $request->validate([
            'comment' => ['required', 'string'],
            'parent_id' => ['nullable', 'integer', 'exists:post_comments,id'],
        ]);

        return (new PostCommentResource($this->save->comment($request, $id)))->resolve();
    }
}
