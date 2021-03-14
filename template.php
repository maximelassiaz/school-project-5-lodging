<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="public/css/style.css">
    <title><?= $title ?? "Darkbnb" ;?></title>
</head>
<body>
    <?php // TODO : change button wether admin/client is connected or not ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-5">
        <a class="navbar-brand" href="#">Darkbnb</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <?php // TODO : add name or email in navbar when connected ?>
            <ul class="navbar-nav mr-5 ml-auto">
                <li class="nav-item mx-auto m-2">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <?php 
                    if ((isset($_SESSION['admin-connected']) && isset($_SESSION['admin-email'])) || (isset($_SESSION['client-connected']) && isset($_SESSION['client-email']))) {
                ?>
                <li class="nav-item mx-auto m-2">
                    <a class="nav-link" href="booking.php">Booking</a>
                </li>
                <?php 
                    }
                    if (isset($_SESSION['admin-connected']) && isset($_SESSION['admin-email'])) {
                ?>
                <li class="nav-item mx-auto m-2">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <?php 
                    }
                    if (empty($_SESSION)) { 
                ?>
                <li class="nav-item mx-auto">
                    <!-- Button trigger modal for login form -->
                    <button type="button" class="btn btn-secondary m-2" data-toggle="modal" data-target="#signinModal">
                        Sign in
                    </button>

                    <!-- Modal login form -->
                    <div class="modal fade" id="signinModal" tabindex="-1" aria-labelledby="signinModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="signinModalLabel">Log In</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <form method="POST" action="login.php">
                                        <div class="form-group">
                                            <label for="login-email">Email address</label>
                                            <input type="email" class="form-control" id="login-email" placeholder="Email" name="login-email">
                                        </div>
                                        <div class="form-group">
                                            <label for="login-password">Password</label>
                                            <input type="password" class="form-control" id="login-password" name="login-password">
                                        </div>
                                        <button type="submit" class="btn btn-primary" name="login-submit">Log In</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item mx-auto">
                    <!-- Button trigger modal for login form -->
                    <button type="button" class="btn btn-secondary m-2" data-toggle="modal" data-target="#signupModal">
                        Sign up
                    </button>

                    <!-- Modal login form -->
                    <div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="signupModalLabel">Sign up</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <form method="POST" action="">
                                        <div class="form-group">
                                            <label for="signup-fname">First name</label>
                                            <input type="text" class="form-control" id="signup-fname" name="signup-fname">
                                        </div>
                                        <div class="form-group">
                                            <label for="signup-lname">Last name</label>
                                            <input type="text" class="form-control" id="signup-lname" name="signup-lname">
                                        </div>
                                        <div class="form-group">
                                            <label for="signup-email">Email address</label>
                                            <input type="email" class="form-control" id="signup-email" placeholder="Email" name="signup-email">
                                        </div>
                                        <div class="form-group">
                                            <label for="signup-password">Password</label>
                                            <input type="password" class="form-control" id="signup-password" name="signup-password">
                                        </div>
                                        <div class="form-group">
                                            <label for="signup-password2">Repeat password</label>
                                            <input type="password" class="form-control" id="signup-password2" name="signup-password2">
                                        </div>
                                        <div class="form-group">
                                            <label for="signup-street">Street address</label>
                                            <input type="text" class="form-control" id="signup-street" name="signup-street">
                                        </div>
                                        <div class="form-group">
                                            <label for="signup-city">City</label>
                                            <input type="text" class="form-control" id="signup-city" name="signup-city">
                                        </div>
                                        <div class="form-group">
                                            <label for="signup-postal">Postal code</label>
                                            <input type="number" class="form-control" id="signup-postal" name="signup-postal">
                                        </div>
                                        <div class="form-group">
                                            <label for="signup-country">Country</label>
                                            <input type="text" class="form-control" id="signup-country" name="signup-country">
                                        </div>
                                        <button type="submit" class="btn btn-primary" name="signup-submit">Sign up</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <?php 
                    }
                    if (!empty($_SESSION)) {
                ?>
                <li class="nav-item mx-auto">
                    <a class="btn btn-primary" href="logout.php" role="button">Log out</a>
                </li>
                <?php
                    }
                ?>
            </ul>
        </div>
    </nav>

    <?= $content ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
</body>
</html>