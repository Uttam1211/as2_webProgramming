<main class="sidebar">
    <?php include __DIR__ . '/../navigation.html.php'; ?>
    <section class="right">
        <?php if (!empty($errors)): ?>
            <ul class="errors">
                <?php foreach ($errors as $error): ?>
                    <li class="error">
                        <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <h2><?php echo isset($_GET['id']) ? 'Update' : 'Create' ?> Category</h2>
        <form action="" method="POST">
            <label>Name</label>
            <input
                type="text"
                name="category[name]"
                value="<?php echo htmlspecialchars($category->name ?? '', ENT_QUOTES, 'UTF-8'); ?>"
            />
            <input type="hidden" name="category[category_id]" value="<?php echo $category->category_id ?? ''; ?>" />
            <input type="submit" name="submit" value="<?php echo isset($_GET['id']) ? 'Update' : 'Create' ?>" />
        </form>
    </section>
</main>
