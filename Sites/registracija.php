<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <meta name="viewport" content="width=device-width; initial-scale=1.0;" />
    <meta name="author" content="Daniel Gluhak">
    <meta name="description" content="Example of a local news page and with several reviews of video games for the subject of web application programming on Zagreb University of Applied Sciences" />
    <meta name="keywords" content="video game, review, example, pwa,latest news, games, web application programming" />

    <!-- Favicon for website -->
    <link rel="icon" href="../Pictures/logo.png" type="image/png" sizes="32x32" />

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous" />
    <!--jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <!-- Bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" />
    <!--Outside css-->
    <link href="style.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />

    <title>GAMIZ</title>
</head>

<body>
    <?php
    include "connect.php";
    session_start();
    ?>
    <header>
        <div class="website-title">
            <h1>GAMIZ</h1>
        </div>
        <nav role="navigation">
            <ul>
                <li class="temporarily-active" onmouseover="highligh()" onmouseout="outhiglight()"><a href="index.php">HOME</a></li>
                <li class="temporarily-active" onmouseover="highligh()" onmouseout="outhiglight()"><a href="kategorija.php?id=Review">REVIEWS</a></li>
                <li class="temporarily-active" onmouseover="highligh()" onmouseout="outhiglight()"><a href="kategorija.php?id=News">NEWS</a></li>
                <?php
                if (!isset($_SESSION['$username'])) {
                    print '<li><a id="main-active" href="registracija.php">SIGN UP</a></li>
                    <li class="temporarily-active3" onmouseover="highligh()" onmouseout="outhiglight()"><a href="administracija.php">ADMINISTRATION</a></li>';
                } else {
                    header("Location:administracija.php");
                    exit;
                }
                ?>
            </ul>
        </nav>
        <div class="clear"></div>
        <!-- Navigation hover -->
        <script>
            function highligh() {
                document.getElementById("main-active").style.color = "crimson";
                document.getElementById("main-active").style.backgroundColor = "transparent";
            }

            function outhiglight() {
                document.getElementById("main-active").style.color = "white";
                document.getElementById("main-active").style.backgroundColor = "crimson";

            }
        </script>
    </header>

    <?php
    $RegisterUser = false;
    $userExist = false;
    if (isset($_POST['Register'])) {
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $PasswordHash = password_hash($password, CRYPT_BLOWFISH);
        $level = 0;
        $sql = "SELECT username FROM korisnik WHERE username=?";
        $stmt = mysqli_stmt_init($dbc);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, 's', $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
        }
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $message = "User already exists!";
            $userExist = true;
        } else {
            $sql = "INSERT INTO korisnik (ime,prezime,username,password,razina) VALUES (?,?,?,?,?)";
            $stmt = mysqli_stmt_init($dbc);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, 'ssssd', $name, $surname, $username, $PasswordHash, $level);
                mysqli_stmt_execute($stmt);
                $RegisterUser = true;
            }
        }
        mysqli_close($dbc);
    }



    ?>
    <main role="main" class="container-fluid Register">
        <div class="div-center">
            <div class="content">
                <?php
                if ($RegisterUser == true) {
                    echo "<p>You have been succesfully registered</p>";
                } else {

                ?>
                    <h3>Sign in Form</h3>
                    <form action="" enctype="multipart/form-data" method="post" class="SignForm">

                        <span id="msgName" class="msgColor"></span>
                        <div class="form-floating col-md-10 col-sm-10">
                            <input name="name" type="text" class="form-control" id="floatingInput1" placeholder="Type your name">
                            <label for="floatingInput1" id="label1">Name *</label>
                        </div>

                        <span id="msgSurname" class="msgColor"></span>
                        <div class="form-floating col-md-10 col-sm-10">
                            <input name="surname" type="text" class="form-control" id="floatingInput2" placeholder="Type your surname">
                            <label for="floatingInput2" id="label2">Surname *</label>
                        </div>


                        <span id="msgUsername" class="msgColor"></span>
                        <?php
                        if ($userExist == true) {
                            echo '<br><span class="msgColor">' . $message . '</span>';
                        }
                        ?>
                        <div class="form-floating col-md-10 col-sm-10">
                            <input name="username" type="text" class="form-control" id="floatingInput3" placeholder="Type your username">
                            <label for="floatingInput3" id="label3">Username *</label>
                        </div>

                        <span id="msgPassword" class="msgColor"></span>
                        <div class="form-floating col-sm-10 col-md-10">
                            <input name="password" type="password" class="form-control" id="floatingPassword1" placeholder="Type your password">
                            <label for="floatingPassword1" id="label4" class="form-label">Password *</label>
                        </div>

                        <span id="msgCheckPassword" class="msgColor"></span>
                        <div class="form-floating col-sm-10 col-md-10">
                            <input name="check_password" type="password" class="form-control" id="floatingPassword2" placeholder="Confirm your password">
                            <label for="floatingPassword2" id="label5" class="form-label">Check your password *</label>
                        </div>

                        <button id="send" name="Register" type="submit" class="login col-md-8 col-sm-8 btn btn-danger">Register</button>
                    </form>

                    <!-- Check form registration -->
                    <script type="text/javascript">
                        document.getElementById("send").onclick = function(event) {
                            var SendForm = true;

                            /* Check name */
                            var InputName = document.getElementById("floatingInput1");
                            var name = document.getElementById("floatingInput1").value;
                            if (name.length == 0) {
                                var SendForm = false;
                                InputName.style.borderBottom = "2px dashed #fcd303";
                                document.getElementById("label1").style.color = "#fcd303";
                                document.getElementById("msgName").innerHTML = "You need to enter your name!";
                            } else {
                                InputName.style.borderBottom = "2px solid green";
                                document.getElementById("label1").style.color = "green";
                                document.getElementById("msgName").innerHTML = "";
                            }

                            /* Check surname */
                            var InputSurname = document.getElementById("floatingInput2");
                            var surname = document.getElementById("floatingInput2").value;
                            if (surname.length == 0) {
                                var SendForm = false;
                                InputSurname.style.borderBottom = "2px dashed #fcd303";
                                document.getElementById("label2").style.color = "#fcd303";
                                document.getElementById("msgSurname").innerHTML = "You need to enter your surname!";
                            } else {
                                InputSurname.style.borderBottom = "2px solid green";
                                document.getElementById("label2").style.color = "green";
                                document.getElementById("msgSurname").innerHTML = "";
                            }

                            /* Check username */
                            var InputUsername = document.getElementById("floatingInput3");
                            var username = document.getElementById("floatingInput3").value;
                            if (username.length == 0) {
                                var SendForm = false;
                                InputUsername.style.borderBottom = "2px dashed #fcd303";
                                document.getElementById("label3").style.color = "#fcd303";
                                document.getElementById("msgUsername").innerHTML = "You need to enter your username!";
                            } else {
                                InputUsername.style.borderBottom = "2px solid green";
                                document.getElementById("label3").style.color = "green";
                                document.getElementById("msgUsername").innerHTML = "";
                            }

                            /* Check password */
                            var InputPassword = document.getElementById("floatingPassword1");
                            var password = document.getElementById("floatingPassword1").value;
                            var InputCheckPassword = document.getElementById("floatingPassword2");
                            var checkpassword = document.getElementById("floatingPassword2").value;
                            if (password.length == 0 || checkpassword.length == 0 || password != checkpassword) {
                                var SendForm = false;
                                if (password.length == 0) {
                                    document.getElementById("label4").style.color = "#fcd303";
                                    InputPassword.style.borderBottom = "2px dashed #fcd303";
                                    document.getElementById("msgPassword").innerHTML = "You need to enter your password!";
                                }
                                if (checkpassword.length == 0) {
                                    InputCheckPassword.style.borderBottom = "2px dashed #fcd303";
                                    document.getElementById("label5").style.color = "#fcd303";
                                    document.getElementById("msgCheckPassword").innerHTML = "You need to confirm your password!";
                                } else if (password != checkpassword) {
                                    document.getElementById("label4").style.color = "#fcd303";
                                    InputPassword.style.borderBottom = "2px dashed #fcd303";
                                    document.getElementById("label5").style.color = "#fcd303";
                                    InputCheckPassword.style.borderBottom = "2px dashed #fcd303";
                                    document.getElementById("msgPassword").innerHTML = "Passwords don't match!";
                                    document.getElementById("msgCheckPassword").innerHTML = "Passwords don't match!";

                                }
                            } else {
                                InputPassword.style.borderBottom = "2px solid green";
                                InputCheckPassword.style.borderBottom = "2px solid  green";
                                document.getElementById("label4").style.color = "green";
                                document.getElementById("label5").style.color = "green";
                                document.getElementById("floatingPassword1").innerHTML = "";
                                document.getElementById("floatingPassword2").innerHTML = "";
                            }
                            if (SendForm != true) {
                                event.preventDefault();
                            }
                        }
                    </script>
            </div>
        </div>
    <?php
                }
    ?>
    </main>



    <button class="scrollToTopBtn">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 6">
            <path d="M12 6H0l6-6z" />
        </svg>
    </button>

    <!-- Script to determine when will the button be visible -->
    <script>
        var scrollToTopBtn = document.querySelector(".scrollToTopBtn");
        var rootElement = document.documentElement;

        function handleScroll() {
            var scrollTotal = rootElement.scrollHeight - rootElement.clientHeight;
            if (rootElement.scrollTop / scrollTotal > 0.8) {
                // Show button
                scrollToTopBtn.classList.add("showBtn");
            } else {
                // Hide button
                scrollToTopBtn.classList.remove("showBtn");
            }
        }

        function scrollToTop() {
            // Scroll to top
            rootElement.scrollTo({
                top: 0,
                behavior: "smooth",
            });
        }
        scrollToTopBtn.addEventListener("click", scrollToTop);
        document.addEventListener("scroll", handleScroll);
    </script>

    <!--Footer-->
    <footer>
        <p>©<b>Daniel Gluhak</b> . All Rights Reserved, TVZ 2021</p>
        <p>
            You can contact me here:
            <a href="mailto: dgluhak1@tvz.hr">dgluhak1@tvz.hr</a>
        </p>
    </footer>

</body>

</html>
