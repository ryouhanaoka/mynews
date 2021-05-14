@extends('layouts.admin')
@section('title', 'ニュースの編集')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <h2>楽器情報編集</h2>
                <form action="{{ action('Admin\NewsController@judgeupdate') }}" method="post" enctype="multipart/form-data">
                    @if (count($errors) > 0)
                        <ul>
                            @foreach($erros->all() as $e)
                            <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="form-group row">
                        <label class="col-md-2" for="title">タイトル</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="title" value="{{ $title }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2" for="body">本文</label>
                        <div class="col-md-10">
                            <textarea class="form-control" name="body" rows="20">{{ $body }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2" for="image">画像</label>
                        <div class="col-md-10">
                            <input type="file" class="form-control-file" name="image">
                            <div class="form-text text-info">
                                設定中: {{ $news_form->image_path }}
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="remove" value="true">画像を削除
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2" for="image">地図</label>    
                        <div id="map"></div>
                    </div>
                    <div class="form-group row">
                        <div class="offset-md-2">
                            <input type="text" id="addressInput" name="name" value = "{{ $name }}" placeholder="住所入力">
                            <button id="searchGeo" name="search">住所検索</button>
                            <div>
                                <input type="hidden" id="lat" name="lat" value="{{ $lat }}">
                                <input type="hidden" id="lng" name="lng" value="{{ $lng }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-10">
                            <input type="hidden" name="id" value="{{ $news_form->id }}">
                            {{ csrf_field() }}
                            <input type="submit" class="btn btn-primary" value="更新">
                        </div>
                    </div>
                </form>
                <div class="row mt-5">
                    <div class="col-md-4 mx-auto">
                        <h2>編集履歴</h2>
                        <ul class="list-group">
                            @if ($news_form->histories != NULL)
                                @foreach ($news_form->histories as $history)
                                    <li class="list-group-item">{{ $history->edited_at }}</li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
    /*javascriptにlaravelからの情報を渡す方法を調べる。*/
        function initMap() {
          map = document.getElementById("map");
          /*console.log(@json($news_form->place));
          console.log("hello");*/
          let tokyoTower = {lat: @json($news_form->place->lat), lng: @json($news_form->place->lng)};
          opt = {
          zoom: 13,
          center: tokyoTower,
          };
          mapObj = new google.maps.Map(map, opt);
          marker = new google.maps.Marker({
          position: tokyoTower,
          map: mapObj,
          title: 'tokyoTower',
          });
        }
        //# sourceURL=hoge.js 
    </script>
    
    <script src={{ "https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key=" . env('GOOGLE_MAP_API_KEY') . "&callback=initMap" }} async defer>
	</script>
	
    @endsection