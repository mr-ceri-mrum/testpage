<?php

namespace application\controllers;

use application\core\Controller;
use application\models\Account;
use application\models\Admin;

class AccountController extends Controller {

	// Регистрация

	public function registerAction()
	{
		if (!empty($_POST)) {
			if (!$this->model->validate(['email', 'login', 'wallet', 'password', 'ref'], $_POST)) {
				$this->view->message('error', $this->model->error);
			}
			elseif ($this->model->checkEmailExists($_POST['email'])) {
				$this->view->message('error', 'Этот E-mail уже используется');
			}
			elseif (!$this->model->checkLoginExists($_POST['login'])) {
				$this->view->message('error', $this->model->error);
			}
			$this->model->register($_POST);
			$this->view->message('success', 'Регистрация завершена, подтвердите свой E-mail');
		}
		$this->view->render('Регистрация');
	}

	public function confirmAction()
	{
		if (!$this->model->checkTokenExists($this->route['token'])) {
			$this->view->redirect('account/login');
		}
		$this->model->activate($this->route['token']);
		$this->view->render('Аккаунт активирован');
	}

	// Вход

	public function loginAction()
	{
		if (!empty($_POST)) {
			if (!$this->model->validate(['login', 'password'], $_POST)) {
				$this->view->message('error', 'Строка 41 указана не верно');
			}
			if (!$this->model->checkData($_POST['login'], $_POST['password'])) {
				$this->view->message('error', 'Логин или пароль указан неверно');
			// }elseif (!$this->model->checknumber($_POST['wallet'])){
			// 	$this->view->message('error', 'Такого номера не существует');
			//
			}
			$this->model->login($_POST['login']);
			$this->view->location('account/profile');
		}
		$this->view->render('Вход');
	}

	// Профиль

	public function profileAction()
	{
		$this->view->render('Профиль');
	}

	// public function lessonsAction() {
	// 	$accountModel = new Account;
	// 	if (!$accountModel->isPostExists($this->route['id'])) {
	// 		$this->view->errorCode(404);
	// 	}
	// 	$vars = [
	// 		'data' => $accountModel->postData($this->route['id'])[0],
	// 	];
	// 	$this->view->render('Пост', $vars);
	// }
	/*
	**********************editAction() 

	*/
	public function editAction()
	{
		if(!empty($_POST))
		{
			if (!$this->model->validate(['email', 'wallet'], $_POST)) {
				$this->view->message('error', $this->model->error);
			}
			$id = $this->model->checkEmailExists($_POST['email']);
			if ($id and $id != $_SESSION['account']['id']) {
				$this->view->message('error', 'Этот E-mail уже используется');
			}
			if (!empty($_POST['password']) and !$this->model->validate(['password'], $_POST)) {
				$this->view->message('error', $this->model->error);
			}
			$this->model->save($_POST);//сохраняет данные с $_POST в ДБ
			$this->view->message('error', 'Сохранено');
		}
		$this->view->render('Настройки профиля');
	}

	public function logoutAction() {
		unset($_SESSION['account']);
		$this->view->redirect('account/login');
	}

	// Восстановление пароля

	public function recoveryAction() 
	{
		if (!empty($_POST)) {
			if (!$this->model->validate(['email'], $_POST)) {
				$this->view->message('error', $this->model->error);
			}
			elseif (!$this->model->checkEmailExists($_POST['email'])) {
				$this->view->message('error', 'Пользователь не найден');
			}
			elseif (!$this->model->checkStatus('email', $_POST['email'])) {
				$this->view->message('error', $this->model->error);
			}
			$this->model->recovery($_POST);
			$this->view->message('success', 'Запрос на восстановление пароля отправлен на E-mail');
		}
		$this->view->render('Восстановление пароля');
	}

	public function resetAction() {
		if (!$this->model->checkTokenExists($this->route['token'])) {
			$this->view->redirect('account/login');
		}
		$password = $this->model->reset($this->route['token']);
		$vars = [
			'password' => $password,
		];
		$this->view->render('Пароль сброшен', $vars);
	}
}
