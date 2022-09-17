function userResetPassword(id, username) {
    var header = "UwU whats the new passywassy code?";
    var description = "Do this only if "+username+" has forgotten their password, DO NOT abuse this power";
    var actionBox = "<form id='userResetPasswordForm' method='POST'>\
    <input id='userNewPassword' class='btn btn-neutral' type='password' name='new_password' placeholder='New Password'>\
    <input id='userConfirmSassword' class='btn btn-neutral' type='password' name='confirm_password' placeholder='Confirm Password'>\
    <br>\
    <button id='userPasswordSubmit' class='btn btn-bad' type='submit' name='reset' value='"+id+"'><img class='svg' src='assets/icons/password.svg'>Reset</button>\
    </form>";

    flyoutShow(header, description, actionBox);
    
    $("#userResetPasswordForm").submit(function(event) {
        event.preventDefault();
        var new_passowrd = $("#userNewPassword").val();
        var confirm_password = $("#userConfirmSassword").val();
        var submit = $("#userPasswordSubmit").val();
        var userId = $("#userPasswordSubmit").val();
        $("#sniffle").load("app/account/password_reset.php", {
            new_passowrd: new_passowrd,
            confirm_password: confirm_password,
            id: userId,
            submit: submit
        });
    });
}
function userDelete(id, username) {
    var header = "Are you very very sure?";
    var description = "This CANNOT be undone, be very carefull with your decition...";
    var actionBox = "<form id='' action='app/image/edit_description.php' method='POST'>\
    <button class='btn btn-bad' type='submit' value='"+id+"'><img class='svg' src='assets/icons/trash.svg'>Delete user "+username+" (keep posts)</button>\
    </form>\
    <form id='' action='app/image/edit_description.php' method='POST'>\
    <button class='btn btn-bad' type='submit' value='"+id+"'><img class='svg' src='assets/icons/trash.svg'>Delete user "+username+" (delete posts)</button>\
    </form>";

    flyoutShow(header, description, actionBox);
    
    /*$("#descriptionConfirm").submit(function(event) {
        event.preventDefault();
        var descriptionInput = $("#descriptionInput").val();
        var userDeleteSubmit = $("#userDeleteSubmit").val();
        $("#sniffle").load("path/to/.php", {
            id: id,
            submit_delete: userDeleteSubmit
        });
    });*/
    /*$("#descriptionConfirm").submit(function(event) {
        event.preventDefault();
        var descriptionInput = $("#descriptionInput").val();
        var userDeleteSubmit = $("#userDeleteSubmit").val();
        $("#sniffle").load("path/to/.php", {
            id: id,
            submit_delete: userDeleteSubmit
        });
    });*/
}
function userToggleAdmin(id, username) {
    var header = "With great power comes great responsibility...";
    var description = "Do you trust this user? With admin permitions they can cause a whole lot of damage to this place, so make sure you're very very sure";
    var actionBox = "<form id='toggleAdminConfirm' action='app/image/edit_description.php' method='POST'>\
    <button id='toggleAdminSubmit' class='btn btn-bad' type='submit' value='"+id+"'>Make "+username+" powerfull!</button>\
    </form>";

    flyoutShow(header, description, actionBox);
    
    $("#toggleAdminConfirm").submit(function(event) {
        event.preventDefault();
        var toggleAdminSubmit = $("#toggleAdminSubmit").val();
        $("#sniffle").load("app/account/account.php", {
            id: toggleAdminSubmit,
            toggle_admin: toggleAdminSubmit
        });
    });
}

function openTab(evt, tabName) {
    var i, tabcontent, tablinks;

    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active-tab", "");
    }

    document.getElementById(tabName).style.display = "flex";
    evt.currentTarget.className += " active-tab";
}