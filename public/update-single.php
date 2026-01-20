<?php
require "../config.php";
require "../common.php";

// Get the DOI from URL
if (isset($_GET['doi'])) {
    try {
        $connection = new PDO($dsn, $username, $password, $options);
        $doi = $_GET['doi'];
        
        // Get article details
        $sql = "SELECT * FROM articole WHERE doi = :doi";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':doi', $doi);
        $statement->execute();
        
        $articol = $statement->fetch(PDO::FETCH_ASSOC);
        
        if (!$articol) {
            echo "Article not found!";
            exit;
        }
        
        // Get authors for this article
        $author_sql = "SELECT a.nume 
                      FROM autori a 
                      JOIN coresp1 c ON a.id = c.id_autor 
                      WHERE c.doi_coresp = :doi";
        $author_stmt = $connection->prepare($author_sql);
        $author_stmt->bindValue(':doi', $doi);
        $author_stmt->execute();
        $authors = $author_stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
        exit;
    }
} else {
    echo "No DOI specified!";
    exit;
}

// Handle form submission
if (isset($_POST['submit'])) {
    try {
        $con = new PDO($dsn, $username, $password, $options);
        
        $articol_data = [
            "doi" => $_POST['doi'],
            "titlu_articol" => $_POST['titlu_articol'],
            "tara" => $_POST['tara'],
            "jurnal" => $_POST['jurnal'],
            "anpub" => $_POST['anpub']
        ];

        // Update article details
        $sql = "UPDATE articole 
                SET titlu_articol = :titlu_articol, 
                    tara = :tara, 
                    jurnal = :jurnal, 
                    anpub = :anpub 
                WHERE doi = :doi";
        
        $statement = $con->prepare($sql);
        $statement->execute($articol_data);
        
        // Handle authors (if you want to update them too)
        // Note: This is complex since authors are in a separate table
        // You'll need to handle the coresp1 table updates separately
        
        $success = true;
        
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>

<?php require "templates/header.php"; ?>

<?php if (isset($success) && $success) : ?>
    <blockquote><?php echo escape($_POST['titlu_articol']); ?> successfully updated.</blockquote>
<?php endif; ?>

<h2>Edit Article</h2>

<form method="post">
    <div class="form-group">
        <label for="doi">DOI</label>
        <input type="text" name="doi" id="doi" value="<?php echo escape($articol['doi']); ?>" class="form-control" readonly>
    </div>
    
    <div class="form-group">
        <label for="titlu_articol">Titlu Articol</label>
        <input type="text" name="titlu_articol" id="titlu_articol" value="<?php echo escape($articol['titlu_articol']); ?>" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label for="tara">Tara</label>
        <input type="text" name="tara" id="tara" value="<?php echo escape($articol['tara']); ?>" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label for="jurnal">Jurnal</label>
        <input type="text" name="jurnal" id="jurnal" value="<?php echo escape($articol['jurnal']); ?>" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label for="anpub">An Publicatie</label>
        <input type="number" name="anpub" id="anpub" value="<?php echo escape($articol['anpub']); ?>" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label for="authors">Current Authors</label>
        <div class="form-control" style="min-height: 100px; background-color: #f8f9fa;">
            <?php if (!empty($authors)): ?>
                <?php foreach ($authors as $author): ?>
                    <div><?php echo escape($author); ?></div>
                <?php endforeach; ?>
            <?php else: ?>
                <div>No authors found</div>
            <?php endif; ?>
        </div>
        <small class="form-text text-muted">Note: To update authors, you need to use a different form with multi-select functionality.</small>
    </div>
    
    <input type="submit" name="submit" value="Update Article" class="btn btn-primary">
    <a href="update.php" class="btn btn-secondary">Back to list</a>
</form>

<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>