@extends('layouts.admin')
@section('title', 'ニュースの新規作成')
        
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <h2>新規楽器情報</h2>
                <form action="{{ action('Admin\NewsController@judgecreate') }}"
                method="post" enctype="multipart/form-data">
                
                    @if (count($errors) > 0)
                        <ul>
                            @foreach($errors->all() as $e)
                            
                               <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="form-group row">
                         <label class="col-md-2">タイトル</label>
                         <div class="col-md-10">
                             <input type="text" class="form-control" name="title" value="{{ old('title', $title) }}">
                         </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2">本文</label>
                        <div class="col-md-10">
                             <textarea class="form-control" name="body" rows="20">{{ old('body', $body) }}
                             </textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2">画像</label>
                        <div class="col-md-10">
                            <input type="file" class="form-control-file" name="image">
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
                    {{ csrf_field() }}
                    <input type="submit" class="btn btn-primary" name="redirect" value="更新">
                </form>
            </div>
        </div>
    </div>
    @endsection
    
    @section('js')
    <script>
        function initMap() {
          map = document.getElementById("map");
          let tokyoTower = {lat: 35.6585769, lng: 139.7454506};
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
    </script>
    
    <script src={{ "https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key=" . env('GOOGLE_MAP_API_KEY') . "&callback=initMap" }} async defer>
	</script>
	
    @endsection