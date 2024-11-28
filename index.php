<?php

$conn = mysqli_connect('localhost', 'root', '', 'votingSystem') or die('connection failed');

if (isset($_POST['submit'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $time = $_POST['time']; // Updated to handle time input
    $date = $_POST['date'];

    $insert = mysqli_query($conn, "INSERT INTO `contact_form`(name, email, time, date) VALUES('$name','$email','$time','$date')") or die('query failed');

    if ($insert) {
        $message[] = 'Appointment made successfully!';
    } else {
        $message[] = 'Appointment failed';
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
            <a href="#home">Home</a>
            <a href="#about">About</a>
            <a href="#rule">Rule</a>
            <a href="#staff">Staff</a>
            <a href="#appointment">Appointment</a>
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

    <!-- icons section starts  -->
    <section class="icons-container">

        <div class="icons">
            <i class="fas fa-globe"></i>
            <h3>100+</h3>
            <p>Workers</p>
        </div>

        <div class="icons">
            <i class="fas fa-users"></i>
            <h3>3000+</h3>
            <p>Participant</p>
        </div>

        <div class="icons">
            <i class="fa fa-map-marker"></i>
            <h3>50+</h3>
            <p>location</p>
        </div>

        <div class="icons">
            <i class="fas fa-fire-alt"></i>
            <h3>5+</h3>
            <p>Events</p>
        </div>
    </section>
    <!-- icons section ends -->

    <!-- about section starts  -->
    <section class="about" id="about">

        <h1 class="heading"> <span>about</span> us </h1>

        <div class="row">

            <div class="image">
                <img src="image/voting.svg" alt="">
            </div>

            <div class="content">
                <h3>TARUMT Voting System for Electing the Next President</h3>
                <p>The TARUMT Voting System enables students to securely and efficiently vote for the next Malaysia
                    President.</p>
                <p>Featuring fingerprint biometric verification, scheduled voting, and real-time result updates, it
                    ensures a fair, transparent, and modern election process for all TARUMT students.</p>
                <a href="#" class="btn"> learn more <span class="fas fa-chevron-right"></span> </a>
            </div>

        </div>
    </section>
    <!-- about section ends -->

    <!-- rules section starts  -->
    <section class="rules" id="rule">
        <h1 class="heading"> TARUMT <span>Rule</span> </h1>

        <div class="box-container">

            <div class="box">
                <img src="image/rule1.png" style=" border-radius: 4px; padding: 5px; width: 220px;" alt="TARUMT Logo">
                <h3>Eligibility to Vote</h3>
                <p>If you are 21 years old or older, register as a voter at any Election Commission (EC) office or post
                    office.</p>
                <a href="#" class="btn"> learn more <span class="fas fa-chevron-right"></span> </a>
            </div>

            <div class="box">
                <img src="image/rule2.png" style=" border-radius: 4px; padding: 5px; width: 220px;" alt="TARUMT Logo">
                <h3>Verify Voter Details</h3>
                <p>Check your voter details on the Election Commission's website or contact us.</p>
                <a href="#" class="btn"> learn more <span class="fas fa-chevron-right"></span> </a>
            </div>

            <div class="box">
                <img src="image/rule3.png" style=" border-radius: 4px; padding: 5px; width: 220px;" alt="TARUMT Logo">
                <h3>Locate Your Polling Station</h3>
                <p>After parliament is dissolved, check your designated polling station on the Election Commission's
                    website.</p>
                <a href="#" class="btn"> learn more <span class="fas fa-chevron-right"></span> </a>
            </div>

            <div class="box">
                <img src="image/rule4.png" style=" border-radius: 4px; padding: 5px; width: 220px;" alt="TARUMT Logo">
                <h3>Arrive Early on Election Day</h3>
                <p>Be at the polling station early to avoid long queues and ensure your IC number is verified.</p>
                <a href="#" class="btn"> learn more <span class="fas fa-chevron-right"></span> </a>
            </div>

            <div class="box">
                <img src="image/rule5.png" style=" border-radius: 4px; padding: 5px; width: 220px;" alt="TARUMT Logo">
                <h3>Verification by Polling Clerks</h3>
                <p>Upon entering the polling station, polling clerks will record your name and IC number and mark you
                    off the electoral list.</p>
                <a href="#" class="btn"> learn more <span class="fas fa-chevron-right"></span> </a>
            </div>

            <div class="box">
                <img src="image/rule6.png" style=" border-radius: 4px; padding: 5px; width: 220px;" alt="TARUMT Logo">
                <h3>Indelible Ink Check</h3>
                <p>A second officer will check your hands for marks then dip your finger in indelible ink to confirm
                    you've voted.</p>
                <a href="#" class="btn"> learn more <span class="fas fa-chevron-right"></span> </a>
            </div>

            <div class="box">
                <img src="image/rule7.png" style=" border-radius: 4px; padding: 5px; width: 220px;" alt="TARUMT Logo">
                <h3>Marking Your Votesss</h3>
                <p>In the voting booth, mark "X" next to your chosen candidate. Avoid marking outside the box to ensure
                    your vote is valid.</p>
                <a href="#" class="btn"> learn more <span class="fas fa-chevron-right"></span> </a>
            </div>

            <div class="box">
                <img src="image/rule8.png" style=" border-radius: 4px; padding: 5px; width: 220px;" alt="TARUMT Logo">
                <h3>Submit Your Ballot Paper</h3>
                <p>Fold your ballot paper, then place it in the appropriate ballot boxes for parliamentary.</p>
                <a href="#" class="btn"> learn more <span class="fas fa-chevron-right"></span> </a>
            </div>

        </div>
    </section>
    <!-- rules section ends -->

    <!-- staff section starts  -->
    <section class="staffs" id="staff">

        <h1 class="heading"> our <span>staff</span> </h1>

        <div class="box-container">

            <div class="box">
                <img src="image/bk1.png" alt="">
                <h3>Boon Kang</h3>
                <span>IT management</span>
                <div class="share">
                    <a href="#" class="fab fa-facebook-f"></a>
                    <a href="#" class="fab fa-twitter"></a>
                    <a href="#" class="fab fa-instagram"></a>
                    <a href="#" class="fab fa-linkedin"></a>
                </div>
            </div>

            <div class="box">
                <img src="image/pz.png" alt="">
                <h3>Pang Zhi Kean</h3>
                <span>IT management</span>
                <div class="share">
                    <a href="#" class="fab fa-facebook-f"></a>
                    <a href="#" class="fab fa-twitter"></a>
                    <a href="#" class="fab fa-instagram"></a>
                    <a href="#" class="fab fa-linkedin"></a>
                </div>
            </div>

            <div class="box">
                <img src="image/joe.png" alt="">
                <h3>Joe</h3>
                <span>IT management</span>
                <div class="share">
                    <a href="#" class="fab fa-facebook-f"></a>
                    <a href="#" class="fab fa-twitter"></a>
                    <a href="#" class="fab fa-instagram"></a>
                    <a href="#" class="fab fa-linkedin"></a>
                </div>
            </div>

            <div class="box">
                <img src="image/qw.png" alt="">
                <h3>Qi wei</h3>
                <span>IT management</span>
                <div class="share">
                    <a href="#" class="fab fa-facebook-f"></a>
                    <a href="#" class="fab fa-twitter"></a>
                    <a href="#" class="fab fa-instagram"></a>
                    <a href="#" class="fab fa-linkedin"></a>
                </div>
            </div>

            <div class="box">
                <img src="image/royce.png" alt="">
                <h3>Royce</h3>
                <span>IT management</span>
                <div class="share">
                    <a href="#" class="fab fa-facebook-f"></a>
                    <a href="#" class="fab fa-twitter"></a>
                    <a href="#" class="fab fa-instagram"></a>
                    <a href="#" class="fab fa-linkedin"></a>
                </div>
            </div>

            <div class="box">
                <img src="image/tiff.png" alt="">
                <h3>Tiffany</h3>
                <span>IT management</span>
                <div class="share">
                    <a href="#" class="fab fa-facebook-f"></a>
                    <a href="#" class="fab fa-twitter"></a>
                    <a href="#" class="fab fa-instagram"></a>
                    <a href="#" class="fab fa-linkedin"></a>
                </div>
            </div>

            <div class="box">
                <img src="image/qw1.png" alt="">
                <h3>Qi Wei again</h3>
                <span>IT management</span>
                <div class="share">
                    <a href="#" class="fab fa-facebook-f"></a>
                    <a href="#" class="fab fa-twitter"></a>
                    <a href="#" class="fab fa-instagram"></a>
                    <a href="#" class="fab fa-linkedin"></a>
                </div>
            </div>

            <div class="box">
                <img src="image/bk2.png" alt="">
                <h3>Boon kang fat</h3>
                <span>IT management</span>
                <div class="share">
                    <a href="#" class="fab fa-facebook-f"></a>
                    <a href="#" class="fab fa-twitter"></a>
                    <a href="#" class="fab fa-instagram"></a>
                    <a href="#" class="fab fa-linkedin"></a>
                </div>
            </div>
        </div>
    </section>
    <!-- staff section ends -->

    <!-- appointmenting section starts   -->
    <section class="appointment" id="appointment">

        <h1 class="heading"> <span>appointment</span> now </h1>

        <div class="row">

            <div class="image">
                <img src="image/appointment.png" alt="">
            </div>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <?php
                if (isset($message)) {
                    foreach ($message as $message) {
                        echo '<p class ="message">' . $message . '</p>';
                    }
                }
                ?>

                <h3>Make Appointment</h3>
                <input type="text" name="name" placeholder="Your name" class="box" required>
                <input type="time" name="time" placeholder="Select time" class="box" required> <!-- Updated to time input -->
                <input type="email" name="email" placeholder="Your email" class="box" required>
                <input type="date" name="date" class="box" required>
                <input type="submit" name="submit" value="Appointment Now" class="btn">
            </form>
        </div>
    </section>
    <!-- appointmenting section ends -->

    <!-- review section starts  -->
    <section class="review" id="review">

        <h1 class="heading"> Student <span>review</span> </h1>

        <div class="box-container">

            <div class="box">
                <img src="image/pic-1.jpg" alt="">
                <h3>win coder</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <p class="text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam sapiente nihil
                    aperiam? Repellat sequi nisi aliquid perspiciatis libero nobis rem numquam nesciunt alias sapiente
                    minus voluptatem, reiciendis consequuntur optio dolorem!</p>
            </div>

            <div class="box">
                <img src="image/pic-1.jpg" alt="">
                <h3>win coder</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <p class="text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam sapiente nihil
                    aperiam? Repellat sequi nisi aliquid perspiciatis libero nobis rem numquam nesciunt alias sapiente
                    minus voluptatem, reiciendis consequuntur optio dolorem!</p>
            </div>

            <div class="box">
                <img src="image/pic-1.jpg" alt="">
                <h3>win coder</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <p class="text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam sapiente nihil
                    aperiam? Repellat sequi nisi aliquid perspiciatis libero nobis rem numquam nesciunt alias sapiente
                    minus voluptatem, reiciendis consequuntur optio dolorem!</p>
            </div>
        </div>
    </section>
    <!-- review section ends -->

    <!-- blogs section starts  -->
    <section class="blogs" id="blogs">

        <h1 class="heading"> our <span>blogs</span> </h1>

        <div class="box-container">

            <div class="box">
                <div class="image">
                    <img src="image/event1.png" alt="">
                </div>
                <div class="content">
                    <div class="icon">
                        <a href="#"> <i class="fas fa-calendar"></i> 21 november, 2022 </a>
                        <a href="#"> <i class="fas fa-user"></i> by win coder </a>
                    </div>
                    <h3>blog title win coder goes here</h3>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Provident, eius.</p>
                    <a href="#" class="btn"> learn more <span class="fas fa-chevron-right"></span> </a>
                </div>
            </div>

            <div class="box">
                <div class="image">
                    <img src="image/event2.png" alt="">
                </div>
                <div class="content">
                    <div class="icon">
                        <a href="#"> <i class="fas fa-calendar"></i> 21 november, 2022 </a>
                        <a href="#"> <i class="fas fa-user"></i> by win coder </a>
                    </div>
                    <h3>blog title win coder goes here</h3>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Provident, eius.</p>
                    <a href="#" class="btn"> learn more <span class="fas fa-chevron-right"></span> </a>
                </div>
            </div>

            <div class="box">
                <div class="image">
                    <img src="image/event3.png" alt="">
                </div>
                <div class="content">
                    <div class="icon">
                        <a href="#"> <i class="fas fa-calendar"></i> 21 november, 2022 </a>
                        <a href="#"> <i class="fas fa-user"></i> by win coder </a>
                    </div>
                    <h3>blog title win coder goes here</h3>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Provident, eius.</p>
                    <a href="#" class="btn"> learn more <span class="fas fa-chevron-right"></span> </a>
                </div>
            </div>
            <div class="box">
                <div class="image">
                    <img src="image/event1.png" alt="">
                </div>
                <div class="content">
                    <div class="icon">
                        <a href="#"> <i class="fas fa-calendar"></i> 21 november, 2022 </a>
                        <a href="#"> <i class="fas fa-user"></i> by win coder </a>
                    </div>
                    <h3>blog title win coder goes here</h3>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Provident, eius.</p>
                    <a href="#" class="btn"> learn more <span class="fas fa-chevron-right"></span> </a>
                </div>
            </div>
            <div class="box">
                <div class="image">
                    <img src="image/event2.png" alt="">
                </div>
                <div class="content">
                    <div class="icon">
                        <a href="#"> <i class="fas fa-calendar"></i> 21 november, 2022 </a>
                        <a href="#"> <i class="fas fa-user"></i> by win coder </a>
                    </div>
                    <h3>blog title win coder goes here</h3>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Provident, eius.</p>
                    <a href="#" class="btn"> learn more <span class="fas fa-chevron-right"></span> </a>
                </div>
            </div>
            <div class="box">
                <div class="image">
                    <img src="image/event3.png" alt="">
                </div>
                <div class="content">
                    <div class="icon">
                        <a href="#"> <i class="fas fa-calendar"></i> 21 november, 2022 </a>
                        <a href="#"> <i class="fas fa-user"></i> by win coder </a>
                    </div>
                    <h3>blog title win coder goes here</h3>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Provident, eius.</p>
                    <a href="#" class="btn"> learn more <span class="fas fa-chevron-right"></span> </a>
                </div>
            </div>
        </div>
    </section>
    <!-- blogs section ends -->

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