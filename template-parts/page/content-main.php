    <div class="four-col">
        <div class="block">
            <div class="thumbnail">
                <span class="garage garage-logo"></span>
                <?php
                $field = get_post_meta( get_the_ID(), 'multiupload', true );
                echo "<img src='".$field[0]."'/>";
                ?>
            </div>

           <?php
           the_title('<h2><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>');
           the_excerpt();
           ?>
            <footer>
                <p>Автор статьи: <?php the_author_posts_link(); ?></p>
            </footer>
        </div>
    </div>