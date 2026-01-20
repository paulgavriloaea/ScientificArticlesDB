<?php
try {
    require "../common.php";
    require "../config.php";

    $con = new PDO($dsn, $username, $password, $options);

    // Get all articles with their authors
    $sql = "SELECT a.doi, a.titlu_articol, a.tara, a.jurnal, a.anpub, 
                   GROUP_CONCAT(DISTINCT au.nume ORDER BY au.nume SEPARATOR ', ') as autori
            FROM articole a
            LEFT JOIN coresp1 c ON a.doi = c.doi_coresp
            LEFT JOIN autori au ON c.id_autor = au.id
            GROUP BY a.doi, a.titlu_articol, a.tara, a.jurnal, a.anpub
            ORDER BY a.doi";

    $statement = $con->prepare($sql);
    $statement->execute();

    $result = $statement->fetchAll();

} catch(PDOException $error) {
    echo "Error: " . $error->getMessage();
}
?>

<?php require "templates/header.php"; ?>

<h2>Update Articles</h2>

<?php if (empty($result)) { ?>
    <p>No articles found.</p>
<?php } else { ?>
    <table>
        <thead>
            <tr>
                <th>DOI</th>
                <th>Titlu Articol</th>
                <th>Tara</th>
                <th>Jurnal</th>
                <th>An Publicatie</th>
                <th>Autori</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $row) : ?>
                <tr>
                    <td><?php echo escape($row["doi"]); ?></td>
                    <td><?php echo escape($row["titlu_articol"]); ?></td>
                    <td><?php echo escape($row["tara"]); ?></td>
                    <td><?php echo escape($row["jurnal"]); ?></td>
                    <td><?php echo escape($row["anpub"]); ?></td>
                    <td><?php echo escape($row["autori"] ?? "No authors"); ?></td>
                    <td><a href="update-single.php?doi=<?php echo escape($row["doi"]); ?>">Edit</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php } ?>

<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>