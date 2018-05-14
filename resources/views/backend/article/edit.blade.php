@extends('layouts.backend.frame')

@section('title', '编辑文章')

@section('one-level', '文章管理')

@section('two-level', '编辑文章')

@section('load_css')
    <link rel="stylesheet" href="/markdown/simplemde.min.css">
@stop

@section('load_js')
    <script src="/markdown/simplemde.min.js"></script>
    <script>
        var simplemde = new SimpleMDE();
        simplemde.codemirror.on("change", function(){
            $("#markdown").text(simplemde.value());
        });
    </script>
@stop

@section('content')
    <div class="box-body">
        <div class="col-md-8 col-md-offset-2">
            <form action="{{ route('back.art.update', [$article->id]) }}" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <label>标题</label>
                    <input type="text" class="form-control" name="title" value="{{ $article->title }}">
                </div>
                <div class="form-group">
                    <label>分类</label>
                    <select name="category_id" class="form-control">
                        @if($category)
                            @foreach($category as $value)
                                <option value="{{ $value->id }}" @if($value->id==$article->category_id) selected="selected" @endif>{{ $value->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group">
                    <label>作者</label>
                    <input type="text" class="form-control" name="author" value="{{ $article->author }}">
                </div>
                <div class="form-group">
                    <label>内容</label>
                    <textarea name="markdown" id="markdown" cols="20" rows="10" class="form-control">{{ $article->markdown }}</textarea>
                </div>
                <div class="form-group">
                    <label>描述</label>
                    <textarea name="describe" id="describe" cols="30" rows="10" class="form-control">{{ $article->describe }}</textarea>
                </div>
                <div class="form-group">
                    <label>是否置顶</label>
                    <input type="radio" name="is_top" value="0" @if($article->is_top==0) checked @endif>否
                    <input type="radio" name="is_top" value="1" @if($article->is_top==1) checked @endif>是
                </div>
                <div class="form-group">
                    <input class="btn btn-primary" type="submit" value="提交">
                </div>
            </form>
        </div>

    </div>
    <!-- /.box-body -->
@stop