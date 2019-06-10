<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserFirebase;
use App\Following;
use App\Post;
use App\Places;
use App\UserPassword;
use App\PostAttachment;
use App\PostLike;
use App\PlaceCategory;
use App\PlaceTags;
use App\PlacesCoverImages;
use App\PlacesGalleryImages;
use App\OpeningHours;
use App\PlacesUsedTags;
use App\PlaceReview;
use App\PostTags;
use App\Followers;
use App\PlaceComments;
use App\PostComments;
use App\PlaceCommentLike;
use App\PostCommentLike;
use App\Feeds;
use App\ArchivePlaces;
use App\Notification;

class TestController extends Controller
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

    public function user()
    {
      $str = file_get_contents('C:\Users\Rushabh\Desktop\Fairfood\Json Table wise\users.json');
      $json = json_decode($str, true);
      // echo "<pre>";print_r($json);
      foreach ($json as $key => $value) 
      {
          $user = new UserFirebase;
          $user->firebase_userId = $key;
          if(isset($value['attachmentCount'])){  
            $user->attachmentCount = $value['attachmentCount'];
          }
          if(isset($value['email'])){  
            $user->email = $value['email'];
          }
          if(isset($value['facebookLinked'])){
            $user->facebookLinked = $value['facebookLinked'];
          }
          if(isset($value['full_name'])){ 
            $user->full_name = $value['full_name'];
          }
          if(isset($value['listsCount'])){ 
            $user->listsCount = $value['listsCount'];
          }
          if(isset($value['oneSignal'])){ 
            foreach($value['oneSignal'] as $arr)
            {
                $user->oneSignal = $arr;
            }
          }
          if(isset($value['profile_picture'])){ 
            $user->profile_picture = $value['profile_picture'];
          }
          if(isset($value['cover_picture'])){ 
            $user->cover_picture = $value['cover_picture'];
          }
          if(isset($value['twitterLinked'])){ 
            $user->twitterLinked = $value['twitterLinked'];
          }
          if(isset($value['username'])){ 
            $user->username = $value['username'];
          }
          $user->save();
      }
      echo 'Imported Successfully';
    }

    public function userpassword()
    {
      $str = file_get_contents('C:\Users\Rushabh\Desktop\Fairfood\Json Table wise\users_password.json');
      $json = json_decode($str, true);
      
      foreach($json as $key => $value)
      {
        $data = UserFirebase::where('firebase_userId',$value['localId'])->first();
        if(COUNT($data)>0)
        {
          $user_pass = new UserPassword;
          if(isset($value['localId'])){ 
            $user_pass->firebase_userId = $value['localId'];
          }
          if(isset($value['email'])){
            $user_pass->email = $value['email'];
          }
          if(isset($value['passwordHash'])){
            $user_pass->password = $value['passwordHash'];
          }
          if(isset($value['emailVerified'])){
            if ($value['emailVerified']==false) {
              $user_pass->email_verify = 0;
            }else{
              $user_pass->email_verify = 1;
            }
          }
          $user_pass->user_id = $data->id;      
          foreach ($value['providerUserInfo'] as $provider) 
          {
              //print_r($provider['providerId']);echo "<br>";
            if(isset($value['providerUserInfo'])){
              $user_pass->providerId = $provider['providerId'];
            }
            if(isset($value['providerUserInfo'])){
              $user_pass->displayName = $provider['displayName'];
            }
            if(isset($value['providerUserInfo'])){
              $user_pass->social_profile = $provider['photoUrl'];
            }
          }
          $user_pass->save();
        }
      }
      echo "Imported Successfully";
    }

    public function userpasswordmove()
    {
      $usr_data = \DB::table('user_firebase as uf')
               ->leftjoin('user_password as up','uf.firebase_userId','=','up.firebase_userId')
               ->get(['up.displayName','up.email_verify','up.providerId','up.social_profile',
                        'uf.firebase_userId','up.firebase_userId as f_UID','up.password as upassword']);
       foreach ($usr_data as $value_usr) 
       {
        $chk = UserFirebase::where('firebase_userId',$value_usr->firebase_userId)->first();
        if(COUNT($chk)>0)
        {
          $user = UserFirebase::find($chk->id);
          if($value_usr->displayName!= '')
          {
             $user->full_name = $value_usr->displayName;
          } 
          if($value_usr->providerId!= '')
          {
             $user->providerId = $value_usr->providerId;
          }
          if($value_usr->social_profile!= '')
          {
             $user->social_profile = $value_usr->social_profile;
          }
          if($value_usr->email_verify!= '')
          {
             $user->emailVerified = $value_usr->email_verify;
          }
          if($value_usr->upassword!= '')
          {
             $user->password = $value_usr->upassword;
          }
          $user->save();
        }
       }
       echo "Imported Successfully";
    }

    public function post()
    {
      $str = file_get_contents('C:\Users\Rushabh\Desktop\Fairfood\Json Table wise\posts.json');
      $json = json_decode($str, true);
      
      foreach($json as $key => $value)
      {
        if(isset($value['authorId']))
        {
          $user = UserFirebase::where('firebase_userId','=',$value['authorId'])->first();
          if(COUNT($user)>0)
          {
              $post = new Post;
              $post->post_fireId = $key;
              $post->user_id = $user->id;
              $post->post_index = $value['index'];
              $post->text = $value['text'];
              $post->save();
          }
        }
      }
      echo "Imported Successfully";
    }

    public function postAttachment()
    {
      $str = file_get_contents('C:\Users\Rushabh\Desktop\Fairfood\Json Table wise\posts.json');
      $json = json_decode($str, true);
      
      foreach($json as $key => $value)
      {
        if(isset($value['attachments']))
        {
            foreach ($value['attachments'] as $key => $value) 
            {
                $user = UserFirebase::where('firebase_userId','=',$value['authorId'])->first();
                $post = Post::where('post_fireId','=',$value['postId'])->first();                            
                if(count($user)>0 && count($post)>0)
                {
                    $postAtt = new PostAttachment;
                    $postAtt->post_id = $post->id;
                    $postAtt->post_fireId = $value['postId'];
                    $postAtt->attachment_fireId = $key;
                    $postAtt->user_fireId = $value['authorId'];
                    $postAtt->user_id = $user->id;
                    $postAtt->filename = $value['fileName'];
                    $postAtt->fileSize = $value['fileSize'];
                    $postAtt->fileType = $value['fileType'];
                    $postAtt->storageUrl = $value['storageUrl'];
                    $postAtt->storgePath = $value['storgePath'];
                    $postAtt->save();
                }
            }          
        }
      }
      echo 'Imported Successfully';
    }

    public function postLike()
    {
      $str = file_get_contents('C:\Users\Rushabh\Desktop\Fairfood\Json Table wise\posts.json');
      $json = json_decode($str, true);
      
      foreach($json as $key => $value)
      {
        if(isset($value['likes']))
        {
          foreach ($value['likes'] as $key2 => $value2) 
          {
            $post = Post::where('post_fireId','=',$key)->first();
            $user = UserFirebase::where('firebase_userId','=',$key2)->first();
            if (count($post)>0 && count($user)>0) 
            {
              $like = new PostLike;
              $like->post_id = $post->id;
              $like->like_userId = $user->id;
              $like->status  = 1;
              $like->save();
            }
          }
        }
      }
      echo 'Imported Successfully';
    }

    public function Places()
    {
      $str = file_get_contents('E:\xampp\htdocs\fair_food_forager\Json Table wise\places.json');
      $json = json_decode($str, true);
      foreach ($json as $key => $value) {
         
        // print_r($key);echo "<br>";
        $addPlace = new Places;
        $addPlace->places_fireId = $key;
        if(isset($value['address']))
        {
            if(isset($value['address']['city']))
            {
                $addPlace->city = $value['address']['city'];
            }
            if(isset($value['address']['line1']))
            {
                $addPlace->line1 = $value['address']['line1'];
            }
            if(isset($value['address']['line2']))
            {
                $addPlace->line2 = $value['address']['line2'];
            }
            if(isset($value['address']['country']))
            {
                $addPlace->country = $value['address']['country'];
            }
            if(isset($value['address']['state']))
            {
                $addPlace->state = $value['address']['state'];
            }
            if(isset($value['address']['street']))
            {
                $addPlace->street = $value['address']['street'];
            }
        }
        if(isset($value['addressFull']))
        {
            $addPlace->addressFull = $value['addressFull'];
        }
        if(isset($value['bio']))
        {
            $addPlace->bio = $value['bio'];
        }
        if(isset($value['categories']))
        {            
          $str = implode(", ", $value['categories']);
          if (count($str)>0) 
          {
            $abc = explode(', ', $str);
            $datas = PlaceCategory::whereIn('name',$abc)->pluck('id')->toArray();
            $xyz = implode(',', $datas);
            $addPlace->categories = $xyz;
          }
        }
        if(isset($value['email']))
        {   
            $addPlace->email = $value['email'];
        }
        if(isset($value['facebookLink']))
        {
            $addPlace->facebookLink = $value['facebookLink'];
        }
        if(isset($value['fff_id']))
        {
            $addPlace->fff_id = $value['fff_id'];
        }
        if(isset($value['fff_type']))
        {
            $addPlace->fff_type = $value['fff_type'];
        }
        if(isset($value['name']))
        {
            $addPlace->name = $value['name'];
        }
        if(isset($value['phoneNumber']))
        {
            $addPlace->phoneNumber = $value['phoneNumber'];
        }
        if(isset($value['phoneNumber2']))
        {
            $addPlace->phoneNumber2 = $value['phoneNumber2'];
        }
        if(isset($value['postal']))
        {
            $addPlace->postal = $value['postal'];
        }
        if(isset($value['postalCity']))
        {
            $addPlace->postalCity = $value['postalCity'];
        }
        if(isset($value['postalLine1']))
        {
            $addPlace->postalLine1 = $value['postalLine1'];
        }
        if(isset($value['postalLine2']))
        {
            $addPlace->postalLine2 = $value['postalLine2'];
        } 
        if(isset($value['postalPostcode']))
        {
            $addPlace->postalPostcode = $value['postalPostcode'];
        }
        if(isset($value['postalState']))
        {
            $addPlace->postalState = $value['postalState'];
        } 
        if(isset($value['shortBio']))
        {
            $addPlace->shortBio = $value['shortBio'];
        }
        if(isset($value['slug']))
        {
            $addPlace->slug = $value['slug'];
        }
        if(isset($value['twitterLink']))
        {
            $addPlace->twitterLink = $value['twitterLink'];
        }
        if(isset($value['type']))
        {
            $addPlace->type = $value['type'];
        }
        if(isset($value['websiteLink']))
        {
            $addPlace->websiteLink = $value['websiteLink'];
        }
        if(isset($value['youtubeLink']))
        {
            $addPlace->youtubeLink = $value['youtubeLink'];
        }
        if(isset($value['country2']))
        {
            $addPlace->country2 = $value['country2'];
        } 
        if(isset($value['postalCountry']))
        {  
            $addPlace->postalCountry = $value['postalCountry'];
        }
        if(isset($value['environmentalScore']))
        {
            $addPlace->environmentalScore = $value['environmentalScore'];
        }
        if(isset($value['foodScore']))
        {
            $addPlace->foodScore = $value['foodScore'];
        }
        if(isset($value['overallScore']))
        {
            $addPlace->overallScore = $value['overallScore'];
        } 
        if(isset($value['serviceScore']))
        {
            $addPlace->serviceScore = $value['serviceScore'];  
        }
        if(isset($value['featuredVideoLink']))
        {     
            $addPlace->featuredVideoLink = $value['featuredVideoLink'];
        }
        if(isset($value['lat']))
        {
            $addPlace->lat = $value['lat'];
        }
        if(isset($value['lon']))
        {
            $addPlace->lon = $value['lon'];
        }
        if(isset($value['googlePlaceID']))
        {
            $addPlace->googlePlaceID = $value['googlePlaceID'];
        }
        if(isset($value['username']))
        {
          $addPlace->username = $value['username'];
        }
        
        $addPlace->save();        
      }
      echo 'Imported Successfully';
    }

    public function PlaceCategories()
    {
      $str = file_get_contents('C:\Users\Rushabh\Desktop\Fairfood\Json Table wise\placeCategories.json');
      $json = json_decode($str, true);
      foreach ($json as $key => $value) 
      {
         // echo "<pre>"; print_r($key);
         $category = new PlaceCategory;
         $category->name = $key;
         $category->save();
      }
      echo "Imported Successfully.";
    }

    public function PlaceTags()
    {
      $str = file_get_contents('C:\Users\Rushabh\Desktop\Fairfood\Json Table wise\placeTags.json');
      $json = json_decode($str, true);
      foreach ($json as $key => $value) 
      {
        $tags = new PlaceTags;
        $tags->name = $key;
        $tags->save();
      }
      echo "Imported Successfully.";
    }

    public function PlaceCoverImages()
    {
      $str = file_get_contents('C:\Users\Rushabh\Desktop\Fairfood\Json Table wise\places.json');
      $json = json_decode($str, true);
      foreach($json as $key => $value)
      {
        if(isset($value['coverImage']))
        {          
          $places = Places::where('places_fireId','=',$key)->first();
          if(COUNT($places)>0)
          {
              $PlaceCoverImg = new PlacesCoverImages;
              $PlaceCoverImg->places_fireId = $key;
              $PlaceCoverImg->places_id = $places->id;
              if(isset($value['coverImage']['authorId']))
              {
                $PlaceCoverImg->authorId = $value['coverImage']['authorId'];
              }
              if(isset($value['coverImage']['fileName']))
              {
                $PlaceCoverImg->fileName = $value['coverImage']['fileName'];
              }
              if(isset($value['coverImage']['fileSize']))
              {
                $PlaceCoverImg->fileSize = $value['coverImage']['fileSize'];
              }
              if(isset($value['coverImage']['storagePath']))
              {
                $PlaceCoverImg->storagePath = $value['coverImage']['storagePath'];
              }
              if(isset($value['coverImage']['storageUrl']))
              {
                $PlaceCoverImg->storageUrl = $value['coverImage']['storageUrl'];
              }
              if(isset($value['coverImage']['authorId']))
              {
                $user = UserFirebase::where('firebase_userId','=',$value['coverImage']['authorId'])->first();
                if(COUNT($user)>0)
                {
                  $PlaceCoverImg->user_id = $user->id;
                }
              } 
              $PlaceCoverImg->save();             
          }
        }        
      }
      echo 'Imported Successfully';
    }

    public function PlaceGalleryImages()
    {
      $str = file_get_contents('C:\Users\Rushabh\Desktop\Fairfood\Json Table wise\places.json');
      $json = json_decode($str, true);
      foreach($json as $key => $value)
      {
        if(isset($value['galleryImages']))
        {          
          foreach($value['galleryImages'] as $galleryImages)
          {
            $places = Places::where('places_fireId','=',$galleryImages['placeId'])->first();
            if(COUNT($places)>0)
            {
                $PlaceGalleryImg = new PlacesGalleryImages;
                $PlaceGalleryImg->places_fireId = $key;
                $PlaceGalleryImg->places_id = $places->id;
                if(isset($galleryImages['authorId']))
                {
                  $PlaceGalleryImg->authorId = $galleryImages['authorId'];
                }
                if(isset($galleryImages['fileName']))
                {
                  $PlaceGalleryImg->fileName = $galleryImages['fileName'];
                }
                if(isset($value['galleryImages']['fileSize']))
                {
                  $PlaceGalleryImg->fileSize = $value['galleryImages']['fileSize'];
                }
                if(isset($galleryImages['storagePath']))
                {
                  $PlaceGalleryImg->storagePath = $galleryImages['storagePath'];
                }
                if(isset($galleryImages['storageUrl']))
                {
                  $PlaceGalleryImg->storageUrl = $galleryImages['storageUrl'];
                }              
                $PlaceGalleryImg->save();             
            }
          }          
        }
      }
      echo 'Imported Successfully';
    }

    public function PlaceOpenigHours()
    {
      $str = file_get_contents('C:\Users\Rushabh\Desktop\Fairfood\Json Table wise\places.json');
      $json = json_decode($str, true);
      foreach($json as $key => $value)
      {
        if(isset($value['openingHours']))
        {          
          $places = Places::where('places_fireId','=',$key)->first();
          if(COUNT($places)>0)
          {
              for($i=0;$i<COUNT($value['openingHours']);$i++)
              {
                $openingHours = new OpeningHours;
                $openingHours->details = $value['openingHours'][$i];
                $openingHours->places_fireId = $key;
                $openingHours->places_id = $places->id;
                $openingHours->save();
              }        
          }
        }     
      }
      echo 'Imported Successfully';
    }

    public function PlaceUsedTags()
    {
      $str = file_get_contents('C:\Users\Rushabh\Desktop\Fairfood\Json Table wise\places.json');
      $json = json_decode($str, true);
      foreach($json as $key => $value)
      {
        if(isset($value['tags']))
        {          
          $places = Places::where('places_fireId','=',$key)->first();
          if(COUNT($places)>0)
          {
            for($i=0;$i<COUNT($value['tags']);$i++)
            {
              $openingHours = new PlacesUsedTags;
              $tags_val = str_replace('/', '-', $value['tags'][$i]);
              $places_tags = \DB::table('place_tags')->where('name', 'like', '%' . $tags_val . '%')->first();
              if(COUNT($places_tags)>0)
              {
                $openingHours->places_tag_id = $places_tags->id;
              }
              $openingHours->name = $value['tags'][$i];
              $openingHours->places_fireId = $key;
              $openingHours->places_id = $places->id;                
              $openingHours->save();
            }        
          }
        }    
      }
      echo 'Imported Successfully';
    }

    public function PlaceReview()
    {
      $str = file_get_contents('C:\Users\Rushabh\Desktop\Fairfood\Json Table wise\reviews.json');
      $json = json_decode($str, true);
      foreach ($json as $key => $value) 
      {
         $user = UserFirebase::where('firebase_userId','=',$value['authorId'])->first();
         $place = Places::where('places_fireId','=',$value['placeId'])->first();
         if (count($user)>0 && count($place)>0) 
         {
            $review = new PlaceReview;
            $review->firebase_placeId = $key;
            $review->user_id = $user->id;
            $review->env_score = round($value['environmentScore']);
            $review->food_score = round($value['foodScore']);
            $review->overall_score = round($value['overallScore']);
            $review->serviceScore = round($value['serviceScore']);
            $review->placeId = $place->id;
            $review->text = $value['text'];
            $review->status = 1;
            $review->save();
         }
      }
      echo "Imported Successfully.";
    }

    public function PlaceReviewPending()
    {
      $str = file_get_contents('C:\Users\Rushabh\Desktop\Fairfood\Json Table wise\pending.json');
      $json = json_decode($str, true);
      foreach ($json as $key => $value) 
      {
         $user = UserFirebase::where('firebase_userId','=',$value['authorId'])->first();
         $place = Places::where('places_fireId','=',$value['placeId'])->first();
         if (count($user)>0 && count($place)>0) 
         {
            $review = new PlaceReview;
            $review->firebase_placeId = $key;
            $review->user_id = $user->id;
            $review->env_score = round($value['environmentScore']);
            $review->food_score = round($value['foodScore']);
            $review->overall_score = round($value['overallScore']);
            $review->serviceScore = round($value['serviceScore']);
            $review->placeId = $place->id;
            $review->text = $value['text'];
            $review->status = 0;
            $review->save();
         }
      }
      echo "Imported Successfully.";
    }

    public function PostTags()
    {
      $str = file_get_contents('C:\Users\Rushabh\Desktop\Fairfood\Json Table wise\tags.json');
      $json = json_decode($str, true);
      foreach ($json as $key => $value) 
      {
        foreach ($value as $key2 => $value2) 
        {
          $post = Post::where('post_fireId','=',$key2)->first();
          if (count($post)>0) 
          {
            $tags = new PostTags;
            $tags->name = $key;
            $tags->post_id = $post->id;
            $tags->save();
          }
        }
      }
      echo "Imported Successfully.";
    }

    public function Following()
    {
      $str = file_get_contents('C:\Users\Rushabh\Desktop\Fairfood\Json Table wise\users.json');
      $json = json_decode($str, true);
      foreach ($json as $key => $value) 
      {
        if(isset($value['following'])){
        // echo "<pre>";print_r($value['following']);
        $user = UserFirebase::where('firebase_userId',$key)->first();
        $folluserrr = NULL;
          foreach ($value['following'] as $key2 => $value2) {      
            $folluser = UserFirebase::where('firebase_userId',$key2)->first();
            if(COUNT($folluser)>0)
            {
              $folluserrr = $folluser->id;
            } else {
              echo $key2."<br>";
            }
            $following = new Following;
            $following->user_id = $user->id;
            $following->following_uid = $folluserrr;
            $following->status = 1;
            $following->save();
          }
        }
      }
      echo "Imported Successfully.";
    }

    public function Followers()
    {
      $str = file_get_contents('C:\Users\Rushabh\Desktop\Fairfood\Json Table wise\followers.json');
      $json = json_decode($str, true);
      foreach ($json as $key => $value) 
      {
        // echo "followers key"."<pre>";print_r($key);
        $user = UserFirebase::where('firebase_userId',$key)->first();
        foreach($value as $key2 => $value2)
        {              
          $folleruser = UserFirebase::where('firebase_userId',$key2)->first();
          if(COUNT($user)>0 && COUNT($folleruser)>0)
          {
            $followers = new Followers;
            $followers->user_id = $user->id;
            $followers->follower_uid = $folleruser->id;
            $followers->status = 1;
            $followers->save();
          } 
        }
      }
      echo "Imported Successfully.";
    }

    public function PostComments()
    {
      $str = file_get_contents('C:\Users\Rushabh\Desktop\Fairfood\Json Table wise\comments.json');
      $json = json_decode($str, true);
      foreach ($json as $key => $value) 
      {
        $user = UserFirebase::where('firebase_userId','=',$value['authorId'])->first();
        $post_val = Post::where('post_fireId','=',$value['objectId'])->first();
        if (count($user)>0 && count($post_val)>0) 
        {
           $comm_data = new PostComments;
           $comm_data->comment_uid = $key;
           if(isset($value['authorId'])){  
              $comm_data->firebase_userId = $value['authorId'];
           }
           if(isset($value['objectId'])){  
              $comm_data->objectId = $value['objectId'];
           }
           if(isset($value['text'])){
              $comm_data->text = $value['text'];
           }
           $comm_data->post_id = $post_val->id;
           $comm_data->user_id = $user->id;
           $comm_data->save();
        }
      }
      echo "Imported Successfully.";
    }

    public function PlaceComments()
    {
      $str = file_get_contents('C:\Users\Rushabh\Desktop\Fairfood\Json Table wise\comments.json');
      $json = json_decode($str, true);
      foreach ($json as $key => $value) 
      {
        $user = UserFirebase::where('firebase_userId','=',$value['authorId'])->first();
        $place = Places::where('places_fireId','=',$value['objectId'])->first();
        if (count($user)>0 && count($place)>0) 
        {
           $comm_data = new PlaceComments;
           $comm_data->comment_uid = $key;
           if(isset($value['authorId'])){  
              $comm_data->firebase_userId = $value['authorId'];
           }
           if(isset($value['objectId'])){  
              $comm_data->objectId = $value['objectId'];
           }
           if(isset($value['text'])){
              $comm_data->text = $value['text'];
           }
           $comm_data->place_id = $place->id;
           $comm_data->user_id = $user->id;
           $comm_data->save();
        }
      }
      echo "Imported Successfully.";
    }

    public function PostCommentLike()
    {
      $str = file_get_contents('C:\Users\Rushabh\Desktop\Fairfood\Json Table wise\comments.json');
      $json = json_decode($str, true);
      foreach ($json as $key => $value) 
      {
        if(isset($value['likes']))
        { 
          $comment = PostComments::where('comment_uid',$key)->first();
          $user = UserFirebase::where('firebase_userId',$value['authorId'])->first();
          $post_val = Post::where('post_fireId',$value['objectId'])->first();
          if(count($user)>0 && count($post_val)>0) 
          {
            foreach ($value['likes'] as $key2 => $arr) 
            {
              $usr_like = UserFirebase::where('firebase_userId',$key2)->first();
              if (count($usr_like)>0) 
              {
                $like_data = new PostCommentLike;
                $like_data->user_id = $user['id'];
                $like_data->comment_id = $comment['id'];
                $like_data->liked_userId = $usr_like['id'];
                $like_data->like = 1;
                $like_data->objectId = $value['objectId'];
                $like_data->post_id = $post_val->id;
                $like_data->save();
              }
            }
          }
        }
         
      }
      echo "Imported Successfully.";
    }

    public function PlaceCommentLike()
    {
      $str = file_get_contents('C:\Users\Rushabh\Desktop\Fairfood\Json Table wise\comments.json');
      $json = json_decode($str, true);
      foreach ($json as $key => $value) 
      {
        if(isset($value['likes']))
        { 
          $comment = PlaceComments::where('comment_uid',$key)->first();
          $user = UserFirebase::where('firebase_userId',$value['authorId'])->first();
          $place = Places::where('places_fireId','=',$value['objectId'])->first();
          if(count($user)>0 && count($place)>0) 
          {
            foreach ($value['likes'] as $key2 => $arr) 
            {
              $usr_like = UserFirebase::where('firebase_userId',$key2)->first();
              if (count($usr_like)>0) 
              {
                $like_data = new PlaceCommentLike;
                $like_data->user_id = $user['id'];
                $like_data->comment_id = $comment['id'];
                $like_data->liked_userId = $usr_like['id'];
                $like_data->like = 1;
                $like_data->objectId = $value['objectId'];
                $like_data->place_id = $place->id;
                $like_data->save();
              }
            }
          }
        }
         
      }
      echo "Imported Successfully.";
    }

    public function QueuedPlaces()
    {
      $str = file_get_contents('E:\xampp\htdocs\fair_food_forager\Json Table wise\queued.json');
      $json = json_decode($str, true);
      foreach ($json as $key => $value) {
        // print_r($key);echo "<br>";
        $addPlace = new Places;
        $addPlace->places_fireId = $key;
        if(isset($value['address']))
        {
            if(isset($value['address']['city']))
            {
                $addPlace->city = $value['address']['city'];
            }
            if(isset($value['address']['line1']))
            {
                $addPlace->line1 = $value['address']['line1'];
            }
            if(isset($value['address']['line2']))
            {
                $addPlace->line2 = $value['address']['line2'];
            }
            if(isset($value['address']['country']))
            {
                $addPlace->country = $value['address']['country'];
            }
            if(isset($value['address']['state']))
            {
                $addPlace->state = $value['address']['state'];
            }
            if(isset($value['address']['street']))
            {
                $addPlace->street = $value['address']['street'];
            }
        }
        if(isset($value['addressFull']))
        {
            $addPlace->addressFull = $value['addressFull'];
        }
        if(isset($value['bio']))
        {
            $addPlace->bio = $value['bio'];
        }
        if(isset($value['categories']))
        {            
          $str = implode(", ", $value['categories']);
          if (count($str)>0) 
          {
            $abc = explode(', ', $str);
            $datas = PlaceCategory::whereIn('name',$abc)->pluck('id')->toArray();
            $xyz = implode(',', $datas);
            $addPlace->categories = $xyz;
          }
        }
        if(isset($value['email']))
        {   
            $addPlace->email = $value['email'];
        }
        if(isset($value['facebookLink']))
        {
            $addPlace->facebookLink = $value['facebookLink'];
        }
        if(isset($value['fff_id']))
        {
            $addPlace->fff_id = $value['fff_id'];
        }
        if(isset($value['fff_type']))
        {
            $addPlace->fff_type = $value['fff_type'];
        }
        if(isset($value['name']))
        {
            $addPlace->name = $value['name'];
        }
        if(isset($value['phoneNumber']))
        {
            $addPlace->phoneNumber = $value['phoneNumber'];
        }
        if(isset($value['phoneNumber2']))
        {
            $addPlace->phoneNumber2 = $value['phoneNumber2'];
        }
        if(isset($value['postal']))
        {
            $addPlace->postal = $value['postal'];
        }
        if(isset($value['postalCity']))
        {
            $addPlace->postalCity = $value['postalCity'];
        }
        if(isset($value['postalLine1']))
        {
            $addPlace->postalLine1 = $value['postalLine1'];
        }
        if(isset($value['postalLine2']))
        {
            $addPlace->postalLine2 = $value['postalLine2'];
        } 
        if(isset($value['postalPostcode']))
        {
            $addPlace->postalPostcode = $value['postalPostcode'];
        }
        if(isset($value['postalState']))
        {
            $addPlace->postalState = $value['postalState'];
        } 
        if(isset($value['shortBio']))
        {
            $addPlace->shortBio = $value['shortBio'];
        }
        if(isset($value['slug']))
        {
            $addPlace->slug = $value['slug'];
        }
        if(isset($value['twitterLink']))
        {
            $addPlace->twitterLink = $value['twitterLink'];
        }
        if(isset($value['type']))
        {
            $addPlace->type = $value['type'];
        }
        if(isset($value['websiteLink']))
        {
            $addPlace->websiteLink = $value['websiteLink'];
        }
        if(isset($value['youtubeLink']))
        {
            $addPlace->youtubeLink = $value['youtubeLink'];
        }
        if(isset($value['country2']))
        {
            $addPlace->country2 = $value['country2'];
        } 
        if(isset($value['postalCountry']))
        {  
            $addPlace->postalCountry = $value['postalCountry'];
        }
        if(isset($value['environmentalScore']))
        {
            $addPlace->environmentalScore = $value['environmentalScore'];
        }
        if(isset($value['foodScore']))
        {
            $addPlace->foodScore = $value['foodScore'];
        }
        if(isset($value['overallScore']))
        {
            $addPlace->overallScore = $value['overallScore'];
        } 
        if(isset($value['serviceScore']))
        {
            $addPlace->serviceScore = $value['serviceScore'];  
        }
        if(isset($value['featuredVideoLink']))
        {     
            $addPlace->featuredVideoLink = $value['featuredVideoLink'];
        }
        if(isset($value['lat']))
        {
            $addPlace->lat = $value['lat'];
        }
        if(isset($value['lon']))
        {
            $addPlace->lon = $value['lon'];
        }
        if(isset($value['googlePlaceID']))
        {
            $addPlace->googlePlaceID = $value['googlePlaceID'];
        }
        if(isset($value['username']))
        {
          $addPlace->username = $value['username'];
        }
        $addPlace->queued = 0;
        $addPlace->save();        
      }
      echo 'Imported Successfully';
    }

    public function QueuedPlacesCoverImages()
    {
      $str = file_get_contents('C:\Users\Rushabh\Desktop\Fairfood\Json Table wise\queued.json');
      $json = json_decode($str, true);
      foreach($json as $key => $value)
      {
        if(isset($value['coverImage']))
        {          
          $places = Places::where('places_fireId','=',$key)->first();
          if(COUNT($places)>0)
          {
              $PlaceCoverImg = new PlacesCoverImages;
              $PlaceCoverImg->places_fireId = $key;
              $PlaceCoverImg->places_id = $places->id;
              if(isset($value['coverImage']['authorId']))
              {
                $PlaceCoverImg->authorId = $value['coverImage']['authorId'];
              }
              if(isset($value['coverImage']['fileName']))
              {
                $PlaceCoverImg->fileName = $value['coverImage']['fileName'];
              }
              if(isset($value['coverImage']['fileSize']))
              {
                $PlaceCoverImg->fileSize = $value['coverImage']['fileSize'];
              }
              if(isset($value['coverImage']['storagePath']))
              {
                $PlaceCoverImg->storagePath = $value['coverImage']['storagePath'];
              }
              if(isset($value['coverImage']['storageUrl']))
              {
                $PlaceCoverImg->storageUrl = $value['coverImage']['storageUrl'];
              }
              if(isset($value['coverImage']['authorId']))
              {
                $user = UserFirebase::where('firebase_userId','=',$value['coverImage']['authorId'])->first();
                if(COUNT($user)>0)
                {
                  $PlaceCoverImg->user_id = $user->id;
                }
              } 
              $PlaceCoverImg->save();             
          }
        }        
      }
      echo 'Imported Successfully';
    }

    public function QueuedPlacesGalleryImages()
    {
      $str = file_get_contents('C:\Users\Rushabh\Desktop\Fairfood\Json Table wise\queued.json');
      $json = json_decode($str, true);
      foreach($json as $key => $value)
      {
        if(isset($value['galleryImages']))
        {          
          foreach($value['galleryImages'] as $galleryImages)
          {
            $places = Places::where('places_fireId','=',$galleryImages['placeId'])->first();
            if(COUNT($places)>0)
            {
                $PlaceGalleryImg = new PlacesGalleryImages;
                $PlaceGalleryImg->places_fireId = $key;
                $PlaceGalleryImg->places_id = $places->id;
                if(isset($galleryImages['authorId']))
                {
                  $PlaceGalleryImg->authorId = $galleryImages['authorId'];
                }
                if(isset($galleryImages['fileName']))
                {
                  $PlaceGalleryImg->fileName = $galleryImages['fileName'];
                }
                if(isset($value['galleryImages']['fileSize']))
                {
                  $PlaceGalleryImg->fileSize = $value['galleryImages']['fileSize'];
                }
                if(isset($galleryImages['storagePath']))
                {
                  $PlaceGalleryImg->storagePath = $galleryImages['storagePath'];
                }
                if(isset($galleryImages['storageUrl']))
                {
                  $PlaceGalleryImg->storageUrl = $galleryImages['storageUrl'];
                }              
                $PlaceGalleryImg->save();             
            }
          }          
        }
      }
      echo 'Imported Successfully';
    }

    public function feeds()
    {
      $str = file_get_contents('C:\Users\Rushabh\Desktop\Fairfood\Json Table wise\feeds.json');
      $json = json_decode($str, true);
      foreach($json as $key => $value)
      {
        $user = UserFirebase::where('firebase_userId','=',$key)->first();
        foreach($value as $key2 => $value2)
        {
          $post = Post::where('post_fireId','=',$key2)->first();
          $feed = new Feeds;
          $feed->user_fireId = $key;
          if(COUNT($user)>0)
          {
            $feed->user_id = $user->id;
          }
          $feed->post_fireId = $key2;
          if(COUNT($post)>0)
          {
            $feed->post_id = $post->id;
          }
          $feed->save();
        }
      }
      echo 'Imported Successfully';
    }

    public function extentation()
    {
      $all = PlacesCoverImages::get();
      foreach ($all as  $value) 
      {
        //$cover = $value->cover_pic;
        $exp = explode('.', $value->cover_pic);
        if (count($exp)<2) 
        {
          $find = PlacesCoverImages::find($value->id);
          $find->cover_pic = $find->cover_pic.'.jpg';
          $find->save();
          //echo "<pre>"; print_r($find);
        }
       
      }
    }

    public function extentation_post()
    {
      $data = PostAttachment::get();
      foreach ($data as $value) 
      {
        $data = PostAttachment::find($value->id);
        $data->post_pic = $data->post_pic.".jpg";
        $data->save();
      }
      echo "Successfully";
    }

    public function testlist()
    {
        $abc = UserFirebase::with('followers')->with('followings')->where('id',2)->get()->toJson();
        print_r($abc);
    }

    


  

    // -------------------------------------------------------- //
}
