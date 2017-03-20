//Routing

var app;

window.onload = function() {
    const Dashboard = {
        template: "#dashboard"
    };
    const Homeworks = {
        template: "#homeworks"
    }
    const Classes = {
        template: "#classes"
    }
    const Leaderboards = {
        template: "#leaderboards"
    }
    const Profile = {
        template: "#profile"
    }
    const Menu = {
        template: "#menu"
    }
    const Login = {
        template: "#loginview"
    }
    const PasswordReset = {
        template: "#passwordreset"
    }
    const NotFound = {
        template: "#notfound"
    }



    const routes = [{
            path: '/404',
            component: NotFound
        },

        {
            path: '/app/',
            component: Menu,
            children: [{
                    path: 'dashboard',
                    component: Dashboard
                },
                {
                    path: '',
                    redirect: 'dashboard'
                },
                {
                    path: 'homeworks',
                    component: Homeworks
                },
                {
                    path: 'classes',
                    component: Classes
                },
                {
                    path: 'leaderboards',
                    component: Leaderboards
                },
                {
                    path: 'profile',
                    component: Profile
                }
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

        ready() {
            window.addEventListener('onload', this.reload());
        },

        methods: {
            reload() {
                this.updateData(this._router.currentRoute.path.split('/')[2]);
            },

            updateData(route) {
                if (readCookies('cookiezi')) {
                    switch (route) {
                        case 'dashboard':
                            Vue.http.post('php/update_dashboard.php', {}).then(response => {
                                let responseCode = JSON.parse(response.body);
                                switch (responseCode.response) {
                                    case 0:
                                        document.getElementById('dashboardUsername').innerText = responseCode.user.name;
                                        document.getElementById('dashboardOpenHomeworks').innerText = responseCode.user.openHomeworks;
                                        document.getElementById('dashboardDollaz').innerText = responseCode.user.dollaz;
                                        document.getElementById('dashboardRespect').innerText = responseCode.user.respect;
                                        document.getElementById('dashboardHomeworkName').innerText = responseCode.nextHomework.name;
                                        document.getElementById('dashboardHomeworkClass').innerText = responseCode.nextHomework.class;
                                        document.getElementById('dashboardHomeworkDate').innerText = "until " + responseCode.nextHomework.date;
                                        break;
                                    case 10:
                                        console.log("fail");
                                        break;
                                    case 12:
                                    default:
                                        app._router.push('/login');
                                        break;
                                }
                                finishLoad();
                            }, response => {

                            });
                            break;
                            case 'homeworks':
                                Vue.http.post('php/update_homeworks.php', {}).then(response => {
                                    let responseCode = JSON.parse(response.body);
                                    switch (responseCode.response) {
                                        case 0:
                                            for (var i in responseCode.homeworks) {
                                                var homework = responseCode.homeworks[i];
                                                document.getElementById('homeworksContainer').insertAdjacentHTML('beforeend', '<div id="homework_'+ homework.id +'" class="card"><h1>'+ homework.name +'</h1><h1>'+ homework.class +'</h1><span>Until '+ homework.date +'</span></div>');
                                            }
                                            break;
                                        case 10:
                                            console.log("fail");
                                            break;
                                        case 12:
                                        default:
                                            app._router.push('/login');
                                            break;
                                    }
                                    finishLoad();
                                }, response => {

                                });
                                break;
                                case 'classes':
                                    Vue.http.post('php/update_classes.php', {}).then(response => {
                                        let responseCode = JSON.parse(response.body);
                                        switch (responseCode.response) {
                                            case 0:
                                                for (var i in responseCode.classes) {
                                                    var clas = responseCode.classes[i];
                                                    document.getElementById('classesContainer').insertAdjacentHTML('beforeend', '<div id="homework_'+ clas.id +'" class="card"><h1>'+ clas.name +'</h1></div>');
                                                }
                                                break;
                                            case 10:
                                                console.log("fail");
                                                break;
                                            case 12:
                                            default:
                                                app._router.push('/login');
                                                break;
                                        }
                                        finishLoad();
                                    }, response => {

                                    });
                                    break;
                                    case 'profile':
                                        Vue.http.post('php/update_profile.php', {}).then(response => {
                                            let responseCode = JSON.parse(response.body);
                                            switch (responseCode.response) {
                                                case 0:
                                                document.getElementById('profileUsername').innerText = responseCode.user.name;
                                                document.getElementById('profileDate').innerText = responseCode.user.timestamp;
                                                document.getElementById('profileDollaz').innerText = responseCode.user.dollaz;
                                                document.getElementById('profileRespect').innerText = responseCode.user.respect;
                                                document.getElementById('profileEmail').innerText = responseCode.user.email;
                                                break;
                                                case 10:
                                                    console.log("fail");
                                                    break;
                                                case 12:
                                                default:
                                                    app._router.push('/login');
                                                    break;
                                            }
                                            finishLoad();
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
        },

        watch: {
            '$route': function(newRoute, oldRoute) {
                this.updateData(newRoute.path.split('/')[2]);
            }
        }

    }).$mount('#app');

    if (readCookies('cookiezi')) {
        app._router.push('/app');
    }
    app.reload();
}

//Login

function login() {

    let username = document.getElementById('username').value;
    let password = document.getElementById('password').value;

    if (username == "" || password == "") {
        return;
    }

    Vue.http.post('php/login.php', {
        u: username,
        pw: password
    }).then(response => {

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
function readCookies(n) {
    var a = document.cookie.split('; ');
    for (var i = 0; i < a.length; i++) {
        var C = a[i].split('=');
        if (C[0] == n) {
            return C[1];
        }
    }
}
