<!doctype html>
<html lang="en" prefix="og: http://ogp.me/ns#">
    <head>
        <!-- Metadata -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0,
            user-scalable=no, viewport-fit=cover"
        />
        <meta http-equiv="x-ua-compatible" content="IE=edge" />

        <!-- Document Title -->
        <title>Jo's Jobs - <?php echo $title; ?></title>

        <!-- Font(s) -->
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css?family=Oxygen&display=swap"
        />

        <!-- Stylesheets -->
        <link rel="stylesheet" href="/styles/main.css" />

        <!-- Favicons -->
        <link
            rel="apple-touch-icon"
            sizes="180x180"
            href="/public/images/favicons/apple-touch-icon.png"
        />
        <link
            rel="icon"
            type="image/png"
            sizes="32x32"
            href="/public/images/favicons/favicon-32x32.png"
        />
        <link
            rel="icon"
            type="image/png"
            sizes="16x16"
            href="/public/images/favicons/favicon-16x16.png"
        />
        <link rel="shortcut icon" href="/favicon.ico" />

        <!-- SEO - Basic -->
        <meta name="author" content="@uttam1211" />
        <meta
            name="description"
            content="Welcome to Jo's Jobs, we're a recruitment agency based in
            Northampton. We offer a range of different office jobs. Get in
            touch if you'd like to list a job with us."
        />

    </head>
    <body>
        <?php if ($user): ?>
            <section style="display: flex; flex-direction: row; flex-flow: nowrap; align-items: center; justify-content: space-between; padding: 1em;">
                <p style="margin: 0; text-align: left;">Welcome back, </p>
                <div class="actions">
                    <p style="margin: 0; text-align: right;">
                        <a href="/admin/" style="margin-right: 1em;">Dashboard</a>
                        <a href="/id/logout">Logout</a>
                    </p>
                </div>
            </section>
        <?php endif; ?>
        <header>
            <section>
                <aside>
                    <h3>Office Hours:</h3>
                    <p>Mon-Fri: 09:00-17:30</p>
                    <p>Sat: 09:00-17:00</p>
                    <p>Sun: Closed</p>
                </aside>
                <h1>Jo's Jobs</h1>
            </section>
        </header>
        <nav>
            <ul>
                <li><a href="/">Home</a></li>
                <li>
                    <a href="/jobs">Jobs</a>
                    <ul>
                        <?php foreach ($categories as $category) : ?>
                            <li>
                                <a href="/jobs?category=<?php echo $category->category_id; ?>">
                                    <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li><a href="/about/">About Us</a></li>
                <li><a href="/about/faq/">FAQ</a></li>
                <li><a href="/contact/">Contact Us</a></li>
                <li><a href="/id/login/">login</a></li>
            </ul>
        </nav>
        <img src="images/random-banner.php" alt="" />
        <?php echo $output; ?>
        <footer>
            <p>&copy; Jo's Jobs <?php echo (new DateTime())->format('Y'); ?></p>
        </footer>
    </body>
</html>
