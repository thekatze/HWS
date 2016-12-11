$(function(){

  const Dashboard = {template: "<p>Dashboard</p>" }
  const Homeworks = {template: "<p>HW</p>"}
  const Classes = {template: "<p>C</p>"}
  const Leaderboards = {template: "<p>L</p>"}
  const Profile = {template: "<p>P</p>"}
  const NotFound = {template: "<p>NF</p>"}

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
