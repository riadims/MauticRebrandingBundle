<!-- Views/Default/index.html.php -->
<?php if (!empty($error)) : ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<?php if (!empty($success)) : ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <label for="siteTitle">Site Title:</label>
    <input type="text" id="siteTitle" name="siteTitle" value="Mautic" /><br><br>
    
    <label for="logo">Logo (200px x 50px):</label>
    <input type="file" id="logo" name="logo" /><br><br>
    
    <label for="mainColor">Main Color:</label>
    <input type="color" id="mainColor" name="mainColor" value="#0000ff" /><br><br>
    
    <button type="submit">Save Rebranding</button>
</form>
