<?php
include "../db.php";

if(isset($_POST['save'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $course = $_POST['course'];

    $conn->query("INSERT INTO students(name,email,phone,course)
                  VALUES('$name','$email','$phone','$course')");

    echo "Student Added Successfully!";
}
?>

<h2>Add Student</h2>

<form method="post">
Name:<br>
<input type="text" name="name" required><br><br>

Email:<br>
<input type="text" name="email" required><br><br>

Phone:<br>
<input type="text" name="phone" required><br><br>

Course:<br>
<input type="text" name="course" required><br><br>

<button name="save">Save</button>
</form>
