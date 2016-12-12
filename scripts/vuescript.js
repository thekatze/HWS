$(function(){

  const Dashboard = {template: "#dashboard" }
  const Homeworks = {template: "#homeworks"}
  const Classes = {template: "#classes"}
  const Leaderboards = {template: "#leaderboards"}
  const Profile = {template: "#profile"}
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
