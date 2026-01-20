<?php

require "../config.php";
require "../common.php";

// Initialize success message
$success = "";

if (isset($_GET["doi"])) {
  try {
    $connection = new PDO($dsn, $username, $password, $options);
  
    $doi = $_GET["doi"];
    
    // Delete from coresp1 table first (authors relationships)
    $sql1 = "DELETE FROM coresp1 WHERE doi_coresp = :doi";
    $statement1 = $connection->prepare($sql1);
    $statement1->bindValue(':doi', $doi);
    $statement1->execute();
    
    // Delete from coresp2 table (domains relationships)
    $sql2 = "DELETE FROM coresp2 WHERE doi_coresp = :doi";
    $statement2 = $connection->prepare($sql2);
    $statement2->bindValue(':doi', $doi);
    $statement2->execute();

    // Delete from articole table
    $sql = "DELETE FROM articole WHERE doi = :doi";
    $statement = $connection->prepare($sql);
    $statement->bindValue(':doi', $doi);
    $statement->execute();
    
    $success = "Article deleted successfully";

  } catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
  }
}

try {
  $connection = new PDO($dsn, $username, $password, $options);

  // Get all articles
  $sql = "SELECT * FROM articole ORDER BY doi";
  $statement = $connection->prepare($sql);
  $statement->execute();
  $articles = $statement->fetchAll();
  
  // Get all authors for each article
  $articles_with_authors = [];
  foreach ($articles as $article) {
    $author_sql = "SELECT a.nume 
                  FROM autori a 
                  JOIN coresp1 c ON a.id = c.id_autor 
                  WHERE c.doi_coresp = :doi";
    $author_stmt = $connection->prepare($author_sql);
    $author_stmt->bindValue(':doi', $article['doi']);
    $author_stmt->execute();
    $authors = $author_stmt->fetchAll();
    
    // Combine article with authors
    $article['autori'] = $authors;
    $articles_with_authors[] = $article;
  }
  
} catch(PDOException $error) {
  echo $sql . "<br>" . $error->getMessage();
}
?>
<?php require "templates/header.php"; ?>
        
<h2>Delete article</h2>

<?php if ($success) { ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php } ?>

<table>
  <thead>
    <tr>
      <th>DOI</th>
      <th>Titlu articol</th>
      <th>Tara</th>
      <th>Jurnal</th>
      <th>An publicatie</th>
      <th>Autori</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($articles_with_authors as $row) : ?>
    <tr>
      <td><?php echo escape($row["doi"]); ?></td>
      <td><?php echo escape($row["titlu_articol"]); ?></td>
      <td><?php echo escape($row["tara"]); ?></td>
      <td><?php echo escape($row["jurnal"]); ?></td>
      <td><?php echo escape($row["anpub"]); ?></td>
      <td>
        <?php 
        if (!empty($row['autori'])) {
            $author_names = [];
            foreach ($row['autori'] as $author) {
                $author_names[] = escape($author['nume']);
            }
            echo implode(", ", $author_names);
        } else {
            echo "No authors";
        }
        ?>
      </td>
      <td><a href="remove.php?doi=<?php echo escape($row["doi"]); ?>" onclick="return confirm('Are you sure you want to delete this article?')">Delete</a></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<?php if (empty($articles_with_authors)) { ?>
    <p>No articles found.</p>
<?php } ?>

<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>