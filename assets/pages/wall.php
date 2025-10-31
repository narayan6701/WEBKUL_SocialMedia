<?php 
    global $user;
    global $posts;
?>
<div class="container col-9 rounded-0 d-flex justify-content-between">
        <div class="col-8">
            <?php
                foreach($posts as $post){
                    ?>
                    <div class="card mt-4 post-container">
                <div class="card-title d-flex justify-content-between  align-items-center">

                    <div class="d-flex align-items-center p-2">
                        <img src="assets/images/profile/<?=$post['profile_pic']?>" alt="" height="30" class="rounded-circle border">&nbsp;&nbsp;<?=$post['first_name']?> <?=$post['last_name']?>
                    </div>
                    <div class="p-2 delete-btn">
                        <button type="button" class="btn btn-danger">Delete</button>
                    </div>
                </div>
                <img src="assets/images/posts/<?=$post['post_img']?>" class="" alt="...">
                <h4 style="font-size: x-larger" class="p-2 border-bottom">
                    <i class="bi bi-hand-thumbs-up" onclick="increment('likeCount')"></i>
                    <span id="likeCount">0</span>&nbsp;&nbsp;
            <i class="bi bi-hand-thumbs-down" onclick="increment('dislikeCount')"></i>
                    <span id="dislikeCount">0</span>
                </h4>

                <div class="card-body">
                   <?=$post['post_text']?>
                </div>

            </div> 
                    
                    <?php
                }
            ?>
            
        </div>

        <div class="col-4 mt-4 p-3">
            <div class="d-flex align-items-center p-2">
                <div><img src="assets/images/profile/<?=$user['profile_pic']?>" alt="" height="60" class="rounded-circle border">
                </div>
                <div>&nbsp;&nbsp;&nbsp;</div>
                <div class="d-flex flex-column justify-content align-items">
                    <h6 style="margin: 0px;"><?=$user['first_name']?> <?=$user['last_name']?></h6>
                    <p class="text-muted">@<?=$user['username']?></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load counts from localStorage
document.addEventListener('DOMContentLoaded', () => {
  const likeCount = localStorage.getItem('likeCount') || 0;
  const dislikeCount = localStorage.getItem('dislikeCount') || 0;
  document.getElementById('likeCount').textContent = likeCount;
  document.getElementById('dislikeCount').textContent = dislikeCount;
});

// Update counts and save to localStorage
function increment(id) {
  const countSpan = document.getElementById(id);
  let count = parseInt(countSpan.textContent, 10);
  count++;
  countSpan.textContent = count;
  localStorage.setItem(id, count);
}
    </script>



<script>
 document.querySelectorAll('.delete-btn button').forEach(button => {
  button.addEventListener('click', function () {
    const post = this.closest('.post-container');
    if (post) {
      post.remove();
      localStorage.setItem(post.id, 'deleted'); // Mark as deleted
    }
  });
});

// On page load, hide deleted posts
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.post-container').forEach(post => {
    if (localStorage.getItem(post.id) === 'deleted') {
      post.remove();
    }
  });
});

</script>




