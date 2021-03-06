@extends('layouts.backend.frame')

@section('title', '编辑文章')

@section('one-level', '文章管理')

@section('two-level', '编辑文章')

@section('load_css')
    <link rel="stylesheet" href="/editor/css/editormd.min.css">
    <link rel="stylesheet" href="/bootcss/plugins/iCheck/all.css">
@stop

@section('load_js')
    <script src="/editor/js/editormd.js"></script>
    <script src="/bootcss/plugins/iCheck/icheck.min.js"></script>
    <script>
        var editor;
        $(function() {
            $.get('/backend/article/markdown/{{ $article->id }}',function(md){
                editor = editormd("content", {
                    markdown: md,
                    width   : "100%",
                    height  : 500,
                    syncScrolling : "single",
                    path    : "/editor/lib/",
//                    saveHTMLToTextarea : true,
                    imageUpload : true,
                    imageFormats : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
                    imageUploadURL : "./php/upload.php",
                    onchange: function(){
                        $("#markhtml").text(editor.getPreviewedHTML());
                    }
                });
            })
        })

        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"], input[type="radio"]').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass   : 'iradio_minimal-blue'
        })
    </script>
@stop

@section('content')
    <div class="box-body">
        <div class="col-md-8 col-md-offset-2">
            <form action="{{ route('back.art.update', [$article->id]) }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                    <label>标题</label>
                    <input type="text" class="form-control" name="title" value="{{ $article->title }}">
                </div>
                <div class="form-group">
                    <label>标签</label>
                    <div>
                        @if($label)
                            @foreach($label as $value)
                                <input type="checkbox" name="label[]" @if(in_array($value->id, $article->label_ids)) checked @endif value="{{ $value->id }}"> {{ $value->name }}&nbsp;&nbsp;
                            @endforeach
                        @endif
                    </div>

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
                    <div id="content" style="z-index:9999;"></div>
                    <textarea name="markhtml" id="markhtml" cols="30" rows="10" style="display: none;">
                        {!! html_entity_decode($article->content) !!}
                    </textarea>
                </div>
                <div class="form-group">
                    <label>描述</label>
                    <textarea name="describe" id="describe" cols="30" rows="10" class="form-control">{{ $article->describe }}</textarea>
                </div>
                <div class="form-group">
                    <label>封面图</label>
                    <input type="file" name="avatar">
                    @if($article->cover)
                        <img class="thumbnail img-responsive" src="{{ $article->cover }}" width="200" />
                    @endif
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
