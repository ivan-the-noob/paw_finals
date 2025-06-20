<?php
session_start();
include '../../../../db.php';
include '../../function/authentication/sign-up.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAWS | Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/signup.css">
      <link rel="icon" href="../../../../assets/img/logo.png" type="image/x-icon">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="row login-container">
                    <div class="col-md-5 login-left text-center">
                        <img src="../../../../assets/img/logo.png" alt="Logo">
                    </div>
                    <div class="col-md-7 login-right">
                        <h5 class="mb-3">Sign Up</h5>
                        <form method="POST" action="signup.php">
                            <div class="mb-3">
                                <input type="text" name="firstname" class="form-control" placeholder="Enter your first name" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" name="lastname" class="form-control" placeholder="Enter your last name" required>
                            </div>
                            <div class="mb-3">
                                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                            </div>
                            <div class="forgot-password"></div>
                        
                            <div class="input-group">
                                <span class="mb-3 input-group-text">+63</span>
                                <input type="tel" 
                                    class="form-control mb-3" 
                                    id="contactNum" 
                                    name="contactNum" 
                                    placeholder="9123456789" 
                                    pattern="[0-9]{10}" 
                                    maxlength="10" 
                                    required>
                            </div>
                        
                            <div class="mb-3">
                              <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-control" 
                               placeholder="Enter password" 
                               pattern="(?=.*[A-Z])(?=.*[\W_]).{8,}" 
                               title="Password must be at least 8 characters, include 1 uppercase letter and 1 special character." 
                               required>
                            </div>
                             <?php if (isset($_SESSION['error'])): ?>
                                <div class="error mb-3 text-white fw-bold"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                            <?php endif; ?>
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="showPassword">
                                <label class="form-check-label" for="showPassword">Show Password</label>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Sign Up</button>
                            <div class="text-center mt-3">
                                <a href="login.php">Have an account? <span class="sign-up">Login</span></a>
                            </div>
                            <div class="forgot-password"></div>
                        </form>

                       
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <script>
        document.getElementById('showPassword').addEventListener('change', function () {
            const passwordInput = document.getElementById('password');
            passwordInput.type = this.checked ? 'text' : 'password';
        });
    </script>


    <!-- Ivan updated this on June 13 -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
