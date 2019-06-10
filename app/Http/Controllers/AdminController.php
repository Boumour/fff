<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Validator;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\Post;
use App\PostAttachment;
use App\UserFirebase;
use App\Admin;
use Hash;
use Auth;
use  App\PlaceCategory;
use  App\PlaceTags;
use  App\Places;
use  App\PlacesUsedTags;
use  App\PlacesCoverImages;
use  App\OpeningHours;
use  App\PlacesGalleryImages;
use  App\Ambassadors;
use  App\OurTeam;
use  App\PlaceReview;
use  App\Testimonial;
use  App\SuggestdVenue;
use  App\Media;
use App\Setting;
use App\AppVersion;
use App\BlogImgs;
use App\Blog;
use App\ReportedPost;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.dashboard')
                ->with('page_title','Dashboard');
    }

    public function users_list()
    {
        $user = User::where('deleted_at',0)->orderBy('id','desc')
                ->select('id','email','full_name','username','created_at')
                ->get();
        return view('admin.users_list')
                ->with('user',$user)
                ->with('page_title','Users List');
       
    }

    public function view_users_detail()
    {
        $id = \Request::segment('3');
        $user = User::where('id',$id)->first();
        if (!empty($user->full_name)) {
              $model = $user->full_name."'s Details";
            }else{
              $model = "User Detail";
            }
        return view('admin.user_detail')
                ->with('user',$user)
                ->with('page_title',$model);
    }

    public function users_delete()
    {
        $id = Input::get('id');
        $data = User::where('id',$id)->update(['deleted_at' => 1]);
        return $data;
    }

    public function users_enable()
    {
        $id = Input::get('id');
        $data = User::where('id',$id)->update(['is_desable' => 0]);
        return $data;
    }

    public function users_desable()
    {
        $id = Input::get('id');
        $data = User::where('id',$id)->update(['is_desable' => 1]);
        return $data;
    }

    public function category_list()
    {
        $category = PlaceCategory::orderBy('id','desc')->get();
        return view('admin.category_list')
                ->with('category',$category)
                ->with('page_title','Category');
    }

    public function category_disable()
    {
        $id = Input::get('id');
        $data = PlaceCategory::where('id',$id)->update(['status' => 0]);
        return $data;
    }

    public function category_enable()
    {
        $id = Input::get('id');
        $data = PlaceCategory::where('id',$id)->update(['status' => 1]);
        return $data;
    }

    public function category_submit()
    {
        $id=Input::get('id');
        $name=Input::get('name');
        if($id>0)
        {
            $data = PlaceCategory::find($id);
            $data->name = $name;           
            $data->save();
            $msg = 'Category Updated Successfully.';
        }
        else
        {
            $data = new PlaceCategory;
            $data->name = $name;      
            $data->save();
            $msg = 'Category Added Successfully.';
        }
        return redirect()->back()->with('msg',$msg);
    }

    public function place_tags_list()
    {
        $data = PlaceTags::orderBy('id','desc')->get();
        return view('admin.place_tags_list')
                    ->with('data',$data)
                    ->with('page_title','Place Tags List');
    }

    public function place_tags_submit(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $file = $request->image;
        $text = $request->text;
        if (Input::hasFile('image'))
            $image = Input::file('image');
        if($id>0)
        {
            $data = PlaceTags::find($id);
            $data->name = $name;
            $data->text = $text;
            /*if($request->hasfile('image') && $request->image != '')
            {
                $file=$request->image;
                $upload_path='place_tags/';
                $uploded_file=img_upload_HQquality($upload_path,$file);
                $data->image=$uploded_file;
            }*/  
            if($request->hasfile('image') && $request->image != '')
            {     
                if(env('S3_BUCKET')!="")
                {
                    $key="file";
                    $ext = $image->getClientOriginalExtension();
                    $file_name_s3=$key . '_' . time() . '.' .$ext;
                    $imageName = 'place_tags/' . $file_name_s3;
                
                    $s3 = \Storage::disk('s3');
                    $s3->put($imageName, file_get_contents($image));
                    $s3->setVisibility($imageName, 'public');
                    $s3_url_image=$file_name_s3;
                }
                $data->image = $s3_url_image;  
            }
            
            $data->save();
            $msg = 'Place Tags Updated Successfully.';
        }
        else
        {
            $data = new PlaceTags;
            $data->name = $name;  
            $data->text = $text;
            /*if($request->hasfile('image') && $request->image != '')
            {
                $file=$request->image;
                $upload_path='place_tags/';
                $uploded_file=img_upload_HQquality($upload_path,$file);
                $data->image=$uploded_file;
            }*/ 
            if($request->hasfile('image') && $request->image != '')
            {     
                if(env('S3_BUCKET')!="")
                {
                    $key="file";
                    $ext = $image->getClientOriginalExtension();
                    $file_name_s3=$key . '_' . time() . '.' .$ext;
                    $imageName = 'place_tags/' . $file_name_s3;
                
                    $s3 = \Storage::disk('s3');
                    $s3->put($imageName, file_get_contents($image));
                    $s3->setVisibility($imageName, 'public');
                    $s3_url_image=$file_name_s3;
                }
                $data->image = $s3_url_image;  
            }
            $data->save();
            $msg = 'Place Tags Added Successfully.';
        }
        return redirect()->back()->with('msg',$msg);
    }

    public function places_list()
    {
        $data = Places::where('deleted_at',0)->orderBy('id','desc')
                    ->select('id','name','country','city','type','username','queued','deleted_at')
                    ->get();
        //$chunks = $data->chunk(100);
        //echo "<pre>"; print_r($chunks); exit;
        return view('admin.place_list')
                    ->with('data',$data)
                    ->with('page_title','Places List');
    }

    public function places_delete()
    {
        $id = Input::get('id');
        $data = Places::where('id',$id)->update(['deleted_at' => 1]);
        return $data;
    }

    public function places_enable()
    {
        $id = Input::get('id');
        $data = Places::where('id',$id)->update(['queued' => 1]);
        return $data;
    }

    public function places_desable()
    {
        $id = Input::get('id');
        $data = Places::where('id',$id)->update(['queued' => 0]);
        return $data;
    }
    

    public function edit_places()
    {
        $id = \Request::segment(3);
        $cat = PlaceCategory::where('status',1)->select('id','name')->get();
        $plac_tag = PlaceTags::select('id','name')->get();
        if ($id > 0) 
        {
            $place = Places::where('id',$id)->first();
            $id = $place->id;
            $name = $place->name;
            $username = $place->username;
            $country = $place->country;
            $state = $place->state;
            $city = $place->city;
            $line1 = $place->line1;
            $line2 = $place->line2;
            $street = $place->street;
            $addressFull = $place->addressFull;
            $bio = $place->bio;
            $categories = $place->categories;
            $email = $place->email;
            $facebookLink = $place->facebookLink;
            $twitterLink = $place->twitterLink;
            $websiteLink = $place->websiteLink;
            $youtubeLink = $place->youtubeLink;
            $featuredVideoLink = $place->featuredVideoLink;
            $phoneNumber = $place->phoneNumber;
            $phoneNumber2 = $place->phoneNumber2;
            $postal = $place->postal;
            $postalCity = $place->postalCity;
            $postalLine1 = $place->postalLine1;
            $postalLine2 = $place->postalLine2;
            $postalPostcode = $place->postalPostcode;
            $postalState = $place->postalState;
            $shortBio = $place->shortBio;
            $slug = $place->slug;
            $type = $place->type;
            $country2 = $place->country2;
            $postalCountry = $place->postalCountry;
            $environmentalScore = $place->environmentalScore;
            $foodScore = $place->foodScore;
            $overallScore = $place->overallScore;
            $serviceScore = $place->serviceScore;
            $googlePlaceID = $place->googlePlaceID;
            $lat = $place->lat;
            $lon = $place->lon;
            $image = PlacesCoverImages::where('places_id',$id)->first();
            $details = OpeningHours::where('places_id',$id)->first();
            $gallery_img = PlacesGalleryImages::where('places_id',$id)->where('deleted_at',0)->first();
            $page_title = 'Edit Place';
        } 
        else{
            $id = 0;
            $name = '';
            $username = '';
            $country = '';
            $state = '';
            $city = '';
            $line1 = '';
            $line2 = '';
            $street = '';
            $addressFull = '';
            $bio = '';
            $categories = '';
            $email = '';
            $facebookLink = '';
            $twitterLink = '';
            $websiteLink = '';
            $youtubeLink = '';
            $featuredVideoLink = '';
            $phoneNumber = '';
            $phoneNumber2 = '';
            $postal = '';
            $postalCity = '';
            $postalLine1 = '';
            $postalLine2 = '';
            $postalPostcode = '';
            $postalState = '';
            $shortBio = '';
            $slug = '';
            $type = '';
            $country2 = '';
            $postalCountry = '';
            $environmentalScore = '';
            $foodScore = '';
            $overallScore = '';
            $serviceScore = '';
            $googlePlaceID = '';
            $lat = '';
            $lon = '';
            $image = '';
            $details = '';
            $gallery_img = '';
            $page_title = 'Add Place';
        }
        return view('admin.edit_places')
                ->with('id',$id)
                ->with('name',$name)
                ->with('username',$username)
                ->with('country',$country)
                ->with('state',$state)
                ->with('city',$city)
                ->with('line1',$line1)
                ->with('line2',$line2)
                ->with('street',$street)
                ->with('addressFull',$addressFull)
                ->with('bio',$bio)
                ->with('categories',$categories)
                ->with('email',$email)
                ->with('facebookLink',$facebookLink)
                ->with('twitterLink',$twitterLink)
                ->with('websiteLink',$websiteLink)
                ->with('youtubeLink',$youtubeLink)
                ->with('featuredVideoLink',$featuredVideoLink)
                ->with('cat',$cat)
                ->with('phoneNumber',$phoneNumber)
                ->with('phoneNumber2',$phoneNumber2)
                ->with('postal',$postal)
                ->with('postalCity',$postalCity)
                ->with('postalLine1',$postalLine1)
                ->with('postalLine2',$postalLine2)
                ->with('postalPostcode',$postalPostcode)
                ->with('postalState',$postalState)
                ->with('shortBio',$shortBio)
                ->with('slug',$slug)
                ->with('type',$type)
                ->with('country2',$country2)
                ->with('postalCountry',$postalCountry)
                ->with('environmentalScore',$environmentalScore)
                ->with('foodScore',$foodScore)
                ->with('overallScore',$overallScore)
                ->with('serviceScore',$serviceScore)
                ->with('googlePlaceID',$googlePlaceID)
                ->with('lat',$lat)
                ->with('lon',$lon)
                ->with('plac_tag',$plac_tag)
                ->with('image',$image)
                ->with('details',$details)
                ->with('gallery_img',$gallery_img)
                ->with('page_title', $page_title);
    }

    public function update_places(Request $request)
    {
        $id = $request->id;
        //echo $id; exit;
        $name = $request->name;
        $username = $request->username;
        $country = $request->country;
        $state = $request->state;
        $city = $request->city;
        $line1 = $request->line1;
        $line2 = $request->line2;
        $street = $request->street;
        $addressFull = $request->addressFull;
        $bio = $request->bio;
        $categorie = $request->categories;
        if ($categorie) 
        {
           $categorie = implode(',', $request->categories);
        }
        $email = $request->email;
        $facebookLink = $request->facebookLink;
        $twitterLink = $request->twitterLink;
        $websiteLink = $request->websiteLink;
        $youtubeLink = $request->youtubeLink;
        $featuredVideoLink = $request->featuredVideoLink;
        $phoneNumber = $request->phoneNumber;
        $phoneNumber2 = $request->phoneNumber2;
        $postal = $request->postal;
        $postalCity = $request->postalCity;
        $postalLine1 = $request->postalLine1;
        $postalLine2 = $request->postalLine2;
        $postalPostcode = $request->postalPostcode;
        $postalState = $request->postalState;
        $shortBio = $request->shortBio;
        $slug = $request->slug;
        $type = $request->type;
        $country2 = $request->country2;
        $postalCountry = $request->postalCountry;
        $environmentalScore = $request->environmentalScore;
        $foodScore = $request->foodScore;
        $overallScore = $request->overallScore;
        $serviceScore = $request->serviceScore;
        $googlePlaceID = $request->googlePlaceID;
        $lat = $request->lat;
        $lon = $request->lon;
        $hour_details = $request->details;
        $o_hour = $request->o_hour;
        $gallery_img = $request->gallery_img;
        if ($id>0) 
        {
            //echo "<pre>"; print_r($request->all()); exit;
            $place = Places::find($id);
            //echo $place; exit;
            $place->name = $name;
            $place->username = $username;
            $place->country = $country;
            $place->state = $state;
            $place->city = $city;
            $place->line1 = $line1;
            $place->line2 = $line2;
            $place->street = $street;
            $place->addressFull = $addressFull;
            $place->bio = $bio;
            $place->categories = $categorie;
            $place->email = $email;
            $place->facebookLink = $facebookLink;
            $place->twitterLink = $twitterLink;
            $place->websiteLink = $websiteLink;
            $place->youtubeLink = $youtubeLink;
            $place->featuredVideoLink = $featuredVideoLink;
            $place->phoneNumber = $phoneNumber;
            $place->phoneNumber2 = $phoneNumber2;
            $place->postal = $postal;
            $place->postalCity = $postalCity;
            $place->postalLine1 = $postalLine1;
            $place->postalLine2 = $postalLine2;
            $place->postalPostcode = $postalPostcode;
            $place->postalState = $postalState;
            $place->shortBio = $shortBio;
            $place->slug = $slug;
            $place->type = $type;
            $place->country2 = $country2;
            $place->postalCountry = $postalCountry;
            $place->environmentalScore = $environmentalScore;
            $place->foodScore = $foodScore;
            $place->overallScore = $overallScore;
            $place->serviceScore = $serviceScore;
            $place->googlePlaceID = $googlePlaceID;
            $place->lat = $lat;
            $place->lon = $lon;
            $place->save();
            $used_tag = $request->used_tag;
            
            $check = PlacesUsedTags::where('places_id',$id)->first();
            if (!empty($check)) 
            {
                $alredy = PlacesUsedTags::where('places_id',$id)->pluck('places_tag_id')->toArray();
                if (count($used_tag) > count($alredy)) 
                {
                    $diff = array_diff($used_tag, $alredy);
                    foreach ($diff as $value) 
                    {
                        $tag_nm = PlaceTags::where('id',$value)->first();
                        $new_tag = new PlacesUsedTags;
                        $new_tag->places_id = $id;
                        $new_tag->name = $tag_nm->name;
                        $new_tag->places_tag_id = $value;
                        $new_tag->save();
                    }
                }
                else
                {
                    $diff = array_diff($alredy, $used_tag);
                    foreach ($diff as $value) 
                    {
                        $tag_nm = PlacesUsedTags::where('places_tag_id',$value)
                                    ->where('places_id',$id)->delete();
                    }
                }
            }
            else
            {
                if (!empty($used_tag))
                {
                    for ($i=0; $i < count($used_tag); $i++) 
                    { 
                        $tag_nm = PlaceTags::where('id',$used_tag[$i])->first();
                        $new_tag = new PlacesUsedTags;
                        $new_tag->places_id = $id;
                        $new_tag->name = $tag_nm->name;
                        $new_tag->places_tag_id = $used_tag[$i];
                        $new_tag->save();
                    }
                }
            }
            if($request->hasfile('image') && $request->image != '')
            {
                $file = $request->image;
                $upload_path='places/cover/';
                $uploded_file = image_upload($upload_path,$file);
                $cover_img = PlacesCoverImages::where('places_id',$id)->first();
                if(!empty($cover_img))
                {
                    $cover_img->cover_pic = $uploded_file;
                    $cover_img->save();
                }else{
                    $cover = new PlacesCoverImages;
                    $cover->cover_pic = $uploded_file;
                    $cover->places_id = $id;
                    $cover->fileName = 'Cover Image';
                    $cover->save();
                }
            }
            if($request->details) 
            {                
                $all = \DB::table('opening_hours')->where('places_id',$id)->get(['id']);
                if(COUNT($all)>0)
                {
                    $i=0;
                    foreach($all as $alls)
                    {
                        $update = OpeningHours::find($alls->id);
                        $update->details = $hour_details[$i];
                        $update->save();
                        $i++;
                    }

                    if(COUNT($hour_details) > COUNT($all))
                    {
                        for($j=COUNT($all);$j<COUNT($hour_details);$j++)
                        {
                            $new = new OpeningHours;
                            $new->details = $hour_details[$j];
                            $new->places_id = $id;
                            $new->save();
                        }
                    }
                }
                else
                {
                    for ($k=0; $k < count($hour_details); $k++) 
                    { 
                        $new = new OpeningHours;
                        $new->details = $hour_details[$k];
                        $new->places_id = $id;
                        $new->save();
                    }
                }    
            }
            if($request->hasfile('gallery_img') && $request->gallery_img != '') 
            {
                $image = $request->gallery_img;
                $upload_path='places/gallery/';
                foreach ($image as $value) 
                {
                    $uploded_file = image_upload($upload_path,$value);
                    $Gallery = new PlacesGalleryImages;
                    $Gallery->storageUrl = $uploded_file;
                    $Gallery->places_id = $place->id;
                    $Gallery->fileName = 'Gallery Image';
                    $Gallery->save();
                }
            }
            $msg = 'Place Updated Successfully';
        }
        else{
            $place = new Places;
            $place->name = $name;
            $place->username = $username;
            $place->country = $country;
            $place->state = $state;
            $place->city = $city;
            $place->line1 = $line1;
            $place->line2 = $line2;
            $place->street = $street;
            $place->addressFull = $addressFull;
            $place->bio = $bio;
            $place->categories = $categorie;
            $place->email = $email;
            $place->facebookLink = $facebookLink;
            $place->twitterLink = $twitterLink;
            $place->websiteLink = $websiteLink;
            $place->youtubeLink = $youtubeLink;
            $place->featuredVideoLink = $featuredVideoLink;
            $place->phoneNumber = $phoneNumber;
            $place->phoneNumber2 = $phoneNumber2;
            $place->postal = $postal;
            $place->postalCity = $postalCity;
            $place->postalLine1 = $postalLine1;
            $place->postalLine2 = $postalLine2;
            $place->postalPostcode = $postalPostcode;
            $place->postalState = $postalState;
            $place->shortBio = $shortBio;
            $place->slug = $slug;
            $place->type = $type;
            $place->country2 = $country2;
            $place->postalCountry = $postalCountry;
            $place->environmentalScore = $environmentalScore;
            $place->foodScore = $foodScore;
            $place->overallScore = $overallScore;
            $place->serviceScore = $serviceScore;
            $place->googlePlaceID = $googlePlaceID;
            $place->lat = $lat;
            $place->lon = $lon;
            $place->save();
            $used_tag = $request->used_tag;
            for ($i=0; $i < count($used_tag); $i++) 
            { 
                $tag_nm = PlaceTags::where('id',$used_tag[$i])->first();
                $new_tag = new PlacesUsedTags;
                $new_tag->places_id = $place->id;
                $new_tag->name = $tag_nm->name;
                $new_tag->places_tag_id = $used_tag[$i];
                $new_tag->save();
            }
            if($request->hasfile('image') && $request->image != '')
            {
                $file = $request->image;
                $cover = new PlacesCoverImages;
                $upload_path='places/cover/';
                $uploded_file = image_upload($upload_path,$file);
                $cover->cover_pic = $uploded_file;
                $cover->places_id = $id;
                $cover->fileName = 'Cover Image';
                $cover->save();
            }
            for ($i=0; $i < count($hour_details); $i++) 
            { 
                $opn_hr = new OpeningHours;
                $opn_hr->details = $hour_details[$i];
                $opn_hr->places_id = $place->id;
                $opn_hr->save();
            }
            if($request->hasfile('gallery_img') && $request->gallery_img != '') 
            {
                $image = $request->gallery_img;
                $upload_path='places/gallery/';
                foreach ($image as $value) 
                {
                    $uploded_file = image_upload($upload_path,$value);
                    $Gallery = new PlacesGalleryImages;
                    $Gallery->storageUrl = $uploded_file;
                    $Gallery->places_id = $place->id;
                    $Gallery->fileName = 'Gallery Image';
                    $Gallery->save();
                }
            }
            $msg = 'Place Added Successfully';
        }
        return redirect::back()->with('msg',$msg);

    }

    public function places_detail(Request $request)
    {
        $id = $request->id;
        $data = Places::where('id','=',$id)->get();
        $place_tag = PlacesUsedTags::where('places_id','=',$id)->get();
        $cover = PlacesCoverImages::where('places_id','=',$id)->first();
        $hour = OpeningHours::where('places_id','=',$id)->get();
        $gallery = PlacesGalleryImages::where('places_id','=',$id)->where('deleted_at',0)->get();
        return view('admin.detail_place')
                    ->with('id',$id)
                    ->with('data',$data)
                    ->with('place_tag',$place_tag)
                    ->with('cover',$cover)
                    ->with('hour',$hour)
                    ->with('gallery',$gallery)
                    ->with('page_title','Place Detail');
    }

    public function places_gallery_delete()
    {
        $id = Input::get('id');
        $data = PlacesGalleryImages::where('id',$id)->update(['deleted_at' => 1]);
        return $data;
    }

    public function places_review()
    {
        $review = PlaceReview::where('deleted_at',0)->orderBy('id','desc')->get();
        return view('admin.place_review')
                    ->with('review',$review)
                    ->with('page_title','Place Reviews');
    }

    public function places_review_delete()
    {
        $id = Input::get('id');
        $data = PlaceReview::where('id',$id)->update(['deleted_at' => 1]);
        return $data;
    }

    public function places_review_enable()
    {
        $id = Input::get('id');
        $data = PlaceReview::where('id',$id)->update(['status' => 1]);
        return $data;
    }

    public function places_review_desable()
    {
        $id = Input::get('id');
        $data = PlaceReview::where('id',$id)->update(['status' => 0]);
        return $data;
    }

    public function our_team()
    {
        $data = OurTeam::where('deleted_at',0)->orderBy('id','desc')->get();
        return view('admin.our_team')
                ->with('data',$data)
                ->with('page_title','Our Team');
    }

    public function our_team_submit(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $designation = $request->designation;
        $file = $request->image;
        if($id>0)
        {
            $data = OurTeam::find($id);
            $data->name = $name;
            $data->designation = $designation;
            if($request->hasfile('image') && $request->image != '')
            {
                $file=$request->image;
                $upload_path='team/';
                $uploded_file=img_upload_HQquality($upload_path,$file);
                $data->image=$uploded_file;
            }         
            $data->save();
            $msg = 'User Updated Successfully.';
        }
        else
        {
            $data = new OurTeam;
            $data->name = $name;
            $data->designation = $designation; 
            if($request->hasfile('image') && $request->image != '')
            {
                $file=$request->image;
                $upload_path='team/';
                $uploded_file=img_upload_HQquality($upload_path,$file);
                $data->image=$uploded_file;
            } 
            $data->save();
            $msg = 'User Added Successfully.';
        }
        return redirect()->back()->with('msg',$msg);
    }

    public function our_team_delete()
    {
        $id = Input::get('id');
        $data = OurTeam::where('id',$id)->update(['deleted_at' => 1]);
        return $data;
    }

    public function ambassador_list()
    {
        $data = Ambassadors::where('deleted_at',0)->orderBy('id','desc')->get();
        return view('admin.ambassador_list')
                ->with('data',$data)
                ->with('page_title','Ambassador List');
    }

    public function ambassador_submit(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $country = $request->country;
        $description = $request->description;
        $ext_link = $request->ext_link;
        $file = $request->image;
        if($id>0)
        {
            $data = Ambassadors::find($id);
            $data->name = $name;
            $data->country = $country;
            $data->description = $description;
            $data->ext_link = $ext_link;
            if($request->hasfile('image') && $request->image != '')
            {
                $file=$request->image;
                $upload_path='ambassadors/';
                $uploded_file=img_upload_HQquality($upload_path,$file);
                $data->image=$uploded_file;
            }         
            $data->save();
            $msg = 'User Updated Successfully.';
        }
        else
        {
            $data = new Ambassadors;
            $data->name = $name;
            $data->country = $country;
            $data->description = $description;
            $data->ext_link = $ext_link;
            if($request->hasfile('image') && $request->image != '')
            {
                $file=$request->image;
                $upload_path='ambassadors/';
                $uploded_file=img_upload_HQquality($upload_path,$file);
                $data->image=$uploded_file;
            } 
            $data->save();
            $msg = 'Ambassador User Added Successfully.';
        }
        return redirect()->back()->with('msg',$msg);
    }

    public function ambassador_delete()
    {
        $id = Input::get('id');
        $data = Ambassadors::where('id',$id)->update(['deleted_at' => 1]);
        return $data;
    }

    public function testimonial()
    {
        $data = Testimonial::where('deleted_at',0)->orderBy('id','desc')->get();
        return view('admin.testimonial')
                ->with('data',$data)
                ->with('page_title','Testimonial List');
    }

    public function testimonial_submit(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $designation = $request->designation;
        $text = $request->text;
        if($id>0)
        {
            $data = Testimonial::find($id);
            $data->name = $name;
            $data->designation = $designation;
            $data->text = $text;
            $data->save();
            $msg = 'Testimonial Updated Successfully.';
        }
        else
        {
            $data = new Testimonial;
            $data->name = $name;
            $data->designation = $designation;
            $data->text = $text; 
            $data->save();
            $msg = 'Testimonial Added Successfully.';
        }
        return redirect()->back()->with('msg',$msg);
    }

    public function testimonial_delete()
    {
        $id = Input::get('id');
        $data = Testimonial::where('id',$id)->update(['deleted_at' => 1]);
        return $data;
    }

    public function suggest_venue()
    {
        $data = SuggestdVenue::where('deleted_at',0)->orderBy('id','desc')->get();
        return view('admin.suggest_venue')
                ->with('data',$data)
                ->with('page_title','Suggested Venue List');
    }

    public function suggest_venue_delete()
    {
        $id = Input::get('id');
        $data = SuggestdVenue::where('id',$id)->update(['deleted_at' => 1]);
        return $data;
    }

    public function media_list()
    {
        $data = Media::where('deleted_at',0)->orderBy('id','desc')->get();
        return view('admin.media')
                ->with('data',$data)
                ->with('page_title','Media List');
    }

    public function media_submit(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $ext_url = $request->ext_url;
        $source_url = $request->source_url;
        $description = $request->description;
        $file = $request->image;
        if($id>0)
        {
            $data = Media::find($id);
            $data->name = $name;
            $data->ext_url = $ext_url;
            $data->description = $description;
            $data->source_url = $source_url;
            if($request->hasfile('image') && $request->image != '')
            {
                $file=$request->image;
                $upload_path='media/';
                $uploded_file=img_upload_HQquality($upload_path,$file);
                $data->image=$uploded_file;
            }         
            $data->save();
            $msg = 'Media Updated Successfully.';
        }
        else
        {
            $data = new Media;
            $data->name = $name;
            $data->ext_url = $ext_url;
            $data->description = $description;
            $data->source_url = $source_url;
            if($request->hasfile('image') && $request->image != '')
            {
                $file=$request->image;
                $upload_path='media/';
                $uploded_file=img_upload_HQquality($upload_path,$file);
                $data->image=$uploded_file;
            } 
            $data->save();
            $msg = 'Media Added Successfully.';
        }
        return redirect()->back()->with('msg',$msg);
    }

    public function media_delete()
    {
        $id = Input::get('id');
        $data = Media::where('id',$id)->update(['deleted_at' => 1]);
        return $data;
    }

    public function privacy_policy()
    {
        return view('admin.privacy_policies')
                ->with('page_title','Privacy Policy');
    }

    public function save_privacy_policy()
    {
        $policy = Input::get('privacy_policy');
        $setting = Setting::get();
        foreach ($setting as $set_val) 
        {
            if ($set_val->key == 'privacy_policy' && Input::get('privacy_policy')) 
            {
                $set_val->value = Input::get('privacy_policy');
            }
            $set_val->save();
        }
        
        $msg = 'Privacy Policy saved Successfully.';
        return redirect()->back()->with('msg', $msg);
    } 

    public function app_version()
    {
        $data = AppVersion::get();
        return view('admin.app_version')
                ->with('data',$data)
                ->with('page_title','Application Version List');
    }

    public function app_version_submit(Request $request)
    {
        $id = $request->id;
        $app_version = $request->app_version;
        $Is_update = $request->Is_update ;
        if($id>0)
        {
            $data = AppVersion::find($id);
            $data->app_version = $app_version;
            $data->Is_update = $Is_update;
            $data->save();
            $msg = 'Application Version Updated Successfully.';
        }
        return redirect()->back()->with('msg',$msg);
    }

    public function settings()
    {
        return view('admin.setting')
                ->with('page_title','Settings');
    }

    public function change_password()
    {
        $old_pass = Input::get('current_password');
        $new_pass = Input::get('new_password');
        $con_pass = Input::get('confirm_password');

        $rules = array(
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',

        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return Redirect()->back()->withErrors($validator);
        } 
        else 
        {
            $id = Auth::id();
            $admin = Admin::find($id);
            if(!Hash::check($old_pass, $admin->password))
            {
                return Redirect()->back()->with('msg','Current password is wrong..!');
            }
            else
            {
                $admin->password = bcrypt($new_pass);
                $admin->save();
                return Redirect()->back()
                           ->with('msg','Password Changed Successfully.');
            }
        }
    }

    public function blog_list()
    {
        $data = Blog::where('deleted_at',0)->orderBy('id','desc')->get();
        return view('admin.blog_list')
                    ->with('data',$data)
                    ->with('page_title','Blog List');
    }

    public function add_blog()
    {
        $id = \Request::segment(3);
        if ($id > 0) 
        {
            $blogs = Blog::where('id',$id)->first();
            $id = $blogs->id;
            $title = $blogs->title;
            $text = $blogs->text;
            $image = $blogs->cover_img;
            $page_title = 'Edit Blog';
        }
        else
        {
            $id =  0;
            $title =  '';
            $text =  '';
            $image = '';
            $page_title = 'Add Blog';
        }
        return view('admin.add_blog')
                ->with('id',$id)
                ->with('title',$title)
                ->with('text',$text)
                ->with('image',$image)
                ->with('page_title',$page_title);
    }

    public function submit_blog()
    {
        $id = Input::get('id');
        $title = Input::get('title');
        $text = Input::get('text');
        if ($id>0)
        {
            $blogs = Blog::find($id);
            $blogs->title = $title;
            $blogs->text = $text;
            if (Input::file('image') && !empty(Input::file('image'))) 
            {
                $file = Input::file('image');
                $upload_path='blog/';
                $uploded_file=img_upload_HQquality($upload_path,$file);
                $blogs->cover_img = $uploded_file;
            }
            $blogs->save();
            $msg = 'Blog Updated Successfully.';
        }
        else
        {
            $blogs = new Blog;
            $blogs->title = $title;
            $blogs->text = $text;
            if(Input::file('image') && !empty(Input::file('image')))
            {
                $file = Input::file('image');
                $upload_path='blog/';
                $uploded_file=img_upload_HQquality($upload_path,$file);
                $blogs->cover_img=$uploded_file;
            } 
            $blogs->save();
            $msg = 'Blog Added Successfully.';
        }
        return redirect()->back()->with('msg',$msg);
    }

    public function blog_delete()
    {
        $id = Input::get('id');
        $data = Blog::where('id',$id)->update(['deleted_at' => 1]);
        return $data;
    }

    public function view_blog_imgs()
    {
        $data = BlogImgs::where('deleted_at',0)->orderBy('id','desc')->get();
        return view('admin.blog_imgs')
                    ->with('data',$data)
                    ->with('page_title','Blog Images');
    }

    public function add_blog_imgs()
    {
        return view('admin.add_blog_imgs')
                ->with('page_title','Add Blog Images');
    }

    public function submit_blog_imgs(Request $request)
    {
        if($request->hasfile('upload_file') && $request->upload_file != '') 
        {
            $image = $request->upload_file;
            $upload_path='blog_imgs/';
            foreach ($image as $value) 
            {
                $uploded_file = img_upload_HQquality($upload_path,$value);
                $blog_img = new BlogImgs;
                $blog_img->image = $uploded_file;
                $blog_img->save();
            }
        }
        $msg = 'Images Uploaded Successfully.';
        return redirect()->back()->with('msg',$msg);
    }

    public function delete_blog_imgs()
    {
        $id = Input::get('id');
        $data = BlogImgs::where('id',$id)->update(['deleted_at' => 1]);
        return $data;
    }

    public function reported_post()
    {
        $data = \DB::table('reported_post as rp')
                        ->leftjoin('post as p','p.id','=','rp.post_id')
                        ->leftjoin('post_attachment as pa','pa.post_id','=','rp.post_id')
                        ->leftjoin('report as r','r.id','=','rp.report_id')
                        ->leftjoin('users as u','u.id','=','rp.reported_userId')
                        ->where('rp.deleted_at',0)
                        ->get(['rp.id','r.name as r_type','p.id as post_id','p.user_id','p.text','u.username as reported_by','pa.post_pic']);
        //echo "<pre>"; print_r($data); exit;
                return view('admin.reported_post')
                            ->with('page_title','Reported Post')
                            ->with('data',$data);
    }

    public function delete_reported_post()
    {
        $id = Input::get('id');
        $data = ReportedPost::where('id',$id)->update(['deleted_at' => 1]);
        return $data;
    }


    // --------------------------------------------------------------------------------------------------- //
}
