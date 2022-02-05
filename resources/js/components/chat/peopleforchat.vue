<template>
    <div><br><br>
        <div class="col-12 p-3 divChat col-sm-10 col-md-6 col-lg-5 mx-auto text-center">
            <div class="col-8 mx-auto inp">
               <i class="fad fa-search text-light"></i> 
               <input type="search" placeholder="search for name .." 
                    v-model="searchUser" @keyup="sear">
            </div>
            <div class="col-12 mt-3 p-1 text-light  chat ">
              <router-link to='/messages'>نامەكانت <i class="fas fa-inbox"></i></router-link>  
            </div>
            <div class="col-12 mt-3 p-3 text-light  divPeople " v-if="searchUser==''">
                <p>هاوڕێكانت</p>
                <div class="col-12 p-1 mt-1 divSngleUser animated zoomIn "
                 v-for="(user,index) in users" :key="index">
                 <router-link :to="{name:'message',params:{name:user.people.name}}">
                        <img src="wallpaper/images.png" v-if="user.people.path==null"
                        class="col-2 col-md-1 p-0 rounded-circle" alt="">
                        <img :src="'wallpaper/'+user.people.path" v-if="user.people.path!=null"
                        class="col-2 col-md-1 p-0 rounded-circle" alt="">{{user.people.name}}
                </router-link>
                </div>
                <infinite-loading @infinite='loadUser' >
                    <div slot="no-more"></div>
                    <div slot="no-results">هاوڕێ زیاد بكە</div>
                </infinite-loading>
                  
            </div>
            <div class="col-12 mt-3 p-3 text-light  divPeople " v-if="searchUser!=''">
                <p>ئەنجامی گەڕان</p>
                 <div>
                        <div class="col-12 p-1 mt-1 divSngleUser animated zoomIn "
                        v-for="(user,index) in resultsUser" :key="index">
                        <router-link :to="{name:'message',params:{name:user.name}}">
                                <img src="wallpaper/images.png" v-if="user.path==null"
                                class="col-2 col-md-1 p-0 rounded-circle" alt="">
                                <img :src="'wallpaper/'+user.path" v-if="user.path!=null"
                                class="col-2 col-md-1 p-0 rounded-circle" alt="">{{user.name}}
                        </router-link>
                        </div>
                        <infinite-loading @infinite='sear' ref="sear" >
                            <div slot="no-more"></div>
                            <div slot="no-results">هاوڕێ زیاد بكە</div>
                        </infinite-loading>
                    </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return{
            users:[],
            pages:1,
            resultsUser:[],
            checkforUser:'',
            searchUser:''
        }
    },
    methods:{
        loadUser($state){
            axios.get('/api/getFollowing',{
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
        },
     sear: _.debounce(function ($state) {
        if(this.searchUser==''){
            this.resultsUser.length=0;
            this.pages=1;
        }else{
            this.checkSearch2(this.searchUser);
            axios
            .get("/api/search/" + this.searchUser, {
            params: {
                page: this.pages
            }
            })
            .then(data => {
            let result = data.data.data;
            if (result.length) {
                console.log(result);
                this.resultsUser = this.resultsUser.concat(result);
                $state.loaded();
            } else {
                $state.complete();
            }
            })
            .catch(() => {});
        this.pages++;
        }
    }, 1300),

    checkSearch2(val){
      
      if(this.checkforUser!=val){
        if(this.resultsUser.length){
        this.$refs.sear.stateChanger.reset();
       }
        this.pages=1;
        this.resultsUser.length=0;
        this.checkforUser=val;
      }
    },
    },
    
}
</script>
<style scoped>
    .divChat {
       background: #538ec8; 
       border-radius: 10px;
    }
    .divPeople,.chat{
        border-radius: 10px;
          box-shadow: -2px -2px 5px rgba(255, 255, 255, 0.685) ,
            3px 3px 7px #000000be ;
    }
    .divPeople{
        height: 400px;
        overflow: scroll;
        overflow-x: hidden;
    }
    .divPeople::-webkit-scrollbar{
        width:10px;
        background: none;border-radius: 20px;
    }
    .divPeople::-webkit-scrollbar-thumb{
        width:7px;
        background: #e6e6e6;
        border-radius: 20px;
    }
    .divSngleUser{
        background: #141414;
        border-radius: 10px;
        box-shadow: -2px -2px 3px rgb(255, 255, 255) inset,
            3px 3px 7px #000000be inset;
    }
</style>