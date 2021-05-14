<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\News;
use App\Place;
use App\History;
use Carbon\Carbon;
use Storage;

class NewsController extends Controller
{
  public function add()
  {
      return view('admin.news.create', ['title' => "", 'body' => "", 'name' => "", 'lat' => "", 'lng' => ""]);
  }
  
  public function judgecreate(Request $request)
  {
      $addressInput = $request->input('name');
      if($request->has('search')) {
        $latlng = $this->getAddress($request, $addressInput);
        $form =  $request->all();
        return view('admin.news.create', ['title' => $form['title'], 'body' => $form['body'], 'name' => $addressInput, 'lat' => $latlng["lat"], 'lng' => $latlng["lng"]]);
      } else {
        $this->create($request);
        return redirect('admin/news/');
      }
  }
  
  public function getAddress(Request $request, $addressInput)
  {
      $myKey = env('GOOGLE_MAP_API_KEY');

      $address = urlencode($addressInput);

      $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $address . "+CA&key=" . $myKey ;
    
      $contents = file_get_contents($url);
      $jsonData = json_decode($contents,true);

      $lat = $jsonData["results"][0]["geometry"]["location"]["lat"];
      $lng = $jsonData["results"][0]["geometry"]["location"]["lng"];
      $latlng = ["lat" => $lat, "lng" => $lng, "name" => $addressInput];
      return $latlng;
  }
  
  public function create(Request $request)
  {
      DB::transaction(function () {
        $this->validate($request, News::$rules);
        $this->validate($request, Place::$rules);
      
      // placeについてplaceテーブルの中から入力された場所の名前で検索
        $news_form = $request->all();
        $place = Place::where('name',$request->name)->first();
        //なかった場合はplacesテーブルから取得したplace情報(id)を取得する。
        if (!isset($place)) {
            $place = new place;
            $place->fill(['name' => $news_form['name'], 'lat' => $news_form['lat'], 'lng' => $news_form['lng']])->save();
        }
          
        $news = new News;
        if (isset($news_form['image'])) {
          $path = Storage::disk('s3')->putFile('/',$news_form['image'],'public');
          $news->image_path = Storage::disk('s3')->url($path);
        } else {
            $news->image_path = null;
        }
      
        unset($news_form['_token']);
        unset($news_form['image']);
        unset($news_form['name']);
        unset($news_form['lat']);
        unset($news_form['lng']);
        unset($news_form['redirect']);
      
        $news->fill($news_form);
        //プレイスの処理placeのidを設定し関連する。
        $news->place_id = $place->id;
        $news->save();
      });
  }
  
  public function index(Request $request)
  {
      $cond_title = $request->cond_title;
      if ($cond_title != '') {
        $posts = News::where('title', $cond_title)->get();
      } else {
        $posts = News::all();
      }
      return view('admin.news.index', ['posts' =>$posts, 'cond_title' => $cond_title]);
  }
  

  public function edit(Request $request)
  {
      $news = News::find($request->id);
      $place = $news->place;
      if (empty($news)) {
        abort(404);
      }
      return view('admin.news.edit', ['news_form' =>$news, 'title' => $news->title, 'body' => $news->body, 'name' => $place->name, 'lat' => $place->lat, 'lng' => $place->lng]);
  }
  
  public function judgeupdate(Request $request)
  {
      $addressInput = $request->input('name');
      if($request->has('search')) {
        $latlng = $this->getAddress($request, $addressInput);
        $news = News::find($request->id);
        $news_form = $request->all();
        return view('admin.news.edit', ['news_form' => $news, 'title' => $news_form['title'], 'body' => $news_form['body'], 'name' => $addressInput, 'lat' => $latlng["lat"], 'lng' => $latlng["lng"]]);
      } else {
        $this->update($request);
        return redirect('admin/news/');
      }
  }
  
  public function update(Request $request)
  {
      $this->validate($request, News::$rules);
      $this->validate($request, Place::$rules);
      $news_form = $request->all();
      $place = Place::where('name',$request->name)->first();
      if (!isset($place)) {
          $place = new place;
          $place->fill(['name' => $news_form['name'], 'lat' => $news_form['lat'], 'lng' => $news_form['lng']])->save();
      }
      $news = News::find($request->id);
      $news->place_id = $place->id;
      if ($request->remove == 'true') {
          $news_form['image_path'] = null;
      } elseif ($request->file('image')) {
        $path = Storage::disk('s3')->putFile('/',$news_form['image'],'public');
        $news->image_path = Storage::disk('s3')->url($path);
      } else {
          $news_form['image_path'] = $news->image_path;
      }
      
      unset($news_form['_token']);
      unset($news_form['image']);
      unset($news_form['remove']);
      unset($news_form['name']);
      unset($news_form['lat']);
      unset($news_form['lng']);
      unset($news_form['redirect']);
      $news->fill($news_form)->save();
      
      $history = new History;
      $history->news_id = $news->id;
      $news->place_id = $place->id;
      $history->edited_at = Carbon::now();
      $history->save();
      
  }

  public function delete(Request $request)
  {
      $news = News::find($request->id);
      $news->delete();
      return redirect('admin/news/');
  }
  
  
}