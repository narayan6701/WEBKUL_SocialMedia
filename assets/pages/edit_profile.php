<?php 
    global $user;
    require_once('assets/php/functions.php');
?>
    <div class="container col-9 rounded-0 d-flex justify-content-between">
        <div class="col-12 bg-white border rounded p-4 mt-4 shadow-sm">
            <form method="POST" action="assets/php/actions.php?updateprofile" enctype="multipart/form-data">
                <div class="d-flex justify-content-center">
                </div>
                <h1 class="h5 mb-3 fw-normal">Edit Profile</h1>
                <?php
                if(isset($_GET['success'])){
                    ?><p class="text-success">Profile updated successfully !</p><?php
                }
                ?>
                <div class="form-floating mt-1 col-6">
                    <img src="assets/images/profile/<?=$user['profile_pic']?>" class="img-thumbnail my-3" style="height:150px;" alt="...">
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Change Profile Picture</label>
                        <input class="form-control" name="profile_pic" type="file" id="formFile">
                    </div>
                </div>
                <?=showError('profile_pic')?>
                <div class="d-flex">
                    <div class="form-floating mt-1 col-6 ">
                        <input type="text" name="first_name" value="<?=$user['first_name']?>" class="form-control rounded-0" placeholder="username/email">
                        <label for="floatingInput">first name</label>
                    </div>
                    <div class="form-floating mt-1 col-6">
                        <input type="text" name="last_name" value="<?=$user['last_name']?>" class="form-control rounded-0" placeholder="username/email">
                        <label for="floatingInput">last name</label>
                    </div>
                </div>
                <?=showError('first_name')?>
                <?=showError('last_name')?>
                <div class="d-flex gap-3 my-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" id="exampleRadios1"
                            value="option1" <?=$user['gender']==1?'checked':''?>>
                        <label class="form-check-label" for="exampleRadios1">
                            Male
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" id="exampleRadios3"
                            value="option2" <?=$user['gender']==2?'checked':''?>>
                        <label class="form-check-label" for="exampleRadios3">
                            Female
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" id="exampleRadios2"
                            value="option0" <?=$user['gender']==0?'checked':''?>>
                        <label class="form-check-label" for="exampleRadios2">
                            Other
                        </label>
                    </div>
                </div>
                <div class="form-floating mt-1">
                    <input type="date" value="<?=$user['dob']?>" name="dob" class="form-control rounded-0" placeholder="Date of Birth">
                    <label for="floatingInput">Date of Birth</label>
                </div>
                <?php
                    if (!empty($user['dob'])) {
                        $age = calculateAge($user['dob']);
                        echo '<div class="mt-2"><strong>Age:</strong> ' . $age . ' years</div>';
                    }
                ?>
                <div class="form-floating mt-1">
                    <input type="email" value="<?=$user['email']?>" class="form-control rounded-0" placeholder="username/email" disabled>
                    <label for="floatingInput">email</label>
                </div>
                <div class="form-floating mt-1">
                    <input type="text" value="<?=$user['username']?>" name="username" class="form-control rounded-0" placeholder="username/email">
                    <label for="floatingInput">username</label>
                </div>
                <?=showError('username')?>
                <div class="form-floating mt-1">
                    <input type="password" name="password" class="form-control rounded-0" id="floatingPassword" placeholder="Password">
                    <label for="floatingPassword">new password</label>
                </div>

                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <button class="btn btn-primary" type="submit">Update Profile</button>



                </div>
            </form>
        </div>
<script>
document.getElementById('formFile').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.querySelector('img.img-thumbnail');

    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});
</script>

    </div>
