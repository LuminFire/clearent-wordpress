<div class="postbox">
    <h3>Active Pages Using Clearent Payments Plugin Shortcode</h3>

    <p>Below is a list of all pages that use the Clearent Payments plugin shortcode (links open in
        new window).</p>
    <?php
    // Display pages using the shortcode
    $args = array('order' => 'ASC', 's' => '[clearent_pay_form ');
    $pages = new WP_Query($args);
    if ($pages->have_posts()) {
        echo '<ul>';
        while ($pages->have_posts()) {
            $pages->the_post();
            ?>
            <li><a target="_blank" href="<?php the_permalink() ?>"><?php the_title(); ?></a>
            </li><?php
        }
        echo '</ul>';
    } else {
        echo('There are no pages that use the Clearent Payments plugin shortcode.');
    }
    wp_reset_postdata();
    ?>
</div>