<?php
include("Includes/functions.php");

$pageTitle = "Full Catalog";
$section = null;

// matching search patterns
$search = null;

// pagination
$items_per_page = 8;

if (isset($_GET["cat"])) {
    if ($_GET["cat"] == "books") {
        $pageTitle = "Books";
        $section = "books";
    } else if ($_GET["cat"] == "movies") {
        $pageTitle = "Movies";
        $section = "movies";
    } else if ($_GET["cat"] == "music") {
        $pageTitle = "Music";
        $section = "music";
    }
}

// matching search patterns
if (isset($_GET["s"])) {
    $search = filter_input(INPUT_GET, "s", FILTER_SANITIZE_STRING);
}

// pagination
if (isset($_GET["pg"])) {
    $current_page = filter_input(INPUT_GET, "pg", FILTER_SANITIZE_NUMBER_INT);
}

if (empty($current_page)) {
    $current_page = 1;
}

// assigning search parameter
$total_items = get_catalog_count($section, $search);
$total_pages = 1;
$offset = 0;

if ($total_items > 0) {
    // Calculations and Links
    $total_pages = ceil($total_items / $items_per_page);

    // limit results in redirect
    $limit_results = "";
    if (!empty($search)) {
        $limit_results = "s=" . urlencode(htmlspecialchars($search)) . "&";
    } else if (!empty($section)) {
        $limit_results = "cat=" . $section . "&";
    }

    // redirect too large page numbers to the last page
    if ($current_page > $total_pages) {
        header("location:catalog.php?"
            . $limit_results
            . "pg=" . $total_pages);
    }

    // redirect too small page numbers to the first page
    if ($current_page < 1) {
        header("location:catalog.php?"
            . $limit_results
        . "pg=1");
    }

    // determine the offset (number of items to skip) for the current page
    // for example: on page 3 with 8 items per page, the offset would be 16
    $offset = ($current_page - 1) * $items_per_page;

    //adding pagination links
    $pagination = "<div clas=\"pagination\" style=\"text-align: center;\">";
    $pagination .= "Pages: ";
    for ($i = 1;$i <= $total_pages;$i++) {
        if ($i == $current_page) {
            $pagination .= " <span>$i</span>";
        } else {
            $pagination .= " <a href='catalog.php?";
            if (!empty($search)) {
                $pagination .= "s=" . urlencode(htmlspecialchars($search)) . "&";
            } else if (!empty($section)) {
                $pagination .= "cat=" . $section . "&";
            }
            $pagination .= "pg=$i'>$i</a>";
        }
    }
    $pagination .= "</div>";
}

// filtering data by search
// filtering data by category
if (!empty($search)) {
    $catalog = search_catalog_array($search, $items_per_page, $offset);
} else if (empty($section)) {   
    $catalog = full_catalog_array($items_per_page, $offset);
} else {
    $catalog = category_catalog_array($section, $items_per_page, $offset);
}

include('Includes/header.php');

?>

<div class="section catalog page">
    <div class="wrapper">

        <h1>
            <?php
            if ($search != null) {
                echo "Search Results for \"" . htmlspecialchars($search) . "\"";
            } else {
                if ($section != null) {
                    echo "<a class='full-catalog' href='catalog.php'> Full Catalog</a> &gt; ";
                }
                echo $pageTitle;
            }
            ?>
        </h1>
        
        <!-- adding pagination links -->
        <?php
        if ($total_items < 1) {
            echo "<p>No items were found matching that search term.</p>";
            echo "<p>Search Again or "
                . "<a href=\"catalog.php\">Browse the Full Catalog</a></p>";
        } else {
            echo $pagination;
        ?>
    
        <ul class="items">
            <?php
    
            foreach ($catalog as $item) {
                echo get_item_html($item);
            }
            ?>
        </ul>
    
        <!-- adding pagination links -->
        <?php echo $pagination;
        }
        ?>

    </div>
</div>

<?php include('Includes/footer.php'); ?>