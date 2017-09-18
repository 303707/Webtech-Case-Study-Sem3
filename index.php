<?php
// Start Session
session_start();

// Include Config
require('config.php');

require('classes/Messages.php');
require('classes/Bootstrap.php');
require('classes/Controller.php');
require('classes/Model.php');

require('controllers/home.php');
require('controllers/requests.php');
require('controllers/offers.php');
require('controllers/orders.php');
require('controllers/users.php');
require('controllers/myTasks.php');

require('models/home.php');
require('models/request.php');
require('models/offer.php');
require('models/order.php');
require('models/user.php');
require('models/myTask.php');

$bootstrap = new Bootstrap($_GET);
$controller = $bootstrap->createController();
if($controller){
	$controller->executeAction();
}