<?php
include('db.php');

$user_id = NULL;

if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $car_model = $_POST['car_model'];
    $service = $_POST['service'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $sql = "INSERT INTO appointments (user_id, name, email, phone, carmodel, service, date, time, status, created_at) 
        VALUES ('$user_id', '$name', '$email', '$phone', '$car_model', '$service', '$date', '$time', 'Pending', NOW())";

    if(mysqli_query($conn, $sql)) {
        $success = "Appointment booked successfully!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book an Appointment</title>
<link rel="stylesheet" href="style.css">
<style>
body {
    font-family: Arial, sans-serif;
    background: #ffffff;
    margin: 0;
    padding: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.container {
    display: flex;
    width: 100%;
    max-width: 1100px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    overflow: hidden;
}


.left-side {
    flex: 1; /* slightly smaller */
    background: #222;
    color: #fff;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 40px 25px;
    text-align: center;
}

.left-side img {
    max-width: 150px;
    margin: 0 auto 20px;
}

.left-side p {
    font-size: 15px;
    line-height: 1.6;
}

.left-side .contact {
    font-size: 18px;
    font-weight: bold;
    margin-top: 20px;
}


.right-side {
    flex: 1.7; /* slightly larger */
    padding: 50px 40px;
}

.appointment-form h2 {
    text-align: center;
    margin-bottom: 25px;
    font-size: 28px;
}

.appointment-form input,
.appointment-form select {
    width: 100%;
    padding: 14px;
    margin: 10px 0;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 15px;
}

.appointment-form button {
    width: 100%;
    padding: 16px;
    background: #ff6600;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
    margin-top: 10px;
}

.appointment-form button:hover {
    background: #e65c00;
}

.success {
    color: green;
    text-align: center;
    margin-bottom: 10px;
}

.error {
    color: red;
    text-align: center;
    margin-bottom: 10px;
}

@media (max-width: 900px) {
    .container {
        flex-direction: column;
    }
    .left-side, .right-side {
        flex: 1 1 100%;
        padding: 30px;
    }
}
</style>
</head>
<body>

<div class="container">
   
    <div class="left-side">
        <div>
            <img src="logo.png" alt="Logo">
            <p>Welcome to our car paint & repair service. We specialize in high-quality finishes, quick turnarounds, and professional service. Book your appointment today!</p>
        </div>
        <div class="contact">
            +230 57940077
        </div>
    </div>

    
    <div class="right-side">
        <div class="appointment-form">
            <h2>Book an Appointment</h2>

            <?php if(isset($success)) { echo "<p class='success'>$success</p>"; } ?>
            <?php if(isset($error)) { echo "<p class='error'>$error</p>"; } ?>

            <form action="" method="POST">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="text" name="phone" placeholder="Phone Number" required>
                <input type="text" name="car_model" placeholder="Car Model" required>
                <select name="service" required>
                    <option value="">Select Service</option>
                    <option value="Full Car Paint">Full Car Paint</option>
                    <option value="Partial Paint">Partial Paint</option>
                    <option value="Scratch Repair">Scratch Repair</option>
                    <option value="Other">Other</option>
                </select>
                <input type="date" name="date" required>
                <input type="time" name="time" required>
                <button type="submit" name="submit">Book Now</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
