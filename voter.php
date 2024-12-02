<?php
session_start();  // Start the session to check user login status

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'votingSystem') or die('Connection failed');

if (!isset($_SESSION['valid'])) {
    echo '<p class="message">You must be logged in to make an appointment.</p>';
    exit(); // Stop the script if the user is not logged in
}

if (isset($_POST['submit'])) {

    // Collect form data and sanitize it
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $time = $_POST['time'];  // Time input
    $date = $_POST['date'];  // Date input

    // Get user ID from session to link with the appointment
    $user_id = $_SESSION['id'];  // Assuming the user ID is stored in the session

    // Insert the appointment details into the contact_form table
    $insert = mysqli_query($conn, "INSERT INTO `contact_form`(name, email, time, date, user_id) 
        VALUES('$name', '$email', '$time', '$date', '$user_id')") or die('Query failed');

    // Check if the appointment was successfully inserted
    if ($insert) {
        $message[] = 'Appointment made successfully!';
    } else {
        $message[] = 'Appointment failed.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TARUMT Voting System</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>
<style>
    /* open hours section start */
    .OpeningHours {
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
        position: relative;
        z-index: 1;
        padding: 5rem 0;
    }

    .OpeningHours::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        opacity: 0.7;
        z-index: -1;
        background-image: linear-gradient(180deg, #09090b 0%, #09090b 100%);
    }

    .OpeningHours .title {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 15px;
        text-align: center;
    }

    .OpeningHours .title h3 {
        color: #30fdff;
        font-family: "Cherish", Sans-serif;
        font-size: 52px;
        font-weight: 400;
        line-height: 1.2em;
        margin: 0;
    }

    .OpeningHours .title span {
        color: #ffffff;
        font-family: "Montserrat Alternates", Sans-serif;
        font-size: 30px;
        font-weight: 500;
        line-height: 1.2em;
        letter-spacing: -1.5px;
    }

    .OpeningHours .title p {
        color: #c6c6c6;
        font-family: "DM Sans", Sans-serif;
        font-size: 17px;
        font-weight: 400;
        line-height: 1.6em;
    }

    .OpeningHours .content {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 3rem;
        margin-top: 4rem;
        flex-wrap: wrap;
        padding: 0 2rem;
    }

    .OpeningHours .content .map {
        position: relative;
        flex: 1;
        min-width: 300px;
        height: 400px;
        border-radius: 8px;
        overflow: hidden;
    }

    .OpeningHours .content .map iframe {
        object-fit: cover;
        opacity: 0.9;
        min-width: 100%;
        height: 100%;
        border: none;
        outline: none;
    }

    .content .hours {
        flex: 1;
        min-width: 300px;
        background-color: rgba(0, 0, 0, 0.6);
        border-radius: 8px;
        padding: 2rem;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        color: #ffffff;
    }

    .content .hours .title {
        font-family: "Montserrat Alternates", Sans-serif;
        font-size: 46px;
        font-weight: 500;
        line-height: 1.2em;
        letter-spacing: 1px;
        border-bottom: 1px solid #fff;
        padding-bottom: 10px;
    }

    .content .hours ul {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .content .hours ul li {
        display: flex;
        justify-content: space-between;
        font-family: "Work Sans", Sans-serif;
        font-size: 18px;
        font-weight: 400;
    }

    .content .hours ul li span {
        color: #ffffff;
    }

    .content .hours ul li span:first-child {
        font-weight: 500;
    }

    /* open hours section end */

    /* General Styles */
    body {
        margin: 0;
        font-family: Arial, sans-serif;
    }

    /* Header Styling */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: lightblue;
        /* Turquoise background */
        padding: 10px 20px;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .logo img {
        width: 200px;
        max-width: 100%;
        height: auto;
    }

    /* Navbar Styling */
    .navbar {
        display: flex;
        list-style: none;
    }

    .navbar a {
        text-decoration: none;
        color: black;
        margin: 0 15px;
        font-size: 1rem;
        line-height: 50px;
        /* Center items vertically */
        transition: color 0.3s;
    }

    .navbar a:hover {
        color: #007BFF;
        /* Blue on hover */
    }

    /* Mobile Menu Button */
    #menu-btn {
        display: none;
        font-size: 24px;
        cursor: pointer;
    }

    /* Responsive Design */
    @media screen and (max-width: 768px) {
        .navbar {
            display: none;
            flex-direction: column;
            background-color: #40e0d0;
            position: absolute;
            top: 60px;
            right: 0;
            width: 100%;
            text-align: center;
        }

        .navbar.active {
            display: flex;
        }

        #menu-btn {
            display: block;
        }
    }
</style>

<body>

    <!-- header section starts  -->
    <header class="header">
        <a href="#" class="logo">
            <img src="image/tarumt.png" alt="TARUMT Logo">
        </a>
        <nav class="navbar">
            <a href="index.php">Home</a>
            <a href="#about">About</a>
            <a href="#rule">Rule</a>
            <a href="#staff">Staff</a>
            <a href="#appointment">Appointment</a>
            <a href="voter.php">Voter</a>
            <a href="#review">Review</a>
            <a href="#blogs">Blogs</a>
            <a href="register.php">Logout</a>
        </nav>
        <div id="menu-btn" class="fas fa-bars">☰</div>
    </header>
    <!-- header section ends -->

    <!-- home section starts  -->
    <section class="home" id="home">

        <div class="image">
            <img src="image/voting1.png" alt="">
        </div>

        <div class="content">
            <h3>TARUMT Voting System</h3>
            <p> The TARUMT Voting System is designed to empower students to elect the next Malaysia President using a
                secure, efficient, and transparent voting process. </p>
            <a href="#appointment" class="btn"> Appointment us <span class="fas fa-chevron-right"></span> </a>
        </div>

    </section>
    <!-- home section ends -->

    <!-- footer section starts  -->
    <section class="footer">
        <div class="box-container">

            <div class="box">
                <h3>quick links</h3>
                <a href="#home"> <i class="fas fa-chevron-right"></i> home </a>
                <a href="#about"> <i class="fas fa-chevron-right"></i> about </a>
                <a href="#rules"> <i class="fas fa-chevron-right"></i> rule </a>
                <a href="#staffs"> <i class="fas fa-chevron-right"></i> staffs </a>
                <a href="#appointment"> <i class="fas fa-chevron-right"></i> appointment </a>
                <a href="#review"> <i class="fas fa-chevron-right"></i> review </a>
                <a href="#blogs"> <i class="fas fa-chevron-right"></i> blogs </a>
            </div>

            <div class="box">
                <h3>our services</h3>
                <a href="#"> <i class="fas fa-chevron-right"></i> dental care </a>
                <a href="#"> <i class="fas fa-chevron-right"></i> message therapy </a>
                <a href="#"> <i class="fas fa-chevron-right"></i> cardioloty </a>
                <a href="#"> <i class="fas fa-chevron-right"></i> diagnosis </a>
                <a href="#"> <i class="fas fa-chevron-right"></i> ambulance service </a>
            </div>

            <div class="box">
                <h3>appointment info</h3>
                <a href="#"> <i class="fas fa-phone"></i> +8801688238801 </a>
                <a href="#"> <i class="fas fa-phone"></i> +8801782546978 </a>
                <a href="#"> <i class="fas fa-envelope"></i> wincoder9@gmail.com </a>
                <a href="#"> <i class="fas fa-envelope"></i> sujoncse26@gmail.com </a>
                <a href="#"> <i class="fas fa-map-marker-alt"></i> sylhet, bangladesh </a>
            </div>

            <div class="box">
                <h3>follow us</h3>
                <a href="#"> <i class="fab fa-faceappointment-f"></i> faceappointment </a>
                <a href="#"> <i class="fab fa-twitter"></i> twitter </a>
                <a href="#"> <i class="fab fa-twitter"></i> twitter </a>
                <a href="#"> <i class="fab fa-instagram"></i> instagram </a>
                <a href="#"> <i class="fab fa-linkedin"></i> linkedin </a>
                <a href="#"> <i class="fab fa-pinterest"></i> pinterest </a>
            </div>
        </div>
        <div class="credit"> created by <span>win coder</span> | all rights reserved </div>
    </section>
    <!-- footer section ends -->


    <!-- js file link  -->
    <script src="js/script.js"></script>
    <script>
    .navbar a:hover {
            color: #007BFF; /* Change color on hover */
        }
    </script>

</body>

</html>