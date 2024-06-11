<?php 

require 'db.php';
require 'inc/lang.php';

$users = $database->select('users', ['user_id','username','created','last_login','failed_login','failed_login_counter','language','preferences'], '*');

$messages = array(
    'success' => [translate('Operation successful', false),'success'],
    'error' => [translate('Unknown error', false),'danger'],
    'success-add' => [translate('User successfully added', false),'success'],
    'success-delete' => [translate('User successfully deleted', false),'success'],
    'error-missing' => [translate('User does not exist', false),'danger'],
    'error-duplicate' => [translate('User already exist', false),'danger'],
    'error-invalid' => [translate('Email is invalid', false),'danger'],
    'error-empty' => [translate('Request is missing data', false),'danger'],
)

?>
<!DOCTYPE html>
<html>
    <?php include('inc/head.php') ?>
    <body>
        <div class="container overflow-hidden">
            <h1 class="fs-4 my-3"><?php translate('Roundcube User Management') ?></h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal"><?php translate('Add User') ?></button>
            <div class="w-100 overflow-auto">
                <table class="table table-striped table-bordered bg-white w-100 mt-3">
                    <thead>
                        <tr>
                            <th class="outline" style="width: calc(100%/8);"><?php translate('User') ?></th>
                            <th class="outline" style="width: calc(100%/8);"><?php translate('Created at') ?></th>
                            <th class="outline" style="width: calc(100%/8);"><?php translate('Last login') ?></th>
                            <th class="outline" style="width: calc(100%/8);"><?php translate('Failed login') ?></th>
                            <th class="outline" style="width: calc(100%/8);"><?php translate('Failed login count') ?></th>
                            <th class="outline" style="width: calc(100%/8);"><?php translate('Language') ?></th>
                            <th class="outline" style="width: calc(100%/8);"><?php translate('Action') ?></th>
                        </tr>
                    </thead>
                    <tbody>    
                        <?php 
                            foreach($users as $user) {
                                echo '<tr><td>'.$user['username'].'</td><td>'.$user['created'].'</td><td>'.$user['last_login'].'</td><td>'.$user['failed_login'].'</td><td>'.$user['failed_login_counter'].'</td><td>'.$user['language'].'</td><td><button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal" data-username="'.$user['username'].'" data-id="'.$user['user_id'].'">'.translate('Delete',false).'</button></td></tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="addUserModalLabel"><?php translate('Add User') ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form id="addUserForm" method="POST" action="process?action=add">
                    <div class="mb-3">
                        <label for="username" class="form-label"><?php translate('Email') ?></label>
                        <input type="email" class="form-control" id="username" name="username" aria-describedby="usernameHelp">
                        <div id="usernameHelp" class="form-text"><?php translate('Enter complete email address.') ?></div>
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php translate('Cancel') ?></button>
                <input class="btn btn-primary" type="submit" form="addUserForm" value="<?php translate('Add') ?>">
              </div>
            </div>
          </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="deleteUserModalLabel"><?php translate('Delete User') ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <p><?php translate('Are you sure you want to delete this user?') ?></p>
                <form id="deleteUserForm" method="POST" action="process?action=delete">
                    <input type="hidden" id="userIdToDelete" name="user_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php translate('Cancel') ?></button>
                <button type="submit" class="btn btn-danger" id="deleteConfirm" form="deleteUserForm"><?php translate('Delete') ?></button>
              </div>
            </div>
          </div>
        </div>

        <!-- Result Modal -->
        <div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="resultModalLabel"><?php translate('Operation result') ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <?php
                    if(!empty($_GET['result'])) {
                        $result = $_GET['result'];
                        echo '<div role="alert" class="alert alert-'.$messages[$result][1].' fw-bold">'.$messages[$result][0].'</div>';
                    }
                ?>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php translate('Close') ?></button>
              </div>
            </div>
          </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var addUserModal = document.getElementById('addUserModal');
                var deleteUserModal = document.getElementById('deleteUserModal');
                var deleteUserModalInstance = new bootstrap.Modal(deleteUserModal);

                addUserModal.addEventListener('show.bs.modal', function (event) {
                    //focus on username input field
                    setTimeout(function() {
                        document.getElementById("username").focus();
                        console.log('focused on input');
                    }, 500); // 0.5 seconds delay
                    // send form by pressing enter key
                    document.addEventListener("keypress", function(event) {
                        // If the user presses the "Enter" key on the keyboard
                        if (event.key === "Enter") {
                            // Cancel the default action, if needed
                            event.preventDefault();
                            document.getElementById("addUserForm").submit();
                            document.removeEventListener('keypress', this);
                        }
                    });
                });

                deleteUserModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var userId = button.getAttribute('data-id');
                    var userName = button.getAttribute('data-username');
                    var userIdInput = deleteUserModal.querySelector('#userIdToDelete');
                    var deleteConfirm = deleteUserModal.querySelector('#deleteConfirm');
                    userIdInput.value = userId;
                    deleteConfirm.innerHTML = '<?php translate('Delete') ?> <b>'+userName+'</b>';
                    
                    // close deleteUserModal by pressing enter key
                    document.addEventListener("keypress", function(event) {
                        // If the user presses the "Enter" key on the keyboard
                        if (event.key === "Enter") {
                            // Cancel the default action, if needed
                            event.preventDefault();
                            document.getElementById("deleteUserForm").submit();
                            document.removeEventListener('keypress', this);
                        }
                    });
                });
            });


            <?php 
                if(!empty($_GET['result'])) {
                    echo 'var showResultModal = true;';
                } 
                else {
                    echo 'var showResultModal = false;';
                }
            ?>
            document.addEventListener("DOMContentLoaded", function() {
                if (showResultModal) {
                    var resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
                    resultModal.show();

                    // close resultModal by pressing enter key
                    document.addEventListener("keypress", function(event) {
                        // If the user presses the "Enter" key on the keyboard
                        if (event.key === "Enter") {
                            // Cancel the default action, if needed
                            event.preventDefault();
                            resultModal.hide();
                            document.removeEventListener('keypress', this);
                        }
                    });

                    //clear URL after result modal close
                    document.getElementById('resultModal').addEventListener('hidden.bs.modal', function (event) {
                        if (window.history.replaceState) {
                            //prevents browser from storing history with each change:
                            window.history.replaceState(null, 'page title', '/');
                        }
                    })
                }
            });
    </script>
    </body>
</html>