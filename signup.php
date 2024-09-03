<?php 
session_start();

if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1) {
  header('location: ./index.php');
} 
else if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 2) {
  header('location: ./admin/index.php');
}

require_once './tools/functions.php';
require_once './classes/user.class.php';

if(isset($_POST['signup'])) {
  $user = new User();
  //sanitize
  $user->password = htmlentities($_POST['password']);
  $user->emp_id = htmlentities($_POST['emp_id']);
  $user->email = htmlentities($_POST['email']);
  $user->f_name = htmlentities($_POST['f_name']);
  $user->l_name = htmlentities($_POST['l_name']);
  $user->m_name = htmlentities($_POST['m_name']);
  $user->user_role = 1;

  if (
    validate_field($user->emp_id) && !$user->is_emp_id_exist() &&
    validate_field($user->email) && !$user->is_email_exist() &&
    validate_field($user->f_name) &&
    validate_field($user->l_name) &&
    validate_field($user->m_name) &&
    validate_field($user->password) && validate_password($user->password) 
  ) {
    if($user->add()) {
      $message = 'Account is successfuly created.';
    }
    else {
			echo 'Something went wrong signing up.';
		}
  }

}


?>


<!DOCTYPE html>
<html lang="en">
<?php 
	$title = 'Signup';
	include_once './includes/head.php'
?>
<body class="signup">
  <main>
    <div class="container-fluid vh-100 d-flex justify-content-center align-items-center">
      <div class="signup-page">
        <p class="text-center">
          <img src="./img/wmsu_logo.png" alt="wmsu-logo" class="img-fluid">
        </p>
        <h1 class="fs-1 fw-bold my-3 mb-4 text-white text-center brand-color">MyWMSU</h1>
        <?php
				if (isset($_POST['signup']) && isset($message)) {
					echo "<script> alert('" . $message . "'); window.location.href='./login.php'; </script>";
				}
				?>
        <form action="#" method="post" onSubmit="return validate()">
          <div class="field-box">
            <div class="field">
              <label for="id">Employee ID</label>
              <input type="text" id="emp_id" name="emp_id" value="<?php if (isset($_POST['emp_id'])) {
																								echo $_POST['emp_id'];
																							} ?>">
              <?php
					    $user_emp_id = new User();
					    if (isset($_POST['emp_id'])) {
					    	$user_emp_id->emp_id = htmlentities($_POST['emp_id']);
					    } else {
					    	$user_emp_id->emp_id = '';
					    }
            
					    if (isset($_POST['emp_id']) && strcmp(validate_emp_id($_POST['emp_id']), 'success') != 0) {
					    ?>
					    	<p><?php echo validate_emp_id($_POST['emp_id']) ?></p>
					    <?php
					    } else if ($user_emp_id->is_emp_id_exist() && $_POST['emp_id']) {
					    ?>
					    	<p>Employee ID already exist</p>
					    <?php
					    }
					    ?>  
            </div>

            <div class="field">
              <label for="email">Email</label>
              <input type="email" id="email" name="email" value="<?php if (isset($_POST['email'])) {
																								echo $_POST['email'];
																							} ?>">
					    <?php
					    $user_email = new User();
					    if (isset($_POST['email'])) {
					    	$user_email->email = htmlentities($_POST['email']);
					    } else {
					    	$user_email->email = '';
					    }
            
					    if (isset($_POST['email']) && strcmp(validate_email($_POST['email']), 'success') != 0) {
					    ?>
					    	<p><?php echo validate_email($_POST['email']) ?></p>
					    <?php
					    } else if ($user_email->is_email_exist() && $_POST['email']) {
					    ?>
					    	<p>Email already exist</p>
					    <?php
					    }
					    ?>
            </div>

            <div class="field">
              <label for="password">Passwor</label>
              <input type="password" id="password" name="password" value="<?php if (isset($_POST['password'])) {
																												echo $_POST['password'];
																											} ?>" onkeyup='check();'>
						  <?php
						  if (isset($_POST['password']) && validate_password($_POST['password']) !== "success") {
						  ?>
						  	<div class="invalid-feedback pass-o">
						  		<?= validate_password($_POST['password']) ?>
						  	</div>
						  <?php
						  }
						  ?>
            </div>

            <div class="field">
              <label for="fname">First Name</label>
              <input type="text" name="fname" required>
            </div>
            <div class="field">
              <label for="lname">Last Name</label>
              <input type="text" name="lname" required>
            </div>
            <div class="field">
              <label for="mname">Middle Name</label>
              <input type="text" name="mname">
            </div>
            <div class="field">
              <label for="acadrank">Academic Rank</label>
              <select name="acadrank" id="acadrank">
                <option value="regularlecturer">Regular Lecturer</option>
                <option value="visitinglecture">Visiting Lecturer</option>
              </select>
            </div>
          </div>
            <button type="submit" class="btn d-flex p-3 justify-content-center">SINGUP</button>
            <div id="emailHelp" class="form-text d-flex justify-content-center">Already have an account? <a href="login.php"> Login</a></div>
        </form>    
      </div>
    </div>
  </main>
</body>
</html>