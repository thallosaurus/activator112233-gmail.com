<?php
    class Categories
    {
        function __construct($data)
        {
            foreach ($data as $d)
            {
                $this->{"#" . $d["value"]} = null;
            }
        }
    }
?>