
require('./bootstrap');
window.Vue = require('vue');
import VueRouter from 'vue-router'
import {routes} from './routes'
import {Form,HasError,AlertError} from 'vform'
import Swal from 'sweetalert2/src/sweetalert2.js'
import VueProgressBar from 'vue-progressbar'
import moment from 'moment'
import animate from 'animate.css'



Vue.component('Infinite-Loading',require('vue-infinite-loading'));
Vue.component(HasError.name,HasError);
window.Form=Form;
window.Swal=Swal;
window.loadUsers=new Vue();

Vue.use(animate);
Vue.use(VueRouter);
Vue.use(VueProgressBar,{
    color:'green',
    height:'5px',
    failedColor:'red'
});

window.loads=new Vue();
window.post=new Vue();
window.loadLike=new Vue();

//filter
Vue.filter('time',(e)=>{
return moment(e).startOf('h').fromNow();
})

Vue.filter('reverse',(e)=>{
return e.slice().reverse();
})

const router=new VueRouter({
    routes,
    mode:'history'
})

//passport
Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);



 
import VueChatScroll from 'vue-chat-scroll'
Vue.use(VueChatScroll)




const app = new Vue({
    el: '#app',
    router
});

