<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Idea;
use Carbon\Carbon;
use App\Job;
use Illuminate\Support\Facades\Auth;

class IdeaController extends Controller
{
    
    //アイデア投稿画面表示
    public function index()
    {
        // dd($latestIdea->id);
        $ideas = Idea::with('user.profile')
            ->inRandomOrder()
            ->limit(30)
            ->get();

        $colorName = [
            [
                'bg'=> 'bg-primary',
                'text'=> 'text-light',
                'heart'=> 'color:red;'
            ],
            [
                'bg'=> 'bg-secondary',
                'text'=> 'text-light',
                'heart'=> 'color:red;'
            ],
            [
                'bg'=> 'bg-success',
                'text'=> 'text-light',
                'heart'=> 'color:red;'
            ],
            [
                'bg'=> 'bg-danger',
                'text'=> 'text-light',
                'heart'=> 'color:yellow;'
            ],
            [
                'bg'=> 'bg-warning',
                'text'=> 'text-dark',
                'heart'=> 'color:red;'
            ],
            [
                'bg'=> 'bg-info',
                'text'=> 'text-light',
                'heart'=> 'color:red;'
            ],
            [
                'bg'=> 'bg-light',
                'text'=> 'text-dark',
                'heart'=> 'color:red;'
            ],
            [
                'bg'=> 'bg-dark',
                'text'=> 'text-light',
                'heart'=> 'color:red;'
            ],
        ];

        $ideas->map(function($idea) use($colorName) {
            $idea->color_name = $colorName[$idea->job_id - 1];
        });

        // dd($ideas);

        return view('ideas.index', [
            'ideas' => $ideas
        ]);
    }
    
    
    //アイデア投稿
    public function create()
    {
        $jobs = Job::all();

        return view('ideas.create',[
            'jobs' => $jobs
        ]);
       
        
    }

    //アイデア投稿データを保存
    public function store(Request $request)
    {
        //Ideaモデルのインスタンスを取得
        $idea = new Idea();
       
        // dd($request->job_id);
        $idea->user_id = Auth::user()->id;
        $idea->job_id = $request->job_id;
        $idea->title = $request->title;
        $idea->body = $request->body;
        $idea->created_at = Carbon::now();
        $idea->updated_at= Carbon::now();

        $idea->save();

        //画面更新
        return redirect()->route('idea.index');
    }


    //アイデア編集
    public function edit()
    {
       
        return view('ideas.edit');

    }

    public function update(int $id)
    {
        $idea = Idea::find($id);
        $idea->title = $request->title;
        $idea->body = $request->body;
        $idea->created_at = Carbon::now();
        $idea->updated_at = Carbon::now();

        return redirect()->route('ideas.index');
    }
}
