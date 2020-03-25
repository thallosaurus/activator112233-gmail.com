<?php
require("./vendor/autoload.php");
require("./db/lib/config.php");
echo "";

function color()
{
    echo $GLOBALS["lm"]["CNF"]["COLOR"];
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
    <link rel="manifest" href="manifest.webmanifest">
    <script async src="https://cdn.jsdelivr.net/npm/pwacompat@2.0.6/pwacompat.min.js",
    integrity="sha384-GOaSLecPIMCJksN83HLuYf9FToOiQ2Df0+0ntv7ey8zjUHESXhthwvq9hXAZTifA",
    crossorigin="anonymous"></script>

    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="apple-touch-icon" href="img/icon512.png">

    <link rel="stylesheet" href="css/main.css">

    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>

    <script type="text/javascript" src="js/materialize.min.js"></script>

    <script src="js/netcode.js"></script>
    <script src="js/main.js"></script>
    <title>(α)</title>
</head>
<body class="<?php color() ?> lighten-5">
    <div class="navbar-fixed">
    <nav>
        <div class="nav-wrapper <?php color() ?> darken-4">
          <a href="#!" class="brand-logo">(α)</a>
          <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
          <ul class="right hide-on-med-and-down">
            <li><a href="sass.html">Sass</a></li>
            <li><a href="badges.html">Components</a></li>
            <li><a href="collapsible.html">Javascript</a></li>
            <li><a href="mobile.html">Mobile</a></li>
          </ul>
        </div>
      </nav>
      </div>
      <ul class="sidenav" id="mobile-demo">
        <!--<nav>
            <div class="nav-wrapper <?php color() ?> darken-4">
                <form>
                    <div class="input-field">
                        <input id="search" type="search">
                        <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                        <i class="material-icons">close</i>
                    </div>
                </form>
            </div>
        </nav>-->
        <li><a href="https://github.com/thallosaurus/liesmich/">GitHub</a></li>
        <li><a href="index.php?cat=-1">All Posts</a></li>
        <li><a href="index.php?cat=7">#tests category</a></li>
      </ul>
    <!--<input type="button" id="update" onclick="update()" value="Update">-->
    <!--<input type="button" id="add_link" onclick="add_link()" value="Add Link"> -->
    <div id="debug"></div>
    <div class="container">
        <div id="posts"></div>
    </div>
    <div style="display: none" id="x">Log:</div>

    <!-- Activate GPS Card -->
    <!--<div class="row">
        <div class="col s12 m6" id="activate_gps">
          <div class="card <?php color() ?> darken-1">
            <div class="card-content white-text">
              <span class="card-title">Please activate location</span>
              <p>To use this app you need to enable locationservices. This app sends your location to the backend server and returns posts around you in a radius of 25km. If you post something, your location will be saved with what you posted (Without IP, for now)!</p>
            </div>
            <div class="card-action">
              <a onclick="enable_location()">Ok, activate!</a>
            </div>
          </div>
        </div>
      </div>-->

    <!-- FAB -->
    <div class="fixed-action-btn">
        <a class="btn-floating btn-large red">
          <i class="large material-icons">add</i>
        </a>
        <ul>
          <li><a class="btn-floating red modal-trigger" data-target="add_text"><i class="material-icons">text_fields</i></a></li>
          <li><a class="btn-floating yellow darken-1 modal-trigger disabled" data-target="add_photo"><i class="material-icons">insert_photo</i></a></li>
          <li><a class="btn-floating green modal-trigger" data-target="add_link"><i class="material-icons">link</i></a></li>
        </ul>
      </div>

    <!-- Modal - Add Contents -->
    <!-- Add Text -->
    <div id="add_text" class="modal">
        <div class="modal-content">
            <div class="row">
                <h4>Add a Textpost</h4>
                <form class="col s12" data-type="add_post" data-t="1">
                    <div class="input-field s12">
                        <i class="material-icons prefix">title</i>
                        <input type="text" name="title" id="title" data-length="100" data-use-counter="true" required>
                        <label for="title">Title</label>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <i class="material-icons prefix">message</i>
                            <textarea name="content" id="content" class="materialize-textarea" data-length="500" data-use-counter="true"></textarea>
                            <label for="content">What do you wanna tell?</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <i class="material-icons prefix cat-text">folder</i>
                            <input value="all" type="text" name="category" class="category-picker" id="category" data-length="20" data-use-counter="true">
                            <label for="category">Category</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!--<a href="#!" class="modal-close waves-effect waves-green btn-flat">Send</a>-->
                        <button class="btn-flat waves-effect waves-light" type="submit" name="action">Send it
                            <i class="material-icons right">send</i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Photo -->
    <div id="add_photo" class="modal">
        <div class="modal-content">
            <h4>Add a Photo</h4>
            <form class="col s12" data-type="add_post" data-t="2">
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">title</i>
                        <input type="text" id="title" data-length="100" data-use-counter="true">
                        <label for="title">Title</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix cat-text">folder</i>
                        <input value="all" type="text" name="category" class="category-picker" id="category" data-length="20" data-use-counter="true">
                        <label for="category">Category</label>
                    </div>
                </div>

                <div class="row">
                    <!--<textarea name="content" id="content" class="materialize-textarea" data-length="500" data-use-counter="true"></textarea>-->
                    <div class="col s12">
                        <div class="file-field input-field">
                            <div class="btn">
                                <span><i class="material-icons">insert_photo</i></span>
                                <input type="file">
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" data-dontuse="true" type="text">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <!--<a href="#!" class="modal-close waves-effect waves-green btn-flat">Send</a>-->
                    <button class="btn-flat waves-effect waves-light modal-close" type="submit" name="action">Send it
                        <i class="material-icons right">send</i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Link -->
    <div id="add_link" class="modal">
        <div class="modal-content">
            <h4>Add Link</h4>
            <p>A bunch of text</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Agree</a>
        </div>
    </div>
</body>
</html>