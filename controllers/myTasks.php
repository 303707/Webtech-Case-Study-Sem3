<?php
class myTasks extends Controller{
	protected function Index(){
		$viewmodel = new myTaskModel();
		$this->returnView($viewmodel->Index(), true);
	}

	protected function add(){
		if(!isset($_SESSION['is_logged_in'])){
			header('Location: '.ROOT_URL.'offers');
		}
		$viewmodel = new myTaskModel();
		$this->returnView($viewmodel->add(), true);
	}
}