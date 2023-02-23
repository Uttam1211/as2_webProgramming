<main class="sidebar">
    <?php include __DIR__ . '/../navigation.html.php'; ?>
    <section class="right">
        <h2>Categories</h2>

        <?php if ($authUser->hasPermission(\josJobs\Entity\User::PERM_CREATE_CATEGORIES)): ?>
            <a class="new" href="/admin/categories/create">
                Create new category
            </a>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th style="width: 5%;">&nbsp;</th>
                    <th style="width: 5%;">&nbsp;</th>
                </tr>
                <?php foreach($categories as $category): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <td>
                            <?php if ($authUser->hasPermission(\josJobs\Entity\User::PERM_UPDATE_CATEGORIES)): ?>
                                <a style="float: right;" href="/admin/categories/update?id=<?php echo $category->category_id; ?>">
                                    Update
                                </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($authUser->hasPermission(\josJobs\Entity\User::PERM_DELETE_CATEGORIES)): ?>
                                <form action="/admin/categories/delete" method="post">
                                    <input type="hidden" name="category_id" value="<?php echo $category->category_id; ?>" />
                                    <input type="submit" name="submit" value="Delete" />
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </thead>
        </table>
    </section>
</main>
