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

class UserController extends Controller
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

    public function registration(Request $request)
    {
    	// echo 1;exit;
    	if($request->has('is_social'))
    	{
    		// $name = $request->name;
	    	$email = $request->email;
	    	$social_id = $request->social_id;
	    	$device_token = $request->device_token;
	    	$device_type = $request->device_type;
	    	$emailExist = User::where('email','=',$email)->first();
	    	$socialExist = User::where('social_id','=',$social_id)->first();
	    	$validator = Validator::make($request->all(),[
	            // 'name' => ['required', 'string', 'max:255'],
	            'email' => ['required', 'string', 'email', 'max:255'],
	            'social_id' => 'required',
	            'device_token' => 'required'
	        ]);

	        if($validator->fails())
	        {
	            // return response($validator->messages(), 200);
	            error(config('messages.general.validation_error'));
	        }
	        elseif(!empty($emailExist))
	        {
	        	error(config('messages.general.email_exist'));
	        }
	        elseif(!empty($socialExist)) 
	        {
	        	error(config('messages.general.socialId_exist'));
	        }
	        else
	        {
	        	$user = new User;
	            $user->full_name = $name;
	            $user->email = $email;
	            $user->is_social = $is_social;
	            $user->social_id = $social_id;
	            $user->device_token = $device_token;
	            $user->session_token = getAuthKey();
	            $user->device_type = $device_type;
	            $user->save();

	            $following = new Following; //Following
	            $following->user_id = $user->id;
	            $following->following_uid = 403;
	            $following->save();

	            $followers = new Followers; //Followers
	            $followers->user_id = 403;
	            $followers->follower_uid = $user->id;
	            $followers->save();

	            success(config('messages.general.register_succ'),$user->toArray());
	        }
    	}
    	else
    	{
	    	$username = $request->username;
	    	$email = $request->email;
	    	$password = $request->password;
	    	$device_token = $request->device_token;
	    	$device_type = $request->device_type;
	    	$emailExist = User::where('email','=',$email)->first();
	    	$UserNameExist = User::where('username','=',$username)->first();
	    	$validator = Validator::make($request->all(),[
	            'username' => ['required', 'string', 'max:255'],
	            'email' => ['required', 'string', 'email', 'max:255'],
	            'password' => ['required', 'string', 'min:6'],
	            'device_token' => 'required'
	        ]);

	        if($validator->fails())
	        {
	            // return response($validator->messages(), 200);
	            error(config('messages.general.validation_error'));
	        }
	        elseif(!empty($emailExist))
	        {
	        	error(config('messages.general.email_exist'));
	        }
	        elseif(!empty($UserNameExist))
	        {
	        	error(config('messages.general.username_exist'));
	        }
	        else 
	        {           
	        	// echo "hello"; exit;
	            $user = new User;
	            $user->username = $username;
	            $user->email = $email;
	            // $user->password = Hash::make($password);
	            $user->password = $password;
	            $user->device_token = $device_token;
	            $user->session_token = getAuthKey();
	            $user->device_type = $device_type;
	            $user->save();

	            $following = new Following; //Following
	            $following->user_id = $user->id;
	            $following->following_uid = 403;
	            $following->save();

	            $followers = new Followers; //Followers
	            $followers->user_id = 403;
	            $followers->follower_uid = $user->id;
	            $followers->save();

	            success(config('messages.general.register_succ'),$user->toArray());
	        }
    	}
    }

    public function login(Request $request)
    {
    	
    	if($request->has('is_social'))
    	{
    		$is_social = 1;
    		$social_id = $request->social_id;
    		$email = $request->email;
	    	$device_token = $request->device_token;
	    	$device_type = $request->device_type;

	    	$validator = Validator::make($request->all(),[
            'email' => ['required', 'string', 'email', 'max:255'],
            'device_token' => 'required',
            'social_id' => 'required'
	        ]);

	        if($validator->fails())
	        {
	            error(config('messages.general.validation_error'));
	        }
	        else
	        {
	        	$user = User::where('email','=',$email)->where('social_id','=',$social_id)->first();
	        	if ($user->is_desable == 1) 
				{
					error(config('messages.general.acc_desable'));
				}
	        	if(!empty($user))
	        	{
	        		$upd_token = User::find($user->id);
        			$upd_token->device_token = $device_token;
		            // $upd_token->session_token = getAuthKey();
		            $upd_token->device_type = $device_type;
		            $upd_token->save();

		            success(config('messages.general.login_success'),$upd_token->toArray());
	        	}
	        	else
	        	{
	        		error(config('messages.general.credentials_missmatch'));
	        	}
	        }
    	}
		else
		{			
	    	$password = $request->password;
	    	$device_token = $request->device_token;
	    	$device_type = $request->device_type;

	    	$validator = Validator::make($request->all(),[
            // 'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'device_token' => 'required'
	        ]);

	        if($validator->fails())
	        {
	            error(config('messages.general.validation_error'));
	        }
	        else
	        {	
	        	if($request->has('email'))
				{
					$email = $request->email;
					$user = User::where('email',$email)->orWhere('username','=',$email)->first();
				}
				if ($user->is_desable == 1) 
				{
					error(config('messages.general.acc_desable'));
				}
	        	if(!empty($user))
	        	{
	        		if(Hash::check($password,$user->password))
	        		{
	        			$upd_token = User::find($user->id);
	        			$upd_token->device_token = $device_token;
			            // $upd_token->session_token = getAuthKey();
			            $upd_token->device_type = $device_type;
			            $upd_token->save();

			            success(config('messages.general.login_success'),$upd_token->toArray());
	        		}
	        		else
	        		{
	        			error(config('messages.general.wrong_password'));
	        		}
	        	}
	        	else
	        	{
	        		error(config('messages.general.credentials_missmatch'));
	        	}
	        }
		}    	    	
    }

    public function home_feed_without_login(Request $request)
    {
		$data=array();
		$take = $request->total_records ?: 10;
        $page = $request->page ?: 1;
    	$post = Post::with('post_picture')
		    	->with('post_like')
		    	->with('post_comments')
		    	->with('post_comm_like')
		    	->with('post_tags')
		    	->where('user_id',403)
		    	->take($take)
		        ->skip(($page - 1) * $take)
		    	->get()
		    	->toArray();
    	// echo "<pre>";print_r(COUNT($post));exit;
    	foreach($post as $posts)
    	{    		
    		$user = User::where('id',$posts['user_id'])->first();
			$posts['username'] = $user->username;
			$posts['name'] = $user->full_name;
			$posts['likes'] = count($posts['post_like']);
			$posts['comments'] = count($posts['post_comments']);
			if($user->profile_pic!='' && $user->profile_pic!=NULL)
			{
				$chk = explode('.',$user->profile_pic);	
		        if($chk[1]=='jpg' || $chk[1]=='jpeg' || $chk[1]=='png')
		        {
					$posts['profile_picture'] = env('STORAGE_URL').'user/profile/'.$user->profile_pic;
				}  
			}  		
    		/*if(isset($posts['post_picture'][0]))
    		{
    			$posts['post_picture'][0]['post_pic'] = env('STORAGE_URL').'post/'.$posts['post_picture'][0]['post_pic'];
    			$posts['PostAttachmentsModel'] = array_values($posts['post_picture']);
    		}*/
    		$post_att = \DB::table('post_attachment')->where('post_id',$posts['id'])->first();
    		if(!empty($post_att))
    		{
    			$post_att->post_pic = env('STORAGE_URL').'post/'.$post_att->post_pic;
    		}
    		$posts['PostAttachmentsModel'] = $post_att;
    		$posts['_index'] = 'posts';
    		$data[] = $posts;
    		 		    		
    	} //exit;
    	success(config('messages.general.fetch_success'),$data);
    }

    public function home_feed(Request $request)
    {
    	$userId = $request->user()->id;
    	$data=array();
		$take = $request->total_records ?: 10;
        $page = $request->page ?: 1;
    	$post = \DB::table('post as p')
    			->leftjoin('following as f','p.user_id','=','f.following_uid')
    			->leftjoin('users as u','p.user_id','=','u.id')
    			->where(function ($q) use ($userId) {
				    $q->where('f.user_id','=',$userId)
				        ->orWhere('p.user_id', '=', $userId);
				})
				->where('p.deleted_at',0)
				->where('f.status',1)
    			->select('p.*','u.username','u.full_name as name','u.profile_pic')
    			->orderBy('p.id','DESC')
    			->take($take)
		        ->skip(($page - 1) * $take)
    			->get();
    	foreach($post as $posts)
    	{
    		if($posts->profile_pic!='' && $posts->profile_pic!=NULL)
			{
    			$chk = explode('.', $posts->profile_pic);				
		        if($chk[1]=='jpg' || $chk[1]=='jpeg' || $chk[1]=='png')
		        {
					$posts->profile_picture = env('STORAGE_URL').'user/profile/'.$posts->profile_pic;
				}
			}			
    		$post_att = PostAttachment::where('post_id',$posts->id)->first();
    		if(!empty($post_att) && $post_att->post_pic!='' && $post_att->post_pic!=NULL)
    		{
    			$post_att->storageUrl = env('STORAGE_URL').'post/'.$post_att->post_pic;
    		}
    		$posts->PostAttachmentsModel = $post_att;
    		$posts->post_comments = PostComments::where('post_id',$posts->id)->get();
    		$posts->post_comment_like = PostCommentLike::where('post_id',$posts->id)->get();
    		$posts->post_like = PostLike::where('post_id',$posts->id)->where('status',1)->get();
    		$posts->post_tags = PostTags::where('post_id',$posts->id)->get();
    		$posts->likes = count($posts->post_like);
			$posts->comments = count($posts->post_comments);

			$like_status = PostLike::where('post_id',$posts->id)->whereIn('like_userId', [$userId])->where('status',1)->get();
			if(COUNT($like_status)>0)
			{
				$posts->likestatus = true;
			}
			else
			{
				$posts->likestatus = false;
			}
    		   		    		
    		$data[] = $posts;
    	}
    	success(config('messages.general.fetch_success'),$data);
    }

    public function post_like(Request $request)
    {
    	$userId = $request->user()->id;
    	$post_id = $request->post_id;
    	$status = 1;

    	$chk_like = PostLike::where('post_id',$post_id)->where('like_userId',$userId)->first();
    	if(!empty($chk_like))
    	{
    		$post_like = PostLike::find($chk_like->id);
    		if ($post_like->status == 1) {
    			$status = 0;
    		}else{
    			$status = 1;
    		}
    	} 
    	else
    	{
    		$post_like = new PostLike;
    	}    	
    	$post_like->post_id = $post_id;
    	$post_like->like_userId = $userId;
    	$post_like->status = $status;
    	$post_like->save();

    	$posts=array();
		$take = $request->total_records ?: 10;
        $page = $request->page ?: 1;
    	$posts = \DB::table('post as p')    			
    			->leftjoin('users as u','p.user_id','=','u.id')    			
    			->where('p.id',$post_id)
    			->select('p.*','u.username','u.full_name as name','u.profile_pic')
    			->take($take)
		        ->skip(($page - 1) * $take)
    			->first();    	
    		if($posts->profile_pic!='' && $posts->profile_pic!=NULL)
			{
    			$chk = explode('.', $posts->profile_pic);				
		        if($chk[1]=='jpg' || $chk[1]=='jpeg' || $chk[1]=='png')
		        {
					$posts->profile_picture = env('STORAGE_URL').'user/profile/'.$posts->profile_pic;
				}
			}			
    		$post_att = PostAttachment::where('post_id',$posts->id)->first();
    		if(!empty($post_att))
    		{
    			$post_att->post_pic = env('STORAGE_URL').'post/'.$post_att->post_pic;
    		}
    		$posts->PostAttachmentsModel = $post_att;
    		$posts->post_comments = PostComments::where('post_id',$posts->id)->get();
    		$posts->post_comment_like = PostCommentLike::where('post_id',$posts->id)->get();
    		$posts->post_like = PostLike::where('post_id',$posts->id)->where('status',1)->get();
    		$posts->post_tags = PostTags::where('post_id',$posts->id)->get();
    		$posts->likes = count($posts->post_like);
			$posts->comments = count($posts->post_comments);

			$like_status = PostLike::where('post_id',$posts->id)->whereIn('like_userId', [$userId])->where('status',1)->get();
			if(COUNT($like_status)>0)
			{
				$posts->likestatus = true;
			}
			else
			{
				$posts->likestatus = false;
			}
			$posts->event = 'like';
    		success2(config('messages.general.fetch_success'),$posts);
    }

    public function get_comments(Request $request)
    {
    	$userId = $request->user()->id;
    	$post_id = $request->post_id;
    	$data = array();
    	$comment = \DB::table('post_comments as pc')
    				->leftjoin('users as u','u.id','=','pc.user_id')
    				->where('pc.post_id','=',$post_id)
    				->select('pc.*','u.username','u.full_name','u.profile_pic as profile_picture')
    				->get();
    	if(COUNT($comment)>0)
    	{
	    	$commentCount = COUNT($comment);	    	
	    	foreach($comment as $comments)
	    	{
	    		$comment_like = \DB::table('post_comment_like')->where('post_id',$post_id)->where('comment_id',$comments->id)->where('like',1)->count();
	    		$comments->likesCount = $comment_like;
	    		$like_comment = \DB::table('post_comment_like')->where('post_id',$post_id)->where('comment_id',$comments->id)->whereIn('liked_userId', [$userId])->where('like',1)->get();	    		
				if(COUNT($like_comment)>0)
				{
					$comments->likeComment = true;
				}
				else
				{
					$comments->likeComment = false;
				}
	    		$comments->commentCount = $commentCount;
	    		if($comments->profile_picture!='' && $comments->profile_picture!=NULL)
				{
	    			$chk = explode('.', $comments->profile_picture);				
			        if($chk[1]=='jpg' || $chk[1]=='jpeg' || $chk[1]=='png')
			        {
						$comments->profile_picture = env('STORAGE_URL').'user/profile/'.$comments->profile_picture;
					}
				}	
	    		$data[] = $comments;
	    	}
	    }
    	success(config('messages.general.record_saved'),$data);
    }

    public function create_comment(Request $request)
    {
    	$userId = $request->user()->id;
    	$post_id = $request->post_id;
    	$text = $request->comment;

    	$post_comm = new PostComments;
    	$post_comm->post_id = $post_id;
    	$post_comm->user_id = $userId;
    	$post_comm->text = $text;
    	$post_comm->save();

    	$comments = \DB::table('post_comments as pc')
    				->leftjoin('users as u','u.id','=','pc.user_id')
    				->where('pc.post_id','=',$post_id)
    				->where('pc.id','=',$post_comm->id)
    				->select('pc.*','u.username','u.full_name','u.profile_pic as profile_picture')
    				->first();
    	// print_r($comments);exit;

    	$comment_count = \DB::table('post_comments as pc')
    				->leftjoin('users as u','u.id','=','pc.user_id')
    				->where('pc.post_id','=',$post_id)
    				// ->where('pc.id','=',$post_comm->id)
    				->select('pc.*','u.username','u.full_name','u.profile_pic as profile_picture')
    				->get();
    	if(!empty($comments))
    	{
	    	$commentCount = COUNT($comment_count);	    	
	    	/*foreach($comment as $comments)
	    	{*/
	    		$comment_like = \DB::table('post_comment_like')->where('post_id',$post_id)->where('comment_id',$comments->id)->where('like',1)->count();
	    		$comments->likesCount = $comment_like;
	    		$like_comment = \DB::table('post_comment_like')->where('post_id',$post_id)->where('comment_id',$comments->id)->whereIn('liked_userId', [$userId])->where('like',1)->get();	    		
				if(COUNT($like_comment)>0)
				{
					$comments->likeComment = true;
				}
				else
				{
					$comments->likeComment = false;
				}
	    		$comments->commentCount = $commentCount;
	    		$comments->event = 'comment';
	    		if($comments->profile_picture!='' && $comments->profile_picture!=NULL)
				{
	    			$chk = explode('.', $comments->profile_picture);				
			        if($chk[1]=='jpg' || $chk[1]=='jpeg' || $chk[1]=='png')
			        {
						$comments->profile_picture = env('STORAGE_URL').'user/profile/'.$comments->profile_picture;
					}
				}	
	    		// $data[] = $comments;
	    	// }
	    }
    	success2(config('messages.general.record_saved'),$comments);
    }

    public function post_comment_like(Request $request)
    {
    	$userId = $request->user()->id;
    	$post_id = $request->post_id;
    	$comment_id = $request->comment_id;
    	$status = 1;
    	$post = Post::where('id',$post_id)->first();

    	$chk_like = PostCommentLike::where('comment_id',$comment_id)->where('liked_userId',$userId)->first();
    	if(!empty($chk_like))
    	{
    		$post_like = PostCommentLike::find($chk_like->id);
    		if ($post_like->status == 1) {
    			$status = 0;
    		}else{
    			$status = 1;
    		}
    	} 
    	else
    	{
    		$post_like = new PostCommentLike;
    	}    	
    	$post_like->post_id = $post_id;
    	$post_like->user_id = $post->user_id;
    	$post_like->liked_userId = $userId;
    	$post_like->comment_id = $comment_id;
    	$post_like->like = $status;
    	$post_like->save();

    	$comments = \DB::table('post_comments as pc')
    				->leftjoin('users as u','u.id','=','pc.user_id')
    				->where('pc.post_id','=',$post_id)
    				->where('pc.id','=',$comment_id)
    				->select('pc.*','u.username','u.full_name','u.profile_pic as profile_picture')
    				->first();
    	// print_r($comments);exit;

    	$comment_count = \DB::table('post_comments as pc')
    				->leftjoin('users as u','u.id','=','pc.user_id')
    				->where('pc.post_id','=',$post_id)
    				->where('pc.id','=',$comment_id)
    				->select('pc.*','u.username','u.full_name','u.profile_pic as profile_picture')
    				->get();
    	if(!empty($comments))
    	{
	    	$commentCount = COUNT($comment_count);	    		    	
    		$comment_like = \DB::table('post_comment_like')->where('post_id',$post_id)->where('comment_id',$comment_id)->where('like',1)->count();
    		$comments->likesCount = $comment_like;
    		$like_comment = \DB::table('post_comment_like')->where('post_id',$post_id)->where('comment_id',$comment_id)->whereIn('liked_userId', [$userId])->where('like',1)->get();	    		
			if(COUNT($like_comment)>0)
			{
				$comments->likeComment = true;
			}
			else
			{
				$comments->likeComment = false;
			}
			$comments->event = 'like';
    		$comments->commentCount = $commentCount;
    		if($comments->profile_picture!='' && $comments->profile_picture!=NULL)
			{
    			$chk = explode('.', $comments->profile_picture);				
		        if($chk[1]=='jpg' || $chk[1]=='jpeg' || $chk[1]=='png')
		        {
					$comments->profile_picture = env('STORAGE_URL').'user/profile/'.$comments->profile_picture;
				}
			}		    		
	    }
    	success2(config('messages.general.record_saved'),$comments);
    }

    public function post_like_user(Request $request)
    {
    	$userId = $request->user()->id;
    	$type = $request->type;
    	$id = $request->id;
    	$data = array();
    	if($type=='post')
    	{
    		$post = \DB::table('post_like as pl')
    				->join('users as u','u.id','=','pl.like_userId')
    				->where('pl.status',1)
    				->where('pl.post_id','=',$id)
    				->select('pl.*','pl.like_userId as follower_uid','u.username','u.full_name','u.profile_pic as profile_picture')
    				->get();
    				//echo "<pre>"; print_r($post); exit;
    		foreach($post as $posts)
    		{
    			if($posts->profile_picture!='' && $posts->profile_picture!=NULL)
				{
	    			$chk = explode('.', $posts->profile_picture);				
			        if($chk[1]=='jpg' || $chk[1]=='jpeg' || $chk[1]=='png')
			        {
						$posts->profile_picture = env('STORAGE_URL').'user/profile/'.$posts->profile_picture;
					}
				}
				$follower = Followers::where('user_id','=',$posts->like_userId)
								->where('follower_uid','=',$userId)
								->where('status',1)
								->first();
				if(COUNT($follower)>0) {
					$posts->followuser = true;
				} else {
					$posts->followuser = false;
				}
				$following = Following::where('user_id','=',$userId)
								->where('following_uid','=',$posts->like_userId)
								->where('status',1)
								->first();
				if(COUNT($following)>0) {
					$posts->followinguser = true;
				} else {
					$posts->followinguser = false;
				}
    			$data[] = $posts;	
    		}
    	}
    	else
    	{
    		$comment = \DB::table('post_comment_like as pcl')
    				->leftjoin('users as u','u.id','=','pcl.liked_userId')
    				->where('pcl.like',1)
    				->where('pcl.comment_id','=',$id)
    				->select('pcl.*','u.username','u.full_name','u.profile_pic as profile_picture')
    				->get();
    		foreach($comment as $comments)
    		{
    			if($comments->profile_picture!='' && $comments->profile_picture!=NULL)
				{
	    			$chk = explode('.', $comments->profile_picture);				
			        if($chk[1]=='jpg' || $chk[1]=='jpeg' || $chk[1]=='png')
			        {
						$comments->profile_picture = env('STORAGE_URL').'user/profile/'.$comments->profile_picture;
					}
				}
				$follower = Followers::where('user_id','=',$userId)
								->where('follower_uid','=',$comments->liked_userId)
								->where('status',1)
								->first();
				if(COUNT($follower)>0) {
					$comments->followuser = true;
				} else {
					$comments->followuser = false;
				}
				$following = Following::where('user_id','=',$userId)
								->where('following_uid','=',$comments->liked_userId)
								->where('status',1)
								->first();
				if(COUNT($following)>0) {
					$comments->followinguser = true;
				} else {
					$comments->followinguser = false;
				}
    			$data[] = $comments;	
    		}
    	}
    	success(config('messages.general.fetch_success'),$data);
    }

    public function create_post(Request $request)
    {    	
    	$userId = $request->user()->id;
    	$text = $request->text;

    	$post = new Post;
    	$post->user_id = $userId;
    	$post->text = $text;
    	$post->save();

    	if($request->hasFile('photo'))
    	{
    		$post_att = new PostAttachment;
    		$upload_path = 'post/';
    		$post_pic = img_upload_HQquality($upload_path,$request->photo);
    		$post_att->user_id = $userId;
    		$post_att->post_id = $post->id;
    		$post_att->post_pic = $post_pic;
    		$post_att->save();
    	}

    	$posts = \DB::table('post as p')
    				->leftjoin('users as u','p.user_id','=','u.id')
	    			->where('p.id','=',$post->id)
	    			->select('p.*','u.username','u.full_name as name','u.profile_pic')
	    			->first();

	    	if($posts->profile_pic!='' && $posts->profile_pic!=NULL)
			{
    			$chk = explode('.', $posts->profile_pic);				
		        if($chk[1]=='jpg' || $chk[1]=='jpeg' || $chk[1]=='png')
		        {
					$posts->profile_picture = env('STORAGE_URL').'user/profile/'.$posts->profile_pic;
				}
			}			
    		$post_att = PostAttachment::where('post_id',$posts->id)->first();
    		if(!empty($post_att))
    		{
    			$post_att->storageUrl = env('STORAGE_URL').'post/'.$post_att->post_pic;
    		}
    		$posts->PostAttachmentsModel = $post_att;
    		$posts->post_comments = PostComments::where('post_id',$posts->id)->get();
    		$posts->post_comment_like = PostCommentLike::where('post_id',$posts->id)->get();
    		$posts->post_like = PostLike::where('post_id',$posts->id)->where('status',1)->get();
    		$posts->post_tags = PostTags::where('post_id',$posts->id)->get();
    		$posts->likes = count($posts->post_like);
			$posts->comments = count($posts->post_comments);

			$like_status = PostLike::where('post_id',$posts->id)->whereIn('like_userId', [$userId])->where('status',1)->get();
			if(COUNT($like_status)>0)
			{
				$posts->likestatus = true;
			}
			else
			{
				$posts->likestatus = false;
			}    
			$posts->checkIn = false;	
			$posts->event = 'create';
    	success2(config('messages.general.fetch_success'),$posts);
    }

    public function report_post(Request $request)
    {
    	$userId = $request->user()->id;
    	$report = $request->report;
    	$post_id = $request->post_id;

    	$post = ReportedPost::where('post_id','=',$post_id)->where('reported_userId','=',$userId)->first();
    	if (!empty($post))
    	{
    		error(config('messages.post.already_report'));
    	}
    	else
    	{
    		$report_data = Report::get();
    		foreach ($report_data as $report_datas) 
    		{
    			if (strcmp($report_datas->name,$report) == 0) 
    			{ 
				    $reportId = $report_datas->id;
				}
    		}
    		$data = new ReportedPost;
    		$data->post_id = $post_id;
    		$data->reported_userId = $userId;
    		$data->report_id = $reportId;
    		$data->save();

    		$post = Post::where('id',$post_id)->update(['report' => 1]);
    		success(config('messages.post.report'));
    	}
    }

    public function delete_post(Request $request)
    {
    	$post_id = $request->post_id;
    	$post = Post::find($post_id);
    	$post->deleted_at = 1;
    	$post->save();
    	$post->event = 'delete';
    	success2(config('messages.post.delete'),$post->toArray());
    }

    public function update_post(Request $request)
    {
    	$userId = $request->user()->id;
    	$post_id = $request->post_id;
    	$text = $request->text;

    	$post = Post::find($post_id);
    	$post->text = $text;
    	$post->save();

    	$posts = \DB::table('post as p')
    				->leftjoin('users as u','p.user_id','=','u.id')
	    			->where('p.id','=',$post_id)
	    			->select('p.*','u.username','u.full_name as name','u.profile_pic')
	    			->first();

	    	if($posts->profile_pic!='' && $posts->profile_pic!=NULL)
			{
    			$chk = explode('.', $posts->profile_pic);				
		        if($chk[1]=='jpg' || $chk[1]=='jpeg' || $chk[1]=='png')
		        {
					$posts->profile_picture = env('STORAGE_URL').'user/profile/'.$posts->profile_pic;
				}
			}
    		$post_att = PostAttachment::where('post_id',$post_id)->first();
    		if(!empty($post_att))
    		{
    			$post_att->storageUrl = env('STORAGE_URL').'post/'.$post_att->post_pic;
    		}
    		$posts->PostAttachmentsModel = $post_att;
    		$posts->post_comments = PostComments::where('post_id',$post_id)->get();
    		$posts->post_comment_like = PostCommentLike::where('post_id',$post_id)->get();
    		$posts->post_like = PostLike::where('post_id',$post_id)->where('status',1)->get();
    		$posts->post_tags = PostTags::where('post_id',$post_id)->get();
    		$posts->likes = count($posts->post_like);
			$posts->comments = count($posts->post_comments);

			$like_status = PostLike::where('post_id',$post_id)->whereIn('like_userId', [$userId])->where('status',1)->get();
			if(COUNT($like_status)>0)
			{
				$posts->likestatus = true;
			}
			else
			{
				$posts->likestatus = false;
			}    
			$posts->checkIn = false;	
			$posts->event = 'update';
    	success2(config('messages.general.fetch_success'),$posts);
    }

    public function get_profile(Request $request)
    {
    	$userId = $request->user()->id;
    	$user = User::where('id',$userId)
    			->select('id','email','full_name','username','profile_pic','cover_pic','oneSignal')
    			->first();
		if($user->profile_pic!='' && $user->profile_pic!=NULL)
		{
			$chk = explode('.', $user->profile_pic);				
	        if($chk[1]=='jpg' || $chk[1]=='jpeg' || $chk[1]=='png')
	        {
				$user->profile_picture = env('STORAGE_URL').'user/profile/'.$user->profile_pic;
			}
		}
		else
		{
			$user->profile_picture = '';
		}
		if ($user->cover_pic!='' && $user->cover_pic!=NULL) 
		{
			$user->cover_picture = env('STORAGE_URL').'user/cover/'.$user->cover_pic;
		}else{
			$user->cover_picture = '';
		}
		$user->followingCount = Following::where('user_id',$userId)->where('status',1)->count();
		$user->followersCount = Followers::where('user_id',$userId)->where('status',1)->count();
    	$user->listsCount = Post::where('user_id',$userId)->where('deleted_at',0)->count();
    	success2(config('messages.general.fetch_success'),$user);
    }

    public function get_user_post(Request $request)
    {
    	$data = array();
    	$userId = $request->user()->id;
    	$take = $request->total_records ?: 10;
    	$page = $request->page ?: 1;
    	$post = \DB::table('post as p')
    			->leftjoin('users as u','p.user_id','=','u.id')
    			->where('p.user_id', '=', $userId)
				->where('p.deleted_at',0)
    			->select('p.*','u.username','u.full_name as name','u.profile_pic')
    			->orderBy('p.id','DESC')
    			->take($take)
		        ->skip(($page - 1) * $take)
    			->get();
		//echo "<pre>"; print_r($post); exit;
    	if(count($post) > 0) 
    	{
    		foreach($post as $posts)
	    	{
	    		if($posts->profile_pic!='' && $posts->profile_pic!=NULL)
				{
	    			$chk = explode('.', $posts->profile_pic);				
			        if($chk[1]=='jpg' || $chk[1]=='jpeg' || $chk[1]=='png')
			        {
						$posts->profile_picture = env('STORAGE_URL').'user/profile/'.$posts->profile_pic;
					}
				}			
	    		$post_att = PostAttachment::where('post_id',$posts->id)->first();
	    		if(!empty($post_att) && $post_att->post_pic!='' && $post_att->post_pic!=NULL)
	    		{
	    			$post_att->storageUrl = env('STORAGE_URL').'post/'.$post_att->post_pic;
	    		}
	    		$posts->PostAttachmentsModel = $post_att;
	    		$posts->post_comments = PostComments::where('post_id',$posts->id)->get();
	    		$posts->post_comment_like = PostCommentLike::where('post_id',$posts->id)->get();
	    		$posts->post_like = PostLike::where('post_id',$posts->id)->where('status',1)->get();
	    		$posts->post_tags = PostTags::where('post_id',$posts->id)->get();
	    		$posts->likes = count($posts->post_like);
				$posts->comments = count($posts->post_comments);

				$like_status = PostLike::where('post_id',$posts->id)->whereIn('like_userId', [$userId])->where('status',1)->get();
				if(COUNT($like_status)>0)
				{
					$posts->likestatus = true;
				}
				else
				{
					$posts->likestatus = false;
				}
	    		   		    		
	    		$data[] = $posts;
	    	}
    	}
    	success(config('messages.general.fetch_success'),$data);    	
    }

    public function edit_user_profile(Request $request)
    {
    	$userId = $request->user()->id;
    	if ($request->has('full_name')) 
    	{
    		$users = User::find($userId);
    		$users->full_name = $request->full_name;
    		$users->save();
    	}
    	if($request->hasFile('profile_image'))
    	{
    		$users = User::find($userId);
    		$upload_path = 'user/profile/';
    		$usr_profile = img_upload_HQquality($upload_path,$request->profile_image);
    		$users->profile_pic = $usr_profile;
			$users->save();
    	}
    	if ($request->hasFile('cover_image'))
    	{
    		$users = User::find($userId);
    		$upload_path = 'user/cover/';
    		$usr_cover = img_upload_HQquality($upload_path,$request->cover_image);
    		$users->cover_pic = $usr_cover;
			$users->save();
    	}

    	$user = User::where('id',$userId)
    			->select('id','email','full_name','username','profile_pic','cover_pic','oneSignal')
    			->first();
		if($user->profile_pic!='' && $user->profile_pic!=NULL)
		{
			$chk = explode('.', $user->profile_pic);				
	        if($chk[1]=='jpg' || $chk[1]=='jpeg' || $chk[1]=='png')
	        {
				$user->profile_picture = env('STORAGE_URL').'user/profile/'.$user->profile_pic;
			}
		}
		else
		{
			$user->profile_picture = '';
		}
		if ($user->cover_pic!='' && $user->cover_pic!=NULL) 
		{
			$user->cover_picture = env('STORAGE_URL').'user/cover/'.$user->cover_pic;
		}else{
			$user->cover_picture = '';
		}
		$user->followingCount = Following::where('user_id',$userId)->where('status',1)->count();
		$user->followersCount = Followers::where('user_id',$userId)->where('status',1)->count();
    	$user->listsCount = Post::where('user_id',$userId)->where('deleted_at',0)->count();
    	success2(config('messages.general.fetch_success'),$user);
    }

    public function get_following_profile(Request $request)
    {
    	$login_userId = $request->user()->id;
    	$userId = $request->user_id;
    	$user = User::where('id',$userId)
    			->select('id','email','full_name','username','profile_pic','cover_pic','oneSignal')
    			->first();
		if($user->profile_pic!='' && $user->profile_pic!=NULL)
		{
			$chk = explode('.', $user->profile_pic);				
	        if($chk[1]=='jpg' || $chk[1]=='jpeg' || $chk[1]=='png')
	        {
				$user->profile_picture = env('STORAGE_URL').'user/profile/'.$user->profile_pic;
			}
		}
		else
		{
			$user->profile_picture = '';
		}
		if ($user->cover_pic!='' && $user->cover_pic!=NULL) 
		{
			$user->cover_picture = env('STORAGE_URL').'user/cover/'.$user->cover_pic;
		}else{
			$user->cover_picture = '';
		}
		$user->oneSignal = [];
		$user->followingCount = Following::where('user_id',$userId)->where('status',1)->count();
		$user->followersCount = Followers::where('user_id',$userId)->where('status',1)->count();
    	$user->listsCount = Post::where('user_id',$userId)->where('deleted_at',0)->count();
    	$data = Following::where('user_id',$login_userId)->where('status',1)->first();
    	if (count($data)>0) 
    	{
    		$user->followinguser = true;
    	}else{
    		$user->followinguser = false;
    	}
    	success2(config('messages.general.fetch_success'),$user);
    }

    public function get_following_post(Request $request)
    {
    	$data = array();
    	$userId = $request->user_id;
    	$take = $request->total_records ?: 10;
    	$page = $request->page ?: 1;
    	$post = \DB::table('post as p')
    			->leftjoin('users as u','p.user_id','=','u.id')
    			->where('p.user_id', '=', $userId)
				->where('p.deleted_at',0)
    			->select('p.*','u.username','u.full_name as name','u.profile_pic')
    			->orderBy('p.id','DESC')
    			->take($take)
		        ->skip(($page - 1) * $take)
    			->get();
		//echo "<pre>"; print_r($post); exit;
    	if(count($post) > 0) 
    	{
    		foreach($post as $posts)
	    	{
	    		if($posts->profile_pic!='' && $posts->profile_pic!=NULL)
				{
	    			$chk = explode('.', $posts->profile_pic);				
			        if($chk[1]=='jpg' || $chk[1]=='jpeg' || $chk[1]=='png')
			        {
						$posts->profile_picture = env('STORAGE_URL').'user/profile/'.$posts->profile_pic;
					}
				}			
	    		$post_att = PostAttachment::where('post_id',$posts->id)->first();
	    		if(!empty($post_att) && $post_att->post_pic!='' && $post_att->post_pic!=NULL)
	    		{
	    			$post_att->storageUrl = env('STORAGE_URL').'post/'.$post_att->post_pic;
	    		}
	    		$posts->PostAttachmentsModel = $post_att;
	    		$posts->post_comments = PostComments::where('post_id',$posts->id)->get();
	    		$posts->post_comment_like = PostCommentLike::where('post_id',$posts->id)->get();
	    		$posts->post_like = PostLike::where('post_id',$posts->id)->where('status',1)->get();
	    		$posts->post_tags = PostTags::where('post_id',$posts->id)->get();
	    		$posts->likes = count($posts->post_like);
				$posts->comments = count($posts->post_comments);

				$like_status = PostLike::where('post_id',$posts->id)->where('like_userId', $request->user()->id)->where('status',1)->get();
				if(COUNT($like_status)>0)
				{
					$posts->likestatus = true;
				}
				else
				{
					$posts->likestatus = false;
				}
	    		   		    		
	    		$data[] = $posts;
	    	}
    	}
    	success(config('messages.general.fetch_success'),$data);
    }

    public function following_list(Request $request)
    {
    	$arr = array();
    	$login_userId = $request->user()->id;
    	$take = $request->total_records ?: 10;
    	$page = $request->page ?: 1;
    	$userId = $request->user_id;
    	$data = \DB::table('following as f')
    			->leftjoin('users as u','f.following_uid','=','u.id')
    			->where('f.user_id','=',$userId)
    			->where('f.status',1)
    			->select('f.*','u.username','u.full_name as full_name','u.profile_pic as profile_picture')
    			->orderBy('f.id','DESC')
    			->take($take)
		        ->skip(($page - 1) * $take)
    			->get();
    	if(COUNT($data)>0)
    	{
    		foreach($data as $datas)
    		{
    			$arr[] = $datas;
    			if($datas->profile_picture!='' && $datas->profile_picture!=NULL)
				{
	    			$chk = explode('.', $datas->profile_picture);				
			        if($chk[1]=='jpg' || $chk[1]=='jpeg' || $chk[1]=='png')
			        {
						$datas->profile_picture = env('STORAGE_URL').'user/profile/'.$datas->profile_picture;
					}
				}
				$follower = Followers::where('user_id','=',$login_userId)
								->where('follower_uid','=',$datas->following_uid)
								->where('status',1)
								->first();
				if(COUNT($follower)>0) {
					$datas->followinguser = true;
				} else {
					$datas->followinguser = false;
				}
				$following = Following::where('user_id','=',$login_userId)
								->where('following_uid','=',$datas->following_uid)
								->where('status',1)
								->first();
				if(COUNT($following)>0) {
					$datas->followuser = true;
				} else {
					$datas->followuser = false;
				}
    		}
    	}
    	success(config('messages.general.fetch_success'),$arr);
    }

    public function followers_list(Request $request)
    {
    	$arr = array();
    	$login_userId = $request->user()->id;
    	$userId = $request->user_id;
    	$take = $request->total_records ?: 10;
    	$page = $request->page ?: 1;
    	$data = \DB::table('followers as f')
    			->leftjoin('users as u','f.follower_uid','=','u.id')
    			->where('f.user_id','=',$userId)
    			->select('f.*','u.username','u.full_name as full_name','u.profile_pic as profile_picture')
    			->orderBy('f.id','DESC')
    			->take($take)
		        ->skip(($page - 1) * $take)
    			->get();
    			// echo "<pre>"; print_r($data); exit;
    	if(COUNT($data)>0)
    	{
    		foreach($data as $datas)
    		{
    			$arr[] = $datas;
    			if($datas->profile_picture!='' && $datas->profile_picture!=NULL)
				{
	    			$chk = explode('.', $datas->profile_picture);				
			        if($chk[1]=='jpg' || $chk[1]=='jpeg' || $chk[1]=='png')
			        {
						$datas->profile_picture = env('STORAGE_URL').'user/profile/'.$datas->profile_picture;
					}
				}
				$follower = Followers::where('user_id','=',$login_userId)
								->where('follower_uid','=',$datas->follower_uid)
								->where('status',1)
								->first();								
				if(COUNT($follower)>0) {
					$datas->followinguser = true;
				} else {
					$datas->followinguser = false;
				}
				$following = Following::where('user_id','=',$login_userId)
								->where('following_uid','=',$datas->follower_uid)
								->where('status',1)
								->first();
				if(COUNT($following)>0) {
					$datas->followuser = true;
				} else {
					$datas->followuser = false;
				}
    		}
    	}
    	success(config('messages.general.fetch_success'),$arr);
    }

    public function follow_user(Request $request)
    {
    	$userId = $request->user()->id;
    	$follow_user_id = $request->user_id;
    	$status = 1;
    	$chk_following = Following::where('user_id',$userId)->where('following_uid',$follow_user_id)->first();
    	if (count($chk_following)>0) 
    	{
    		$following = Following::find($chk_following->id);
    		if ($following->status == 1) {
    			$status = 0;
    		}else{
    			$status = 1;
    		}
    	}
    	else
    	{
    		$following = new Following; //Following
    	}
        $following->user_id = $userId;
        $following->following_uid = $follow_user_id;
        $following->status = $status;
        $following->save();

        $chk_followers = Followers::where('user_id',$follow_user_id)->where('follower_uid',$userId)->first();
        if (count($chk_followers)>0) 
    	{
    		$followers = Followers::find($chk_followers->id);
    		if ($followers->status == 1) {
    			$status = 0;
    		}else{
    			$status = 1;
    		}
    	}
    	else
    	{
    		$followers = new Followers; //Followers
    	}
        $followers->user_id = $follow_user_id;
        $followers->follower_uid = $userId;
        $followers->status = $status;
        $followers->save();

        $datas = \DB::table('following as f')
    			->leftjoin('users as u','f.following_uid','=','u.id')
    			->where('f.user_id','=',$userId)
    			->where('f.following_uid','=',$follow_user_id)
    			->select('f.id','f.user_id','f.following_uid','f.following_uid as follower_uid','f.status','f.created_at','f.updated_at','u.username','u.full_name as full_name','u.profile_pic as profile_picture')
    			->first();
    			//echo "<pre>"; print_r($datas); exit;
    	if(COUNT($datas)>0)
    	{
			if($datas->profile_picture!='' && $datas->profile_picture!=NULL)
			{
    			$chk = explode('.', $datas->profile_picture);				
		        if($chk[1]=='jpg' || $chk[1]=='jpeg' || $chk[1]=='png')
		        {
					$datas->profile_picture = env('STORAGE_URL').'user/profile/'.$datas->profile_picture;
				}
			}
			$follower = Followers::where('user_id','=',$follow_user_id)
							->where('follower_uid','=',$datas->follower_uid)
							->where('status',1)
							->first();
			if(COUNT($follower)>0) {
				$datas->followinguser = true;
			} else {
				$datas->followinguser = false;
			}
				
			$following = Following::where('user_id','=',$userId)
							->where('following_uid','=',$datas->follower_uid)
							->where('status',1)
							->first();
			if(COUNT($following)>0) {
				$datas->followuser = true;
			} else {
				$datas->followuser = false;
			}

			$datas->followersCount = Followers::where('user_id',$follow_user_id)->where('status',1)->count();
			$datas->followingCount = Following::where('user_id',$follow_user_id)->where('status',1)->count();
    	}
    	success2(config('messages.general.fetch_success'),$datas);

    }
// -------------------------------------------------------------------------------------- //

}

?>