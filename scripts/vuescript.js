//Routing

window.onload = function() {
  const Dashboard = {template: "#dashboard" }
  const Homeworks = {template: "#homeworks"}
  const Classes = {template: "#classes"}
  const Leaderboards = {template: "#leaderboards"}
  const Profile = {template: "#profile"}
  const Menu = {template: "#menu"}
  const Login = {template: "#loginview"}
  const PasswordReset = {template: "#passwordreset"}

  const routes = [
    {
      path: '/app/',
      component: Menu,
      children: [
        {path: 'dashboard', component: Dashboard},
        {path: '', redirect: 'dashboard'},
        {path: 'homeworks', component: Homeworks},
        {path: 'classes', component: Classes},
        {path: 'leaderboards', component: Leaderboards},
        {path: 'profile', component: Profile}
      ]
    },

    {
      path: '/login/',
      component: Login
    },

    {
      path: '',
      redirect: 'login'
    },

    {
      path: '/resetpassword',
      component: PasswordReset
    }

  ]

  const router = new VueRouter({
    routes

  })

  const app = new Vue({
    router,

    http: {
      root: '/php'
    }

  }).$mount('#app')

}

//Login

function login() {

    let username = document.getElementById('username').value;
    let password = document.getElementById('password').value;

    console.log(username);

    Vue.http.post('/login', {u: username, pw: password}).then(response => {

    }, response => {

    });
}

function passwordReset() {

    let email = document.getElementsByName('email');

}
