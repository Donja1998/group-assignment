<?php

class Template
{
    public static function header($title, $error = false)
    {
        $home_path = getHomePath();
        $user = getUser();
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?= $title ?> - Travel blog</title>

            <link rel="stylesheet" href="<?= $home_path ?>/assets/css/style.css">

            <script src="<?= $home_path ?>/assets/js/script.js"></script>
        </head>
        <nav>
            <a href="<?= $home_path ?>">Home</a>

            <?php if ($user) : ?>
                <a href="<?= $home_path ?>/auth/profile">Profile</a>
                <a href="<?= $home_path ?>/blogs">My Blogs</a>
            <?php else : ?>
                <a href="<?= $home_path ?>/auth/login">Log in</a>
            <?php endif; ?>
        </nav>

        <body>
        

            <main>

                <?php if ($error) : ?>
                    <div class="error">
                        <p><?= $error ?></p>
                    </div>
                <?php endif; ?>

            <?php }



public static function footer()
{
    $home_path = getHomePath();

    ?>
        <footer>
            <div class="footer-content">
                <div class="footer-section">
                <p id="logo">THYNK TRAVEL</p>
                    <p>Sign up for our newsletter:</p>
                    <input type="email" placeholder="Enter your email" class="newsletter-input">
                    <button class="newsletter-button">Sign up</button>
                </div>
                <div class="footer-section">
                    <ul class="footer-pages">
                    <li><a href="<?= $home_path ?>">Home</a></li>
                    <li>   <a href="<?= $home_path ?>/auth/profile">Profile</a></li>
                    <li> <a href="<?= $home_path ?>/blogs">My Blogs</a>
                
                    </ul>
                </div>
                
            </div>
            <div class="footer-bottom">
                &copy; 2025 Your Website. All rights reserved.
            </div>
        </footer>
</body>

</html>
<?php
}
}


    class HomeTemplate
    {
        public static function HomeHeader($title, $error = false)
        {
            $home_path = getHomePath();
            $user = getUser();
    ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?= $title ?> - Travel blog</title>

            <link rel="stylesheet" href="<?= $home_path ?>/assets/css/style.css">

            <script src="<?= $home_path ?>/assets/js/script.js"></script>
        </head>
        <nav>
            <a href="<?= $home_path ?>">Home</a>

            <?php if ($user) : ?>
                <a href="<?= $home_path ?>/auth/profile">Profile</a>
                <a href="<?= $home_path ?>/blogs">My Blogs</a>
            <?php else : ?>
                <a href="<?= $home_path ?>/auth/login">Log in</a>
            <?php endif; ?>
        </nav>

        <body>
        <section class="hero">
        <img src="<?= $home_path ?>/assets/img/home-bg.jpeg" alt="Image 1">
            <h1>TRAVEL</h1>
        </section>

            <body>
            <h1 class="h1-home"> Latest articles  </h1>

                <div class="home-articles">
                    <?php
                    $latestBlogs = BlogsServices::getAllBlogs();
                    $articleCount = 0;

                    echo '<div class="row">';
                    foreach ($latestBlogs as $blog) {
                        if ($articleCount >= 6) {
                            break; // Exit the loop after displaying 6 articles
                        }

                        if ($articleCount % 2 == 0 && $articleCount > 0) {
                            echo '</div><div class="row">';
                        }
                    ?>
                        <div class="home-column">
                            <article class="home-articles-item">
                                <img src="/group-assignment/multitier-blog/home/<?= $blog->blog_pic_url ?>" alt="<?= $blog->title ?>">
                                <h2><?= $blog->title ?></h2>
                                <p><?= $blog->content ?></p>
                                <a href="<?= $home_path ?>/blogs/<?= $blog->blog_id ?>">Show Blog</a>

                            </article>
                        </div>
                    <?php
                        $articleCount++;
                    }
                    echo '</div>';
                    ?>
                </div>




            </body>


            <main>

                <?php if ($error) : ?>
                    <div class="error">
                        <p><?= $error ?></p>
                    </div>
                <?php endif; ?>

            <?php }



        public static function footer()
        {
            ?>
            </main>
            <footer>
                Copyright 2025
            </footer>
        </body>

        </html>
<?php }
    }
