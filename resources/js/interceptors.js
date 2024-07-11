/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import config from './config';
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });
function getCookie(titulo){
    let token = document.cookie.split(';').find(cookie => cookie.includes(`${titulo}=`));
    if (token) {
        token = token.split('=')[1];
        return token;
    }
}

axios.interceptors.request.use(
    config => {
        let token = document.cookie.split(';').find(cookie => cookie.includes('access_token='));
        if (token) {
            token = token.split('=')[1];
            config.headers.Authorization = `Bearer ${token}`;
        }
        console.log('headers: ', config.headers)
        // config.headers.Accept = 'application/json'
        
        console.log('request', config)
        return config
    },
    error => {
        console.error('request', error)
        return Promise.reject(error)
    }
    
)  

axios.interceptors.response.use(
    response => {
        console.log('response', response)
        
        return response
    },
    error => {
        console.error('response', error.response)
        if(error.response.status == 401 && error.response.data.message == 'Token has expired'){
            console.log('let\'s do it!')
            axios.post(`${config.apiUrl}/auth/refresh`)
                .then(response => {
                    console.log('cookie antigo', getCookie('access_token'))
                    document.cookie = 'access_token='+response.data.access_token
                    console.log('cookie novo', getCookie('access_token'))
                    window.location.reload()
                })
                .catch(errors => {
                    console.log('erro refresh', errors)
                })
        }
        return Promise.reject(error)
    }
)  