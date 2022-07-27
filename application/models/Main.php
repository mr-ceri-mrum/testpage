<?php

namespace application\models;

use application\core\Model;

class Main extends Model {
    public function postsCount() {
		return $this->db->column('SELECT COUNT(id) FROM posts');
	}

    public function postsList($route) 
	{
		$max = 10;
		$params = [
			'max' => $max,
			'start' => ((($route['page'] ?? 1) - 1) * $max),
		];
		return $this->db->row('SELECT * FROM posts ORDER BY id DESC LIMIT :start, :max', $params);
	}

	public function checkStatuslessons($vars, $session)
	{
		if($vars <= $session){
			return true;
		}
		// $params = [
		// 	'vars' => $vars,
		// ];
		// $sql = 'SELECT Predmet.PredmetID, Uchebnick, UchebnickName FROM Predmet INNER JOIN Uchebnik ON Predmet.UchebnikID = Uchebnik.UchebnikID';
		// $this->db->column(
		// 	'SELECT accounts.statuslessons, posts.statuslessons  FROM posts INNER JOIN posts ON accounts.statuslessons = posts.statuslessons', $params
		// );
	}

	public function updateStatuslessons($session)
	{
		$session = 1;
	}
}