<template>
  <div>
    <div
      class="col-12 p-2 text-center col-md-6 mx-auto p-0"
      style=" background: #457fca;  
      background: -webkit-linear-gradient(to right, #5691c8, #457fca);  
      background: linear-gradient(to right, #5691c8, #457fca); 
      padding: 7px;
      border-radius: 7px;
      color: #dfdfdf;"
      @click="modal"
      v-if="countRequest!=0">
      <p class="d-inline" >
        {{countRequest}}
        <i class="fad fa-user-plus"></i>
      </p>
    </div>

    <div class="col-12 mt-2 Follow col-md-6 mx-auto p-0">  
      <div
        class="col-12 text-center border-bottom diVnoti p-2"
        v-for="(noti,index) in orderedNoti "
        :key="index"
      >
        <router-link :to="noti.user.name+'/profile'">
          <img
            src="wallpaper/images.png"
            class="col-3 col-sm-2 col-md-2 col-lg-1 rounded-circle"
            v-if="noti.user.path ==null"
          />
          <img
            :src="'wallpaper/'+noti.user.path"
            class="col-3 col-sm-2 col-md-2 col-lg-1 rounded-circle"
            v-if="noti.user.path !=null"
          />
          <p class="m-0">{{noti.user.name}}</p>
        </router-link>

        <div v-if="noti.user_id!=null && noti.comment==null">
          <router-link :to="{name:'detailsPosts',params:{id:noti.post_id}}" tag="li">
            <img :src="'posts/'+noti.posts.path" class="col-3 col-lg-2" />لایكی كرد بۆ
            <i class="fas fa-heart-circle"></i>
          </router-link>
        </div>

        <div v-if="noti.user_id!=null && noti.comment!=null">
          <router-link :to="{name:'detailsPosts',params:{id:noti.post_id}}">
            <img :src="'posts/'+noti.posts.path" class="col-3 col-lg-2" /> كۆمێنتی كرد بۆ
            <i class="fas fa-comment"></i>
          </router-link>
        </div>
        <div v-if="noti.check==1 && noti.sender_id!=null">
          <p class="m-0 pb-1 d-inline">بوو بە شوێنكەوتەت</p>
          <i class="fad fa-user-plus"></i>
        </div>
        <small>{{noti.created_at | time}}</small>
      </div>
      <infinite-loading @infinite="loadNoti">
        <div slot="no-more"></div>
        <div slot="no-results">هیچ ئاگاداركردنەوەیەك نییە</div>
      </infinite-loading>
    </div>

    <div class="modal fade animated slideInUp" id="modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
             <p class="h4 text-right" data-dismiss="modal">
              <i class="fas fa-times-square"></i>
            </p>
            <div class="modalScroll">
              <div v-for="(req,index) in request" :key="index">
                <div class="col-12 text-center border-bottom">
                   <a @click="changeRoute(req.user.name)">
                      <img
                        src="wallpaper/images.png"
                        class="col-3 rounded-circle"
                        v-if="req.user.path ==null"
                      />
                      <img
                        :src="'wallpaper/'+req.user.path"
                        class="col-3 rounded-circle"
                        v-if="req.user.path !=null"
                      />
                      <p class="m-0">{{req.user.name}}</p>
                    </a>
                  <p class="m-0 pb-1">ئەیەوێت ببێت بە شوێنكەوتەت</p>
                  <div class="row text-center col-12">
                    <p class="badge badge-success" @click="acceptRequest(req,req.user.id)">
                      <i class="fas fa-check-circle"></i> وەرگرتن
                    </p>
                    <p class="badge badge-danger ml-2" @click="deleteRequest(req.id)">
                      <i class="fas fa-times-circle"></i> لابردن
                    </p>
                  </div>
                </div>
              </div>
              <infinite-loading @infinite="loadRequest">
                <div slot="no-more"></div>
                <div slot="no-results"></div>
              </infinite-loading>
            </div>
             
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      notification: [],
      request: [],
      countRequest: 0,
      page: 1,
      pageRequest: 1
    };
  },
  computed: {
    orderedNoti: function() {
      this.notification.sort((a, b) => {
        return new Date(b.created_at) - new Date(a.created_at);
      });
      return this.notification;
    }
  },
  methods: {
    loadNoti($state) {
      this.$Progress.start();
      axios
        .get("api/notification", {
          params: {
            page: this.page
          }
        })
        .then(data => {
          this.$Progress.finish();
          let follows = data.data[0].data;
          let likes = data.data[1].data;
          let comments = data.data[2].data;
          if (follows.length || likes.length || comments.length) {
            this.notification = this.notification.concat(
              follows,
              likes,
              comments
            );
            $state.loaded();
          } else {
            $state.complete();
          }
        })
        .catch(err => {
          this.$Progress.fail();
          console.log(err.response);
        });
      this.page++;
    },
    loadRequest($state) {
      axios
        .get("api/notifiRequest", {
          params: {
            page: this.pageRequest
          }
        })
        .then(data => {
          let request = data.data[0].data;
          this.countRequest = data.data[1];
          if (request.length) {
            this.request = this.request.concat(request);
            $state.loaded();
          } else {
            $state.complete();
          }
        })
        .catch(err => {
          console.log(err.response);
        });
      this.pageRequest++;
    },
    acceptRequest(req) {
      axios.put("/api/follow/" + req.id).then(() => {
        this.request=this.request.filter(element => {
          return element.id != req.id;
        });
        this.notification=this.notification.concat(req);
          this.countRequest--;
      });
    },
    deleteRequest(id) {
      axios.delete("/api/follow/" + id).then(() => {
        this.request=this.request.filter((item)=>{
         return item.id != id;
        })
        this.countRequest--;
      });
    },
    modal(){
      $('#modal').modal('show');
    },
    changeRoute(name){
      $('#modal').modal('hide');
      this.$router.push(name+'/profile');
    }
  },
  created() {
    this.loadRequest();
  }
};
</script>
<style scoped>
.Follow {
  background: #457fca;
  background: -webkit-linear-gradient(to right, #5691c8, #457fca);
  background: linear-gradient(to right, #5691c8, #457fca);
  padding: 7px;
  border-radius: 7px;
  color: #dfdfdf;
  height: 480px;
  overflow: scroll;
  overflow-x: hidden;
}
.Follow::-webkit-scrollbar {
  width: 10px;
}
.Follow::-webkit-scrollbar-thumb {
  width: 6px;
  background-color: #dfdfdf;
  border-radius: 10px;
}
.diVnoti::before {
  content: "";
  position: absolute;
  top: 0px;
  left: 0px;
  background-color: #141414;
  height: 100%;
  width: 100%;
  clip-path: circle(0px at 50% 50%);
  transition: 0.5s;
}
.diVnoti * {
  position: relative;
}
.diVnoti:hover a {
  color: #457fca;
}
.diVnoti:hover::before {
  content: "";
  position: absolute;
  top: 0px;
  left: 0px;
  background-color: #141414;
  height: 100%;
  width: 100%;
  clip-path: circle(1000px at 10% 99%);
}

.badge {
  cursor: pointer;
}
li {
  list-style-type: none;
}
a {
  color: #252525;
  text-decoration: none !important;
}
.modalScroll{
  height: 400px;
  overflow: scroll;
  overflow-x:hidden;
}
.modalScroll::-webkit-scrollbar{
  width: 10;
  background: none;
}
.modalScroll::-webkit-scrollbar-thumb{
  width: 7px;
  background: #dfdfdf;
  border-radius: 20px;
}
</style>