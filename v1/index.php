<?php

require_once '../include/DbHandler.php';
require '../Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$app->post('/register', function() use ($app) {
            // check for required params
            //verifyRequiredParams(array('name', 'email', 'password'));

            $response = array();
			$name = $app->request->post('Name');
            $email = $app->request->post('Email');
			$user = $app->request->post('Username');
            $password = $app->request->post('Password');

            // validating email address
            validateEmail($email);

            $db = new DbHandler();
            $res = $db->createUser($name,$user, $email, $password);

            if ($res == USER_CREATED_SUCCESSFULLY) {
                $response["error"] = false;
                $response["message"] = "You are successfully registered";
            } else if ($res == USER_CREATE_FAILED) {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while registereing";
            } else if ($res == USER_ALREADY_EXISTED) {
                $response["error"] = true;
                $response["message"] = "Sorry, this email already existed";
            }
            // echo json response*/
            echoRespnse(201, $response);
        });

/**
 * User Login
 * url - /login
 * method - POST
 * params - email, password
 */
$app->post('/login', function() use ($app) {
            // check for required params
            //verifyRequiredParams(array('email', 'password'));

            // reading post params
            $uname = $app->request()->post('Username');
            $password = $app->request()->post('Password');
            $response = array();

            $db = new DbHandler();
            // check for correct email and password
            if ($db->checkLogin($uname, $password)) {
				$response["error"] = false;
            } else {
                // user credentials are wrong
                $response['error'] = true;
                $response['message'] = 'Login failed. Incorrect credentials';
            }

            echoRespnse(200, $response);
        });
		

$app->post('/session', function() use ($app) {
		$uname = $app->request()->post('Username');
		
		$response = array();
		$db = new DbHandler();
		
		$user = $db->getUserByUser($uname);
		
		if ($user != NULL) {
			$response["error"] = false;
			$response['name'] = $user['name'];
			$response['email'] = $user['email'];
			$response['task'] = $user['task'];
			$response['assignedby'] = $user['assignedby'];
		} else {
			// unknown error occurred
			$response['error'] = true;
			$response['message'] = "An error occurred. Please try again";
		}
		 echoRespnse(200, $response);
		});

/*
 * ------------------------ METHODS WITH AUTHENTICATION ------------------------
 */

/**
 * Listing all tasks of particual user
 * method GET
 * url /tasks          
 */
$app->get('/displaydirectory', function() use ($app){
        
            $response = array();
            $db = new DbHandler();
			$result=$db->allUsers();
			
            $response["error"] = false;
            $response["tasks"] = array();
            // looping through result and preparing tasks array
            while ($task = $result->fetch_assoc()) {
                $tmp = array();
                $tmp["userName"] = $task["userName"];
                $tmp["fullname"] = $task["fullname"];
                $tmp["email"] = $task["email"];
                $tmp["task"] = $task["task"];
				$tmp["assignedby"] = $task["assignedby"];
                array_push($response["tasks"], $tmp);
            }
            echoRespnse(200, $response);
        });
		
		
$app->put('/updatepass', function() use($app) {
            // check for required params
            //verifyRequiredParams(array('task', 'status'));
          
            $pass = $app->request->put('Password');
			$user = $app->request->put('Username');

            $db = new DbHandler();
            $response = array();

            // updating task
            $result = $db->updatePass($pass,$user);
            if ($result) {
                // task updated successfully
                $response["error"] = false;
                $response["message"] = "Task updated successfully";
            } else {
                // task failed to update
                $response["error"] = true;
                $response["message"] = "Task failed to update. Please try again!";
            }
            echoRespnse(200, $response);
        });

$app->put('/updateusertask', function() use($app) {		

		$user = $app->request->put('Username');
		$task = $app->request->put('Task');
		$myname = $app->request->put('Myname');
		
		$db = new DbHandler();
        $response = array();
		//$response["message"] = $user;
		$res=$db->isUserExists($user);
		if($res){
			$result = $db->updateUserTask($user,$task,$myname);
			
			if ($result) {
                // task updated successfully
                $response["error"] = false;
                $response["message"] = "Task updated successfully";
            } else {
                // task failed to update
                $response["error"] = true;
                $response["message"] = "Task failed to update. Please try again!";
            }
		}else{
			$response["error"] = true;
            $response["message"] = "Invalid Username";
		}
		echoRespnse(200, $response);
		});
		
$app->delete('/deleteuser', function() use($app) {
			$uname = $app->request()->post('Username');
            $db = new DbHandler();
            $response = array();
            $result = $db->deleteUser($uname);
            if ($result) {
                // task deleted successfully
                $response["error"] = false;
                $response["message"] = "Task deleted succesfully";
            } else {
                // task failed to delete
                $response["error"] = true;
                $response["message"] = "Task failed to delete. Please try again!";
            }
            echoRespnse(200, $response);
        });


/**
 * Validating email address
 */
function validateEmail($email) {
    $app = \Slim\Slim::getInstance();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["error"] = true;
        $response["message"] = 'Email address is not valid';
        echoRespnse(400, $response);
        $app->stop();
    }
}

/**
 * Echoing json response to client
 * @param String $status_code Http response code
 * @param Int $response Json response
 */
function echoRespnse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response);
}

$app->run();
?>