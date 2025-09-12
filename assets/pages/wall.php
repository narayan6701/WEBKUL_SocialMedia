<?php 
    global $user;
    global $posts;
?>
<div class="container col-9 rounded-0 d-flex justify-content-between">
        <div class="col-8">
            <?php
                foreach($posts as $post){
                    ?>
                    <div class="card mt-4">
                <div class="card-title d-flex justify-content-between  align-items-center">

                    <div class="d-flex align-items-center p-2">
                        <img src="assets/images/profile/<?=$post['profile_pic']?>" alt="" height="30" class="rounded-circle border">&nbsp;&nbsp;<?=$post['first_name']?> <?=$post['last_name']?>
                    </div>
                    <div class="p-2">
                        <i class="bi bi-trash"></i>
                    </div>
                </div>
                <img src="assets/images/posts/<?=$post['post_img']?>" class="" alt="...">
                <h4 style="font-size: x-larger" class="p-2 border-bottom"><i class="bi bi-hand-thumbs-up"></i>&nbsp;&nbsp;<i
                        class="bi bi-hand-thumbs-down"></i>
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
