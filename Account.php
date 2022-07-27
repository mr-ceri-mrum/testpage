<?php

namespace application\models;

use application\core\Model;


class Account extends Model {

	public function validate($input, $post) {
		$rules = [
			'email' => [
				'pattern' => '#^([a-z0-9_.-]{1,20}+)@([a-z0-9_.-]+)\.([a-z\.]{2,10})$#',
				'message' => 'E-mail адрес указан неверно',
			],
			'login' => [
				'pattern' => '#^[a-z0-9]{3,15}$#',
				'message' => 'Логин указан неверно (разрешены только латинские буквы и цифры от 3 до 15 символов',
			],
			'ref' => [
				'pattern' => '#^[a-z0-9]{3,15}$#',
				'message' => 'Логин пригласившего указан неверно',
			],
			'wallet' => [
				'pattern' => '#^[A-z0-9]{3,15}$#',
				'message' => 'Кошелек Perfect Money указан неверно',
			],
			'password' => [
				'pattern' => '#^[a-z0-9]{3,15}$#',
				'message' => 'Пароль указан неверно (разрешены только латинские буквы и цифры от 10 до 30 символов',

			],
		];
		foreach ($input as $val) {
			if (!isset($post[$val]) or !preg_match($rules[$val]['pattern'], $post[$val])) {
				$this->error = $rules[$val]['message'];
				return false;
			}
		}
		if (isset($post['ref'])) {
			if ($post['login'] == $post['ref']) {
				$this->error = 'Регистрация невозможна';
				return false;
			}
		}
		return true;
	}

	public function checkEmailExists($email) {
		$params = [
			'email' => $email,
		];
		return $this->db->column('SELECT id FROM accounts WHERE email = :email', $params);
	}

	public function checkLoginExists($login) 
	{
		$params = [
			'login' => $login,
		];
		if ($this->db->column('SELECT id FROM accounts WHERE login = :login', $params)) {
			$this->error = 'Этот логин уже используется';
			return false;
		}
		return true;
	}
	public function checknumber($wallet)
	{
		$params = [
			'wallet' => $wallet,
		];

		$number = $this->db->column('SELECT id FROM accounts WHERE wallet = :wallet', $params);
		if(!$number){
			return false;
		}
		return true;
	}

	public function checkTokenExists($token) {
		$params = [
			'token' => $token,
		];
		return $this->db->column('SELECT id FROM accounts WHERE token = :token', $params);
	}

	public function activate($token) {
		$params = [
			'token' => $token,
		];
		$this->db->query('UPDATE accounts SET status = 1, token = "" WHERE token = :token', $params);
	}

	public function checkRefExists($login) {
		$params = [
			'login' => $login,
		];
		return $this->db->column('SELECT id FROM accounts WHERE login = :login', $params);
	}

	public function createToken() {
		return substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyz', 30)), 0, 30);
	}

	public function register($post) 
	{
		$token = $this->createToken();
		if ($post['ref'] == 'none') {
			$ref = 0;
		}
		else {
			$ref = $this->checkRefExists($post['ref']);
			if (!$ref) {
				$ref = 0;
			}
		}
		$params = [
			'id' => '',
			'email' => $post['email'],
			'login' => $post['login'],
			'wallet' => $post['wallet'],
			'password' => password_hash($post['password'], PASSWORD_BCRYPT),
			'ref' => $ref,
			'refBalance' => 0,
			'token' => $token,
			'status' => 0,
			'name' => $post['name'],
			'lastname' => $post['lastname'],
			'points' => 0,
			'time' =>  time()
		];
		$this->db->query('INSERT INTO accounts VALUES (:id, :email, :login, :wallet, :password, :ref, :refBalance, :token, :status, :name, :lastname, :points, :time)', $params);
		mail($post['email'], 'Register', 'Confirm: '.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/account/confirm/'.$token);
	}

	public function checkData($login, $password) 
	{
		$params = [
			'login' => $login,
		];
		$hash = $this->db->column('SELECT password FROM accounts WHERE login = :login', $params);
		if (!$hash or !password_verify($password, $hash)) {
			return false;
		}
		return true;
	}

	

	public function checkStatus($type, $data) {
		$params = [
			$type => $data,
		];
		$status = $this->db->column('SELECT status FROM accounts WHERE '.$type.' = :'.$type, $params);
		if ($status != 1) {
			$this->error = 'Аккаунт ожидает подтверждения по E-mail';
			return false;
		}
		return true;
	}

	public function login($login) {
		$params = [
			'login' => $login,
		];
		$data = $this->db->row('SELECT * FROM accounts WHERE login = :login', $params);
		$_SESSION['account'] = $data[0];
	}

	public function recovery($post) {
		$token = $this->createToken();
		$params = [
			'email' => $post['email'],
			'token' => $token,
		];
		$this->db->query('UPDATE accounts SET token = :token WHERE email = :email', $params);
		mail($post['email'], 'Recovery', 'Confirm: '.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/account/reset/'.$token);
	}

	public function reset($token) {
		$new_password = $this->createToken();
		$params = [
			'token' => $token,
			'password' => password_hash($new_password, PASSWORD_BCRYPT),
		];
		$this->db->query('UPDATE accounts SET status = 1, token = "", password = :password WHERE token = :token', $params);
		return $new_password;
	}

	public function save($post) {
		$params = [
			'id' => $_SESSION['account']['id'],
			'email' => $post['email'],
			'wallet' => $post['wallet'],
		];
		if (!empty($post['password'])) {
			$params['password'] = ($post['password']);
			$sql = ',password = :password';
		}
		else {
			$sql = '';
		}
		foreach ($params as $key => $val) {
			$_SESSION['account'][$key] = $val;
		}
		$this->db->query('UPDATE accounts SET email = :email, wallet = :wallet'.$sql.' WHERE id = :id', $params);
	}
	public function postsCount()
	{
		return $this->db->column('SELECT COUNT(id) FROM users_posts');
	}

	public function postValidateUser($post)
	{
		$nameLen = iconv_strlen($post['name']);
		
		$textLen = iconv_strlen($post['text']);
		if ($nameLen < 3 or $nameLen > 100) {
			$this->error = 'Название должно содержать от 3 до 100 символов';
			return false;
		} 
		elseif ($textLen < 10 or $textLen > 5000) {
			$this->error = 'Текст должнен содержать от 10 до 5000 символов';
			return false;
		}else{
			return true;
		}
		
		return true;
	}
	public function postsList()
	{
		$max = 10;
		$params = [
			'max' => $max,
			'start' => ((($route['page'] ?? 1) - 1) * $max),
		];
		return $this->db->row('SELECT * FROM users_posts ORDER BY id DESC LIMIT :start, :max', $params);
	}
	public function postListUser($login)
	{
		//$max = 10;
		$params = [
			'login' => $login,
		];
		return $this->db->row('SELECT `text`, `name`, `post_login` FROM users_posts WHERE users_posts.post_login =:login', $params);
	}
	
	public function postAddUsersPost($post, $post_login)//id from session
	{
		$params = [
			'id' => '',
			'name' => $post['name'],
			'text' => $post['text'],
			'post_login' => $post_login,
		];
		$this->db->query('INSERT INTO users_posts VALUES (:id, :name, :text, :post_login )', $params);
		
		return $this->db->lastInsertId();
	}
	
}