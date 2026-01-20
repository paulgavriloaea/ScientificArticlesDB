<?php
if (isset($_POST['submit'])) {
    try {
        require "../config.php";
        require "../common.php";

        $con = new PDO($dsn, $username, $password, $options);

        $sql = "SELECT * FROM articole WHERE doi = :doi";
        $doi = $_POST['doi'];

        $statement = $con->prepare($sql);
        $statement->bindParam(':doi', $doi, PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetchAll();
        
        // Get authors for this article
        if ($result && $statement->rowCount() > 0) {
            $article_doi = $result[0]['doi'];
            
            // Query to get authors for this article
            $author_sql = "SELECT a.nume 
                          FROM autori a 
                          JOIN coresp1 c ON a.id = c.id_autor 
                          WHERE c.doi_coresp = :doi";
            
            $author_stmt = $con->prepare($author_sql);
            $author_stmt->bindParam(':doi', $article_doi, PDO::PARAM_STR);
            $author_stmt->execute();
            $authors = $author_stmt->fetchAll();
        }
        
    } catch(PDOException $error) {
        echo $sql."<br>".$error->getMessage();
    }
}
?>
<?php require "templates/header.php"; ?>

<?php
if(isset($_POST['submit'])) {
    if($result && $statement->rowCount() > 0) { 
?>

<h2>Results</h2>

<table>
    <thead>
        <tr>
            <th>DOI</th>
            <th>Titlu articol</th>
            <th>Tara</th>
            <th>Jurnal</th>
            <th>An publicație</th>
            <th>Autori</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($result as $row) { ?>
        <tr>
            <td><?php echo escape($row["doi"]); ?></td>
            <td><?php echo escape($row["titlu_articol"]); ?></td>
            <td><?php echo escape($row["tara"]); ?></td>
            <td><?php echo escape($row["jurnal"]); ?></td>
            <td><?php echo escape($row["anpub"]); ?></td>
            <td>
                <?php 
                // Display authors
                if (isset($authors) && count($authors) > 0) {
                    $author_names = [];
                    foreach ($authors as $author) {
                        $author_names[] = escape($author['nume']);
                    }
                    echo implode(", ", $author_names);
                } else {
                    echo "No authors found";
                }
                ?>
            </td>
        </tr>
        <?php } ?> 
    </tbody>
</table>
<?php } else { ?>
    <blockquote>No results found for <?php echo escape($_POST['doi']); ?>.</blockquote>
<?php } 
} ?> 

<h2>Find article based on DOI</h2>

<form method="post">
    <label for="doi">DOI</label>
    <input type="text" id="doi" name="doi" required>
    <input type="submit" name="submit" value="View Results">
</form>

<a href="index.php">Back</a>

<?php include "templates/footer.php"; ?>