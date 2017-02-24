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
    const NotFound = {template: "#notfound"}

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

    const app = new Vue({
        router,

        http: {
        root: '/'
    }

    }).$mount('#app')
}

//Login

function login() {

    let username = document.getElementById('username').value;
    let password = document.getElementById('password').value;

    if (username = "") {
        return;
    }

    if (password = "") {
        return;
    }

    Vue.http.post('php/login.php', {u: username, pw: password}).then(response => {

        console.log(response);

        let responseCode = JSON.parse(response)[0];

        switch (responseCode) {
            //Code 00: Success
            case 0:
                console.log("Successfully logged in as " + username);
                Vue.router.go('/app');
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
    document.getElementById('cssloadContainer').className += " hidden";
}

//Removes the .hidden class from the loading screen
function finishLoad() {
    document.getElementById('cssloadContainer').className += " hidden";
}
