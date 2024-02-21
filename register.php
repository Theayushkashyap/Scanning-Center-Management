<?php
@include 'config.php';

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $repeatPassword = $_POST['repeatPassword'];
    $userType = $_POST['userType'];

    // Check if the email already exists
    $select = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $error[] = 'User already exists!';
    } else {
        if ($password != $repeatPassword) {
            $error[] = 'Passwords do not match!';
        } else {
            // Hash the password using bcrypt
            $hash = password_hash($password, PASSWORD_BCRYPT);

            // Insert the user details into the database
            $insert = "INSERT INTO users (name, age, gender, phone, address, email, password, userType) 
                       VALUES ('$name', '$age', '$gender', '$phone', '$address', '$email', '$hash', '$userType')";
            mysqli_query($conn, $insert);

            // Show a pop-up message using JavaScript
            echo '<script>alert("New ' . $userType . ' has been registered.");';
            // Redirect to the dashboard after the user clicks "OK"
            echo 'window.location.replace("/admin/index.html");</script>';
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Register</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form class="user" method="post" action="">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" name="name"
                                            placeholder="Full Name" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="number" class="form-control form-control-user" name="age"
                                            placeholder="Age" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="gender">Select Gender:</label>
                                    <select class="form-control" id="gender" name="gender" required>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="tel" class="form-control form-control-user" name="phone"
                                        placeholder="Phone Number" required>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control form-control-user" name="address"
                                        placeholder="Address" required></textarea>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" name="email"
                                        placeholder="Email Address" required>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user" name="password"
                                            placeholder="Password" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            name="repeatPassword" placeholder="Repeat Password" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="userType">Select User Type:</label>
                                    <select class="form-control" id="userType" name="userType" required>
                                        <option value="staff">Staff</option>
                                        <option value="admin">Admin</option>
                                        <option value="doctor">Doctor</option>
                                    </select>
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary btn-user btn-block">
                                    Register Account
                                </button>
                            </form>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
