<?php

namespace application\controllers;

use application\core\Controller;

use application\lib\PostsPagination;
use application\models\Admin;


class MainController extends Controller {

	public function indexAction() {
		 
		$this->view->render('Главная страница');
	}
	
	public function postlistAction() 
	{	// Список
		$pagination = new PostsPagination($this->route, $this->model->postsCount());
		$vars = [
			'pagination' => $pagination->get(),
			'list' => $this->model->postsList($this->route),//дотягиваеться до Модуля
		];
		$this->view->render('Posts', $vars);
	}

	public function postAction() 
	{
		$adminModel = new Admin;
		if (!$adminModel->isPostExists($this->route['id'])) {
			$this->view->errorCode(404);
		}
		//view data from DB 'posts'
		$vars = [
			'data' => $adminModel->postData($this->route['id'])[0],
		];
		
		$this->view->render('Пост', $vars);
	}	
	
}