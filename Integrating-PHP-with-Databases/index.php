<?php
include("Includes/functions.php");

$pageTitle = "Personal Media Library";
$section = null;

include('Includes/header.php');

 ?>

    <div class="section catalog random">
        <div class="wrapper">
            <h2>May we suggest Something?</h2>
            <ul class="items">
                <?php

                //Random Fun with Arrays
                $random = random_catalog_array();

                foreach ($random as $item) {
                    echo get_item_html($item);
                }
                ?>
            </ul>

        </div>

    </div>

<?php include('Includes/footer.php'); ?>