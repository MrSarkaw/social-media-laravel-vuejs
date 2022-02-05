<template >
  <div class="col-12 col-md-8 p-0 mx-auto row">  

  <button class="text-center btn btn-light col-5 mx-auto mt-5" data-target="#modalImg" data-toggle="modal">
    زبادكردنی بڵاوكراوە <i class="fad fa-image"></i>
    </button>
    
    <div class="modal fade animated slideInUp" id="modalImg">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body text-center">
              <small>وێنەیەك هەڵبژێرە</small>
              <vue-editior @modal="insertImage"></vue-editior>
          </div>
        </div>
      </div>
    </div>
    
    
    
    
    
    
    <div class="col-12 mt-3 p-0 mx-auto">    
        <p class="text-center text-light  " v-if="posts.length ==0 ||currentUser==''">
            هیچ بڵاوكراوەیەك نییە
        </p>
        
      <div
        class="col-10 col-sm-8 col-md-6 col-lg-6 text-light
         mx-auto mt-3 divUserImg p-0 animated bounceInLeft mt-5 "
        v-for="(post,index) in posts"
        :key="index"
      >
        <div class="col-7 mt-2">
         <router-link :to="{name:'peopleProfile',params:{name:post.user.name}}">
          <img
            src="wallpaper/images.png"
            class="col-2 p-0 rounded-circle"
            v-if="post.user.path ==null"
          />
          <img
            :src="'wallpaper/'+post.user.path"
            class="col-2 p-0 rounded-circle"
            v-if="post.user.path !=null"
          />
          <p class="d-inline">{{post.user.name}}</p>
          </router-link>
        </div>
        <div class="col-12 mt-3 p-0">
          <img :src="'posts/'+post.path" class="col-12 p-0" />
        </div>
        <p class="text-light text-center col-10 mx-auto mt-2">{{post.title}}</p>
        <!-- likes -->
        <div class="col-10 mx-auto row p-0"> 
          <likes :id="post.id" @likes="showLike"></likes>
        <div class="text-light text-right  col-7 float-right" @click="comment(post)">
              <i class="fas fa-comment-alt-smile "></i>
              {{post.comments.length}}
         </div>
        </div>
        
      </div>
      <infinite-loading @infinite="loadPosts" ref="postsInf">
        <div slot="no-more"></div>
        <div slot="no-results"></div>
        <div slot="spinner"></div>
      </infinite-loading>
    </div> 

      <!--modal-->
      <div 
      class="modal fade animated slideInUp"
      data-backdrop="static"
      data-keyboard="false"
      id="modal"
      >
            <div class="modal-dialog ">
                <div class="modal-content">
                  <!-- modal Likes -->
                  <like 
                    :likes="likes" 
                    :check="checkModal"
                    :pagelike="pagelike"
                      v-if="checkModal==1"
                    @resetInf="resetInf"
                    @name="changeRoute">
                    <infinite-loading @infinite="loadLike" ref="infLike">
                      <div slot="no-more"></div>
                      <div slot="no-results"></div>
                    </infinite-loading>
                  </like> 

                  <!-- modal comment -->
                  <div class="modal-body" v-if="checkModal==2">
                      <p class="h4 text-right" data-dismiss="modal" @click="resetInfComm">
              <i class="fas fa-times-square"></i>
            </p>
            <div class="inp">
              <i class="fas fa-comment"></i>
              <input
                type="text"
                name="comment"
                v-model="form.comment"
                placeholder="..."
                :class="{'is-invalid':form.errors.has('comment')}"
              />
            </div>
            <button
              class="mt-2 btn-sm btn-block btnBlue"
              v-if="form.comment !=''"
              @click="insertComment"
            >
              <i class="fad fa-arrow-down"></i>
            </button>
          </div>
          <div class="modal-footer" v-if="checkModal==2">
            <div class="col-12 p-0 comSec mt-2">
              <div
                class="col-12 ml-0 text-left row border-bottom"
                v-for="(comment,index) in comments"
                :key="index"
              >
                <div class="col-12 mt-1 p-0 text-center mx-auto"  @click="changeRoute(comment.user.name)">
                    <img
                      src="/wallpaper/images.png"
                      class="col-4 col-md-3 rounded-circle"
                      v-if="comment.user.path==null"
                    />
                    <img
                      :src="'/wallpaper/'+comment.user.path"
                      class="col-4 col-md-3 rounded-circle"
                      v-if="comment.user.path!=null"
                    />
                    <p class>{{comment.user.name}}</p>
                </div>
                <div class="col-12 p-0">
                  <p class="text-center">{{comment.comment}}</p>
                </div>
                <small class="col-12 text-right">{{comment.created_at|time}}</small>
                   <p class="text-left text-danger" @click="deleteComment(comment)"
                  v-if="comment.user.id==currentUser">
                    <i class="fas fa-trash"></i>
                    </p>
              </div>
              <infinite-loading @infinite="showCommet" ref="comment">
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
import like from './modal/like'
import likes from './likes.vue'
import VueEditior from "./VueEditior.vue";
export default {
   watch: {
    $route(to, from) {
      $("#modal").modal("hide");
      this.posts.length = 0;
      this.pagePost = 1;
      this.pageComment = 1;
      this.pageIdLike = 1;
      this.loadposts();
    }
  },
  data() {
      return{ 
        currentUser:'',
          posts:[],
          pagePost:1, 
          checkModal:'',   
          likes:[],
          pagelike:1,
          pageIdLike:'',
          form :new Form({
            comment:'',
            post_id:''
          }),
          pageComment:1,
          comments:[]
           }
  },
  methods:{
      loadPosts($state){ 
          axios.get('api/postsFriends',{
              params:{
                  page:this.pagePost
              }
          })
          .then((data)=>{
            this.currentUser=data.data[1];
              let posts=data.data[0].data;
              if(posts.length){
                  this.posts=this.posts.concat(posts);
                  $state.loaded();
              }
              else{
                  $state.complete();
              }
          }).catch(()=>{

          })
          this.pagePost++;
      },
      showLike(likes){  
          this.likes.length = 0;
          this.checkModal=1;
          this.pagelike=1;
          this.pageIdLike=likes;
          this.loadLike();
          $('#modal').modal('show');
      },
      loadLike($state) {
          axios
            .get("/api/peopleWhoLike/" + this.pageIdLike, {
              params: {
                page: this.pagelike
              }
            })
            .then(data => {
              let likes = data.data.data;
              if (likes.length) {
                this.likes = this.likes.concat(likes);
                $state.loaded();
              } else {
                $state.complete();
              }
            })
            .catch(err => {
              console.log(err.response);
          });
          this.pagelike += 1;
        },
      resetInf() {
        this.$refs.infLike.stateChanger.reset();
        this.pagelike=1;
        $('#modal').modal('hide');
      },

    comment(post) {
      this.form.reset();
      this.form.clear();
      this.form.post_id = post.id;
      if (this.comments.length != 0) {
        this.resetInfComm();
      }
      this.checkModal = 2;
      this.pageComment = 1;
      $("#modal").modal("show");
      this.showCommet();
    },
    showCommet($state) {
      axios
        .get("/api/comment/" + this.form.post_id, {
          params: {
            page: this.pageComment
          }
        })
        .then(response => {
          let comment = response.data["data"];
          if (comment.length > 0) {
            this.comments = this.comments.concat(comment);
            $state.loaded();
          } else {
            $state.complete();
          }
        })
        .catch(error => {
          console.log(error.response);
        });
      this.pageComment += 1;
    },

    insertComment() {
      this.$Progress.start();
      this.form
        .post("/api/comment")
        .then(() => {
          this.$Progress.finish();
           this.form.reset();
           $('#modal').modal('hide');
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    deleteComment(comment) {
      this.$Progress.start();
      axios
        .delete("/api/comment/" + comment.id)
        .then(() => {
          this.comments = this.comments.filter(function(item) {
            return comment.id != item.id;
          });
          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    resetInfComm() {
      this.$refs.comment.stateChanger.reset();
      this.comments.length = 0;
    },
    changeRoute(name){
      $('#modal').modal('hide');
      this.$router.push({name:'peopleProfile',params:{name:name}})
    },
    insertImage(){
      $('#modalImg').modal('hide');
    }
  },
  components:{
      likes,like,VueEditior
  },
  created() {

  }
};
</script>

<style  scoped>
.comSec{
  height: 500px;
  overflow: scroll;
  overflow-x: hidden;
}
.comSec::-webkit-scrollbar{
  width:10px;
  background: none;
}
.comSec::-webkit-scrollbar-thumb{
  width: 5px;
  background: #8a8a8a;
}
</style>