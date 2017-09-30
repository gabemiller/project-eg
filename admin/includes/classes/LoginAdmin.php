<?php


class LoginAdmin extends Object{
	
	private $defaul_pass = '12345';
	private $email;
	private $name;
	private $pwd;
	private $login;
	
	public function __construct(){
		parent::__construct();
		global $_POST, $_SESSION;
		
		if(isset($_POST['name']) && !empty($_POST['name'])
		   && isset($_POST['pwd']) && !empty($_POST['pwd'])){
			   
			$this->email = NULL;
			$this->name = $_POST['name'];
			$this->pwd = sha1($_POST['pwd']);
			$this->login = false;
			
		} else if(isset($_SESSION['admin_name']) && !empty($_SESSION['admin_name'])
				  && isset($_SESSION['login']) && $_SESSION['login'] === true){
					  
			$this->email = $_SESSION['email'];
			$this->name = $_SESSION['admin_name'];
			$this->pwd = NULL;
			$this->login = $_SESSION['login'];
			
		} else {
			$this->email = NULL;
			$this->name = NULL;
			$this->pwd = NULL;
			$this->login = false;
		}
	}
	
	public function __get($prop){
		return $this->$prop;
	}
	
	public function __set($prop,$val){
		$this->$prop = $val;
	}
	
	public function getSessionId(){
		return session_id();
	}
	
	public function login(){
		global $_SESSION;
		
		$connection = self::connectSQL();
		$result = $connection->query('SELECT * FROM admin 
									  WHERE name =\''.$connection->real_escape_string($this->name).'\'
									  AND password = \''.$connection->real_escape_string($this->pwd).'\'');
		
		if($result->num_rows == 1){
			$row = $result->fetch_assoc();
			
			$_SESSION['admin_name'] = $row['name'];
			$_SESSION['email'] = $row['email'];
			$_SESSION['login'] = true;
			
			$connection->close();
			return array('message'=>'Sikeres bejelentkezés!',
						 'login'=>true);
		} else {
			$connection->close();
			return array('message'=>'Helytelen felhasználónév vagy jelszó!',
						 'login'=>false);
		}
	}
	
	public function logout(){
		global $_SESSION;
		
		$_SESSION = array();
		session_destroy();
	}
    
	public function logoutAfterTime(){
		global $_SESSION;
		
		$timeout = 15*60;
		if (isset($_SESSION['start_time']) && !empty($_SESSION['start_time'])) {
			$elapsed_time = time() - $_SESSION['start_time'];
			if ($elapsed_time >= $timeout) {
				$this->logout();
				header("Location: index.php");
			}
		}
	
	}
	
    public function checkPermission(){
		
		if(!$this->isLoggedIn()){
			header('Location: index.php');
			return false;
		} 
		
		return true;
	}
	
    public function isLoggedIn(){
		global $_SESSION;
		
		if(isset($_SESSION['admin_name']) && !empty($_SESSION['admin_name']) 
		&& isset($_SESSION['login']) && $_SESSION['login'] === true){
			return true;
		} else {
			return false;
		}
	}
	
	public function checkDefaultPass(){
		$connection = self::connectSQL();
		$result = $connection->query('SELECT password FROM admin WHERE '
									.'name = \''.$connection->real_escape_string($this->name).'\'');
		$result = $result->fetch_assoc();
					
		if(strcmp(sha1($this->defaul_pass),$result['password']) === 0){
			return true;
		} else {
			return false;
		}
		
	}
	
	public function checkEmailIsEmpty(){
		$connection = self::connectSQL();
		$result = $connection->query('SELECT email FROM admin WHERE '
									.'name = \''.$connection->real_escape_string($this->name).'\'');
		$result = $result->fetch_assoc();
					
		if(empty($result['email'])){
			return true;
		} else {
			return false;
		}
		
	}
	
	public function modifyPassword($old_pwd,$new_pwd,$new_pwd_confirm){
		
		$connection = self::connectSQL();
		$result = $connection->query('SELECT password FROM admin WHERE '
									.'name = \''.$connection->real_escape_string($this->name).'\'');
		
		$old_pwd = sha1($old_pwd);
		$new_pwd = sha1($new_pwd);
		$new_pwd_confirm = sha1($new_pwd_confirm);							
		
		if($result->num_rows > 0){
			$result = $result->fetch_assoc();
			if(strcmp($result['password'],$old_pwd)==0 || strcmp($new_pwd,$new_pwd_confirm) == 0){
				$result2 = $connection->query('UPDATE admin '
											 .'SET password =\''.$connection->real_escape_string($new_pwd).'\''
											 .'WHERE name = \''.$connection->real_escape_string($this->name).'\'');
				
				if($connection->affected_rows > 0){
						return array('message'=>'A jelszó módosítása sikeres volt!'
									,'type'=>1);
				} else {
					return array('message'=>'Nem sikerült a jelszót módosítani!'
								,'type'=>0);
				}
				
			} else {
				return array('message'=>'Nem sikerült a jelszót módosítani!'
						,'type'=>0);
			}
		} else {
			return array('message'=>'Nem sikerült a jelszót módosítani!'
						,'type'=>0);
		}
	}
	
	public function modifyEmail($email){
		$connection = self::connectSQL();
		$result = $connection->query('UPDATE admin '
									.'SET email =\''.$connection->real_escape_string($email).'\''
									.'WHERE name = \''.$connection->real_escape_string($this->name).'\'');
									 
		if($connection->affected_rows > 0){
			$_SESSION['email'] = $email;
			return array('message'=>'Az email módosítása sikeres volt!'
						,'type'=>1);
		} else {
			return array('message'=>'Nem sikerült az emailt módosítani!'
						,'type'=>0);
		}
	}

}

?>