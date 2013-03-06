<?php

namespace Cmex\Modules\Admin\Controller;

use Authentication, View, Redirect, Input, Validator;

class User extends AdminController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('Admin::userindex', array(
			'users' => Authentication::getUserProvider()->findAll()
		));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		if($this->canCreate()) {
			return View::make('Admin::usercreate', array('groups' => Authentication::getGroupProvider()->findAll()));
		} else {
			return Redirect::to('admin/user')->with('error', 'Sie haben nicht die nötigen Rechte Benutzer zu erstellen!');
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if(!$this->canCreate())
			return json_encode(array('success' => 0, 'message' => 'Sie haben nicht die nötigen Rechte Benutzer zu erstellen!'));
		try
		{
			$user = Authentication::getUserProvider()->findByCredentials(array(
				'email' => Input::get('email')
			));
			return json_encode(array('success' => 0, 'message' => 'Die E-Mail ist bereits vergeben!'));
		}
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			$error = 0;
			$message = '';
			if(Input::has('lastName'))
				$lastName = Input::get('lastName');
			if(Input::has('firstName'))
				$firstName = Input::get('firstName');
			if(Input::has('email'))
				$email = Input::get('email');
			if(Input::has('password'))
				$password = Input::get('password');
			
			if(empty($lastName)) {
				$error = 1;
				$message.= 'Bitte geben Sie einen Nachnamen an!<br >';
			}
			if(empty($firstName)) {
				$error = 1;
				$message.= 'Bitte geben Sie einen Vornamen an!<br >';
			}
			if(empty($email)) {
				$error = 1;
				$message.= 'Bitte geben Sie eine E-Mail Adresse an!<br >';
			}
			if(empty($password)) {
				$error = 1;
				$message.= 'Bitte geben Sie ein Passwort an!<br >';
			}
			
			if(!$error) {
				Authentication::getUserProvider()->create(Input::only('lastName', 'firstName', 'email', 'password'));
				return json_encode(array('success' => 1, 'message' => 'Der Benutzer wurde hinzugefügt!'));
			} else {
				return json_encode(array('success' => 0, 'message' => $message));
			}
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @return Response
	 */
	public function show($id)
	{
		try
		{
			return Authentication::getUserProvider()->findById($id);
		}
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			return null;
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @return Response
	 */
	public function edit($id)
	{
		try
		{
			$user = Authentication::getUserProvider()->findById($id);
			if($this->canEdit($id)) {
				return View::make('Admin::useredit', array(
					'user' => $user,
					'groups' => Authentication::getGroupProvider()->findAll())
				);
			} else {
				return Redirect::to('admin/user')->with('error', 'Sie haben nicht die nötigen Rechte den Benutzer zu bearbeiten!');
			}
		}
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			return Redirect::to('admin/user')->with('error', 'Der Benutzer wurde nicht gefunden!');
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @return Response
	 */
	public function update($id)
	{
		try
		{
			$user = Authentication::getUserProvider()->findById($id);
			if($this->canEdit($id)) {
				$rules = array(
					array('lastName' => 'required|min:3'),
					array('firstName' => 'required|min:3'),
					array('email' => 'required|email|unique:users,email,' . $user->key()),
					array('password' => 'min:5|same:password_confirm')
				);
				
				$validator = Validator::make(Input::all(), $rules);
				
				if($validator->fails()) {
					$message = implode("<br />\n", $validator->messages()->all());
					return json_encode(array('success' => 0, 'message' => $message));
				}
				return json_encode(array('success' => 1, 'message' => "Jop"));
				
				$error = 0;
				$message = '';
				if(Input::has('lastName'))
					$lastName = Input::get('lastName');
				if(Input::has('firstName'))
					$firstName = Input::get('firstName');
				if(Input::has('email'))
					$email = Input::get('email');
				
				if(empty($lastName)) {
					$error = 1;
					$message.= 'Bitte geben Sie einen Nachnamen an!<br >';
				}
				if(empty($firstName)) {
					$error = 1;
					$message.= 'Bitte geben Sie einen Vornamen an!<br >';
				}
				if(empty($email)) {
					$error = 1;
					$message.= 'Bitte geben Sie eine E-Mail Adresse an!<br >';
				}
				
				if(!$error) {
					$user->last_name = $lastName;
					$user->first_name = $firstName;
					$user->email = $email;
					$user->activated = Input::get('activated', 0);
					$user->save();
					
					return json_encode(array('success' => 1, 'message' => 'Die Änderungen wurden gespeichert!'));
				} else {
					return json_encode(array('success' => 0, 'message' => $message));
				}
			} else {
				return json_encode(array('success' => 0, 'message' => 'Sie haben nicht die nötigen Rechte den Benutzer zu bearbeiten!'));
			}
		}
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			return json_encode(array('success' => 0, 'message' => 'Der Benutzer wurde nicht gefunden!'));
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		try
		{
			$user = Authentication::getUserProvider()->findById($id);
			$user->delete();
			return $user->delete();
		}
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			return null;
		}
	}
	
	private function canCreate() {
		return (Authentication::getUser()->hasAccess('admin') || Authentication::getUser()->hasAccess('superuser'));
	}
	
	private function canEdit($id) {
		try
		{
			$user = Authentication::getUserProvider()->findById($id);
			
			if(Authentication::getUser()->hasAccess('superuser'))
				return true;
			if(Authentication::getUser()->hasAccess('admin') && !$user->hasAccess('superuser'))
				return true;
		}
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			return false;
		}
		return false;
	}
	
	private function canDelete($id) {
		try
		{
			return Authentication::getUserProvider()->findById($id);
		}
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			return null;
		}
		return (Authentication::getUser()->hasAccess('admin') || Authentication::getUser()->hasAccess('superuser'));
	}
}