<template>
    <div class="container">
      <div class="row justify-content-center mt-5">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h1 class="text-center">Log In</h1>
            </div>
            <div class="card-body">
              <div v-if="errorMessage" class="alert alert-danger">
                {{ errorMessage }}
              </div>
              <form @submit.prevent="login">
                <div class="form-group">
                  <label for="username">Username</label>
                  <input type="text" class="form-control" id="username" v-model="username" required>
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" class="form-control" id="password" v-model="password" required>
                </div>
                <br>
                <div class="form-group text-center">
                  <button type="submit" class="btn btn-primary btn-block">Log In</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  import { useRouter } from 'vue-router';
  import { useStore } from 'vuex'; // Ensure Vuex is imported correctly
  
  const username = ref('');
  const password = ref('');
  const errorMessage = ref('');
  
  const router = useRouter();
  const store = useStore();
  
  const login = () => {
    const data = {
      action: 'login',
      username: username.value,
      password: password.value,
    };
  
    axios.post('https://yourdomain.com/loginapi.php', data)
      .then(response => {
        if (response.data.success) {
          const user = response.data.user;
          store.dispatch('logIn', {
            id: user.userID,
            username: user.username,
            email: user.email,
            firstName: user.firstName,
            lastName: user.lastName,
            userLevel: user.userLevel,
            notes: user.notes,
          });
  
          localStorage.setItem('user', JSON.stringify(user));
          router.push('/');
        } else {
          errorMessage.value = response.data.error || 'Invalid username or password';
        }
      })
      .catch(error => {
        console.error('Error logging in:', error);
        errorMessage.value = 'Error logging in';
      });
  };
  </script>
  