<?php
class Orders extends Controller{
    protected function Index(){
        $viewmodel = new orderModel();
        $this->returnView($viewmodel->Index(), true);
    }

    protected function add(){
        if(!isset($_SESSION['is_logged_in'])){
            header('Location: '.ROOT_URL.'orders');
        }
        $viewmodel = new orderModel();
        $this->returnView($viewmodel->add(), true);
    }
}