<?php 

require_once 'database.php';

class User {
  //attributes

  public $user_id;
  public $emp_id;
  public $user_role;
  public $email;
  public $password;
  public $f_name;
  public $l_name;
  public $m_name;
  public $profile_image;
  public $acad_rank;
  protected $db;

  function __construct() {
    $this->db = new Database();
  }
 
  //Methods

  function add() {
    $sql = "INSERT INTO user (user_id, emp_id, email, f_name, l_name, m_name, profile_image, acad_rank, user_role, password) VALUES
    (:user_id, :emp_id, :email, :f_name, :l_name, :m_name, :profile_image, :acad_rank, :user_role, :password);";
    
    $query = $this->db->connect()->prepare($sql);
    $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
    $query->bindParam(':password', $hashedPassword);
    $query->bindParam(':user_id', $this->user_id);
    $query->bindParam(':emp_id', $this->emp_id);
    $query->bindParam(':email', $this->email);
    $query->bindParam(':f_name', $this->f_name);
    $query->bindParam(':l_name', $this->l_name);
    $query->bindParam(':m_name', $this->m_name);
    $query->bindParam(':profile_image', $this->profile_image);
    $query->bindParam(':user_role', $this->user_role);

    if ($query->execute()) {
      return true;
    } else {
      return false;  // Return false in case of an error
    }
  }

  function edit_profile($user_id) {
    $sql = "UPDATE user SET profile_image=:profile_image WHERE user_id = :user_id;";

    $query = $this->db->connect()->prepare($sql);
    $query->bindParam(':profile_image', $this->profile_image);
    $query->bindParam(':user_id', $this->user_id);

    if ($query->execute()) {
      return true;
    } else {
      return false;
    }
  }

  function edit(){
    $sql = "UPDATE user SET f_name=:f_name, 
                            l_name=:l_name, 
                            m_name=:m_name
            WHERE user_id = :user_id;";

    $query=$this->db->connect()->prepare($sql);
    $query->bindParam(':f_name', $this->f_name);
    $query->bindParam(':l_name', $this->l_name);
    $query->bindParam(':m_name', $this->m_name);
    
    $query->bindParam(':user_id', $this->user_id);
    
    if($query->execute()){
      return true;
    }
    else{
      return false;
    }   
  }

  function is_email_exist() {
    $sql = "SELECT * FROM user WHERE email = :email;";
    $query = $this->db->connect()->prepare($sql);
    $query->bindParam(':email', $this->email);
    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        return true;
      }
    }
    return false;
  }

  function is_emp_id_exist() {
    $sql = "SELECT * FROM user WHERE emp_id = :emp_id;";
    $query = $this->db->connect()->prepare($sql);
    $query->bindParam(':emp_id', $this->emp_id);
    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        return true;
      }
    }
    return false;
  }

  function show() {
    $sql = "SELECT * FROM user ORDER BY user_id ASC;";
    $query = $this->db->connect()->prepare($sql);
    $data = null;
    if ($query->execute()) {
        $data = $query->fetchAll();
    }
    return $data;
  }

  function fetch($record_user_id) {
    $sql = "SELECT * FROM user WHERE user_id = :user_id;";
    $query = $this->db->connect()->prepare($sql);
    $query->bindParam(':user_id', $record_user_id);
    if ($query->execute()) {
      $data = $query->fetch();
    }
    return $data;
  }
}

?>