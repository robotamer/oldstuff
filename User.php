<?php
/**
 * @author     Chad Smith
 */
// PHP Login Script Thing
// Developed by Chad Smith
// Web: http://mktgdept.com/
// Download: http://mktgdept.com/php-login-script.zip
// Support: http://posttopic.com/topic/php-login-script
// Twitter: chadsmith
// Google Talk: chad@mktgdept.com
//
// Copyright (C) 2008 Chad Smith
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// Build: 20090107211851

class User {
	protected $config=array( // the settings
		'username'=>array(
			'min'=>4, // minumum username length allowed
			'max'=>24 // maximum username length allowed
		),
		'password'=>array(
			'min'=>4, // minumum password length allowed
			'salt'=>'nfgdgfhymjsv' // random characters for salting passwords & sessions
		),
		'pages'=>array(
			'login'   =>'auth/index.php/login', // login page
			'signup'  =>'auth/index.php/signup', // registration page
			'manage'  =>'auth/index.php/manage', // change email page
			'change'  =>'auth/index.php/change', // change password page
			'activate'=>'auth/index.php/activate' // activation page
		),
		'site'=>array(
			'admin'=>'Dennis T Kaplan', // your name
			'email'=>'troy@pzzazz.com', // address to send new account emails from
			'name'=>'PzzAzz', // site name to display in emails
			'cookie'=>'authTaMeR' // cookie name
		)
	);

	function __construct(){ // generates the user class and determines what we are doing
		//session_name($this->config['site']['cookie']);
		//session_start();
		$this->baseUri = Uri::baseUrl();
		$this->set_actions();
		if(isset($_GET['logout']))
			$this->logout();
		elseif(isset($_POST['nonce'])){
			if($this->nonce('login')==$_POST['nonce'])
			   $this->login();
			elseif($this->nonce('signup')==$_POST['nonce'])
			   $this->signup();
			elseif($this->nonce('change')==$_POST['nonce'])
			   $this->change();
			elseif($this->nonce('edit')==$_POST['nonce'])
			   $this->edit();
			else $this->fail("Please try again");
		}
		elseif(isset($_GET['activate']))
			$this->activate();
	}

	protected function fail($message,$to=''){ // fails forward
		$_SESSION['message']=$message;
		@header('Location: '.($to!=''?$to:$_SERVER['HTTP_REFERER']));
		die();
	}

	protected function errors(){ // displays errors
		$message='';
		if(isset($_SESSION['message'])){
			$message=$_SESSION['message'];
			unset($_SESSION['message']);
		}
		return $message;
	}

	private function signup(){ // processes the registration form
		if(!isset($_POST['name'])||!isset($_POST['password'])||!isset($_POST['confirm-password'])||!isset($_POST['email'])||!isset($_POST['confirm-email']))
			die();
		$name=strtolower($_POST['name']);
		if(strlen($name)<$this->config['username']['min'])
			$this->fail("Username must be at least ".$this->config['username']['min']." characters");
		if(strlen($name)>$this->config['username']['max'])
			$this->fail("Username cannot be more than ".$this->config['username']['max']." characters");
		if(!$this->is_username($name))
			$this->fail("Username can only contain alphanumeric characters");
		$db=$this->sqlite();
		$q=$db->prepare("SELECT id FROM users WHERE name=?");
		$q->execute(array($name));
		if ($q->fetchColumn()!=0)
			$this->fail($name." is already in use");
		if($_POST['password']!=$_POST['confirm-password'])
			$this->fail("Password did not match confirmation");
		$password=$_POST['password'];
		if(strlen($password)<$this->config['password']['min'])
			$this->fail("Password must be at least ".$this->config['password']['min']." characters");
		if(strtolower($_POST['email'])!=strtolower($_POST['confirm-email']))
			$this->fail("E-mail did not match confirmation");
		$email=strtolower($_POST['email']);
		if(!$this->is_email($email))
			$this->fail("E-mail address does not appear to be valid");
		$password=md5(sha1($name).$this->config['password']['salt'].sha1($password));
		$activate=md5(uniqid(rand(),true));
		if($db->prepare("INSERT INTO users (name,password,email,temp) VALUES (?,?,?,?)")->execute(array($name,$password,$email,'a'.$activate))){
			$subject='New Account at '.$this->config['site']['name'];
			$message='<p>Thank you for creating an account at '.$this->config['site']['name'].'. Please click the link below to activate your account.</p>'."\r\n";
			$message.="\t".'<p><a href="http://'.$_SERVER['SERVER_NAME'].'/'.$this->config['pages']['activate'].'/'.$activate.'">http://'.$_SERVER['SERVER_NAME'].'/'.$this->config['pages']['activate'].'/'.$activate.'</a></p>';
			$this->mail($email,$subject,$message);
			$q=$db->prepare("SELECT id FROM users WHERE name=? AND password=?");
			$q->execute(array($name,$password));
			$id=$q->fetchColumn();
			$this->do_action('signup',array($id,$name,$email));
			$this->fail("Success! Please check your e-mail to activate your account.");
		}
		else $this->fail("Something went wrong");
	}

	private function login(){ // processes the login form
		if(!isset($_POST['name'])||!isset($_POST['password']))
			die();
		$name=strtolower($_POST['name']);
		$password=$_POST['password'];
		if(strlen($name)<$this->config['username']['min']||strlen($name)>$this->config['username']['max']||strlen($password)<$this->config['password']['min'])
			$this->fail("Invalid username or password"); // invalid username or password length 
		if(!$this->is_username($name))
			$this->fail("Invalid username or password"); // invalid character in username or sql injection attempt
		$password=md5(sha1($name).$this->config['password']['salt'].sha1($password));
		$db=$this->sqlite();
		$q=$db->prepare("SELECT id, name, email, temp FROM users WHERE name=? AND password=?");
		$q->execute(array($name,$password));
		$result=$q->fetch(PDO::FETCH_ASSOC);
		if(!$result)
			$this->fail("Invalid username or password"); // invalid username or password
		else
			if(isset($result['temp'][0]) && $result['temp'][0]=='a')
				$this->fail("You have not activated your account"); // account not activated
		//session_regenerate_id();
		$_SESSION['thumbprint']=$this->nonce(session_id().'thumbprint',86400);
		$_SESSION['user']['id']=$result['id'];
		$_SESSION['user']['name']=$result['name'];
		$_SESSION['user']['email']=$result['email'];
		$_SERVER['PHP_AUTH_USER']=$result['name'];
		$redirect=(isset($_SESSION['redirect'])?$_SESSION['redirect']:'/auth/');
		unset($_SESSION['redirect']);
		$this->do_action('login',array($result['id'],$result['name'],$result['email']));
		header('Location: '.$redirect);
		die();
	}

	protected function nonce($str='',$expires=300){ // generates a secure nonce
		return md5(date('Y-m-d H:i',ceil(time()/$expires)*$expires).$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].$this->config['password']['salt'].$str);
	}

	private function logout(){ // destroys the session to logout
		$this->do_action('logout',array($_SESSION['user']['id'],$_SESSION['user']['name'],$_SESSION['user']['email']));
		session_unset();
		session_destroy();
		header('Location: /');
		die();
	}

	protected function sqlite(){ // returns the database object

        $dbname = ROOT.'/data/users.db3';
        try {
            $db = new PDO('sqlite:'.$dbname);
            if (DEBUG === TRUE) $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            trigger_error("PDO Sqlite3 Connection with config failed:<br />$dbname<br />" . $e->getMessage());
        }
        if(defined('DEBUG') && DEBUG !== FALSE) {
            $sql = "SELECT name FROM sqlite_master WHERE type='table' UNION ALL SELECT name FROM sqlite_temp_master WHERE type='table' ORDER BY name";
            $result = $db->query($sql);
            foreach ($result as $row) $tables[] = current($row);
            if( ! in_array('users', $tables)) {
                $db->exec("CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name TEXT NOT NULL, password TEXT NOT NULL, email TEXT NOT NULL, temp TEXT)");
            }
        }
        return $db;
	}

	protected function mail($to,$subject,$body){ // sends an html email
		$headers='MIME-Version: 1.0'."\r\n";
		$headers.='Content-type: text/html; charset=iso-8859-1'."\r\n";
		$headers.='From: '.$this->config['site']['name'].' <'.$this->config['site']['email'].'>'."\r\n";
		$message='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"'."\r\n";
		$message.="\t".'"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'."\r\n";
		$message.='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">'."\r\n";
		$message.='<head>'."\r\n";
		$message.="\t".'<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />'."\r\n";
		$message.="\t".'<meta http-equiv="Content-Language" content="en-us" />'."\r\n";
		$message.="\t".'<title>'.$subject.'</title>'."\r\n";
		$message.='</head>'."\r\n";
		$message.='<body>'."\r\n";
		$message.="\t".$body."\r\n";
		$message.='</body>'."\r\n";
		$message.='</html>';
		return @mail($to,$subject,$message,$headers);
	}

	private function activate(){ // process account and email activation
		$key=$_GET['activate'];
		if(strlen($key)<>32||ereg("[^a-f0-9]",$key))
			$this->fail('Invalid activation key','/'.$this->config['pages']['login']);
		$db=$this->sqlite();
		$q=$db->prepare("UPDATE users SET temp='' WHERE temp=?");
		$q->execute(array('a'.$key));
		if($q->rowCount()!=0)
			$this->fail('Your account has been activated','/'.$this->config['pages']['login']);
		$q=$db->prepare("SELECT id, temp FROM users WHERE temp LIKE ?");
		$q->execute(array('a'.$key.'&e=%'));
		$result=$q->fetch(PDO::FETCH_ASSOC);
		if(!$result)
			$this->fail("Invalid activation key",'/'.$this->config['pages']['login']);//invalid key
		parse_str($result['temp']);
		$q=$db->prepare("UPDATE users SET temp='', email=? WHERE temp=?");
		$q->execute(array($e,$result['temp']));
		if($q->rowCount()!=0){
			if($_SESSION['user']['id']==$result['id'])
				$_SESSION['user']['email']=$e;
			$this->fail('Your new e-mail has been activated','/'.$this->config['pages']['manage']);
		}
		else
			$this->fail("Something went wrong",'/'.$this->config['pages']['login']);
	}

	private function change(){ // processes the password recovery form
		if($_POST['login']!=''){
			$user=strtolower($_POST['login']);
			if(!$this->is_username($user)||($this->is_username($user)&&(strlen($user)<$this->config['username']['min']||strlen($user)>$this->config['username']['max'])))
				$this->fail('Invalid username');
			$db=$this->sqlite();
			$q=$db->prepare("SELECT id, name, email, temp FROM users WHERE name=?");
			$q->execute(array($user));
			$result=$q->fetch(PDO::FETCH_ASSOC);
			if(!$result)
				$this->fail("Invalid username");
			else
				if($result['temp'][0]=='a')
					$this->fail("You have not activated your account");
			$id=$result['id'];
			$email=$result['email'];
			$change=md5(uniqid(rand(),true));
			$subject='Your password for '.$this->config['site']['name'];
			$message='<p>A request was made on '.$this->config['site']['name'].' to change your password. If this request was made by you, please click the link below to create a new password.</p>'."\r\n";
			$message.="\t".'<p><a href="http://'.$_SERVER['SERVER_NAME'].'/'.$this->config['pages']['change'].'/'.$change.'">http://'.$_SERVER['SERVER_NAME'].'/'.$this->config['pages']['change'].'/'.$change.'</a></p>';
			$q=$db->prepare("UPDATE users SET temp=? WHERE id=?");
			$q->execute(array('p.'.$change,$id));
			if($q->rowCount()==0)
				$this->fail('Something went wrong');
			if(@$this->mail($email,$subject,$message))
				$this->fail('Please check your email for instructions on changing your password.');
			$this->fail('Something went wrong');
		}
		elseif(isset($_POST['name'])&&isset($_POST['password'])&&isset($_POST['confirm-password'])&&isset($_GET['key'])){
			$key=$_GET['key'];
			if(strlen($key)<>32||ereg("[^a-f0-9]",$key))
				$this->fail('Invalid key');
			$name=strtolower($_POST['name']);
			if(!$this->is_username($name)||strlen($name)<$this->config['username']['min']||strlen($name)>$this->config['username']['max'])
				$this->fail('Invalid username');
			if($_POST['password']!=$_POST['confirm-password'])
				$this->fail("Password did not match confirmation");
			$password=$_POST['password'];
			if(strlen($password)<$this->config['password']['min'])
				$this->fail("Password must be at least ".$this->config['password']['min']." characters");
			$password=md5(sha1($name).$this->config['password']['salt'].sha1($password));
			$db=$this->sqlite();
			$q=$db->prepare("SELECT id FROM users WHERE name=?");
			$q->execute(array($name));
			$result=$q->fetch(PDO::FETCH_ASSOC);
			if(!$result)
				$this->fail("Invalid username");
			$id=$result['id'];
			$q=$db->prepare("UPDATE users SET password=?, temp='' WHERE temp=? AND id=?");
			$q->execute(array($password,'p.'.$key,$id));
			if($q->rowCount()!=0)
				$this->fail('Your new password has been saved.','/'.$this->config['pages']['login']);
			else $this->fail('Invalid key');
		}
		else $this->fail('Please complete all fields');
	}

	private function is_email($str){ // returns true if email is a valid format
		//return ereg("^[a-z0-9,!#\$%&'\*\+/=\?\^_`\{\|}~-]+(\.[a-z0-9,!#\$%&'\*\+/=\?\^_`\{\|}~-]+)*@([a-z0-9]([a-z0-9-]*[a-z0-9])?\.)+([a-z]{2}|com|org|net|gov|mil|biz|tel|info|mobi|name|aero|jobs|museum)$",$str);
		return filter_var($str, FILTER_VALIDATE_EMAIL);
	}

	private function is_username($str){ // returns true if the username contains only alphanumeric characters
		return ctype_alnum($str);
	}

	private function valid_session(){ // returns true if the session is valid
		return (isset($_SESSION['thumbprint']) && $_SESSION['thumbprint']==$this->nonce(session_id().'thumbprint',86400));
	}

	public function logged_in(){ // returns true if the user is logged in
		return ($this->valid_session()&&isset($_SESSION['user']['id'])&&isset($_SESSION['user']['name'])&&isset($_SESSION['user']['email']));
	}

	public function require_login(){ // allow only authenticated users
		if(!$this->valid_session()){
			$_SESSION['redirect']=$_SERVER['REQUEST_URI'];
			$this->fail('You must log in to access this page','/'.$this->config['pages']['login']);
		}
	}	
	
	public function require_admin(){ // allow only admin users
		if(!$this->logged_in() || ($_SESSION['user']['id']!=1)){
			$_SESSION['redirect']=$_SERVER['REQUEST_URI'];
			$this->fail('You must log in to access this page','/'.$this->config['pages']['login']);
		}
	}

	private function edit(){ // processes the change of email form
		if(!isset($_POST['email'])||!isset($_POST['confirm-email']))
			die();
		$this->require_login();
		$db=$this->sqlite();
		if(strtolower($_POST['email'])!=strtolower($_POST['confirm-email']))
			$this->fail("E-mail did not match confirmation");
		$email=strtolower($_POST['email']);
		if($email==$_SESSION['user']['email'])
			$this->fail("That is already set as your e-mail address");
		if(!$this->is_email($email))
			$this->fail("E-mail address does not appear to be valid");
		$activate=md5(uniqid(rand(),true));
		$q=$db->prepare("UPDATE users SET temp=? WHERE id=?");
		$q->execute(array('a='.$activate.'&e='.$email,$_SESSION['user']['id']));
		if($q->rowCount()!=0){
			$subject='Account Change at '.$this->config['site']['name'];
			$message='<p>A request was made to change the e-mail address associated with your account from '.$_SESSION['user']['email'].' to '.$email.'. Please click the link below to confirm the new address.</p>'."\r\n";
			$message.="\t".'<p><a href="http://'.$_SERVER['SERVER_NAME'].'/'.$this->config['pages']['activate'].'/'.$activate.'">http://'.$_SERVER['SERVER_NAME'].'/'.$this->config['pages']['activate'].'/'.$activate.'</a></p>';
			$this->mail($email,$subject,$message);
			$this->fail("Success! Please check your e-mail to confirm the new address.");
		}
		else $this->fail("Something went wrong");
	}

	public function login_form(){ // prints the login form
		echo "".
		"\t\t".'<form method="post" action="/'.$this->config['pages']['login'].'">'."\n".
		"\t\t\t".'<fieldset>'."\n".
		"\t\t\t\t".'<label for="name">Username:</label><input name="name" id="name" type="text" /><br/>'."\n".
		"\t\t\t\t".'<label for="password">Password:</label><input name="password" id="password" type="password" />'."\n".
		"\t\t\t".'</fieldset>'."\n".
		"\t\t\t".'<fieldset>'."\n".
		"\t\t\t\t".'<p class="error">'.$this->errors().'</p>'."\n".
		"\t\t\t\t".'<input type="hidden" name="nonce" value="'.$this->nonce('login').'" /><input value="Login" type="submit" /><input value="Reset" type="reset" />'."\n".
		"\t\t\t\t".'<p>Need an account? <a href="/'.$this->config['pages']['signup'].'">Sign Up</a>.<br /><a href="/'.$this->config['pages']['change'].'">Change Password</a>.</p>'."\n".
		"\t\t\t".'</fieldset>'."\n".
		"\t\t".'</form>'."\n";
	}

	public function signup_form(){ // prints the registration form
		echo "".
		"\t\t".'<form method="post" action="/'.$this->config['pages']['signup'].'">'."\n".
		"\t\t\t".'<fieldset>'."\n".
		"\t\t\t\t".'<label for="name">Username:</label><input name="name" id="name" type="text" /><br/>'."\n".
		"\t\t\t\t".'<label for="password">Password:</label><input name="password" id="password" type="password" /><br/>'."\n".
		"\t\t\t\t".'<label for="confirm-password">Confirm Password:</label><input name="confirm-password" id="confirm-password" type="password" /><br/>'."\n".
		"\t\t\t\t".'<label for="email">E-Mail:</label><input name="email" id="email" type="text" /><br/>'."\n".
		"\t\t\t\t".'<label for="confirm-email">Confirm E-Mail:</label><input name="confirm-email" id="confirm-email" type="text" />'."\n".
		"\t\t\t".'</fieldset>'."\n".
		"\t\t\t".'<fieldset>'."\n".
		"\t\t\t\t".'<p class="error">'.$this->errors().'</p>'."\n".
		"\t\t\t\t".'<input type="hidden" name="nonce" value="'.$this->nonce('signup').'" /><input value="Sign Up" type="submit" /><input value="Reset" type="reset" />'."\n".
		"\t\t\t".'</fieldset>'."\n".
		"\t\t".'</form>'."\n";
	}

	public function password_form(){ // prints the recover/change password form
		echo "".
		(!isset($_GET['key'])?
		"\t\t".'<form method="post" action="/'.$this->config['pages']['change'].'">'."\n".
		"\t\t\t".'<fieldset>'."\n".
		"\t\t\t\t".'<label for="login">Username:</label><input name="login" id="login" type="text" />'."\n".
		"\t\t\t".'</fieldset>'."\n"
		:
		"\t\t".'<form method="post" action="/'.$this->config['pages']['change'].'/'.$_GET['key'].'">'."\n".
		"\t\t\t".'<fieldset>'."\n".
		"\t\t\t\t".'<label for="name">Username:</label><input name="name" id="name" type="text" /><br/>'."\n".
		"\t\t\t\t".'<label for="password">New Password:</label><input name="password" id="password" type="password" /><br/>'."\n".
		"\t\t\t\t".'<label for="confirm-password">Confirm Password:</label><input name="confirm-password" id="confirm-password" type="password" />'."\n".
		"\t\t\t".'</fieldset>'."\n")
		.
		"\t\t\t".'<fieldset>'."\n".
		"\t\t\t\t".'<p class="error">'.$this->errors().'</p>'."\n".
		"\t\t\t\t".'<input type="hidden" name="nonce" value="'.$this->nonce('change').'" /><input value="Change Password" type="submit" /><input value="Reset" type="reset" />'."\n".
		"\t\t\t".'</fieldset>'."\n".
		"\t\t".'</form>'."\n";
	}

	public function account_form(){ // prints the change e-mail form 
		echo "".
		"\t\t".'<form method="post" action="/'.$this->config['pages']['manage'].'">'."\n".
		"\t\t\t".'<fieldset>'."\n".
		"\t\t\t\t".'<label for="email">New E-Mail:</label><input name="email" id="email" type="text" /><br/>'."\n".
		"\t\t\t\t".'<label for="confirm-email">Confirm E-Mail:</label><input name="confirm-email" id="confirm-email" type="text" />'."\n".
		"\t\t\t".'</fieldset>'."\n".
		"\t\t\t".'<fieldset>'."\n".
		"\t\t\t\t".'<p class="error">'.$this->errors().'</p>'."\n".
		"\t\t\t\t".'<input type="hidden" name="nonce" value="'.$this->nonce('edit').'" /><input value="Change eMail" type="submit" /><input value="Reset" type="reset" />'."\n".
		"\t\t\t".'</fieldset>'."\n".
		"\t\t".'</form>'."\n";
	}

	protected $actions=array();

	protected function add_action($tag,$function_to_add,$priority=10,$accepted_args=1){ // modified from wordpress - used for adding actions to events
		$idx=$this->action_id($tag,$function_to_add,$priority);
		$this->actions[$tag][$priority][$idx]=array('function'=>$function_to_add,'accepted_args'=>$accepted_args);
		return true;
	}

	protected function do_action($tag,$arg=''){ // modified from wordpress - used for running actions on certain events
		$action=array();
		$action[]=$tag;
		if(!isset($this->actions[$tag])){
			array_pop($action);
			return;
		}
		$args=array();
		if(is_array($arg)&&1==count($arg)&&is_object($arg[0]))
			$args[]=&$arg[0];
		else
			$args[]=$arg;
		for($a=2;$a<func_num_args();$a++)
			$args[]=func_get_arg($a);
		ksort($this->actions[$tag]);
		reset($this->actions[$tag]);
		do{
			foreach((array)current($this->actions[$tag]) as $the_)
				if(!is_null($the_['function']))
					call_user_func_array($the_['function'],array_slice($args,0,(int)$the_['accepted_args']));
		}while(next($this->actions[$tag])!==false);
		array_pop($action);
	}
	
	private function action_id($tag,$function,$priority){ // modified from wordpress - used privately by add_action
		if(is_string($function))
			return $function;
		else if(is_object($function[0])){
			$obj_idx=get_class($function[0]).$function[1];
			if(!isset($function[0]->action_id)){
				if(false===$priority)
					return false;
				$count=isset($this->actions[$tag][$priority])?count((array)$this->actions[$tag][$priority]):0;
				$function[0]->action_id=$count;
				$obj_idx.=$count;
				unset($count);
			}else
				$obj_idx.=$function[0]->action_id;
			return $obj_idx;
		}
		else if(is_string($function[0]))
			return $function[0].$function[1];
	}
	
	private function set_actions(){ // sets default actions
		$this->add_action('signup',array('user','signup_notification'));
	}
	
	private function signup_notification($args){ // sends an e-mail notification when a user signs up
		$this->mail($this->config['site']['email'],'New User at '.$this->config['site']['name'],'<p>'.$args[1].' just registered at '.$this->config['site']['name'].'</p>');
	}
}
