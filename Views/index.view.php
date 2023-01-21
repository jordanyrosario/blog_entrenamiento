<?php
    $title = 'Blog';

    require_once '../Views/template/header.index.php';

    ?>


<main class="container min-vh-100">
    <div class="p-4 p-md-5 mb-4 text-white rounded bg-dark">
        <?php if (empty($posts)) { ?>
        <div class="col-md-6 px-0">
            <h1 class="display-4 fst-italic">No blogs yet :(</h1>
        </div>
        <?php } else { ?>
        <div class="col-md-6 px-0">
            <h1 class="display-4 fst-italic"><?php echo $posts[0]->title; ?></h1>
            <p class="lead my-3"><?php echo substr($posts[0]->description, 0, 40); ?></p>
            <p class="lead mb-0"><a href="/view/<?php echo $posts[0]->getSlug(); ?>" class="text-white fw-bold">Continue
                    reading...</a></p>
        </div>
        <?php } ?>
    </div>



    <div class="row g-5">
        <div class="col-md-8">
            <h3 class="pb-4 mb-4 fst-italic border-bottom">
                Personal Blog
            </h3>
            <?php
                if (!empty($posts)) {
                    $posts = array_slice($posts, 1, 4);
                    foreach ($posts as $post) { ?>

            <article class="blog-post">
                <h6 class="blog-post-title">
                    <a class="text-black fw-bold" id="Blog-title"  href="/view/<?php echo $post->getSlug(); ?>">
                        <?php echo $post->title; ?>
                    </a>
                </h6>
                
                <p class="blog-post-meta"><?php echo $post->getPublishDate()->format('F d, Y '); ?> </p>

                <p> <?php echo $post->description; ?> </p>
                <hr>
                <p><?php echo substr($post->body, 0, 90); ?></p>

            </article>

            <?php }
                    } ?>


            <nav class="blog-pagination" aria-label="Pagination">
                <a class="btn   <?php echo $page == $pageCount ? 'btn-outline-secondary disabled' : 'btn-outline-primary'; ?>" href="?page=<?php echo $page + 1; ?>">Older</a>
                <a class="btn  <?php echo 1 == $page ? 'btn-outline-secondary disabled' : 'btn-outline-primary'; ?>" href="?page=<?php echo $page - 1; ?>" tabindex="-1" aria-disabled="true">Newer</a>
            </nav>

        </div>

        <div class="col-md-4">
            <div class="position-sticky" style="top: 2rem;">
                <div class="p-4 mb-3 bg-light rounded">
                    <h4 class="fst-italic">About</h4>
                    <p class="mb-0">This blog has been created to share my opinion of the things that surround me
                    </p>
                </div>
            </div>
        </div>
    </div>

</main>

<footer class="blog-footer">
    <p>Jordany 2022</p>
    <p>
        <a href="#">Back to top</a>
    </p>
</footer>



</body>

</html>