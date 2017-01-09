$(function(){

  const Dashboard = {template: "#dashboard" }
  const Homeworks = {template: "#homeworks"}
  const Classes = {template: "#classes"}
  const Leaderboards = {template: "#leaderboards"}
  const Profile = {template: "#profile"}
  const Menu = {template: "#menu"}
  const Login = {template: "#login"}

  const routes = [
    {
      path: '/private/', 
      component: Menu,
      children: [
        {path: 'dashboard', component: Dashboard},
        {path: '', component: Dashboard}
      ]
    },

    {
      path: '/private/', 
      component: Menu,
      children: [
        {path: 'homeworks', component: Homeworks}
      ]
    },

    {
      path: '/private/', 
      component: Menu,
      children: [
        {path: 'classes', component: Classes}
      ]
    },

    {
      path: '/private/', 
      component: Menu,
      children: [
        {path: 'leaderboards', component: Leaderboards}
      ]
    },

    {
      path: '/private/', 
      component: Menu,
      children: [
        {path: 'profile', component: Profile}
      ]
    },

    {
      path: '/login/', 
      component: Login
    }

  ]

  const router = new VueRouter({
    routes
  })

  const app = new Vue({
    router
  }).$mount('#app')




});
