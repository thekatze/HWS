$(function(){

  const Dashboard = {template: "#dashboard" }
  const Homeworks = {template: "#homeworks"}
  const Classes = {template: "#classes"}
  const Leaderboards = {template: "#leaderboards"}
  const Profile = {template: "#profile"}
  const Menu = {template: "#menu"}
  const Login = {template: "#loginview"}

  const routes = [
    {
      path: '/app/', 
      component: Menu,
      children: [
        {path: 'dashboard', component: Dashboard},
        {path: '', component: Dashboard}
      ]
    },

    {
      path: '/app/', 
      component: Menu,
      children: [
        {path: 'homeworks', component: Homeworks}
      ]
    },

    {
      path: '/app/', 
      component: Menu,
      children: [
        {path: 'classes', component: Classes}
      ]
    },

    {
      path: '/app/', 
      component: Menu,
      children: [
        {path: 'leaderboards', component: Leaderboards}
      ]
    },

    {
      path: '/app/', 
      component: Menu,
      children: [
        {path: 'profile', component: Profile}
      ]
    },

    {
      path: '/login/', 
      component: Login
    },

    {
      path: '',
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
