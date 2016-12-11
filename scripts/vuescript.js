$(function(){

  const Dashboard = {template: "<p>Hello</p>" }
  const Homeworks = {template: "<p>Hello</p>"}
  const Classes = {template: "<p>Hello</p>"}
  const Leaderboards = {template: "<p>Hello</p>"}
  const Profile = {template: "<p>Hello</p>"}
  const NotFound = {template: "<p>Hello</p>"}

  const routes = {
    '/': Dashboard,
    '/homeworks': Homeworks,
    '/classes': Classes,
    '/leaderboards': Leaderboards,
    '/profile': Profile
  }

  new Vue({
    el: '#app',
    data: {
      currentRoute: window.location.pathname
    },
    computed: {
      ViewComponent() {
        return routes[this.currentRoute] || NotFound
      }
    },
    render(h) {return h(this.ViewComponent)}
  })
});
