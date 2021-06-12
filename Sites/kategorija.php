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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
  <!--jQuery and Bootstrap Bundle (includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
  <!-- Bootstrap icon -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
  <!--Outside css-->
  <link rel="stylesheet" type="text/css" href="style.css?v=<?php echo time(); ?>" />

  <title>GAMIZ</title>
</head>

<body>
  <?php
  include "connect.php";
  define('IMGPATH', '../Pictures/');
  session_start();
  ?>
  <!--Header with navigation-->
  <header>
    <div class="website-title">
      <h1>GAMIZ</h1>
    </div>
    <nav role="navigation">
      <ul>
        <li class="temporarily-active" onmouseover="highlight()" onmouseout="outhiglight()"><a href="index.php">HOME</a></li>
        <li class="temporarily-active" onmouseover="highlight1()" onmouseout="outhiglight1()"><a href="kategorija.php?id=Review" <?= $_GET["id"] == "Review" ? "id=\"main-active-review\"" : ""; ?>>REVIEWS</a></li>
        <li class="temporarily-active" onmouseover="highlight2()" onmouseout="outhiglight2()"><a href="kategorija.php?id=News" <?= $_GET["id"] == "News" ? "id=\"main-active-news\" " : ""; ?>>NEWS</a></li>
        <?php
        if (!isset($_SESSION['$username'])) {
          print '<li class="temporarily-active" onmouseover="highligh()" onmouseout="outhiglight()"><a href="registracija.php">SIGN UP</a></li>
          <li class="temporarily-active2"><a href="administracija.php">ADMINISTRATION</a></li>';
        } elseif (isset($_SESSION['$username']) && $_SESSION['$level'] == 1) {
          print '<li class="temporarily-active" onmouseover="highligh()" onmouseout="outhiglight()"><a href="unos.php">CREATE</a></li>
          <li class="temporarily-active2"><a href="administracija.php">ADMINISTRATION</a></li>
                    <li class="temporarily-active" onmouseover="highligh()" onmouseout="outhiglight()"><a href="signout.php">SIGN OUT</a></li>';
        } elseif (isset($_SESSION['$username']) && $_SESSION['$level'] == 0) {
          print '<li class="temporarily-active2"><a href="administracija.php">ADMINISTRATION</a></li>
                    <li class="temporarily-active" onmouseover="highligh()" onmouseout="outhiglight()"><a href="signout.php">SIGN OUT</a></li>';
        }
        ?>
      </ul>
    </nav>
    <div class="clear"></div>
    <!-- Navigation hover -->
    <script>
      let highlight = () => {
        document.getElementById("main-active").style.color = "crimson";
        document.getElementById("main-active").style.backgroundColor = "transparent";

        let review = document.getElementById("main-active-review");
        review.setAttribute("style", "color: crimson !important;");
        review.setAttribute("style", "background-color: transparent !important;");

        let news = document.getElementById("main-active-news");
        news.setAttribute("style", "color: crimson !important;");
        news.setAttribute("style", "background-color: transparent !important;");
      }

      let outhiglight = () => {
        document.getElementById("main-active").style.color = "white";
        document.getElementById("main-active").style.backgroundColor = "crimson";


        var review = document.getElementById("main-active-review");
        review.setAttribute("style", "color: white !important;");
        review.setAttribute("style", "background-color: crimson !important;");

        var news = document.getElementById("main-active-news");
        news.setAttribute("style", "color: white !important;");
        news.setAttribute("style", "background-color: crimson !important;");
      }
      /*------------------------------------------------------------*/

      function highlight1() {
        document.getElementById("main-active-news").style.color = "crimson";
        document.getElementById("main-active-news").style.backgroundColor = "transparent";
      }

      function outhiglight1() {
        document.getElementById("main-active-news").style.color = "white";
        document.getElementById("main-active-news").style.backgroundColor = "crimson";
      }

      function highlight2() {
        document.getElementById("main-active-review").style.color = "crimson";
        document.getElementById("main-active-review").style.backgroundColor = "transparent";
      }

      function outhiglight2() {
        document.getElementById("main-active-review").style.color = "white";
        document.getElementById("main-active-review").style.backgroundColor = "crimson";
      }
    </script>

  </header>


  <!--Section with specific category -->


  <main class="all-article">
    <?php
    if (isset($_GET['id']) && isset($_GET['id']) != "") {
      $kategorija = $_GET['id'];
      $query = "SELECT * FROM clanci WHERE kategorija='$kategorija'";
      $result = mysqli_query($dbc, $query);
      if (!$result) {
        printf("Error: %s\n", mysqli_error($dbc));
        exit();
      }
      print '<h3>' . $kategorija . ' category</h3>';
      print '<section id="' . $kategorija . '" class="row">';
      while ($row = mysqli_fetch_array($result)) {
        if ($kategorija == "Review") {
          print '<article class="col-xs-4 col-sm-6 col-md-5 col-lg-3">
                                <a class="button" href="članak.php?id=' . $row['id'] . '">
                                    <div class="article-image">
                                        <img src="' . IMGPATH . $row['slika'] . '"/>
                                    </div>    
                                    <h2 class="individual-title">' . $row['naslov'] . '</h2>
                                    <p>' . $row['sazetak'] . '</p>
                                    <p><span>' . $row['autor'] . '</span> ' . date('d.m.Y', strtotime($row['datum'])) . '</p>
                                </a>
                              </article>
                    ';
        } elseif ($kategorija == "News") {
          print '<article class="col-xs-4 col-md-5 col-lg-3">
                                <a class="button" href="članak.php?id=' . $row['id'] . '">                              
                                    <div class="article-image">
                                      <img src="' . IMGPATH . $row['slika'] . '"/>
                                    </div>
                                    <h4 class="individual-title">' . $row['naslov'] . '</h4>
                                    <p>' . $row['sazetak'] . '</p>
                                    <p><span>' . $row['autor'] . '</span> ' . date('d.m.Y', strtotime($row['datum'])) . '</p>
                                </a>
                              </article>
                    ';
        }
      }
      print '</section>';
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
    var scrollToTopBtn = document.querySelector(".scrollToTopBtn")
    var rootElement = document.documentElement

    function handleScroll() {

      var scrollTotal = rootElement.scrollHeight - rootElement.clientHeight
      if ((rootElement.scrollTop / scrollTotal) > 0.80) {
        // Show button
        scrollToTopBtn.classList.add("showBtn")
      } else {
        // Hide button
        scrollToTopBtn.classList.remove("showBtn")
      }
    }

    function scrollToTop() {
      // Scroll to top
      rootElement.scrollTo({
        top: 0,
        behavior: "smooth"
      })
    }
    scrollToTopBtn.addEventListener("click", scrollToTop)
    document.addEventListener("scroll", handleScroll)
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