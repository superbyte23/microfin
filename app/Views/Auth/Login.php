    <link rel="stylesheet" href="<?php echo ASSETS ?>/dist/assets/compiled/css/auth.css"> 
    <div id="auth">
      
      <div class="row h-100">
        <div class="col-lg-5 col-12">
          <div id="auth-left">
            <div class="auth-logo text-center">
              <a href="index.html"><img src="<?php echo ASSETS ?>/dist/assets/static/images/brand-logo.png" alt="Logo"></a>
            </div>
            <!-- <h1 class="auth-title">Log in.</h1> -->
            <p class="auth-subtitle mb-5">Log in with your data that you entered during registration.</p>
            <div id="alert_container">
                
            </div>
            <form id="formAuthentication" action="" method="POST">
              <div class="form-group position-relative has-icon-left mb-4">
                <input type="email" class="form-control form-control-xl" placeholder="Email" id="email">
                <div class="form-control-icon">
                  <i class="bi bi-person"></i>
                </div>
              </div>
              <div class="form-group position-relative has-icon-left mb-4">
                <input type="password" class="form-control form-control-xl" placeholder="Password" id="password">
                <div class="form-control-icon">
                  <i class="bi bi-shield-lock"></i>
                </div>
              </div>
              <div class="form-check form-check-lg d-flex align-items-end">
                <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
                <label class="form-check-label text-gray-600" for="flexCheckDefault">
                  Keep me logged in
                </label>
              </div>
              <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5" type="submit">Log in</button>
            </form>
            <div class="text-center mt-5 text-lg fs-4">
              <p class="text-gray-600">Don't have an account? <a href="./register" class="font-bold">Sign
              up</a>.</p>
              <p><a class="font-bold" href="./forgot-password">Forgot password?</a>.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
          <div id="auth-right">
          </div>
        </div>
      </div>
    </div> 
    <script>

      function User(email, password){ 
        this.email = email;
        this.password = password;
      }

      const form = document.querySelector('#formAuthentication');
      form.addEventListener('submit', async (e) => {
        e.preventDefault(); 
        let email = e.target.querySelector('#email');
        let password = e.target.querySelector('#password');
        
        let newUser = new User(email.value, password.value);

        try { 
          const alert_container = document.getElementById('alert_container');
          // fetch api send post request
          let response = await fetch('<?php echo API; ?>/login', {
            method: 'POST',
            headers: { 'Content-Type' : 'application/json', },
            body: JSON.stringify(newUser)
          })

          if(response.ok){
            let jsonResponse = await response.json();
            if(jsonResponse.login == "failed"){
              // user not found
              alert_container.innerHTML = "";
              alert_container.innerHTML = '<div class="alert alert-danger alert-dismissible show fade"><i class="bi bi-exclamation-triangle"></i> '+ jsonResponse.message +'.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            }else{
              // user found
              alert_container.innerHTML = "";
              alert_container.innerHTML = '<div class="alert alert-success" role="alert">Successfully Logged In. Redirecting to Dashboard</div>';
              setTimeout(function(){
                window.location = "./dashboard";
              }, 2000);
            } 
            console.log('Server response: ', jsonResponse);
            
          }else{
            console.log('Server error: ', jsonResponse);
            alert('Failed to submit form. Please try again');
          }
        } catch (error) {
          console.log('Fetch errpr: ', error);
          alert('Failed to submit form. Please try again');
        } 
      });
    </script>
  </body>
</html>