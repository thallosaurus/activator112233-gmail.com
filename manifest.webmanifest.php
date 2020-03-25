<?php
    require("./db/lib/config.php");
?>

{
"dir": "ltr",
"lang": "de-DE",
"name": "Liesmich (α) <?php print get_dev() ?>",
"short_name": "(α) <?php print get_dev() ?>",
"description": "Hyperlokales Soziales Netzwerk",
"icons": [{
        "src": "img/icon<?php print get_dev() ?>512.png",
        "type": "image/png",
        "sizes": "512x512"
    }],
"background_color": "white",
"theme_color": "#f4f4f4",
"start_url": "https://liesmich<?php print get_dev() ?>.herokuapp.com/",
"scope": "https://liesmich<?php print get_dev() ?>.herokuapp.com",
"display": "standalone",
"orientation": "portrait-primary"
}