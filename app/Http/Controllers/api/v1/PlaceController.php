<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use App\User;
use App\UserFirebase;
use App\Followers;
use App\Following;
use App\Post;
use App\PostAttachment;
use App\PostCommentLike;
use App\PostComments;
use App\PostLike;
use App\PostTags;
use App\ReportedPost;
use App\Report;
use Auth;
use App\PlaceTags;

class PlaceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function place_tags()
    {
    	$list = array();
    	$data = PlaceTags::orderBy('id','desc')->get();
    	foreach ($data as $datas) 
    	{
    		if (!empty($datas->image)) 
	    	{
	    		$datas->image = env('STORAGE_URL').'place_tags/'.$datas->image;
	    	}
	    	$list[] = $datas;
    	}
    	success(config('messages.general.fetch_success'),$list);
    	
    }

    /*public function place_list_old(Request $request)
    {
        $data = array();
        $take = $request->total_records ?: 10;
        $page = $request->page ?: 1;
        $place_list = \DB::table('places')
                        ->orderBy('id','DESC')
                        ->take($take)
                        ->skip(($page - 1) * $take)
                        ->get();        
        foreach($place_list as $place_list)
        {
            $data[] = $place_list;
            $place_list->_index = "places";
            $place_list->_type =  "place";
            $place_list->_id = $place_list->id;
            $place_list->_score = 1;
            $place_list->_source = array("name"=>$place_list->name, "email"=>$place_list->email, "bio"=>$place_list->bio);
            $place_list->tags = \DB::table('places_used_tag')->where('places_id',$place_list->id)->pluck('name');
            $place_list->coverImage = \DB::table('places_cover_images')->where('places_id',$place_list->id)->get();
            $place_list->address = array("city"=>$place_list->city, "country"=>$place_list->country, "state"=>$place_list->state , "street" =>$place_list->street );
            $place_list->location = array("lat"=>$place_list->lat, "lon"=>$place_list->lon);
        }
        success(config('messages.general.fetch_success'),$data);
    }*/

    public function place_list(Request $request)
    {
        $data = array();
        $place_tags=array();
        $result = array();
        $take = $request->total_records ?: 10;
        $page = $request->page ?: 1;
        $place_list = \DB::table('places')
                        ->orderBy('id','DESC')
                        ->take($take)
                        ->skip(($page - 1) * $take)
                        ->get();           
        foreach($place_list as $place_lists)
        {
            $data['exploreModel'] = $place_list;
                        
            $place_lists->tags = \DB::table('places_used_tag')->where('places_id',$place_lists->id)->pluck('name');
            $place_lists->coverImage = \DB::table('places_cover_images')->where('places_id',$place_lists->id)->get();
            $place_lists->address = array("city"=>$place_lists->city, "country"=>$place_lists->country, "state"=>$place_lists->state , "street" =>$place_lists->street );
            $place_lists->location = array("lat"=>$place_lists->lat, "lon"=>$place_lists->lon);
            $placeTag = \DB::table('places_used_tag as put')
                                        ->leftjoin('place_tags as pt','pt.id','=','put.places_tag_id')
                                        ->where('put.places_id',$place_lists->id)
                                        ->get(['pt.*']);
            foreach($placeTag as $placeTags)
            {
                $place_tags[] = $placeTags;
                $placeTags->image = env('S3_LINK').'place_tags/'.$placeTags->image;
            }
            $place_lists->placeTages = $place_tags;
            /*$data['_index'] = "places";
            $data['_type'] =  "place";
            $data['_id'] = $place_lists->id;
            $data['_score'] = 1; */
            $place_lists->_index = "places";
            $place_lists->_type =  "place";
            $place_lists->_id = $place_lists->id;
            $place_lists->_score = 1;
        }
        $result[] = $data;
        success2(config('messages.general.fetch_success'),$result);
    }


// -------------------------------------------------------------------------------------- //

}

?>