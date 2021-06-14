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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <!--Outside css-->
  <link href="style.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />

  <title>GAMIZ</title>
</head>

<body>

  <?php
  session_start();
  include "connect.php";
  define('IMGPATH', '../Pictures/');
  $LogedIn = '';
  $errormsg = false;
  // Check if user logged in
  if (isset($_POST['Login'])) {

    // Check if user exist in database with SQL injection
    $LoginUsername = $_POST["username"];
    $LoginPassword = $_POST["password"];

    $sql = "SELECT username,password,razina FROM korisnik WHERE username = ?";
    $stmt = mysqli_stmt_init($dbc);
    if (mysqli_stmt_prepare($stmt, $sql)) {
      mysqli_stmt_bind_param($stmt, 's', $LoginUsername);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
    }
    mysqli_stmt_bind_result($stmt, $UsersName, $UsersPassword, $UsersLevel);
    mysqli_stmt_fetch($stmt);

    // Check password
    if (password_verify($_POST["password"], $UsersPassword) && mysqli_stmt_num_rows($stmt) > 0) {
      $LogedIn = true;

      //Check if admin
      if ($UsersLevel == 1) {
        $admin = true;
      } else {
        $admin = false;
      }

      $_SESSION['$username'] = $UsersName;
      $_SESSION['$level'] = $UsersLevel;
    } else {
      $LogedIn = false;
      $errormsg = true;
    }
  }
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
        if (!isset($_SESSION['$username']) || empty(isset($_SESSION['$username']))) {
          print '<li class="temporarily-active" onmouseover="highligh()" onmouseout="outhiglight()"><a href="registracija.php">SIGN UP</a></li>
                    <li class="temporarily-active3"><a id="main-active" href=" administracija.php">ADMINISTRATION</a></li>';
        } elseif (isset($_SESSION['$username']) && $_SESSION['$level'] == 1) {
          print '<li class="temporarily-active" onmouseover="highligh()" onmouseout="outhiglight()"><a href="unos.php">CREATE</a></li>
                    <li class="temporarily-active3"><a id="main-active" href=" administracija.php">ADMINISTRATION</a></li>
                    <li class="temporarily-active" onmouseover="highligh()" onmouseout="outhiglight()"><a href="signout.php">SIGN OUT</a></li>';
        } elseif (isset($_SESSION['$username']) && $_SESSION['$level'] == 0) {
          print '<li class="temporarily-active3"><a id="main-active" href=" administracija.php">ADMINISTRATION</a></li>
                    <li class="temporarily-active" onmouseover="highligh()" onmouseout="outhiglight()"><a href="signout.php">SIGN OUT</a></li>';
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





  # UPDATE article 
  if (isset($_POST['update'])) {
    $id = $_POST["idcontent"];
    $picturename = $_FILES["picture"]["name"];
    $title = $_POST["Title"];
    $author = $_POST["Author-name"];
    $date = $_POST["Date"];
    $abstract = $_POST["Abstract"];
    $content  = $_POST["Article"];
    $category = $_POST["Category"];
    $evaluation = $_POST["review-numb"];
    $pros1 = $_POST["Pros1"];
    $pros2 = $_POST["Pros2"];
    $pros3 = $_POST["Pros3"];
    $cons1 = $_POST["Cons1"];
    $cons2 = $_POST["Cons2"];
    $cons3 = $_POST["Cons3"];

    $pic_path = "../Pictures/" . $picturename;
    move_uploaded_file($_FILES["picture"]["tmp_name"], $pic_path);
    if (isset($_POST["archive"])) {
      $archive = 1;
    } else {
      $archive = 0;
    }

    $query = "UPDATE clanci SET naslov='$title', autor='$author', datum='$date', sazetak='$abstract',
            tekst='$content', kategorija ='$category', slika='$picturename', ocjena='$evaluation', pozitivno1='$pros1',
            pozitivno2='$pros2',pozitivno3='$pros3',negativno1='$cons1',negativno2='$cons2',negativno3='$cons3',
            arhiva='$archive' WHERE id = $id";
    $result = mysqli_query($dbc, $query);
    if (!$result) {
      printf("Error: %s\n", mysqli_error($dbc));
      exit();
    }
  }



  # DELETE article
  if (isset($_POST['delete'])) {
    $id = $_POST['idcontent'];
    $que = mysqli_query($dbc, "SELECT slika FROM clanci WHERE id=$id");
    $red = mysqli_fetch_array($que);
    $query = "DELETE FROM clanci WHERE id = $id ";
    unlink('../Pictures/' . $red['slika']);
    $result = mysqli_query($dbc, $query);
    if (!$result) {
      printf("Error: %s\n", mysqli_error($dbc));
      exit();
    }
  }

  ?>



  <?php
  if (($LogedIn == true && $admin == true) || (isset($_SESSION['$username'])) && $_SESSION['$level'] == 1) {
    $query = "SELECT * FROM clanci";
    $result = mysqli_query($dbc, $query);
    if (!$result) {
      printf("Error: %s\n", mysqli_error($dbc));
      exit();
    }
    echo '<main role="main" class="container-fluid unos">';
    echo "<p>Welcome " . $_SESSION['$username'] . " ! You're successfully logged in and you're an administrator.</p>";

    while ($row = mysqli_fetch_array($result)) {
      print '  <form enctype="multipart/form-data" class="row g-3" id="forma_' . $row['id'] . '" action="" method="POST">
                <span id="msgQuote_' . $row['id'] . '" class="msgQuote msgColor"></span>
            
                <div class="form-floating col-md-5">
                    <input name="Title" type="text" maxlength="60" class="form-control" id="title_' . $row['id'] . '" pattern="[^\':]*$" value="' . $row['naslov'] . '" placeholder="TitleExample" />
                    <label for="title_' . $row['id'] . '" class="form-label" id="label1_' . $row['id'] . '">Enter a title</label>
                    <span id="msgTitle_' . $row['id'] . '" class="msgTitle msgColor"></span>
                </div>

                <div class="col-md-7"></div>


                <div class="form-floating col-md-3">
                    <input name="Author-name" type="text" maxlength="32" class="form-control" id="Author_' . $row['id'] . '" value="' . $row['autor'] . '" placeholder="NameExample" pattern="[^\':]*$" />
                    <label for="Author_' . $row['id'] . '" id="label2_' . $row['id'] . '">Your name</label>
                    <span id="msgAuthor_' . $row['id'] . '" class="msgAuthor msgColor"></span>
                </div>

                <div class="form-floating col-md-3">
                    <input type="date" name="Date" class="form-control" max="2099-12-31" id="DateUpload_' . $row['id'] . '" value="' . date('Y-m-d', strtotime($row['datum'])) . '" placeholder="DateExample" />
                    <label for="DateUpload_' . $row['id'] . '" class="form-label">Upload date</label>
                    <span id="msgDate_' . $row['id'] . '" class="msgDate msgColor"></span>                
                </div>

                <div class="col-md-6"></div>

                <div class="col-md-7">
                    <label for="Summary_' . $row['id'] . '" class="form-label">Write a short summary (<span>avoid using single quotes</span>)</label>
                    <span id="msgShort_' . $row['id'] . '" class="msgShort msgColor"></span>
                    <textarea name="Abstract" rows="4" maxlength="320" class="form-control" id="Summary_' . $row['id'] . '" pattern="[^\':]*$" placeholder="TitleExample...">' . $row['sazetak'] . '</textarea>
                </div>

                <div class="col-md-5"></div>


                <div class="col-md-12">
                    <label for="Content_' . $row['id'] . '" class="form-label">Write an article (<span>avoid using single quotes</span>) </label>
                    <span id="msgLong_' . $row['id'] . '" class="msgLong msgColor"></span>
                    <textarea name="Article" rows="10" class="form-control" id="Content_' . $row['id'] . '" pattern="[^\':]*$" placeholder="Start writing text...">' . $row['tekst'] . '</textarea>
                </div>
                        ';
      print '
                <div id="check-review' . $row['id'] . '" class="row">

                    <div class="col-md-4">
                    <label for="Number_' . $row['id'] . '" class="form-label">Verdict for the game (from 1 to 10)</label>
                    <span id="msgNumber_' . $row['id'] . '" class="msgNumber msgColor"></span>
                    <input type="number" class="form-control" value="' . $row['ocjena'] . '" name="review-numb" id="Number_' . $row['id'] . '">
                    </div>


                    <div class="col-md-4">
                    <fieldset>
                        <legend>Strengths:</legend>
                        <span id="msgPositive_' . $row['id'] . '" class="msgPositive msgColor"></span>
                        <div class="form-floating">
                        <input type="text" class="form-control" value="' . $row['pozitivno1'] . '" name="Pros1" maxlength="50" id="good1_' . $row['id'] . '" pattern="[^\':]*$" placeholder="example" />
                        <label for="good1_' . $row['id'] . '" class="form-label" id="labelpro1_' . $row['id'] . '">Pros 1:</label>
                        </div>
                        <div class="form-floating">
                        <input type="text" class="form-control" name="Pros2" value="' . $row['pozitivno2'] . '" maxlength="50" id="good2_' . $row['id'] . '" pattern="[^\':]*$" placeholder="example" />
                        <label for="good2_' . $row['id'] . '" class="form-label" id="labelpro2_' . $row['id'] . '">Pros 2:</label>
                        </div>
                        <div class="form-floating">
                        <input type="text" class="form-control" name="Pros3" value="' . $row['pozitivno3'] . '" maxlength="50" id="good3_' . $row['id'] . '" pattern="[^\':]*$" placeholder="example" />
                        <label for="good3_' . $row['id'] . '" class="form-label" id="labelpro3_' . $row['id'] . '">Pros 3:</label>
                        </div>
                    </fieldset>
                    </div>

                    <div class="col-md-4">
                    <fieldset>
                        <legend>Weaknesses:</legend>
                        <span id="msgNegative_' . $row['id'] . '" class="msgNegative msgColor"></span>
                        <div class="form-floating">
                        <input type="text" class="form-control" name="Cons1" value="' . $row['negativno1'] . '" maxlength="50" id="bad1_' . $row['id'] . '" pattern="[^\':]*$" placeholder="example" />
                        <label for="bad1_' . $row['id'] . '" class="form-label" id="labelneg1_' . $row['id'] . '">Cons 1:</label>
                        </div>
                        <div class="form-floating">
                        <input type="text" class="form-control" name="Cons2" maxlength="50" value="' . $row['negativno2'] . '" id="bad2_' . $row['id'] . '" pattern="[^\':]*$" placeholder="example" />
                        <label for="bad2_' . $row['id'] . '" class="form-label" class="form-label" id="labelneg2_' . $row['id'] . '">Cons 2:</label>
                        </div>
                        <div class="form-floating">
                        <input type="text" class="form-control" name="Cons3" maxlength="50" value="' . $row['negativno3'] . '" id="bad3_' . $row['id'] . '" pattern="[^\':]*$" placeholder="example" />
                        <label for="bad3_' . $row['id'] . '" class="form-label" id="labelneg3_' . $row['id'] . '">Cons 3:</label>
                        </div>
                    </fieldset>
                    </div>
                </div>
                ';


      /* Show more if Review category is selected */
      print ' 
        <script type="text/javascript">';
      if ($row['kategorija'] == "Review" && 'checkReview' . $row['id'] == 'checkReview' . $row['id']) {
        print 'document.getElementById("check-review' . $row['id'] . '").style.display = "contents";';
      } elseif ($row['kategorija'] == "News" && 'checkReview' . $row['id'] == 'checkReview' . $row['id']) {
        echo 'document.getElementById("check-review' . $row['id'] . '").style.display = "none";';
      }
      print 'function checkReview' . $row['id'] . '(that) {
                  if (that.value == "Review") {
                    document.getElementById(\'check-review' . $row['id'] . '\').style.display = \'contents\';
                    
                  }

                  if (that.value == "News") {
                    document.getElementById(\'check-review' . $row['id'] . '\').style.display = \'none\';
                      document.getElementById("good1_' . $row['id'] . '").value = "";
                      document.getElementById("good2_' . $row['id'] . '").value = "";
                      document.getElementById("good3_' . $row['id'] . '").value = "";
                      document.getElementById("bad1_' . $row['id'] . '").value = "";
                      document.getElementById("bad2_' . $row['id'] . '").value = "";
                      document.getElementById("bad3_' . $row['id'] . '").value = "";
                      document.getElementById("Number_' . $row['id'] . '").value = "0";

                  }  
              }';
      print '
        </script>';


      print '
      <div class="col-md-4">
        <label for="Category_' . $row['id'] . '" class="form-label">Select category in which you want to upload</label>
        <span id="msgCategory_' . $row['id'] . '" class="msgCategory msgColor"></span>
        <select name="Category" id="Category_' . $row['id'] . '" class="form-select" aria-label="Default select example" onchange="checkReview' . $row['id'] . '(this)">';
      if ($row['kategorija'] == 'Review') {
        print '
                            <option value=""  disabled>Choose...</option>
                            <option value="Review" selected>Review</option>
                            <option value="News">News</option>
                            ';
      } else if ($row['kategorija'] == 'News') {
        print '
                            <option value="" disabled>Choose...</option>
                            <option value="Review" >Review</option>
                            <option value="News" selected>News</option>
                            ';
      }
      print '
        </select>
      </div>';



      print '
      <div class="col-md-4">
        <label for="Pictures_' . $row['id'] . '" class="form-label">Picture</label>
        <input class="form-control" id="Pictures_' . $row['id'] . '" name="picture" type="file" accept="image/jpg,image/jpeg,image/png,image/gif,image/bmp" />      
        <span id="msgPicture_' . $row['id'] . '" class="msgPicture msgColor"></span>
      </div>

      <div class="col-md-3 picPreview">
        <img src="' . IMGPATH . $row['slika'] . '">
      </div>
    
      <div class="col-md-2 col-sm-11 form-check">';
      if ($row['arhiva'] == 0) {
        print '<input name="archive" type="checkbox" class="form-check-input" id="Check_' . $row['id'] . '" />
                            <label class="form-check-label" for="Check">Save into archive</label>';
      } else if ($row['arhiva'] == 1) {
        print '<input name="archive" type="checkbox" class="form-check-input" id="Check_' . $row['id'] . '" checked />
                            <label class="form-check-label" for="Check">Save into archive</label>';
      }


      print '
      </div>
      <div class="col-md-8"></div>
      <br /><br /><br />
      
      <input type="hidden" name="idcontent" value="' . $row['id'] . '"> 
      <input value="Update" type="submit" name="update" id="send' . $row['id'] . '"  class="col-md-2 btn btn-danger">        
      
      <div class="col-md-1"></div>
      <input value="Reset" type="reset" id="reset_' . $row['id'] . '" class="col-md-2 btn btn-danger">
      
      <div class="col-md-1"></div>
      <input value="Delete" type="submit" name="delete" class="col-md-2 btn btn-danger">';
      print '
      <script type="text/javascript">
          document.getElementById("send' . $row['id'] . '").onclick = function(event){
            var SendForm = true;              
            
            var InputDate = document.getElementById("DateUpload_' . $row['id'] . '");
            var date = document.getElementById("DateUpload_' . $row['id'] . '").value;
            if (!date) {
              var SendForm = false;
              InputDate.style.border = "2px dashed #fcd303";
              document.getElementById("msgDate_' . $row['id'] . '").innerHTML="You need to enter a valid date";              
            } else {
              InputDate.style.border = "3px solid green";
              document.getElementById("msgDate_' . $row['id'] . '").innerHTML="";
            }

            var InputCategory = document.getElementById("Category_' . $row['id'] . '");
            if (InputCategory.value == "") {
              var SendForm = false;
              InputCategory.style.border = "2px dashed #fcd303";
              document.getElementById("msgCategory_' . $row['id'] . '").innerHTML = "You need to select a category!";
            } else {
              InputCategory.style.border = "3px solid green";
              document.getElementById("msgCategory_' . $row['id'] . '").innerHTML = "";
              document.getElementById("msgCategory_' . $row['id'] . '").style.display = "block";
            }

            if(InputCategory.value == "Review") {
              
                var InputScore = document.getElementById("Number_' . $row['id'] . '");
                var score = document.getElementById("Number_' . $row['id'] . '").value;
                if (score < 1 || score > 10) {
                  var SendForm = false;
                  InputScore.style.border = "2px dashed #fcd303";
                  InputScore.style.color = "#fcd303";
                  document.getElementById("msgNumber_' . $row['id'] . '").innerHTML="Only from 1 to 10 is acceptable!";                  
                } else {
                  InputScore.style.border = "3px solid green";
                  InputScore.style.color = "green";
                  document.getElementById("msgNumber_' . $row['id'] . '").innerHTML="";                 
                }
              
                var Positive1 = document.getElementById("good1_' . $row['id'] . '").value;
                var Positive2 = document.getElementById("good2_' . $row['id'] . '").value;
                var Positive3 = document.getElementById("good3_' . $row['id'] . '").value;
                if (Positive1 === "" && Positive2 === "" && Positive3 === "") {
                  var SendForm = false;
                  document.getElementById("good1_' . $row['id'] . '").style.borderBottom = "2px dashed #fcd303";
                  document.getElementById("good2_' . $row['id'] . '").style.borderBottom = "2px dashed #fcd303";
                  document.getElementById("good3_' . $row['id'] . '").style.borderBottom = "2px dashed #fcd303";
                  document.getElementById("labelpro1_' . $row['id'] . '").style.color = "#fcd303";
                  document.getElementById("labelpro2_' . $row['id'] . '").style.color = "#fcd303";
                  document.getElementById("labelpro3_' . $row['id'] . '").style.color = "#fcd303";
                  document.getElementById("msgPositive_' . $row['id'] . '").innerHTML="At least one positive review!";
                  
                } else {
                  if (Positive1 !== "") {
                    document.getElementById("good1_' . $row['id'] . '").style.borderBottom = "3px solid green";
                    document.getElementById("labelpro1_' . $row['id'] . '").style.color = "green";
                  } else if (Positive2 !== "") {
                    document.getElementById("good2_' . $row['id'] . '").style.borderBottom = "3px solid green";
                    document.getElementById("labelpro2_' . $row['id'] . '").style.color = "green";
                  } else if (Positive3 !== "") {
                    document.getElementById("good3_' . $row['id'] . '").style.borderBottom = "3px solid green";
                    document.getElementById("labelpro3_' . $row['id'] . '").style.color = "green";
                  }
                  document.getElementById("msgPositive_' . $row['id'] . '").innerHTML="";
                }

                var Negative1 = document.getElementById("bad1_' . $row['id'] . '").value;
                var Negative2 = document.getElementById("bad2_' . $row['id'] . '").value;
                var Negative3 = document.getElementById("bad3_' . $row['id'] . '").value;
                if (Negative1 === "" && Negative2 === "" && Negative3 === "") {
                  var SendForm = false;
                  document.getElementById("bad1_' . $row['id'] . '").style.borderBottom = "2px dashed #fcd303";
                  document.getElementById("bad2_' . $row['id'] . '").style.borderBottom = "2px dashed #fcd303";
                  document.getElementById("bad3_' . $row['id'] . '").style.borderBottom = "2px dashed #fcd303";
                  document.getElementById("labelneg1_' . $row['id'] . '").style.color = "#fcd303";
                  document.getElementById("labelneg2_' . $row['id'] . '").style.color = "#fcd303";
                  document.getElementById("labelneg3_' . $row['id'] . '").style.color = "#fcd303";
                  document.getElementById("msgNegative_' . $row['id'] . '").innerHTML="At least one negative review!";
                  
                } else {
                  if (Negative1 !== "") {
                    document.getElementById("bad1_' . $row['id'] . '").style.borderBottom = "3px solid green";
                    document.getElementById("labelneg1_' . $row['id'] . '").style.color = "green";
                  } else if (Negative2 !== "") {
                    document.getElementById("bad2_' . $row['id'] . '").style.borderBottom = "3px solid green";
                    document.getElementById("labelneg2_' . $row['id'] . '").style.color = "green";
                  } else if (Negative3 !== "") {
                    document.getElementById("bad3_' . $row['id'] . '").style.borderBottom = "3px solid green";
                    document.getElementById("labelneg3_' . $row['id'] . '").style.color = "green";
                  }
                  document.getElementById("msgNegative_' . $row['id'] . '").innerHTML="";
                }

            }

            var InputTitle = document.getElementById("title_' . $row['id'] . '");
            var title = document.getElementById("title_' . $row['id'] . '").value;
            if (title.length < 5 || title.length > 50) {
              var SendForm = false;
              InputTitle.style.borderBottom = "2px dashed #fcd303";
              document.getElementById("label1_' . $row['id'] . '").style.color = "#fcd303";          
              document.getElementById("msgTitle_' . $row['id'] . '").innerHTML = "You need to enter a title between 5 and 50 characters!";
            } else {
              InputTitle.style.borderBottom = "3px solid green";
              document.getElementById("label1_' . $row['id'] . '").style.color = "green";
              document.getElementById("msgTitle_' . $row['id'] . '").innerHTML = "";
            }

            var InputAuthor = document.getElementById("Author_' . $row['id'] . '");
            var name = document.getElementById("Author_' . $row['id'] . '").value;
            if (name.length < 2 || name.length > 25) {
              var SendForm = false;
              InputAuthor.style.borderBottom = "2px dashed #fcd303";
              document.getElementById("label2_' . $row['id'] . '").style.color = "#fcd303";
              document.getElementById("msgAuthor_' . $row['id'] . '").innerHTML = "The length of author name needs to be between 2 and 25!";
            } else {
              InputAuthor.style.borderBottom = "3px solid green";
              document.getElementById("label2_' . $row['id'] . '").style.color = "green";
              document.getElementById("msgAuthor_' . $row['id'] . '").innerHTML = "";
            }


            var InputSummary = document.getElementById("Summary_' . $row['id'] . '");
            var summary = document.getElementById("Summary_' . $row['id'] . '").value;
            if (summary.length < 10 || summary.length > 236) {
              var SendForm = false;
              InputSummary.style.border = "2px dashed #fcd303";
              InputSummary.style.color = "#fcd303";
              document.getElementById("msgShort_' . $row['id'] . '").innerHTML = "The length of summary needs to be between 10 and 236 characters!";
            } else {
              InputSummary.style.border = "3px solid green";
              InputSummary.style.color = "green";
              document.getElementById("msgShort_' . $row['id'] . '").innerHTML = "";
            }


            var InputContent = document.getElementById("Content_' . $row['id'] . '");
            var content = document.getElementById("Content_' . $row['id'] . '").value;
            if (content.length == 0) {
              var SendForm = false;
              InputContent.style.border = "2px dashed #fcd303";
              InputContent.style.color = "#fcd303";
              document.getElementById("msgLong_' . $row['id'] . '").innerHTML = "You need to write a content!";
            } else {
              InputContent.style.border = "3px solid green";
              InputContent.style.color = "green";
              document.getElementById("msgLong_' . $row['id'] . '").innerHTML = "";
            }


            


            var InputPhoto = document.getElementById("Pictures_' . $row['id'] . '");
            var Photo = document.getElementById("Pictures_' . $row['id'] . '").value;
            var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif|\.bmp|\.tif|\.tiff|\.eps|\.raw)$/i;
            if (!allowedExtensions.exec(Photo)) {
              var SendForm = false;
              InputPhoto.style.border = "2px dashed #fcd303";
              document.getElementById("msgPicture_' . $row['id'] . '").innerHTML = "You need to choose a valid photo format!";
            } else {
              InputPhoto.style.border = "3px solid green";
              document.getElementById("msgPicture_' . $row['id'] . '").innerHTML = "";
              document.getElementById("msgPicture_' . $row['id'] . '").style.display = "block";
            }
            
            if (SendForm != true) {
              event.preventDefault();
            }

          }
      </script>';
      print '<script> 
      $("#forma_' . $row['id'] . ' input[type=text],#forma_' . $row['id'] . ' textarea").keyup(function() {
        var check = $(this).val();
        var checkID = this.id;
        if (check.includes("\'")) {
          var SendForm = false;
          document.getElementById("msgQuote_' . $row['id'] . '").innerHTML = "You need to remove quotation mark!";
          if (document.getElementById(checkID) == title_' . $row['id'] . ' || document.getElementById(checkID) == Author_' . $row['id'] . '
           || document.getElementById(checkID) == good1_' . $row['id'] . ' || document.getElementById(checkID) == good2_' . $row['id'] . '
            || document.getElementById(checkID) == good3_' . $row['id'] . ' || document.getElementById(checkID) == bad1_' . $row['id'] . '
           || document.getElementById(checkID) == bad2_' . $row['id'] . ' || document.getElementById(checkID) == bad3_' . $row['id'] . ') {
            document.getElementById(checkID).style.borderBottom = "2px dashed #fcd303";
            document.getElementById(checkID).style.color = "#fcd303";
          } else {
            document.getElementById(checkID).style.border = "2px dashed #fcd303";
            document.getElementById(checkID).style.color = "#fcd303";
          }
        } else {
          var SendForm = true;
          document.getElementById("msgQuote_' . $row['id'] . '").innerHTML = "";
          if (document.getElementById(checkID) == title_' . $row['id'] . ' || document.getElementById(checkID) == Author_' . $row['id'] . '
           || document.getElementById(checkID) == good1_' . $row['id'] . ' || document.getElementById(checkID) == good2_' . $row['id'] . '
            || document.getElementById(checkID) == good3_' . $row['id'] . ' || document.getElementById(checkID) == bad1_' . $row['id'] . '
             || document.getElementById(checkID) == bad2_' . $row['id'] . ' || document.getElementById(checkID) == bad3_' . $row['id'] . ') {
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
          document.getElementById("msgQuote_' . $row['id'] . '").innerHTML = "";
        }
      });


      $("#forma_' . $row['id'] . ' textarea").keyup(validateTextarea);

        function validateTextarea() {
          var errorMsg = "Please match the format requested";
          var textarea = this;
          var pattern = new RegExp("^" + $(textarea).attr("pattern") + "$");
          // check each line of text
          $.each($(this).val().split("\n"), function() {
            // check if the line matches the pattern
            var hasError = !this.match(pattern);
            if (typeof textarea.setCustomValidity === "function") {
              textarea.setCustomValidity(hasError ? errorMsg : "");
            } else {
              // Not supported by the browser, fallback to manual error display...
              $(textarea).toggleClass("error", !!hasError);
              $(textarea).toggleClass("ok", !hasError);

              if (hasError) {
                $(textarea).attr("title", errorMsg);
                textarea.style.setProperty("border", "2px dashed #fcd303", "important");
                textarea.style.setProperty("color", "#fcd303", "important");
              } else {
                $(textarea).removeAttr("title");

              }
            }
            return !hasError;
          });
        }

    </script>';
      print '</form>
    <br><br>
    <hr>';
    }
    echo '</main>';
  } else if ($LogedIn == true && $admin == false) {
    echo '<main role="main" class="container-fluid unos">';
    echo "<p>Welcome $UsersName ! You're successfully logged in, but you're not an administrator.</p>";
    echo '</main>';
  } else if (isset($_SESSION['$username']) && $_SESSION['$level'] == 0) {
    echo '<main role="main" class="container-fluid unos">';
    echo "<p>Welcome " . $_SESSION['$username'] . " ! You're successfully logged in, but you're not an administrator.</p>";
    echo '</main>';
  } else if ($LogedIn == false) {
  ?>
    <main role="main" class="container-fluid Register">
      <div class="div-center">
        <div class="content">
          <h3>Log in Form</h3>
          <?php if ($errormsg) {
            print '<p>Username or password is incorrect. Try to register yourself if your first time here</p>';
          } ?>
          <form action="" enctype="multipart/form-data" method="post" class="SignForm">

            <span id="msgUsername" class="msgColor"></span>
            <div class="form-floating col-md-10 col-sm-10">
              <input name="username" type="text" class="form-control" id="floatingInput3" placeholder="Type your username">
              <label for="floatingInput3" id="label3">Username *</label>
            </div>

            <span id="msgPassword" class="msgColor"></span>
            <div class="form-floating col-sm-10 col-md-10">
              <input name="password" type="password" class="form-control" id="floatingPassword1" placeholder="Type your password">
              <label for="floatingPassword1" id="label4" class="form-label">Password *</label>
            </div>

            <button id="log" name="Login" type="submit" class="login col-md-8 col-sm-8 btn btn-danger">LOG IN</button>
          </form>

          <!-- Check form -->
          <script type="text/javascript">
            document.getElementById("log").onclick = function(event) {
              var SendForm = true;

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
              if (password.length == 0) {
                var SendForm = false;
                document.getElementById("label4").style.color = "#fcd303";
                InputPassword.style.borderBottom = "2px dashed #fcd303";
                document.getElementById("msgPassword").innerHTML = "You need to enter your password!";
              } else {
                InputPassword.style.borderBottom = "2px solid green";
                document.getElementById("label4").style.color = "green";
                document.getElementById("floatingPassword1").innerHTML = "";
              }
              if (SendForm != true) {
                event.preventDefault();
              }
            }
          </script>
        </div>
      </div>
    </main>
  <?php
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
      var scrollTotal = rootElement.scrollHeight - rootElement.clientHeight;
      if (rootElement.scrollTop / scrollTotal > 0.5) {
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
