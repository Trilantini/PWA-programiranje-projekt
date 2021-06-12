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
  define('IMGPATH', '../Pictures/');
  session_start();
  ?>
  <header>
    <div class="website-title">
      <h1>GAMIZ</h1>
    </div>
    <nav role="navigation">
      <ul>
        <li class="temporarily-active"><a href="index.php">HOME</a></li>
        <li class="temporarily-active"><a href="kategorija.php?id=Review">REVIEWS</a></li>
        <li class="temporarily-active"><a href="kategorija.php?id=News">NEWS</a></li>
        <?php
        if (!isset($_SESSION['$username'])) {
          print '<li class="temporarily-active" onmouseover="highligh()" onmouseout="outhiglight()"><a href="registracija.php">SIGN UP</a></li>
          <li class="temporarily-active3" onmouseover="highligh()" onmouseout="outhiglight()"><a href="administracija.php">ADMINISTRATION</a></li>';
        } elseif (isset($_SESSION['$username']) && $_SESSION['$level'] == 1) {
          print '<li class="temporarily-active" onmouseover="highligh()" onmouseout="outhiglight()"><a href="unos.php">CREATE</a></li>
          <li class="temporarily-active3" onmouseover="highligh()" onmouseout="outhiglight()"><a href="administracija.php">ADMINISTRATION</a></li>
          <li class="temporarily-active" onmouseover="highligh()" onmouseout="outhiglight()"><a href="signout.php">SIGN OUT</a></li>';
        } elseif (isset($_SESSION['$username']) && $_SESSION['$level'] == 0) {
          print '<li class="temporarily-active3" onmouseover="highligh()" onmouseout="outhiglight()"><a href="administracija.php">ADMINISTRATION</a></li>
          <li class="temporarily-active" onmouseover="highligh()" onmouseout="outhiglight()"><a href="signout.php">SIGN OUT</a></li>';
        }
        ?>
      </ul>
    </nav>
    <div class="clear"></div>
  </header>

  <!-- Article with text and picture -->
  <?php
  if (isset($_GET['id']) && isset($_GET['id']) != "") {
    $query = "SELECT * FROM clanci WHERE id=" . $_GET['id'];
    $result = mysqli_query($dbc, $query);
    if (!$result) {
      printf("Error: %s\n", mysqli_error($dbc));
      exit();
    }
    while ($row = mysqli_fetch_array($result)) {

      if ($row['kategorija'] == "News") {
        print '
            <main class="individual-article" role="main">
              <h1 class="title-category"> ' . strtoupper($row['kategorija']) . '</h1>
              <hr>
              <article class="content">
                <h1 class="title"> ' . $row['naslov'] . '</h1>
                <p class="published-date">By <span>' . $row['autor'] . '</span>, ' . date('d.m.Y', strtotime($row['datum'])) . '</p>
                <section class="image">              
                    <img src="' . IMGPATH . $row['slika'] . '"/>              
                </section>
                <section class="about">
                  <p>' . $row['sazetak'] . '</p>
                </section>
                <section class="full-text">
                  <p>' . nl2br($row['tekst']) . '</p>          
                </section>
              </article>
            </main>';
      } else if ($row['kategorija'] == "Review") {
        print '
            <main class="individual-article" role="main">
              <h1 class="title-category"> ' . strtoupper($row['kategorija']) . '</h1>
              <hr>
              <article class="content">
                <h1 class="title"> ' . $row['naslov'] . '</h1>
                <p class="published-date">By <span>' . $row['autor'] . '</span>, ' . date('d.m.Y', strtotime($row['datum'])) . '</p>
                <section class="image">              
                    <img src="' . IMGPATH . $row['slika'] . '"/>              
                </section>
                <section class="about">
                  <p>' . $row['sazetak'] . '</p>
                </section>
                <section class="full-text">
                  <p>' . nl2br($row['tekst']) . '</p>          
                </section>
                <section class="final-review row">
                  <div class="col-md-auto col-sm-1"></div>
                  <div class="positive col-sm-5 col-md-4">                    
                      <ul>
                        <li>' . $row['pozitivno1'] . '</li>
                        <li>' . $row['pozitivno2'] . '</li>
                        <li>' . $row['pozitivno3'] . '</li>
                      </ul>                    
                  </div>
                  <div class="col-md-auto col-sm-1"></div>
                  <div class="negative col-sm-5 col-md-4">
                    <ul>
                      <li>' . $row['negativno1'] . '</li>
                      <li>' . $row['negativno2'] . '</li>
                      <li>' . $row['negativno3'] . '</li>
                    </ul>
                  </div>
                  <div class="col-md-auto col-sm-3"></div>
                  ';
        if ($row['ocjena'] > 0) {
          print '
                  <div class="number col-sm-9 col-md-4">
                    <p>' . $row['ocjena'] . ' / 10</p>
                  </div>';
        }
        print '</section>
              </article>
            </main>';
      }
    }
  }
  ?>

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
      // Do something on scroll
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
      // Scroll to top logic
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