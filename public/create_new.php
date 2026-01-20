<?php
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include config and common at the top
require_once "../config.php";
require_once "../common.php";

if (isset($_POST['submit'])) {
    try {
        $con = new PDO($dsn, $username, $password, $options);
        
        // Process authors if they exist
        $authors = isset($_POST['name']) ? $_POST['name'] : [];
        
        // Use custom values if provided
        $tara = (!empty($_POST['custom_tara'])) ? $_POST['custom_tara'] : $_POST['tara'];
        $jurnal = (!empty($_POST['custom_jurnal'])) ? $_POST['custom_jurnal'] : $_POST['jurnal'];
        
        // Insert article
        $new_article = array(
            "doi" => $_POST['doi'],
            "titlu_articol"  => $_POST['titlu_articol'],
            "tara" => $tara,
            "jurnal" => $jurnal,
            "anpub"  => $_POST['anpub']
        );
        
        $sql = sprintf(
            "INSERT INTO %s (%s) values (%s)",
            "articole",
            implode(", ", array_keys($new_article)),
            ":".implode(", :", array_keys($new_article))
        );

        $statement = $con->prepare($sql);
        $statement->execute($new_article);
        
        // Insert authors and create relationships in coresp1 table
        if (!empty($authors)) {
            foreach ($authors as $author_name) {
                if (!empty(trim($author_name))) {
                    // 1. Check if author already exists
                    $check_author = $con->prepare("SELECT id FROM autori WHERE nume = ?");
                    $check_author->execute([$author_name]);
                    $author_result = $check_author->fetch(PDO::FETCH_ASSOC);
                    
                    if ($author_result) {
                        // Author exists, get their ID
                        $author_id = $author_result['id'];
                    } else {
                        // Insert new author
                        $insert_author = $con->prepare("INSERT INTO autori (nume) VALUES (?)");
                        $insert_author->execute([$author_name]);
                        $author_id = $con->lastInsertId();
                    }
                    
                    // 2. Create relationship in coresp1 table
                    $insert_relation = $con->prepare("INSERT INTO coresp1 (id_autor, doi_coresp) VALUES (?, ?)");
                    $insert_relation->execute([$author_id, $_POST['doi']]);
                }
            }
        }
        
        // Store success message in session
        $_SESSION['success_message'] = $_POST['doi'] . ' successfully added.';
        
        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
        
    } catch (PDOException $error) {
        echo "Database Error: " . $error->getMessage() . "<br>";
        if (isset($sql)) {
            echo "SQL: " . $sql . "<br>";
        }
    }
}
?>

<?php require "templates/header.php"; ?>

<div class="container mt-4">
    <?php 
    // Display success message if it exists
    if (isset($_SESSION['success_message'])) { 
        echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
        unset($_SESSION['success_message']);
    }
    ?>

    <h2>Adăugă articol</h2>

    <form method="post" name="add_name" id="articles-form">
        <div class="form-group">
            <label for="doi">DOI</label>
            <input type="text" name="doi" id="doi" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="titlu_articol">Titlul articolului</label>
            <input type="text" name="titlu_articol" id="titlu_articol" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="autor">Nume Autor</label>
            <div id="first">
                <table id="dynamic_field" class="table">
                    <tr>
                        <td style="width: 80%;">
                            <input type="text" name="name[]" placeholder="Introdu numele autorului" class="form-control name_list" required />
                        </td>
                        <td>
                            <button type="button" name="add" id="add" class="btn btn-success">Add More</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="form-group">
            <label for="anpub">An publicatie</label>
            <input type="text" name="anpub" id="anpub" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="tara">Tara publicatie</label>
            <div class="input-group">
                <select id="country-dropdown" name="tara" class="form-control">
                    <option value="">Selectează țara</option>
                    <?php
                    try {
                        $con = new PDO($dsn, $username, $password, $options);
                        
                        // Get distinct countries from existing articles
                        $query = $con->query("SELECT DISTINCT tara FROM articole WHERE tara IS NOT NULL AND tara != '' ORDER BY tara");
                        
                        $rowCount = $query->rowCount();
                        if ($rowCount == 0) {
                            echo '<option value="">No countries found</option>';
                        } else {
                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                echo '<option value="'.$row['tara'].'">'.$row['tara'].'</option>';
                            }
                        }
                        echo '<option value="other">Other (introduce manually)</option>';
                    } catch (PDOException $e) {
                        echo '<option value="">Error: ' . $e->getMessage() . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div id="custom-country" style="display: none; margin-top: 10px;">
                <input type="text" name="custom_tara" placeholder="Introdu altă țară" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="jurnal">Jurnal</label>
            <div class="input-group">
                <select id="journal-dropdown" name="jurnal" class="form-control">
                    <option value="">Selectează jurnalul</option>
                    <?php
                    try {
                        // Get distinct journals from existing articles
                        $query2 = $con->query("SELECT DISTINCT jurnal FROM articole WHERE jurnal IS NOT NULL AND jurnal != '' ORDER BY jurnal");
                        
                        $rowCount = $query2->rowCount();
                        if ($rowCount == 0) {
                            echo '<option value="">No journals found</option>';
                        } else {
                            while ($row = $query2->fetch(PDO::FETCH_ASSOC)) {
                                echo '<option value="'.$row['jurnal'].'">'.$row['jurnal'].'</option>';
                            }
                        }
                        echo '<option value="other">Other (introduce manually)</option>';
                    } catch (PDOException $e) {
                        echo '<option value="">Error: ' . $e->getMessage() . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div id="custom-journal" style="display: none; margin-top: 10px;">
                <input type="text" name="custom_jurnal" placeholder="Introdu alt jurnal" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <input type="submit" name="submit" value="Submit" id="form-submit" class="btn btn-primary">
            <a href="index.php" class="btn btn-secondary">Mergi înapoi</a>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    var i = 2;
    
    // Add more author fields
    $('#add').click(function(){
        i++;
        $('#dynamic_field').append('<tr id="row'+i+'"><td><input type="text" name="name[]" placeholder="Introdu numele autorului" class="form-control name_list" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');
    });
    
    // Remove author fields
    $(document).on('click', '.btn_remove', function(){
        var button_id = $(this).attr("id"); 
        $('#row'+button_id+'').remove();
    });
    
    // Handle "other" option for country
    $('#country-dropdown').change(function(){
        if($(this).val() === 'other') {
            $('#custom-country').show();
            $('#custom-country input').focus();
        } else {
            $('#custom-country').hide();
            $('#custom-country input').val('');
        }
    });
    
    // Handle "other" option for journal
    $('#journal-dropdown').change(function(){
        if($(this).val() === 'other') {
            $('#custom-journal').show();
            $('#custom-journal input').focus();
        } else {
            $('#custom-journal').hide();
            $('#custom-journal input').val('');
        }
    });
    
    // Form submission - handle custom inputs
    $('#articles-form').on('submit', function(e){
        // Validate country
        var countryVal = $('#country-dropdown').val();
        var customCountry = $('#custom-country input').val().trim();
        
        if (countryVal === 'other' && customCountry === '') {
            e.preventDefault();
            alert('Please enter a country name.');
            $('#custom-country input').focus();
            return false;
        }
        
        // Validate journal
        var journalVal = $('#journal-dropdown').val();
        var customJournal = $('#custom-journal input').val().trim();
        
        if (journalVal === 'other' && customJournal === '') {
            e.preventDefault();
            alert('Please enter a journal name.');
            $('#custom-journal input').focus();
            return false;
        }
        
        // If all fields are valid, the form will submit normally
        return true;
    });
});
</script>

<?php include "templates/footer.php"; ?>