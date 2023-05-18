<?php
require_once __DIR__ . "/../../Template.php";

Template::header("Edit " . $this->model->blog_id);
?>

<h1>Edit <?= $this->model->blog_id ?></h1>

<form action="<?= $this->home ?>/blogs/<?= $this->model->blog_id ?>/edit" method="post">
    <input type="text" name="title" value="<?= $this->model->title ?>" placeholder="title"> <br>
    <input type="text" name="content" value="<?= $this->model->content ?>" placeholder="content"> <br>
    <input type="text" name="place_id" value="<?= $this->model->place_id ?>" placeholder="place ID"> <br>
    
    <input type="number" name="user_id" value="<?= $this->model->user_id ?>" placeholder="User ID"> <br>

    <input type="submit" value="Save" class="btn">
</form>

<form action="<?= $this->home ?>/blogs/<?= $this->model->blog_id ?>/delete" method="post">
    <input type="submit" value="Delete" class="btn delete-btn">
</form>

<?php Template::footer(); ?>