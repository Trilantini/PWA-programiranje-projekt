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
  <!-- Bootstrap icon -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" />
  <!--jQuery and Bootstrap Bundle (includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <!--Outside css-->
  <link rel="stylesheet" type="text/css" href="style.css?v=<?php echo time(); ?>" />

  <title>GAMIZ</title>
</head>

<body>
  <?php
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
        if (isset($_SESSION['$username']) && $_SESSION['$level'] == 1) {
          print '<li><a id="main-active" href="unos.php">CREATE</a></li>
          <li class="temporarily-active3" onmouseover="highligh()" onmouseout="outhiglight()"><a href="administracija.php">ADMINISTRATION</a></li>
          <li class="temporarily-active" onmouseover="highligh()" onmouseout="outhiglight()"><a href="signout.php">SIGN OUT</a></li>';
        } else {
          header("Location:index.php");
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

    <div class="slider">
      <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="../Pictures/article.png" class="d-block w-100" alt="..." />
          </div>
        </div>
      </div>
    </div>
  </header>


  <?php

  if (isset($_POST["submit"])) {
    include 'connect.php';
    $title = $_POST["Title"];
    $author = $_POST["Author-name"];
    $date = $_POST["Date"];
    $abstract = $_POST["Abstract"];
    $content  = $_POST["Article"];
    $category = $_POST["Category"];
    if ($category == "Review") {
      $evaluation = $_POST["review-numb"];
      $pros1 = $_POST["Pros1"];
      $pros2 = $_POST["Pros2"];
      $pros3 = $_POST["Pros3"];
      $cons1 = $_POST["Cons1"];
      $cons2 = $_POST["Cons2"];
      $cons3 = $_POST["Cons3"];
    }
    $picturename = $_FILES['picture']['name'];
    $pic_path = "../Pictures/" . $picturename;
    move_uploaded_file($_FILES["picture"]["tmp_name"], $pic_path);
    if (isset($_POST["archive"])) {
      $archive = 1;
    } else {
      $archive = 0;
    }
    if ($category == "Review") {
      $query = "INSERT INTO clanci(datum,autor,naslov,sazetak,tekst,kategorija,slika,ocjena,pozitivno1,pozitivno2,pozitivno3,negativno1,negativno2,negativno3,arhiva)
     VALUES ('$date','$author','$title','$abstract','$content','$category','$picturename','$evaluation','$pros1','$pros2','$pros3','$cons1','$cons2','$cons3','$archive')";
    } else {
      $query = "INSERT INTO clanci(datum,autor,naslov,sazetak,tekst,kategorija,slika,arhiva)
     VALUES ('$date','$author','$title','$abstract','$content','$category','$picturename','$archive')";
    }
    $result = mysqli_query($dbc, $query);
    if (!$result) {
      printf("Error: %s\n", mysqli_error($dbc));
      exit();
    }
    mysqli_close($dbc);
    if ($category == "News") {
      header("Location:kategorija.php?id=News");
      die();
    } elseif ($category == "Review") {
      header("Location:kategorija.php?id=Review");
      die();
    }
  }
  ?>






  <!--Section with entering information -->

  <main class="container-fluid unos">
    <form enctype="multipart/form-data" class="row g-3" action="" method="POST">
      <span id="msgQuote" class="msgQuote msgColor"></span>

      <div class="form-floating col-md-5">
        <input name="Title" type="text" maxlength="60" class="form-control" id="title" pattern="[^':]*$" placeholder="TitleExample" />
        <label for="title" class="form-label" id="label1">Enter a title</label>
        <span id="msgTitle" class="msgTitle msgColor"></span>
      </div>

      <div class="col-md-7"></div>


      <div class="form-floating col-md-3">
        <input name="Author-name" type="text" maxlength="32" class="form-control" id="Author" placeholder="NameExample" pattern="[^':]*$" />
        <label for="Author" id="label2">Your name</label>
        <span id="msgAuthor" class="msgAuthor msgColor"></span>
      </div>

      <div class="form-floating col-md-3">
        <input type="date" name="Date" class="form-control" max="2099-12-31" id="DateUpload" placeholder="DateExample" />
        <label for="DateUpload" class="form-label">Upload date</label>
        <span id="msgDate" class="msgDate msgColor"></span>
        <script>
          DateUpload.min = new Date().toISOString().slice(0, -14);
          let today = new Date().toISOString().substr(0, 10);
          document.querySelector("#DateUpload").value = today;
        </script>
      </div>

      <div class="col-md-6"></div>


      <div class="col-md-7">
        <label for="Summary" class="form-label">Write a short summary (<span>avoid using single quotes</span>)</label>
        <span id="msgShort" class="msgShort msgColor"></span>
        <textarea name="Abstract" rows="4" maxlength="320" pattern="[^':]*$" class="form-control" id="Summary" placeholder="TitleExample..."></textarea>
      </div>

      <div class="col-md-5"></div>


      <div class="col-md-12">
        <label for="Content" class="form-label">Write an article (<span>avoid using single quotes</span>) </label>
        <span id="msgLong" class="msgLong msgColor"></span>
        <textarea name="Article" rows="10" class="form-control" pattern="[^':]*$" id="Content" placeholder="Start writing text..."></textarea>
      </div>

      <div id="check-review" class="row">

        <div class="col-md-4">
          <label for="Number" class="form-label">Verdict for the game (from 1 to 10)</label>
          <span id="msgNumber" class="msgNumber msgColor"></span>
          <input type="number" class="form-control" name="review-numb" min="1" max="10" id="Number">
        </div>


        <div class="col-md-4">
          <fieldset>
            <legend>Strengths:</legend>
            <span id="msgPositive" class="msgPositive msgColor"></span>
            <div class="form-floating">
              <input type="text" class="form-control" name="Pros1" maxlength="50" id="good1" pattern="[^':]*$" placeholder="example" />
              <label for="good1" class="form-label" id="labelpro1">Pros 1:</label>
            </div>
            <div class="form-floating">
              <input type="text" class="form-control" name="Pros2" maxlength="50" id="good2" pattern="[^':]*$" placeholder="example" />
              <label for="good2" class="form-label" id="labelpro2">Pros 2:</label>
            </div>
            <div class="form-floating">
              <input type="text" class="form-control" name="Pros3" maxlength="50" id="good3" pattern="[^':]*$" placeholder="example" />
              <label for="good3" class="form-label" id="labelpro3">Pros 3:</label>
            </div>
          </fieldset>
        </div>

        <div class="col-md-4">
          <fieldset>
            <legend>Weaknesses:</legend>
            <span id="msgNegative" class="msgNegative msgColor"></span>
            <div class="form-floating">
              <input type="text" class="form-control" name="Cons1" maxlength="50" id="bad1" pattern="[^':]*$" placeholder="example" />
              <label for="bad1" class="form-label" id="labelneg1">Cons 1:</label>
            </div>
            <div class="form-floating">
              <input type="text" class="form-control" name="Cons2" maxlength="50" id="bad2" pattern="[^':]*$" placeholder="example" />
              <label for="bad2" class="form-label" class="form-label" id="labelneg2">Cons 2:</label>
            </div>
            <div class="form-floating">
              <input type="text" class="form-control" name="Cons3" maxlength="50" id="bad3" pattern="[^':]*$" placeholder="example" />
              <label for="bad3" class="form-label" id="labelneg3">Cons 3:</label>
            </div>
          </fieldset>
        </div>
      </div>

      <!-- Show more if Review category is selected -->
      <script type="text/javascript">
        function checkReview(that) {
          if (that.value == "Review") {
            document.getElementById('check-review').style.display = 'contents';
          } else if (that.value == "News") {
            document.getElementById('check-review').style.display = 'none';
            /* Delete input from review if hidden */
            document.getElementById("good1").value = "";
            document.getElementById("good2").value = "";
            document.getElementById("good3").value = "";
            document.getElementById("bad1").value = "";
            document.getElementById("bad2").value = "";
            document.getElementById("bad3").value = "";
            document.getElementById("Number").value = "";
          }
        }
      </script>


      <div class="col-md-4">
        <label for="Category" class="form-label">Select category in which you want to upload</label>
        <span id="msgCategory" class="msgCategory msgColor"></span>
        <select name="Category" id="Category" class="form-select" aria-label="Default select example" onchange="checkReview(this)">
          <option value="" selected disabled>Choose...</option>
          <option value="Review">Review</option>
          <option value="News">News</option>
        </select>
      </div>

      <div class="col-md-4">
        <label for="Pictures" class="form-label">Picture</label>
        <input class="form-control" id="Pictures" value="Choose a picture" name="picture" type="file" accept="image/*,.jpg,.jpeg,.png,.gif,.bmp,.tif,.tiff,.eps,.raw" id="Pictures" />
        <span id="msgPicture" class="msgPicture msgColor"></span>
      </div>

      <div class="col-md-3"></div>

      <div class="col-md-2 col-sm-11 form-check">
        <input name="archive" type="checkbox" class="form-check-input" id="Check" />
        <label class="form-check-label" for="Check">Save into archive</label>
      </div>
      <div class="col-md-8"></div>
      <br /><br /><br />
      <button name="submit" id="send" type="submit" class="btn col-md-2 btn btn-danger">
        Submit
      </button>
      <div class="col-md-1"></div>
      <button type="reset" onclick="hide()" class="btn col-md-2 btn btn-danger">Cancel</button>

      <!-- Form validation -->
      <script type="text/javascript">
        document.getElementById("send").onclick = function(event) {
          var SendForm = true;
          /* Check date */

          var InputDate = document.getElementById("DateUpload");
          var date = document.getElementById("DateUpload").value;
          if (!date) {
            var SendForm = false;
            InputDate.style.border = "2px dashed #fcd303";
            document.getElementById("msgDate").innerHTML = "You need to enter a valid date";
          } else {
            InputDate.style.border = "3px solid green";
            document.getElementById("msgDate").innerHTML = "";
          }
          /* Check the category */
          var InputCategory = document.getElementById("Category");
          if (InputCategory.value == "") {
            var SendForm = false;
            InputCategory.style.border = "2px dashed #fcd303";
            document.getElementById("msgCategory").innerHTML = "You need to select a category!";
          } else {
            InputCategory.style.border = "3px solid green";
            document.getElementById("msgCategory").innerHTML = "";
            document.getElementById("msgCategory").style.display = "block";
          }

          if (InputCategory.value == "Review") {
            /* Check review score */
            var InputScore = document.getElementById("Number");
            var score = document.getElementById("Number").value;
            if (score < 1 || score > 10) {
              var SendForm = false;
              InputScore.style.border = "2px dashed #fcd303";
              InputScore.style.color = "#fcd303";
              document.getElementById("msgNumber").innerHTML = "Only from 1 to 10 is acceptable!";
            } else {
              InputScore.style.border = "3px solid green";
              InputScore.style.color = "green";
              document.getElementById("msgNumber").innerHTML = "";
            }

            /* Check positive and negative review */
            var Positive1 = document.getElementById("good1").value;
            var Positive2 = document.getElementById("good2").value;
            var Positive3 = document.getElementById("good3").value;
            if (Positive1 === "" && Positive2 === "" && Positive3 === "") {
              var SendForm = false;
              document.getElementById("good1").style.borderBottom = "2px dashed #fcd303";
              document.getElementById("good2").style.borderBottom = "2px dashed #fcd303";
              document.getElementById("good3").style.borderBottom = "2px dashed #fcd303";
              document.getElementById("labelpro1").style.color = "#fcd303";
              document.getElementById("labelpro2").style.color = "#fcd303";
              document.getElementById("labelpro3").style.color = "#fcd303";
              document.getElementById("msgPositive").innerHTML = "At least one positive review!";
            } else {
              if (Positive1 !== "") {
                document.getElementById("good1").style.borderBottom = "3px solid green";
                document.getElementById("labelpro1").style.color = "green";
              } else if (Positive2 !== "") {
                document.getElementById("good2").style.borderBottom = "3px solid green";
                document.getElementById("labelpro2").style.color = "green";
              } else if (Positive3 !== "") {
                document.getElementById("good3").style.borderBottom = "3px solid green";
                document.getElementById("labelpro3").style.color = "green";
              }
              document.getElementById("msgPositive").innerHTML = "";
            }

            var Negative1 = document.getElementById("bad1").value;
            var Negative2 = document.getElementById("bad2").value;
            var Negative3 = document.getElementById("bad3").value;
            if (Negative1 === "" && Negative2 === "" && Negative3 === "") {
              var SendForm = false;
              document.getElementById("bad1").style.borderBottom = "2px dashed #fcd303";
              document.getElementById("bad2").style.borderBottom = "2px dashed #fcd303";
              document.getElementById("bad3").style.borderBottom = "2px dashed #fcd303";
              document.getElementById("labelneg1").style.color = "#fcd303";
              document.getElementById("labelneg2").style.color = "#fcd303";
              document.getElementById("labelneg3").style.color = "#fcd303";
              document.getElementById("msgNegative").innerHTML = "At least one negative review!";
            } else {
              if (Negative1 !== "") {
                document.getElementById("bad1").style.borderBottom = "3px solid green";
                document.getElementById("labelneg1").style.color = "green";
              } else if (Negative2 !== "") {
                document.getElementById("bad2").style.borderBottom = "3px solid green";
                document.getElementById("labelneg2").style.color = "green";
              } else if (Negative3 !== "") {
                document.getElementById("bad3").style.borderBottom = "3px solid green";
                document.getElementById("labelneg3").style.color = "green";
              }
              document.getElementById("msgNegative").innerHTML = "";
            }
          }

          /* Check title */
          var InputTitle = document.getElementById("title");
          var title = document.getElementById("title").value;
          if (title.length < 5 || title.length > 50) {
            var SendForm = false;
            InputTitle.style.borderBottom = "2px dashed #fcd303";
            document.getElementById("label1").style.color = "#fcd303";
            document.getElementById("msgTitle").innerHTML = "You need to enter a title between 5 and 50 characters!";
          } else {
            InputTitle.style.borderBottom = "3px solid green";
            document.getElementById("label1").style.color = "green";
            document.getElementById("msgTitle").innerHTML = "";
          }

          /* Check name */
          var InputAuthor = document.getElementById("Author");
          var name = document.getElementById("Author").value;
          if (name.length < 2 || name.length > 25) {
            var SendForm = false;
            InputAuthor.style.borderBottom = "2px dashed #fcd303";
            document.getElementById("label2").style.color = "#fcd303";
            document.getElementById("msgAuthor").innerHTML = "The length of author name needs to be between 2 and 25!";
          } else {
            InputAuthor.style.borderBottom = "3px solid green";
            document.getElementById("label2").style.color = "green";
            document.getElementById("msgAuthor").innerHTML = "";
          }

          /* Check short summary */
          var InputSummary = document.getElementById("Summary");
          var summary = document.getElementById("Summary").value;

          if (summary.length < 10 || summary.length > 236) {
            var SendForm = false;
            InputSummary.style.border = "2px dashed #fcd303";
            InputSummary.style.color = "#fcd303";
            document.getElementById("msgShort").innerHTML = "The length of summary needs to be between 10 and 236 characters!";
          } else {
            InputSummary.style.border = "3px solid green";
            InputSummary.style.color = "green";
            document.getElementById("msgShort").innerHTML = "";
          }


          /* Check the main content */
          var InputContent = document.getElementById("Content");
          var content = document.getElementById("Content").value;
          if (content.length == 0) {
            var SendForm = false;
            InputContent.style.border = "2px dashed #fcd303";
            InputContent.style.color = "#fcd303";
            document.getElementById("msgLong").innerHTML = "You need to write a content!";
          } else {
            InputContent.style.border = "3px solid green";
            InputContent.style.color = "green";
            document.getElementById("msgLong").innerHTML = "";
          }


          /* Check the picture */
          var InputPhoto = document.getElementById("Pictures");
          var Photo = document.getElementById("Pictures").value;
          var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif|\.bmp|\.tif|\.tiff|\.eps|\.raw)$/i;
          if (!allowedExtensions.exec(Photo)) {
            var SendForm = false;
            InputPhoto.style.border = "2px dashed #fcd303";
            document.getElementById("msgPicture").innerHTML = "You need to choose a valid photo format!";
          } else {
            InputPhoto.style.border = "3px solid green";
            document.getElementById("msgPicture").innerHTML = "";
          }



          if (SendForm != true) {
            event.preventDefault();
          }
        }
      </script>
      <script>
        /* Check quotation mark */
        $("input[type=text],textarea").keyup(function() {
          var check = $(this).val();
          var checkID = this.id;
          if (check.includes("'")) {
            var SendForm = false;
            document.getElementById("msgQuote").innerHTML = "You need to remove quotation mark!";
            if (document.getElementById(checkID) == title || document.getElementById(checkID) == Author ||
              document.getElementById(checkID) == good1 || document.getElementById(checkID) == good2 ||
              document.getElementById(checkID) == good3 || document.getElementById(checkID) == bad1 ||
              document.getElementById(checkID) == bad2 || document.getElementById(checkID) == bad3) {
              document.getElementById(checkID).style.borderBottom = "2px dashed #fcd303";
              document.getElementById(checkID).style.color = "#fcd303";
            } else {
              document.getElementById(checkID).style.border = "2px dashed #fcd303";
              document.getElementById(checkID).style.color = "#fcd303";
            }
          } else {
            var SendForm = true;
            document.getElementById("msgQuote").innerHTML = "";
            if (document.getElementById(checkID) == title || document.getElementById(checkID) == Author ||
              document.getElementById(checkID) == good1 || document.getElementById(checkID) == good2 ||
              document.getElementById(checkID) == good3 || document.getElementById(checkID) == bad1 ||
              document.getElementById(checkID) == bad2 || document.getElementById(checkID) == bad3) {
              document.getElementById(checkID).style.borderBottom = "1px solid crimson";
              document.getElementById(checkID).style.color = "white";
            } else {
              document.getElementById(checkID).style.border = "1px solid crimson";
              document.getElementById(checkID).style.color = "white";

            }
          }
          if (SendForm != true) {
            event.preventDefault();
          } else {
            document.getElementById("msgQuote").innerHTML = "";
          }
        });

        $('textarea').keyup(validateTextarea);

        function validateTextarea() {
          var errorMsg = "Please remove quotes";
          var textarea = this;
          var pattern = new RegExp('^' + $(textarea).attr('pattern') + '$');
          // check each line of text
          $.each($(this).val().split("\n"), function() {
            // check if the line matches the pattern
            var hasError = !this.match(pattern);
            if (typeof textarea.setCustomValidity === 'function') {
              textarea.setCustomValidity(hasError ? errorMsg : '');
            } else {
              // Not supported by the browser, fallback to manual error display...
              $(textarea).toggleClass('error', !!hasError);
              $(textarea).toggleClass('ok', !hasError);

              if (hasError) {
                $(textarea).attr('title', errorMsg);
                textarea.style.setProperty("border", "2px dashed #fcd303", "important");
                textarea.style.setProperty("color", "#fcd303", "important");
              } else {
                $(textarea).removeAttr('title');

              }
            }
            return !hasError;
          });
        }
      </script>

      <script type="text/javascript">
        function hide() {
          document.getElementById('check-review').style.display = 'none';
        }
      </script>
    </form>

  </main>



  <!-- Script to determine when will the button be visible -->
  <button class="scrollToTopBtn">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 6">
      <path d="M12 6H0l6-6z" />
    </svg>
  </button>

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
