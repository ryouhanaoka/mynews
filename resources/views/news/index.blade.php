@extends('layouts.front')

@section('content')
    <div class="container">
        <hr color="#c0c0c0">
        <div class="row">
            <div class="posts col-md-8 mx-auto mt-3">
                @foreach($posts as $post)
                    <div class="post">
                        <div class="row">
                            <div class="text col-md-6">
                                <div class="date">
                                    {{ $post->updated_at->format('Y年m月d日') }}
                                </div>
                                <div class="title">
                                    {{ str_limit($post->title, 150) }}
                                </div>
                                <div class="body mt-3">
                                    {{ str_limit($post->body, 1500) }}
                                </div>
                            </div>
                            <div class="image col-md-6 text-right mt-4">
                                @if ($post->image_path)
                                    <img src="{{ $post->image_path }}">
                                @endif
                            </div>
                            <div id="map_{{ $post->id }}" style="height:300px;width:80%;"></div>
                        </div>
                    </div>
                    <hr color="#c0c0c0">
                @endforeach
            </div>
        </div>
    </div>
    </div>
@endsection

<script>
    function initMap() {
        var posts = @json($posts->toArray());
        console.log(posts);
        Object.keys(posts).forEach(function (key) {
            post = posts[key]
            map = document.getElementById("map_"+ post.id);
            let postPlace = {lat: post.place.lat, lng: post.place.lng};
            opt = {
            zoom: 13,
            center: postPlace,
            };
            mapObj = new google.maps.Map(map, opt);
            marker = new google.maps.Marker({
            position: postPlace,
            map: mapObj,
            title: 'postPlace',
            });
        })
    }
    //# sourceURL=hoge.js 
</script>