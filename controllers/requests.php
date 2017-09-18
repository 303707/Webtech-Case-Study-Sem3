<?php
class Requests extends Controller{
	protected function Index(){
		$viewmodel = new RequestModel();
		$this->returnView($viewmodel->Index(), true);
	}

	protected function add(){
		if(!isset($_SESSION['is_logged_in'])){
			header('Location: '.ROOT_URL.'requests');
		}
		$viewmodel = new RequestModel();
		$this->returnView($viewmodel->add(), true);
	}
}