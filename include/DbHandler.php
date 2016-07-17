<?php

/**
 * Class to handle all db operations
 * This class will have CRUD methods for database tables
 *
 * @author Ravi Tamada
 * @link URL Tutorial link
 */
class DbHandler {

    private $conn;

    function __construct() {
        require_once dirname(__FILE__) . '/config.php';
        // opening db connection
         $this->conn = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die("Failed to connect to MySQL: " . mysql_error());
    }

    /* ------------- `users` table method ------------------ */

    /**
     * Creating new user
     * @param String $name User full name
     * @param String $email User login email id
     * @param String $password User login password
     */
    public function createUser($name,$uname,$email,$password) {
        $response = array();

        // First check if user already existed in db
        if (!$this->isUserExists($uname)) {
            // Generating password hash
            $options = [
				'cost' => 12, 
			];
			$password_hash = password_hash($password, PASSWORD_BCRYPT, $options);

            // Generating API key
            //$api_key = $this->generateApiKey();

            // insert query
            $stmt = $this->conn->prepare("INSERT INTO WebsiteUsers(fullname, userName, email,pass) values(?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $uname,$email, $password_hash);

            $result = $stmt->execute();

            $stmt->close();

            // Check for successful insertion
            if ($result) {
                // User successfully inserted
                return USER_CREATED_SUCCESSFULLY;
            } else {
                // Failed to create user
                return USER_CREATE_FAILED;
            }
        } else {
            // User with same email already existed in the db
            return USER_ALREADY_EXISTED;
        }

        return $response;
    }

    /**
     * Checking user login
     * @param String $email User login email id
     * @param String $password User login password
     * @return boolean User login status success/fail
     */
    public function checkLogin($uname, $password) {
        // fetching user by email
        $stmt = $this->conn->prepare("SELECT pass FROM WebsiteUsers WHERE userName = ?");

        $stmt->bind_param("s", $uname);

        $stmt->execute();

        $stmt->bind_result($password_hash);

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Found user with the email
            // Now verify the password

            $stmt->fetch();

            $stmt->close();

            if (password_verify($password, $password_hash)) {
                // User password is correct
                return TRUE;
            } else {
                // user password is incorrect
                return FALSE;
            }
        } else {
            $stmt->close();

            // user not existed with the email
            return FALSE;
        }
    }

    /**
     * Checking for duplicate user by email address
     * @param String $email email to check in db
     * @return boolean
     */
    public function isUserExists($uname) {
        $stmt = $this->conn->prepare("SELECT * from WebsiteUsers WHERE userName = ?");
        $stmt->bind_param("s", $uname);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    /**
     * Fetching user by email
     * @param String $email User email id
     */
    public function getUserByUser($uname) {
        $stmt = $this->conn->prepare("SELECT fullname, email, task , assignedby FROM WebsiteUsers WHERE userName = ?");
        $stmt->bind_param("s", $uname);
        if ($stmt->execute()) {
            // $user = $stmt->get_result()->fetch_assoc();
            $stmt->bind_result($name, $email, $task, $assignedby);
            $stmt->fetch();
            $user = array();
            $user["name"] = $name;
            $user["email"] = $email;
            $user["task"] = $task;
            $user["assignedby"] = $assignedby;
            $stmt->close();
            return $user;
        } else {
            return NULL;
        }
    }
	
	public function updatePass($pass,$user) {
		//$sql="UPDATE WebsiteUsers SET pass='$pass' WHERE userName='$user'";
		//$query = mysqli_query($this->conn,$sql) or die(mysql_error());
        $stmt = $this->conn->prepare("UPDATE WebsiteUsers SET pass=? WHERE userName=?");
        $stmt->bind_param("ss", $pass,$user);
        $stmt->execute();
        $num_affected_rows = mysqli_affected_rows($stmt);
		$stmt->close();
        return $num_affected_rows > 0;
    }

    public function deleteUser($uname) {
        $stmt = $this->conn->prepare("Delete FROM WebsiteUsers where userName= ?");
        $stmt->bind_param("s", $uname);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }
	
	private function checkAvail($user){
		$stmt = $this->conn->prepare("SELECT * from WebsiteUsers WHERE userName = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = mysqli_num_rows($stmt);
        $stmt->close();
        return $num_rows > 0;
	}

   public function updateUserTask($user,$task,$myname){
		
		$stmt = $this->conn->prepare("UPDATE WebsiteUsers SET task=? , assignedby=? WHERE userName=?");
		$stmt->bind_param("sss", $task,$myname,$user);
		$stmt->execute();
		$num_affected_rows = mysqli_affected_rows($stmt);
		$stmt->close();
		return $num_affected_rows > 0;
   }
   
   public function allUsers(){
		//$stmt = $this->conn->prepare("SELECT * FROM WebsiteUsers");
		//$stmt->execute();
		$sql="SELECT * FROM WebsiteUsers";
		$result = mysqli_query($this->conn, $sql);
		mysqli_close($this->conn);
		return $result;
   }
   
   /*public function getAllUsers() {
        $stmt = $this->conn->prepare("SELECT * FROM WebsiteUsers");
        $stmt->execute();
        $tasks = $stmt->fetchAll();
        $stmt->close();
        return $tasks;
    }*/

}

?>
