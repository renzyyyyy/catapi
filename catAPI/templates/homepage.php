<?php get_header(); ?>
<div class="main">
    <?php catapi_breeds(); ?>
    <div class="loading-top">Loading..</div>
    <div id="container">
        <?php list($pagination, $cats) = catapi_get_cats();
        $hide = 'hide-me';
        echo '<div id="cats" class="cat-container">';
        if (!empty($cats)) {
            foreach ($cats as $cat) {
                echo '<div class="cat">';
                echo '<div class="cat-image">';
                echo '<a href="'. site_url('/cat').'?id='.$cat['id'].'" style="background-image: url(' . $cat['url'] . ');"></a>';
                echo '</div>';
                echo '<a class="button view-details" href="'. site_url('/cat').'?id='.$cat['id'].'">View Details</a>';
                echo '</div>'; // #cat
            }
            $hide = ($pagination['current'] < $pagination['total'] ? '' : 'hide-me');
        }
        echo '</div>'; // #cats

        echo '<div class="button load-more ' . $hide . '" id="load-more" data-page="' . $pagination['page'] . '">Load More</div>';

        ?>

    </div>
    <div class="loading-bottom">Loading..</div>
</div>
<?php get_footer(); ?>