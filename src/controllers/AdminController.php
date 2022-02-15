<?php namespace charlie\crudbooster\controllers;

use charlie\crudbooster\controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use CRUDBooster;
use Carbon\Carbon;
use DateTime;

class AdminController extends CBController {

	public $mdp_min_caract = 8;
	public $mdp_lower_req = true;
	public $mdp_upper_req = true;
	public $mdp_number_req = true;
	public $mdp_special_req = true;

	function getIndex() {
		$data = array();			
		$data['page_title']       = '<strong>Dashboard</strong>';				
		return view('crudbooster::home',$data);
	}

	public function getLockscreen() {
		
		if(!CRUDBooster::myId()) {
			Session::flush();
			return redirect()->route('getLogin')->with('message',cbLang('crudbooster.alert_session_expired'));
		}
		
		Session::put('admin_lock',1);
		return view('crudbooster::lockscreen');
	}

	public function postUnlockScreen() {
		$id       = CRUDBooster::myId();
		$password = Request::input('password');		
		$users    = DB::table(config('crudbooster.USER_TABLE'))->where('id',$id)->first();		

		if(\Hash::check($password,$users->password)) {
			Session::put('admin_lock',0);	
			return redirect(CRUDBooster::adminPath());
		}else{
			echo "<script>alert('".cbLang('crudbooster.alert_password_wrong')."');history.go(-1);</script>";				
		}
	}	

	public function getLogin()
	{							

		if(CRUDBooster::myId()) {
			return redirect(CRUDBooster::adminPath());
		}

		return view('crudbooster::login');
	}
 
	public function postLogin() {		

		$validator = Validator::make(Request::all(),			
			[
			'email'=>'required|email|exists:'.config('crudbooster.USER_TABLE'),
			'password'=>'required'			
			]
		);
		
		if ($validator->fails()) 
		{
			$message = 'Email ou mot de passe incorrect !';
			return redirect()->back()->with(['message'=>implode(', ',$message),'message_type'=>'danger']);
		}

		$email 		= Request::input("email");
		$password 	= Request::input("password");
		$users 		= DB::table(config('crudbooster.USER_TABLE'))->where("email",$email)->first(); 		

		// Getting the current dateTime
		$current_date_time = \Carbon\Carbon::now()->toDateTimeString();

		$userLogAttempt = $users->loginAttempt;
		//dd($users->loginAttempt);
		if($userLogAttempt == 0){
			if(\Hash::check($password,$users->password)) {
				$priv = DB::table("cms_privileges")->where("id",$users->id_cms_privileges)->first();

				$roles = DB::table('cms_privileges_roles')
				->where('id_cms_privileges',$users->id_cms_privileges)
				->join('cms_moduls','cms_moduls.id','=','id_cms_moduls')
				->select('cms_moduls.name','cms_moduls.path','is_visible','is_create','is_read','is_edit','is_delete')
				->get();
				
				$photo = ($users->photo)?asset($users->photo):asset('vendor/crudbooster/avatar.jpg');
				Session::put('admin_id',$users->id);			
				Session::put('admin_is_superadmin',$priv->is_superadmin);
				Session::put('admin_name',$users->name);	
				Session::put('admin_photo',$photo);
				Session::put('admin_privileges_roles',$roles);
				Session::put("admin_privileges",$users->id_cms_privileges);
				Session::put('admin_privileges_name',$priv->name);			
				Session::put('admin_lock',0);
				Session::put('theme_color',$priv->theme_color);
				Session::put("appname",CRUDBooster::getSetting('appname'));		

				CRUDBooster::insertLog(cbLang("crudbooster.log_login",['email'=>$users->email,'ip'=>Request::server('REMOTE_ADDR')]));		

				$cb_hook_session = new \App\Http\Controllers\CBHook;
				$cb_hook_session->afterLogin();

				// Updating the login attempts to 0
				DB::table('cms_users')->where('email',$email)->update(['loginAttempt' => 0]);

				return redirect(CRUDBooster::adminPath());
			}else{
				// Number of attempts in value
				$nbrAttemptLogin = $users->loginAttempt+1;

				// Updating the login attempts to the current login attempt
				DB::table('cms_users')->where('email',$email)->update(['loginAttempt' => $nbrAttemptLogin,'updated_at'=>$current_date_time]);

				// Redirecting with message
				return redirect()->route('getLogin')->with('message', 'Votre mot de passe ne correspond pas !');
			}
		}else{
			// Getting the user with the updated fields
			$userUpdated = DB::table('cms_users')->where('email',$email)->first();

			// Number of attempts in value
			$nbrAttemptLogin = $users->loginAttempt+1;

			if($userUpdated->loginAttempt < 4){
				if(\Hash::check($password,$users->password)) {
					$priv = DB::table("cms_privileges")->where("id",$users->id_cms_privileges)->first();
	
					$roles = DB::table('cms_privileges_roles')
					->where('id_cms_privileges',$users->id_cms_privileges)
					->join('cms_moduls','cms_moduls.id','=','id_cms_moduls')
					->select('cms_moduls.name','cms_moduls.path','is_visible','is_create','is_read','is_edit','is_delete')
					->get();
					
					$photo = ($users->photo)?asset($users->photo):asset('vendor/crudbooster/avatar.jpg');
					Session::put('admin_id',$users->id);			
					Session::put('admin_is_superadmin',$priv->is_superadmin);
					Session::put('admin_name',$users->name);	
					Session::put('admin_photo',$photo);
					Session::put('admin_privileges_roles',$roles);
					Session::put("admin_privileges",$users->id_cms_privileges);
					Session::put('admin_privileges_name',$priv->name);			
					Session::put('admin_lock',0);
					Session::put('theme_color',$priv->theme_color);
					Session::put("appname",CRUDBooster::getSetting('appname'));		
	
					CRUDBooster::insertLog(cbLang("crudbooster.log_login",['email'=>$users->email,'ip'=>Request::server('REMOTE_ADDR')]));		
	
					$cb_hook_session = new \App\Http\Controllers\CBHook;
					$cb_hook_session->afterLogin();
	
					// Updating the login attempts to 0
					DB::table('cms_users')->where('email',$email)->update(['loginAttempt' => 0]);
	
					return redirect(CRUDBooster::adminPath());
				}else{
					// Updating the login attempts to the current login attempt
					DB::table('cms_users')->where('email',$email)->update(['loginAttempt' => $nbrAttemptLogin,'updated_at'=>$current_date_time]);

					// Redirecting with message
					return redirect()->route('getLogin')->with('message', 'Votre mot de passe ne correspond pas !');
				}
			}else{
				// Calculating the time diffrence between the current time and the last updated at time
				$date = Carbon::parse($userUpdated->updated_at);
				$now = Carbon::now();
				$diffTime = $date->diffInSeconds($now);
				// Check if time past 10 minutes
				if($diffTime < 601){
					// Converting seconds to minutes 
					$minutes = floor(($diffTime / 60) % 60);
					// Getting the tim left for the next round of attempts
					$timeToShow = 10 - $minutes;
					// Personalized message 
					$message = "Veuillez réessayer dans ".$timeToShow." minutes s'il vous plait !";
					return redirect()->route('getLogin')->with('message', $message);
				}else{
					// Updating the login attempts to 0
					DB::table('cms_users')->where('email',$email)->update(['loginAttempt' => 0,'updated_at'=>$current_date_time]);

					return redirect()->route('getLogin')->with('message', 'Votre mot de passe ne correspond pas !');
				}
			}		
		}		
	}

	public function getForgot() {	
		if(CRUDBooster::myId()) {
			return redirect(CRUDBooster::adminPath());
		}
			
		return view('crudbooster::forgot');
	}

	public function postForgot() {
		$validator = Validator::make(Request::all(),			
			[
			'email'=>'required|email|exists:'.config('crudbooster.USER_TABLE')			
			]
		);
		
		if ($validator->fails()) 
		{
			$message = $validator->errors()->all();
			return redirect()->back()->with(['message'=>implode(', ',$message),'message_type'=>'danger']);
		}	

		$rand_string = $this->randomPassword();
		$password = \Hash::make($rand_string);

		DB::table(config('crudbooster.USER_TABLE'))->where('email',Request::input('email'))->update(array('password'=>$password));
 	
		$appname = CRUDBooster::getSetting('appname');		
		$user = CRUDBooster::first(config('crudbooster.USER_TABLE'),['email'=>g('email')]);	
		$user->password = $rand_string;
		CRUDBooster::sendEmail(['to'=>$user->email,'data'=>$user,'template'=>'forgot_password_backend']);

		CRUDBooster::insertLog(cbLang("crudbooster.log_forgot",['email'=>g('email'),'ip'=>Request::server('REMOTE_ADDR')]));

		return redirect()->route('getLogin')->with('message', cbLang("crudbooster.message_forgot_password"));

	}	

	public function getLogout() {
		
		$me = CRUDBooster::me();
		CRUDBooster::insertLog(cbLang("crudbooster.log_logout",['email'=>$me->email]));

		Session::flush();
		return redirect()->route('getLogin')->with('message',"Merci à plus tard !");
	}

	public function getNewPassword() {	
		if(CRUDBooster::myId()) {
			return redirect(CRUDBooster::adminPath());
		}
			
		return view('crudbooster::newPassword');
	}

	public function postNewPassword() {
		// Getting the email, password and confirmation from inputs
		$email = Request::input("email");
		$pass  = Request::input("password");
		$passConfirm = Request::input("passwordConfirm");

		// Message d erreur
		$message = "The password must respect the requirements below.";

		// Testing the password requirement
		if (!preg_match("#[0-9]+#",$pass) && $this->mdp_number_req) {
			return redirect()->back()->with(['message'=>$message,'message_type'=>'danger']);
		}else if(!preg_match("#[A-Z]+#",$pass) && $this->mdp_upper_req){
			return redirect()->back()->with(['message'=>$message,'message_type'=>'danger']);
		}else if(!preg_match("#[a-z]+#",$pass) && $this->mdp_lower_req){
			return redirect()->back()->with(['message'=>$message,'message_type'=>'danger']);
		}else if(!preg_match("#[\W]+#",$pass) && $this->mdp_special_req){
			return redirect()->back()->with(['message'=>$message,'message_type'=>'danger']);
		}
		// Check if email is in database
		$validator = Validator::make(Request::all(),			
			[
			'email'=>'required|email|exists:'.config('crudbooster.USER_TABLE'),
			'password' => 'required|min:'. $this->mdp_min_caract,
			'passwordConfirm' => 'required'			
			]
		);
		// if email not in database redirect with error message
		if ($validator->fails()) 
		{
			return redirect()->back()->with(['message'=>$message,'message_type'=>'danger']);
		}

		// Check if password and confirm password is correct
		if($pass == $passConfirm){
			// Crypt password
			$password = \Hash::make($pass);
			// update database with new password
			DB::table(config('crudbooster.USER_TABLE'))->where('email',$email)->update(array('password'=>$password));
			// Login user
			// get user from database
			$users = DB::table(config('crudbooster.USER_TABLE'))->where("email",$email)->first();
			if(\Hash::check($pass,$users->password)){
				// Getting the users privilege
				$priv = DB::table("cms_privileges")->where("id",$users->id_cms_privileges)->first();
				// Gtting the users role
				$roles = DB::table('cms_privileges_roles')
				->where('id_cms_privileges',$users->id_cms_privileges)
				->join('cms_moduls','cms_moduls.id','=','id_cms_moduls')
				->select('cms_moduls.name','cms_moduls.path','is_visible','is_create','is_read','is_edit','is_delete')
				->get();
				// Getting the users Data in the session
				$photo = ($users->photo)?asset($users->photo):asset('vendor/crudbooster/avatar.jpg');
				Session::put('admin_id',$users->id);			
				Session::put('admin_is_superadmin',$priv->is_superadmin);
				Session::put('admin_name',$users->name);	
				Session::put('admin_photo',$photo);
				Session::put('admin_privileges_roles',$roles);
				Session::put("admin_privileges",$users->id_cms_privileges);
				Session::put('admin_privileges_name',$priv->name);			
				Session::put('admin_lock',0);
				Session::put('theme_color',$priv->theme_color);
				Session::put("appname",CRUDBooster::getSetting('appname'));	
				CRUDBooster::insertLog(cbLang("crudbooster.log_login",['email'=>$users->email,'ip'=>Request::server('REMOTE_ADDR')]));		

				$cb_hook_session = new \App\Http\Controllers\CBHook;
				$cb_hook_session->afterLogin();
				// Redirecting the dashboard page
				return redirect(CRUDBooster::adminPath());
			}
		}else{
			// if password and confirmation isn't the same, return error message
			return redirect()->back()->with(['message'=>$message,'message_type'=>'danger']);
		}
	}

	// Generating random letters
	function randomPassword() {
		$count_request = 0;
		$alphabet = [];
		
		//The order of condition is important for respect the required
		if ($this->mdp_lower_req){
			array_unshift($alphabet, 'abcdefghijklmnopqrstuvwxyz');
			$count_request++;
		} else {
			array_push($alphabet, 'abcdefghijklmnopqrstuvwxyz');
		}
		if ($this->mdp_upper_req){
			array_unshift($alphabet, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
			$count_request++;
		} else {
			array_push($alphabet, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
		}
		if ($this->mdp_number_req){
			array_unshift($alphabet, '1234567890');
			$count_request++;
		} else {
			array_push($alphabet, '1234567890');
		}
		if ($this->mdp_special_req){
			array_unshift($alphabet, ',;:!?./%*$<>@#&()-+=_{}[]|');
			$count_request++;
		} else {
			array_push($alphabet, ',;:!?./%*$<>@#&()-+=_{}[]|');
		}

		$pass = array(); //remember to declare $pass as an array
		for ($i = 0; $i < $this->mdp_min_caract; $i++) {
			if($i < $count_request){ //condition for take obligatorily conditions
				$x = $i;
			} else { //take a random type of caractere
				$x=rand(0, 3);
			}
			$y=rand(0, strlen($alphabet[$x]) -1); //take the length, -1 for avoid chance to take a undifined value
    		array_push($pass,$alphabet[$x][$y]); 
		} 
			shuffle($pass);//to avoid a pattern with the condition
		return implode($pass); //turn the array into a string 
	}

}
