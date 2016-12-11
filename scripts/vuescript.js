$(function(){

  const Dashboard = {template: "<h1>Dashboard<h1>" }
  const Homeworks = {template: "<h1>Homeworks</h1>"}
  const Classes = {template: "<h1>Classes</h1>"}
  const Leaderboards = {template: "<h1>Leaderboards</h1>"}
  const Profile = {template: "<h1>Profile</h1>"}
  const NotFound = {template: "<h1>Not Found, please stop breaking this website</h1>"}


  const routes = [
    {path: '/', component: Dashboard},
    {path: '/homeworks', component: Homeworks},
    {path: '/classes', component: Classes},
    {path: '/leaderboards', component: Leaderboards},
    {path: '/profile', component: Profile}
  ]

  const router = new VueRouter({
    routes
  })

  const app = new Vue({
    router
  }).$mount('#app')




});
