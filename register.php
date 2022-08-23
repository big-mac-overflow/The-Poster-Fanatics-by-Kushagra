<?php
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '1lcbtfmo'; 
$DATABASE_NAME = 'form';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if(mysqli_connect_error()) {                                                                                    //validation
    exit('Error connecting to the database' . mysqli_connect_error());
}

if(!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
    exit('Empty Field(s)');
}

if(empty($_POST['username'] || empty($_POST['password']) || empty($_POST['email']))) {
    exit('Values Empty');
}

if($stmt = $con->prepare('SELECT id, password FROM users WHERE username = ?')) {                               //check if username is taken
    $stmt->bind_param('s', $_POST['username']);                     
    $stmt->execute();
    $stmt->store_result();
    
    if($stmt->num_rows>0) {                                                                                     //case where username already exists
        echo('Username Already Exists, Try Again');
    }
    else{
        if($stmt = $con->prepare('INSERT INTO users (username, password, email) VALUES (?, ?, ?)')) {           //program for insertion of data in the database
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt->bind_param('sss', $_POST['username'], $_POST['password'], $_POST['email']);
            $stmt->execute();
            echo 'Successfully Registered, Welcome to the club!';
        }          
        else{
            echo 'Error Occurred';
        }
    }
    $stmt->close();
}
else{
    echo 'Error Ocurred';
}
$con->close();
?>