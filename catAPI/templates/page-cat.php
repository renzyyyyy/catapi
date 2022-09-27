<?php
get_header();

$id = get_query_var('id', false);
$catAPI = new Cats();
list($headers, $cat) = $catAPI->get_cat($id);
?>

<div class="main">
    <div class="cat">
        <?php if (!empty($cat)) : ?>
            <div class="cat-image">
                <a href="<?php echo get_site_url() ?>?breed=<?php echo $cat['breeds'][0]['id'] ?>" class="button">❮ BACK</a>
                <img src="<?php echo $cat['url'] ?>" />
            </div>
            <div class="cat-details">
                <h1 class="name"><?php echo $cat['breeds'][0]['name'] ?></h1>
                <h2>Origin:&nbsp;<?php echo $cat['breeds'][0]['origin'] ?></h2>
                <h3><?php echo $cat['breeds'][0]['temperament'] ?></h3>
                <h3><?php echo $cat['breeds'][0]['description'] ?></h3>
            </div>
        <?php else : ?>
            <div class="cat-error">
            <p>Apologies but we could not load the cat details for you at this time! Miau!</p>
            <a href="<?php echo get_site_url() ?>" class="button">❮ BACK</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php get_footer(); ?>