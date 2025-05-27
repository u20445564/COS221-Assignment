document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
  
    if (loginForm) {
      loginForm.addEventListener('submit', function (e) {
        e.preventDefault();
  
        const requestData = {
          type: 'Login',
          email: this.email.value,
          password: this.password.value
        };
  
        fetch('finalAPI.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(requestData)
        })
        .then(response => {
          if (!response.ok) return response.text().then(text => { throw new Error(text); });
          return response.json();
        })
        .then(data => {
            const login = data.data;
            if(login.userID <= 4){
                window.location.href = 'retailer_products.php';
            }else if(login.userID <= 15){
                window.location.href = 'retailer_products.php';
            }else{
                window.location.href = 'products.html';
            }
            localStorage.setItem('api_key', login.api_key);
            localStorage.setItem('userID', login.userID);
        })
        .catch(err => {
          console.error('Login Error:', err);
          alert('Login failed: ' + err.message);
        });
      });
    }
  
    if (registerForm) {
      registerForm.addEventListener('submit', function (e) {
        e.preventDefault();
  
        const userType = this.user_type.value;
        let requestData = { type: 'Register', user_type: userType };
  
        if (userType === 'user') {
          requestData = {
            ...requestData,
            name: this.customer_name.value,
            surname: this.customer_surname.value,
            username: this.customer_username.value,
            phone: this.customer_phone.value,
            email: this.customer_email.value,
            password: this.password.value,
            confirm_password: this.confirm_password.value
          };
        } else {
          requestData = {
            ...requestData,
            retailer_name: this.retailer_name.value,
            registration_number: this['retailer-registrationnumber'].value,
            email: this.retailer_email.value,
            phone: this.retailer_phone.value,
            password: this.password.value,
            confirm_password: this.confirm_password.value
          };
        }
  
        fetch('finalAPI.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(requestData)
        })
        .then(response => {
          if (!response.ok) return response.text().then(text => { throw new Error(text); });
          return response.json();
        })
        .then(data => {
          console.log('Registration Success:', data);
          // You can redirect or show confirmation here
        })
        .catch(err => {
          console.error('Registration Error:', err);
          alert('Registration failed: ' + err.message);
        });
      });
    }
  });
  