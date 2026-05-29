<?php
session_start();
$conn = new mysqli("localhost","root","","civic");

$result = $conn->query("SELECT * FROM songs ORDER BY id DESC");
?>

<h2>All Songs</h2>

<?php while($row = $result->fetch_assoc()){ ?>

<div class="payment-card">

    <div>
        <strong><?php echo $row['title']; ?></strong><br>
        Artist: <?php echo $row['artist']; ?><br>
        Price: $<?php echo $row['price']; ?>
    </div>

    <div class="payment-actions">
        <a href="delete_song.php?id=<?php echo $row['id']; ?>" 
           class="reject-btn"
           onclick="return confirm('Delete this song?')">
            Delete
        </a>
    </div>

</div>

<?php } ?>