<template >
  <div class="col-12 col-md-8 p-0 mx-auto row">
    <p class="text-center text-light col-12" v-if="newUsers.length">تازەترین هەژمارەكان</p>
    <div class="col-12 p-1 row mx-auto followDiv" v-if="newUsers.length">
      <div
        class="col-9 col-sm-3 mt-4 animated fadeIn mx-auto text-center text-light"
        v-for="(user,index) in newUsers"
        :key="index"
      >
        <div @click="changeRoute(user.name)">
          <img
            src="wallpaper/images.png"
            class="mx-auto col-4 col-sm-8 rounded-circle"
            v-if="user.path==null"
          />
          <img
            :src="'wallpaper/'+user.path"
            class="mx-auto col-4 col-sm-8 rounded-circle"
            v-if="user.path!=null"
          />
          <p>{{user.name}}</p>
        </div>

        <button class="btngreen mx-auto col-12 animated pulse" @click="sendFollow(user)">
          follow
          <i class="fad fa-user-plus"></i>
        </button>
      </div>
    </div>

    <div class="col-12 p-0 mx-auto mt-2 row">
      <div class="col-4 p-0 animated bounceInLeft" v-for="(post,index) in posts" :key="index">
        <img
          :src="'posts/'+post.path"
          class="col-12 p-0"
          @click="showModal(post,post.user,post.comments)"
        />
              </div>
      <infinite-loading @infinite="loadPosts" ref="postsInf">
        <div slot="no-more"></div>
        <div slot="no-results"></div>
        <div slot="spinner"></div>
      </infinite-loading>
    </div>

    <div
      class="modal fade animated slideInUp"
      data-backdrop="static"
      data-keyboard="false"
      id="modal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="my-modal-title"
      aria-hidden="true"
     >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <p class="h4 text-right" @click="hideModal">
              <i class="fas fa-times-square" ></i>
            </p>
            <div class="col-12">
              <!-- header -->
              <div >
                <img
                  src="wallpaper/images.png"
                  class="col-2 p-0 rounded-circle"
                  v-if="modalUser.path ==null"
                />
                <img
                  :src="'wallpaper/'+modalUser.path"
                  class="col-2 p-0 rounded-circle"
                  v-if="modalUser.path !=null"
                />
                <p class="d-inline" @click="changeRoute(modalUser.name)">{{modalUser.name}}</p> 
                
                <button
                class="btngreen mx-auto float-right mt-3"
                @click="sendFollow(modalUser)"
                v-if="check==0"
                >
                follow
                <i class="fad fa-user-plus"></i>
              </button>
              </div>

             
              <!-- body -->
              <p class="mt-2 col-12 mx-auto text-center">{{modalData.title}}</p>
              <img :src="'posts/'+modalData.path" class="col-12" alt />
              <div class="row">
                <p class="text-light text-left p-0 mt-2 col-4 mx-auto">
                  <i
                    class="fal fa-heart heartBeat"
                    style="font-size:15px"
                    v-if="checkLike==''"
                    @click="insertLike"
                  ></i>
                  <i
                    class="fas fa-heart text-danger animated heartBeat"
                    style="font-size:15px"
                    v-if="checkLike"
                    @click="deleteLike"
                  ></i>

                  <span @click="modal2" class="ml-2">{{likeCount}}</span>
                </p>

                <p class="text-light text-right mt-2 col-4 mx-auto">
                  <i class="fas fa-comment-alt-smile" style="font-size:15px"></i>
                  {{comments.length}}
                </p>
              </div>
              <hr class="bg-light" />
              <!-- comment -->
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

              <div class="commentSec" v-if="commentsData.length!=0">
                <p
                  v-if="commentsData.length ==0"
                  class="h4 mt-4 text-center"
                >هیچ كۆمێنتێك بوونی نییە</p>
                <div
                  class="col-12 mt-2 border-bottom"
                  v-for="(comment,index) in commentsData"
                  :key="index"
                >
                  <div @click="changeRoute(comment.user.name)">
                    <img
                      src="wallpaper/images.png"
                      class="col-2 p-0 rounded-circle"
                      v-if="comment.user.path ==null"
                    />
                    <img
                      :src="'wallpaper/'+comment.user.path"
                      class="col-2 p-0 rounded-circle"
                      v-if="comment.user.path !=null"
                    />
                    <p class="d-inline">{{comment.user.name}}</p>
                  </div>

                  <p class="col-12 mt-2 mx-auto text-center">{{comment.comment}}</p>
                  <small class="col-12 text-right">{{comment.created_at|time}}</small>

                  <p class="text-right text-danger" @click="deleteComment(comment)"
                  v-if="comment.user.id==currentUser || modalData.user_id==currentUser">
                    <i class="fas fa-trash"></i>
                    </p>
                </div>
                <infinite-loading @infinite="showComments" ref="commentInf">
                  <div slot="no-more"></div>
                  <div slot="no-results"></div>
                </infinite-loading>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal2" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <div class="col-12 likeSec">
              <p class="h4 text-right" @click="hideModal2">
                <i class="fas fa-times-square"></i>
              </p>
              <div v-for="(like,index) in userWhoLike" class="mt-2" :key="index">
                <div @click="changeRoute(like.user.name)">
                  <img
                    src="wallpaper/images.png"
                    class="col-2 p-0 rounded-circle"
                    v-if="like.user.path ==null"
                  />
                  <img
                    :src="'wallpaper/'+like.user.path"
                    class="col-2 p-0 rounded-circle"
                    v-if="like.user.path !=null"
                  />
                  <p class="d-inline">{{like.user.name}}</p>
                </div>
              </div>
              <infinite-loading @infinite="loadPersonLike" ref="likeInf">
                <div slot="no-more"></div>
                <div slot="no-results"></div>
                <div slot="spinner"></div>
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
      currentUser:'',
      check: 3,
      checkLike: "",
      newUsers: [],
      posts: [],
      modalData: [],
      modalUser: [],
      likes: [],
      userWhoLike: [],
      likeCount: 0,
      comments: 0,
      commentsData: [],
      pagePost: 1,
      pageLikes: 1,
      commentsPages: 1,

      form: new Form({
        comment: "",
        post_id:''
      })
    };
  },
  methods: {
    userForFollow() {
      axios.get("api/followPublic").then(data => {
        this.newUsers = data.data.data;
      });
    },
    sendFollow(user) {
      this.newUsers = this.newUsers.filter(item => {
        return item.id != user.id;
      });
      this.check = 1;
      axios
        .post("api/follow", {
          id: user.id
        })
        .catch(() => {
          console.log("error");
        });
    },
    loadPosts($state) {
      axios
        .get("api/postsPublic", {
          params: {
            page: this.pagePost
          }
        })
        .then(data => {
          this.currentUser=data.data[1];
          let posts = data.data[0].data;
          if (posts.length) {
            this.posts = this.posts.concat(posts);
            $state.loaded();
          } else {
            $state.complete();
          }
        })
        .catch(error => {
          console.log(error.reponse);
        });
      this.pagePost++;
    },
    showModal(post, user, comments) {
      this.$refs.postsInf.stateChanger.reset();
      this.commentsData.length = 0;
      this.likes.length = 0;
      this.modalData = post;
      this.modalUser = user;
      this.comments = comments;
      this.commentsPages = 1;
      this.form.post_id=post.id;
      this.showComments();
      this.loadLike();
      this.checkFollow();
      $("#modal").modal("show");
    },
    showComments($state) {
      axios
        .get("api/comment/" + this.modalData.id, {
          params: {
            page: this.commentsPages
          }
        })
        .then(data => {
          let commentsData = data.data.data;
          if (commentsData.length) {
            this.commentsData = this.commentsData.concat(commentsData);
            $state.loaded();
          } else {
            $state.complete();
          }
        })
        .catch(error => {
          console.log(error.response);
        });
      this.commentsPages += 1;
    },
    checkFollow() {
      axios.get("api/checkFollow/" + this.modalUser.id).then(data => {
        this.check = data.data[1];
      });
    },
    loadLike() {
      axios.get("api/likes/" + this.modalData.id).then(data => {
        this.checkLike = data.data[1];
        this.likeCount = data.data[2];
      });
    },
    loadPersonLike($state) {
      axios
        .get("/api/peopleWhoLike/" + this.modalData.id, {
          params: {
            page: this.pageLikes
          }
        })
        .then(data => {
          let whoLike = data.data.data;
          if (whoLike.length) {
            this.userWhoLike = this.userWhoLike.concat(whoLike);
            $state.loaded();
          } else {
            $state.complete();
          }
        })
        .catch(() => {});
      this.pageLikes++;
    },
    insertLike() {
      this.likeCount++;
      this.checkLike = true;
      axios
        .post("/api/likes", { id: this.modalData.id })
        .then(data => {})
        .catch(() => {});
    },
    deleteLike() {
      this.likeCount--;
      this.checkLike = "";
      axios
        .post("/api/likes", { id: this.modalData.id })
        .then(data => {})
        .catch(() => {});
    },

    insertComment() {
      this.$Progress.start();
      this.form
        .post("/api/comment")
        .then(() => {
          this.hideModal();
          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
     deleteComment(comment) {
      this.$Progress.start();
      axios
        .delete("api/comment/" + comment.id)
        .then(() => {
          this.commentsData = this.commentsData.filter(function(item) {
            return comment.id != item.id;
          });
          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },

    hideModal() {
      this.checkLike = "";
      this.form.reset();
      if(this.commentsData.length !=0){
        this.$refs.commentInf.stateChanger.reset();
      }
      $("#modal").modal("hide");
    },
    modal2() {
      this.hideModal();
      this.loadPersonLike();
      this.userWhoLike.length = 0;
      this.pageLikes = 1;
      $("#modal2").modal("show");
    },
    hideModal2() {
      this.userWhoLike.length = 0;
      if(this.userWhoLike.length !=0){     
         this.$refs.likeInf.stateChanger.reset();
      }
      $("#modal2").modal("hide");
    },
    changeRoute(name) {
      $("#modal").modal("hide");
      $("#modal2").modal("hide");
      this.$router.push(name + "/profile");
    }
  },
  created() {
    this.userForFollow();
    this.loadPosts();
  }
};
</script>

<style  scoped>
@media only screen and (max-width: 514px) {
  p {
    font-size: 10px;
  }
  button {
    font-size: 10px;
  }
  i {
    font-size: 10px;
  }
}

.followDiv {
  background: #4882ca;
  border-radius: 6px;
  height: 200px;
  overflow: scroll;
  overflow-x: hidden;
}
.followDiv::-webkit-scrollbar,
.commentSec::-webkit-scrollbar,
.likeSec::-webkit-scrollbar {
  width: 10px;
  background: none;
}
.followDiv::-webkit-scrollbar-thumb,
.commentSec::-webkit-scrollbar-thumb,
.likeSec::-webkit-scrollbar-thumb {
  width: 6px;
  border-radius: 20px;
  background: #d8d8d8;
}
@media only screen and (max-width: 575px) {
  .followDiv {
    height: 190px;
  }
}
@media only screen and (max-width: 490px) {
  .followDiv {
    height: 167px;
  }
}
@media only screen and (max-width: 440px) {
  .followDiv {
    height: 137px;
  }
}

@media only screen and (max-width: 345px) {
  .followDiv {
    height: 115px;
  }
}

.commentSec,
.likeSec {
  height: 300px;
  overflow: scroll;
  overflow-x: hidden;
}
</style>