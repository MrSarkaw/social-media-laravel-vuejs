<template>
    <div><br><br>
        <div class="col-12 p-3 divChat col-sm-10 col-md-6 col-lg-5 mx-auto text-center">
            <div class="col-12 mt-3 p-3 text-light  divPeople ">
                <div class="col-12 p-1 mt-1 divSngleUser animated fadeIn"
                 v-for="(user,index) in users.slice().reverse()" :key="index">
                 <router-link :to="{name:'message',params:{name:user.name}}">
                        <img src="wallpaper/images.png" v-if="user.path==null"
                        class="col-2 col-md-1 p-0 rounded-circle" alt="">
                        <img :src="'wallpaper/'+user.path" v-if="user.path!=null"
                        class="col-2 col-md-1 p-0 rounded-circle" alt="">{{user.name}}
                </router-link>
                </div>
                <infinite-loading @infinite='loadUser'>
                    <div slot="no-more"></div>
                    <div slot="no-results">هاوڕێ زیاد بكە</div>
                </infinite-loading>
                  
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return{
            users:[],
            pages:1
        }
    },
    methods:{
        loadUser($state){
            axios.get('/api/oldMessage',{
                params:{
                    page:this.pages
                }
            }).then((data)=>{
                let follower=data.data.data;
                if(follower.length){
                    this.users=this.users.concat(follower);
                    $state.loaded();
                }else{
                    $state.complete();
                }
            }).catch(()=>{

            })
            this.pages++;
        }
    },
    
}
</script>
<style scoped>
    .divChat{
       background: #538ec8; 
       border-radius: 10px;
    }
    .divPeople{
        border-radius: 10px;
          box-shadow: -2px -2px 5px rgba(255, 255, 255, 0.685) ,
            3px 3px 7px #000000be ;
    }
    .divSngleUser{
        background: #141414;
        border-radius: 10px;
        box-shadow: -2px -2px 3px rgb(255, 255, 255) inset,
            3px 3px 7px #000000be inset;
    }
</style>