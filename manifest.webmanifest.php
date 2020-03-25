<?php
    require("./db/lib/config.php");
    function get_dev()
    {
        return is_dev() ? "dev" : "";
    }
?>

{
"dir": "ltr",
"lang": "de-DE",
"name": "Liesmich (α)",
"short_name": "(α)",
"description": "Hyperlokales Soziales Netzwerk",
"icons": [{
        "src": "img/icon512<?php print get_dev() ?>.png",
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