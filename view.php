<?php
require("./vendor/autoload.php");
require("./db/lib/config.php");
require("./db/db_test.php");
require("./db/lib/icons_lib.php");
echo "";

$id = isset($_GET["id"])    ?   trim($_GET["id"])   :   -1;

if ($id == -1)
{
    header("Location: index.php");
}

if ($id == -2)
{
    $a = array();
    $a[] = get_fake_post();
    $thread = $a;
}
else
{
    $thread = dbExec("get_whole_topic", array("id" => $id))->fetchAll(PDO::FETCH_ASSOC);
}

function color()
{
    echo $GLOBALS["lm"]["CNF"]["COLOR"];
}

function get_pinned_cats()
{
    return getenv("PINNED_CATEGORIES");
}

function generate_pins()
{
    //<li><a href="index.php?cat=7">#tests category</a></li>
    $str = "";
    $a = get_categories();

    //debugWrite($a);

    $str = "";

    //$cats = explode(",",get_pinned_cats());
    for ($i = 0; $i < sizeof($a); $i++)
    {
        //$c = get_category_by_id($cats[$i]);

        /*foreach($c as $t)
        {
            debugWrite($t);
            print_r()
        }*/
        $str = $str . "<li ><a href='index.php?cat=" . $a[$i]["id"] . "'><i class='material-icons'>" . /*$a[$i]["icon"]*/ get_random_icon() . "</i>#" . $a[$i]["value"] . " category</a></li>";
    }
    return $str;
}

function generate_comments($id)
{
    /* 
            <ul class="collapsible">
            <li>
                <div class="collapsible-header"><i class="material-icons">filter_drama</i>First</div>
                <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
            </li>
            <li>
                <div class="collapsible-header"><i class="material-icons">place</i>Second</div>
                <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
            </li>
            <li>
                <div class="collapsible-header"><i class="material-icons">whatshot</i>Third</div>
                <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
            </li>
        </ul>
    */

    $str = "";

    $c = get_comments_for_post($id);

    $str = $str . "<!-- " . "Sizeof: " . sizeof($c) . "--><span><h5>Comments</h5></span>";
    if (sizeof($c) > 0)
    {
        $str = $str . "<ul class='collapsible popout'>";
        for ($i = 0; $i < sizeof($c); $i++)
        {
            $str = $str . "<li " . ($i < 1 ? "class='active'" : "")  . ">";
            $str = $str . "<div class='collapsible-header'><i class='material-icons'>comment</i>". ($i + 1) . ": " . $c[$i]["from_user"]  ."</div>";
            $str = $str . "<div class='collapsible-body'" . ($i < 1 ? "style='display: block'" : "") . "><span>". $c[$i]["body"] ."</span></div>";
            $str = $str . "</li>";
        }
        $str = $str . "</ul>";
    }
    else
    {
        $str = $str . "<span>No comments yet</span>";
    }
    return $str;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
    <link rel="manifest" href="manifest.webmanifest.php">
    <script async src="https://cdn.jsdelivr.net/npm/pwacompat@2.0.6/pwacompat.min.js",
    integrity="sha384-GOaSLecPIMCJksN83HLuYf9FToOiQ2Df0+0ntv7ey8zjUHESXhthwvq9hXAZTifA",
    crossorigin="anonymous"></script>

    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="apple-touch-icon" href="img/icon<?php print get_dev()?>512.png">

    <link rel="stylesheet" href="css/main.css">

    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>

    <script type="text/javascript" src="js/materialize.min.js"></script>

    <script src="js/netcode.js"></script>
    <script src="js/view.js"></script>
    <title>(α)</title>
</head>
<body class="<?php color() ?> lighten-5">
    <!--<div id="egg">
        <img id="kitty" src="img/cat.png">
    </div>-->
    <div class="navbar-fixed">
    <nav>
        <div class="nav-wrapper <?php color() ?> darken-4">
          <a href="#!" class="brand-logo center">(α)</a>
          <!--<a href="#" data-target="mobile-demo" class="sidenav-trigger override-display-block"><i class="material-icons">menu</i></a>-->

            <ul class="left">
                <li><a href="index.php"><i class="material-icons">keyboard_arrow_left</i></a></li>
            </ul>
          <ul class="right hide-on-med-and-down">
          <?php 
        if (is_dev())
        {
            echo '<li><a href="/db/call.php?nuke=1">Nuke database</a></li>';
            echo '<li><a href="/newview.php">Test New View</a></li>';
        }
        ?>
          </ul>
        </div>
      </nav>
      </div>
      <!--<ul class="sidenav" id="mobile-demo">
        
        <li><a href="index.php?cat=-1"><i class="material-icons">all_inclusive</i>All Posts</a></li>
        
        <li class="divider"></li>
        <!- class='waves-effect waves-light' ->
        <li><a href="https://github.com/thallosaurus/liesmich/"><i class="material-icons">code</i>GitHub</a></li>
        <?php 
        /*if (is_dev())
        {
            echo '<li class="divider"></li><li><a href="/db/call.php?nuke=1"><i class="material-icons">delete_sweep</i>Nuke database</a></li>';
            echo '<li><a href="/newview.php"><i class="material-icons">delete_sweep</i>Test New View</a></li>';
        }
        echo '<li class="divider"></li>';
        echo generate_pins();*/
        ?>
            <!--<li class="divider"></li><li><a href="/db/call.php?nuke=1"><i class="material-icons">delete_sweep</i>Nuke database</a></li>--

      </ul> -->
    <!--<input type="button" id="update" onclick="update()" value="Update">-->
    <!--<input type="button" id="add_link" onclick="add_link()" value="Add Link"> -->
    <div id="debug"></div>
    <div class="container">
        <!--<div id="posts"></div>-->

        <!-- h4 title -->
        <div class="row">
            <div class="col s12 m6">
                <div class="card blue-grey darken-1">
                    <div class="card-content white-text">
                        <span class="card-title"><?php echo $thread[0]["title"] ?></span>
                            <p><?php echo $thread[0]["content"] ?></p>
                    </div>
                    <div class="card-action">
                        <a href="#">disabled</a>
                        <a href="#">disabled</a>
                    </div>
                </div>
            </div>
        </div>

        <!--<ul class="collapsible">
            <li>
                <div class="collapsible-header"><i class="material-icons">filter_drama</i>First</div>
                <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
            </li>
            <li>
                <div class="collapsible-header"><i class="material-icons">place</i>Second</div>
                <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
            </li>
            <li>
                <div class="collapsible-header"><i class="material-icons">whatshot</i>Third</div>
                <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
            </li>
        </ul>-->
        <?php
            echo generate_comments($id);
        ?>

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
    <div class="fixed-action-btn <?php fab_use_subbuttons() ? "" : "toolbar" ?>">
        <a class="btn-floating btn-large red">
          <i class="large material-icons">add</i>
        </a>
        <ul>
            <!--<li><a class="btn-floating red modal-trigger" data-target="add_text"><i class="material-icons">text_fields</i></a></li>
            <li><a class="btn-floating yellow darken-1 modal-trigger disabled" data-target="add_photo"><i class="material-icons">insert_photo</i></a></li>
            <li><a class="btn-floating green modal-trigger" data-target="add_link"><i class="material-icons">link</i></a></li>-->
            <?php
            if (fab_use_subbuttons())
            {
                print '<li>
                    <a class="btn-floating red modal-trigger" data-target="add_comment">
                        <i class="material-icons">comment</i>
                    </a>
                </li>';
            }
            else
            {
                print '<li>
                    <a class="modal-trigger" data-target="add_comment">
                        <i class="material-icons">comment</i>
                    </a>';
            }
            ?>
        </ul>
      </div>

    <!-- Modal - Add Contents -->
    <!-- Add Text -->
    <div id="add_comment" class="modal">
        <div class="modal-content">
            <div class="row">
                <h4>Add a Comment</h4>
                <form class="col s12" data-type="add_post">

                    <div class="row">
                        <div class="input-field col s12">
                            <i class="material-icons prefix">message</i>
                            <textarea name="content" id="content" class="materialize-textarea" data-length="500" data-use-counter="true"></textarea>
                            <label for="content">What do you wanna tell?</label>
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

</body>
<script>
    const FAB_USE_SUBBUTTONS = <?php print (fab_use_subbuttons() ? "true" : "false")?>
</script>
</html>
