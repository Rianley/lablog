<?php

namespace App\Http\Controllers\Home;

use App\Models\Article;
use App\Models\Config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cache;

class IndexController extends Controller
{
    //首页
    public function index()
    {
        $article = Article::select('id', 'category_id', 'author', 'title', 'content', 'describe','created_at')
                    ->orderBy('created_at', 'desc')
                    ->with(['category', 'labels'])
                    ->paginate(10);
        $config = cache('common:config');
        $tdk = [
            'title'=>$config->get('WEB_TITLE'),
            'desc'=>$config->get('WEB_DESCRIPTION'),
            'keyword'=>$config->get('WEB_KEYWORDS'),
        ];

        $assign = ['tdk'=>$tdk, 'article'=>$article, 'tagName'=>'','category_id' => 'index'];
        return view('home/index/index', $assign);
    }

    // 文章详情页
    public function article(Request $request, Article $article, $id)
    {
        // 获取文章数据
        $data = $article::with(['category', 'labels'])->find($id);
        if (is_null($data)) {
            return abort(404);
        }
        // 同一个用户访问同一篇文章每天只增加1个访问量  使用 ip+id 作为 key 判别
        $ipAndId = 'articleRequestList'.$request->ip().':'.$id;
        if (!Cache::has($ipAndId)) {
            cache([$ipAndId => ''], 1440);
            // 文章点击量+1
            $data->increment('readed');
        }

        // 获取上一篇
        $prev = $article
            ->select('id', 'title')
            ->orderBy('created_at', 'asc')
            ->where('id', '>', $id)
            ->limit(1)
            ->first();

        // 获取下一篇
        $next = $article
            ->select('id', 'title')
            ->orderBy('created_at', 'desc')
            ->where('id', '<', $id)
            ->limit(1)
            ->first();

        // 获取评论
//        $comment = $commentModel->getDataByArticleId($id);
        $comment = [];
        $category_id = $data->category->id;
        $assign = compact('category_id', 'data', 'prev', 'next', 'comment');
        return view('home.index.article', $assign);
    }
}