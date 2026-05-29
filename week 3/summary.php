<?php
/**
 * Week 3 – Summary page
 * -------------------------------------------------
 * This page demonstrates reuse of Week 1 and Week 2 assets.
 * It includes the Week 1 Hello World page and the Week 2
 * car‑grid homepage, using the same stylesheet and a tiny
 * JavaScript helper.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rmt Cars – Weekly Summary</title>
    <!-- Re‑use Week 2 stylesheet -->
    <link rel="stylesheet" href="../week 2/style.css">
    <!-- JavaScript for interactive elements -->
    <script src="main.js" defer></script>
</head>
<body>
    <header>
        <div class="logo-container"><span></span> Rmt Cars – Summary</div>
        <nav>
            <ul>
                <li><a href="../week 1/index.php">Week 1 Hello</a></li>
                <li><a href="../week 2/homepage.php">Week 2 Car Grid</a></li>
                <li><a href="summary.php">Summary</a></li>
            </ul>
        </nav>
    </header>

    <main class="container" style="margin-top:2rem;">
        <h2>Welcome to Week 3</h2>
        <p>This page pulls together the work you did in the previous weeks.</p>

        <section style="margin-top:2rem;">
            <h3>Week 1 – Hello World</h3>
            <?php include __DIR__ . '/../week 1/index.php'; ?>
        </section>

        <section style="margin-top:2rem;">
            <h3>Week 2 – Car Listings</h3>
            <?php include __DIR__ . '/../week 2/homepage.php'; ?>
        </section>
    </main>
</body>
</html>
