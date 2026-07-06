<?php
session_start();

$conn = new mysqli("localhost","root","","civic");

if(!isset($_GET['song_id'])){
    exit();
}

$song_id = $_GET['song_id'];

$comments = $conn->query("
SELECT *
FROM song_comments
WHERE song_id='$song_id'
ORDER BY id DESC
");
?>

<div class="comments-box">

<h2>💬 Comments</h2>

<div class="comments-list">

<?php

if($comments->num_rows > 0){

while($row = $comments->fetch_assoc()){

?>

<div class="comment">

<div class="comment-user">

<?php echo $row['user']; ?>

</div>

<div class="comment-text">

<?php echo nl2br($row['comment']); ?>

</div>

</div>

<?php

}

}else{

echo "<p>No comments yet.</p>";

}

?>

</div>

<form action="comment.php" method="POST">

<input
type="hidden"
name="song_id"
value="<?php echo $song_id; ?>">

<textarea
name="comment"
placeholder="Write a comment..."
required></textarea>

<button>

Post Comment

</button>

</form>

</div>