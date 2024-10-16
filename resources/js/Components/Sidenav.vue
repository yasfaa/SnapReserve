<template>
    <div class="sidenav">
      <ul>
        <li><router-link to="/profile">Profile</router-link></li>
        <li><router-link to="/create-post">Post Foto</router-link></li>
        <li @click="logout" class="logout">Logout</li>
      </ul>
    </div>
  </template>
  
  <script>
  export default {
    methods: {
      logout() {
        // Aksi logout
        this.$inertia.post('/logout');
      }
    }
  };
  </script>
  
  <style scoped>
  .sidenav {
    position: fixed;
    left: 0;
    top: 0;
    height: 100vh;
    width: 200px;
    background-color: #000;
    color: #fff;
    padding-top: 20px;
  }
  .sidenav ul {
    list-style: none;
    padding: 0;
  }
  .sidenav li {
    padding: 10px 20px;
    cursor: pointer;
  }
  .logout {
    position: absolute;
    bottom: 20px;
    width: 100%;
  }
  </style>
  