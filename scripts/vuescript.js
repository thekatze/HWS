//Routing

var app;

window.onload = function() {
    //var Dashboard = {template: "#dashboard" };
    const Homeworks = {template: "#homeworks"}
    const Classes = {template: "#classes"}
    const Leaderboards = {template: "#leaderboards"}
    const Profile = {template: "#profile"}
    const Menu = {template: "#menu"}
    const Login = {template: "#loginview"}
    const PasswordReset = {template: "#passwordreset"}
    const NotFound = {template: "#notfound"}

    var Dashboard = Vue.component({
      data: function () {
        return {
          user: {
            name: 'Tets'
          }
        }
      },
      template: `
        <div class="cardContainer">
          <div class="card">
              <h1>Welcome back,</h1>
              <span id="username" class="username">{{user.name}}</span> <!-- TODO: Make it show stuff -->
              <router-link to="/app/profile"> Details </router-link>

          </div>

          <div class="card">
              <h1>Homeworks</h1>
              <b class="importantNumber"> 3 </b> <!-- TODO: Make it show stuff -->
              <router-link to="/app/homeworks"> Show </router-link>
          </div>

          <div class="card">
              <h1>Dollaz</h1>
              <span> Amount </span>
              <b class="importantNumber"> 36.42 $ </b>
          </div>

          <div class="card">
              <h1>Respect</h1>
              <span> Amount </span>
              <b class="importantNumber"> 12 </b>
          </div>

          <div class="card">
              <h1>Next Homework</h1>

          </div>

      </div>
      `
    });


    const routes = [
        {
            path: '/404',
            component: NotFound
        },

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
            path: '/login',
            component: Login
        },

        {
            path: '',
            redirect: 'login'
        },

        {
            path: '/resetpassword',
            component: PasswordReset
        },

        {
            path: '/*',
            redirect: '404'
        }


    ]

    const router = new VueRouter({
        routes
    })

    app = new Vue({
        router,
        http: {
            root: '/'
        },
        data :function () {
          return {
            user: {
              name: 'Tets'
            }
          }
        },
        watch: {
            '$route': function (newRoute, oldRoute){
              if (readCookies('cookiezi')) {
                switch (newRoute.path.split('/')[2]) {
                  case 'dashboard':
                      Vue.http.post('php/update_dashboard.php', {}).then(response => {
                        let responseCode = JSON.parse(response.body);
                        switch (responseCode.response) {
                          case 0:
                            finishLoad();
                            app.data = responseCode;
                            //document.getElementById('username').innerText = this.data.user.name;
                            console.log(this);
                            break;
                          case 10:
                            console.log("fail");
                            break;
                          case 12:
                          default:
                            app._router.push('/login');
                            break;
                        }
                      }, response => {

                      });
                    break;
                  default:
                    //app._router.push('/login');
                    break;

                }
              } else {
                app._router.push('/login');
              }
            }
        }

    }).$mount('#app');

    if (readCookies('cookiezi')) {
        app._router.push('/app');
    }
}

//Login

function login() {

    let username = document.getElementById('username').value;
    let password = document.getElementById('password').value;

    if (username == "" || password == "") {
        return;
    }

    Vue.http.post('php/login.php', {u: username, pw: password}).then(response => {

        let responseCode = JSON.parse(response.body);

        responseCode = responseCode.response;

        switch (responseCode) {
            //Code 00: Success
            case 0:
                console.log("Successfully logged in as " + username);

                console.log(app._router);
                console.log(readCookies('cookiezi'));
                app._router.push('/app');
                startLoad();
                break;
            //Code 10: Wrong Password
            case 10:
                console.log("Wrong Password")
                break;
            //Code 11: MySQL Error
            case 11:

                break;
            //Any other code: wtf
            default:
                console.log("WTF, Login returned invalid response code.");
        }


    }, response => {
        console.log("Failed to reach server.");
    });
}

function passwordReset() {
    let email = document.getElementsById('email').value;
    Vue.http.post('php/resetPassword.php')
}

//Adds the .hidden class to the loading screen
function startLoad() {
    document.getElementById('cssloadContainer').classList.remove("hidden");
}

//Removes the .hidden class from the loading screen
function finishLoad() {
    document.getElementById('cssloadContainer').classList.add("hidden");
}

//Does Cookie stuff
function readCookies(n){
  var a = document.cookie.split('; ');
  for (var i = 0; i < a.length; i++){
    var C = a[i].split('=');
    if (C[0] == n){
      return C[1];
    }
  }
}
