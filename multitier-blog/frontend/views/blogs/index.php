<?php
require_once __DIR__ . "/../../Template.php";

Template::header("My Blogs");
?>

<h1>My Blogs </h1>

<div>
<a id="creatNew" href="<?= $this->home ?>/blogs/new">Create new</a>
</div>

<div class="item-grid">
    <?php foreach ($this->model as $blog) : ?>
        <article class="item">
            <div>
                <b><?= $blog->title ?></b> <br>
                <span>Content: <?= $blog->content ?></span> <br>
            </div>

            <?php if ($this->user->user_role === "admin") : ?>
                <p>
                    <b>User ID: </b>
                    <?= $blog->user_id ?>
                </p>
            <?php endif; ?>

            <?php if ($this->user->user_role === "admin") : ?>
                <a href="<?= $this->home ?>/blogs/<?= $blog->blog_id ?>/edit">Edit</a>
            <?php else: ?>
                <a href="<?= $this->home ?>/blogs/<?= $blog->blog_id ?>/edit">Edit</a>
            <?php endif; ?>

            <a href="<?= $this->home ?>/blogs/<?= $blog->blog_id ?>">Show</a>
        </article>
    <?php endforeach; ?>
</div>

<?php Template::footer(); ?>