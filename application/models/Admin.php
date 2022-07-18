<?php

namespace application\models;

use application\core\Model;
use application\controllers\AdminController;
class Admin extends Model {

	public function loginValidate($post) {
		$config = require 'application/config/admin.php';
		if ($config['login'] != $post['login'] or $config['password'] != $post['password']) {
			$this->error = 'Логин или пароль указан неверно';
			return false;
		}
		return true;
	}

	public function historyCount() {
		return $this->db->column('SELECT COUNT(id) FROM history');
	}

	public function historyList($route) {
		$max = 10;
		$params = [
			'max' => $max,
			'start' => ((($route['page'] ?? 1) - 1) * $max),
		];
		$arr = [];
		$result = $this->db->row('SELECT * FROM history ORDER BY id DESC LIMIT :start, :max', $params);
		if (!empty($result)) {
			foreach ($result as $key => $val) {
				$arr[$key] = $val;
				$params = [
					'id' => $val['uid'],
				];
				$account = $this->db->row('SELECT login, email FROM accounts WHERE id = :id', $params)[0];
				$arr[$key]['login'] = $account['login'];
				$arr[$key]['email'] = $account['email'];
			}
		}
		return $arr;
	}

	public function withdrawRefList() {
		$arr = [];
		$result = $this->db->row('SELECT * FROM ref_withdraw ORDER BY id DESC');
		if (!empty($result)) {
			foreach ($result as $key => $val) {
				$arr[$key] = $val;
				$params = [
					'id' => $val['uid'],
				];
				$account = $this->db->row('SELECT login, wallet FROM accounts WHERE id = :id', $params)[0];
				$arr[$key]['login'] = $account['login'];
				$arr[$key]['wallet'] = $account['wallet'];
			}
		}
		return $arr;
	}

	public function withdrawTariffsList() {
		$arr = [];
		$result = $this->db->row('SELECT * FROM tariffs WHERE UNIX_TIMESTAMP() >= unixTimeFinish AND sumOut != 0 ORDER BY id DESC');
		if (!empty($result)) {
			foreach ($result as $key => $val) {
				$arr[$key] = $val;
				$params = [
					'id' => $val['uid'],
				];
				$account = $this->db->row('SELECT login, wallet FROM accounts WHERE id = :id', $params)[0];
				$arr[$key]['login'] = $account['login'];
				$arr[$key]['wallet'] = $account['wallet'];
			}
		}
		return $arr;
	}

	public function withdrawRefComplete($id) {
		$params = [
			'id' => $id,
		];
		$data = $this->db->row('SELECT uid, amount FROM ref_withdraw WHERE id = :id', $params);
		if (!$data) {
			return false;
		}
		$this->db->query('DELETE FROM ref_withdraw WHERE id = :id', $params);
		$data = $data[0];
		$params = [
			'id' => '',
			'uid' => $data['uid'],
			'unixTime' => time(),
			'description' => 'Выплата реферального вознаграждения произведена, сумма '.$data['amount'].' $',
		];
		$this->db->query('INSERT INTO history VALUES (:id, :uid, :unixTime, :description)', $params);
		return true;
	}

	public function withdrawTariffsComplete($id) {
		$params = [
			'id' => $id,
		];
		$data = $this->db->row('SELECT uid, sumOut FROM tariffs WHERE id = :id', $params);
		if (!$data) {
			return false;
		}
		$this->db->query('UPDATE tariffs SET sumOut = 0 WHERE id = :id', $params);
		$data = $data[0];
		$params = [
			'id' => '',
			'uid' => $data['uid'],
			'unixTime' => time(),
			'description' => 'Выплата по тарифу # '.$id.' произведена, сумма '.$data['sumOut'].' $',
		];
		$this->db->query('INSERT INTO history VALUES (:id, :uid, :unixTime, :description)', $params);
		return true;
	}

	public function tariffsCount() {
		return $this->db->column('SELECT COUNT(id) FROM tariffs');
	}

	public function tariffsList($route) {
		$max = 10;
		$params = [
			'max' => $max,
			'start' => ((($route['page'] ?? 1) - 1) * $max),
		];
		$arr = [];
		$result = $this->db->row('SELECT * FROM tariffs ORDER BY id DESC LIMIT :start, :max', $params);
		if (!empty($result)) {
			foreach ($result as $key => $val) {
				$arr[$key] = $val;
				$params = [
					'id' => $val['uid'],
				];
				$account = $this->db->row('SELECT login, email FROM accounts WHERE id = :id', $params)[0];
				$arr[$key]['login'] = $account['login'];
				$arr[$key]['email'] = $account['email'];
			}
		}
		return $arr;
	}

	/*
	******************************* postValidate********************
	*/
	 
	public function postValidate($post, $type) 
	{
		$nameLen = iconv_strlen($post['name']);
		$descriptionLen = iconv_strlen($post['description']);
		$textLen = iconv_strlen($post['text']);
		if ($nameLen < 3 or $nameLen > 100) {
			$this->error = 'Название должно содержать от 3 до 100 символов';
			return false;
		} elseif ($descriptionLen < 3 or $descriptionLen > 100) {
			$this->error = 'Описание должно содержать от 3 до 100 символов';
			return false;
		} elseif ($textLen < 10 or $textLen > 5000) {
			$this->error = 'Текст должнен содержать от 10 до 5000 символов';
			return false;
		}
		// if (empty($_FILES['img']['tmp_name']) and $type == 'add') {
		// 	$this->error = 'Изображение не выбрано';
		// 	return false;
		// }
		return true;
	}
	
	
	public function postAdd($post)
	{
		$params = [
			'id' => '',
			'name' => $post['name'],
			'description' => $post['description'],
			'text' => $post['text'],
		];
		$this->db->query('INSERT INTO posts VALUES (:id, :name, :description, :text)', $params);
		return $this->db->lastInsertId();
	}
	public function postDelete($id) {
		$params = [
			'id' => $id,
		];
		$this->db->query('DELETE FROM posts WHERE id = :id', $params);
		unlink('public/materials/'.$id.'.jpg');
	}

	public function isPostExists($id) {
		$params = [
			'id' => $id,
		];
		return $this->db->column('SELECT id FROM posts WHERE id = :id', $params);
	}
	
	public function postsCount() {
		return $this->db->column('SELECT COUNT(id) FROM posts');
	}
	public function postsList($route){
		$max = 10;
		$params = [
			'max' => $max,
			'start' => ((($route['page'] ?? 1) - 1) * $max),
		];
		return $this->db->row('SELECT * FROM posts ORDER BY id DESC LIMIT :start, :max', $params);
	}

	public function postData($id) {
		$params = [
			'id' => $id,
		];
		return $this->db->row('SELECT * FROM posts WHERE id = :id', $params);
	}

}