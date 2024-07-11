<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Login</div>

                    <div class="card-body">
                        <form method="POST" action="" @submit.prevent="login($event)">
                            <input type="hidden" name="_token" autocomplete="off" :value="csrf_token" wfd-id="id0">

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">E-mail</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="" required autocomplete="email" autofocus v-model="email">

                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">Senha</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password" v-model="password">

                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember">

                                        <label class="form-check-label" for="remember">
                                            Mantenha-me conectado
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Entrar
                                    </button>

                                    <a class="btn btn-link" href="#" @click="testeAxios">
                                        Esqueci a senha
                                    </a>
                                    <a class="btn btn-link" href="#" @click="getUser">
                                        Esqueci a senha²
                                    </a>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import config from '../config'
    import axios from 'axios'

    export default {
        props: [
            'csrf_token'
        ],
        data() {
            return {
                email: '',
                password: ''
            }
        },
        // created() {
        //     Cookie.remove('access_token')
        // },
        methods: {
            testeAxios(){
                let url = `http://127.0.0.1:8000/marcas`
                axios.get(url)
                   .then(response => {
                    this.data = response.data
                    window.location.href = '/marcas';
                    console.log(this.data)
                   })
                   .catch(error => {
                    this.error = "Error"
                    console.error(error)
                   })
            },
            login(e) {
                let url = `${config.apiUrl}/auth/login`
                let formData = new URLSearchParams()
                formData.append('email', this.email)
                formData.append('password', this.password)

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.access_token) {
                        document.cookie = 'access_token=' + data.access_token
                    }
                    e.target.submit()
                    console.log("gerou token")
                })
                .catch(errors => {
                    console.log('Error logging in', errors)
                })
                
                // axios.post(url, formData, {
                //     headers: {
                //         'Content-Type': 'application/x-www-form-urlencoded',
                //         'Accept': 'application/json'
                //     }
                // })
                // .then(response => {
                //     if(response.data.access_token){
                //         document.cookie = 'access_token=' + response.data.access_token
                //     }
                //     e.target.submit()
                //     console.log("gerou token")
                // })
                // .catch(errors => {
                //     console.log('Error logging in', errors)
                // })   
            },
            getUser() {
                let url = `${config.apiUrl}/auth/me`
                let formData = new URLSearchParams()
                
                axios.post(url, formData, {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    console.log(response)
                })
                .catch(errors => {
                    console.log('Error logging in', errors)
                })   
            }
        }
    }
</script>