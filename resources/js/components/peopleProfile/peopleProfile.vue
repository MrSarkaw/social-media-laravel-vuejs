<template>
  <div>

    <div class="col-11 mx-auto p-0">
      <div class="col-10 col-md-6 col-lg-4 mx-auto divs text-center">
        <img
          :src="'/wallpaper/'+user.path"
          alt
          class="p-1 ImgShadow col-4 rounded-circle"
          v-if="user.path !=null"
        />
        <img
          src="/wallpaper/images.png"
          alt
          class="p-1 col-4 ImgShadow rounded-circle"
          v-if="user.path==null"
        />
        <p class="text-center text-light mt-3 font-weight-bold">{{user.name}}</p>

        <div class="row col-12 col-sm-8 col-md-6 mx-auto p-0 text-center">
          <button
            class="btngreen mx-auto col-12 animated pulse"
            v-if="checkSendFollow==0"
            @click="follow(user.id,)"
          >
            follow
            <i class="fad fa-user-plus"></i>
          </button>
          <button
            class="btnrequest mx-auto col-12 animated pulse"
            v-if="checkSendFollow==1"
            @click="unfollow(user.id)"
          >
            request
            <i class="fad fa-clock"></i>
          </button>
          <button
            class="btnunfollow mx-auto col-12 animated pulse"
            v-if="checkSendFollow==2"
            @click="unfollow(user.id)"
          >
            unfollow
            <i class="fad fa-user-times"></i>
          </button>
        </div>
        <span v-if="checkPrivate==1">
          <small class="mt-2 badge badge-light">
            {{countFollower}} followers
            <i class="fad fa-users"></i>
          </small>
          <small class="badge badge-light mt-2">
            {{countFollowing}} following
            <i class="fad fa-users"></i>
          </small>
        </span>
        <span v-if="checkPrivate==0">
          <small class="mt-2 badge badge-light" @click="followers">
            {{countFollower}} followers
            <i class="fad fa-users"></i>
          </small>
          <small class="badge badge-light mt-2" @click="following">
            {{countFollowing}} following
            <i class="fad fa-users"></i>
          </small>
        </span>
        <small class="mt-2 badge badge-light" v-if="user.posts && user.posts.length >= 0">
          {{user.posts.length}} بڵاوكراوەكانی
          <i class="fas fa-images"></i>
        </small>
        <br />

        <div class="col-12 mt-3 text-light">{{user.bio}}</div>
      </div>
    </div>

    <posts :posts="posts" @like="like" @comment="comment" v-if="checkPrivate==0">
      <infinite-loading @infinite="loadposts" ref="infLike">
        <div slot="no-more"></div>
        <div slot="no-results"></div>
      </infinite-loading>
    </posts>
    <div class="col-12 mx-auto text-center text-light" v-if="checkPrivate==1">
      <i class="fad fa-user-lock" style="font-size:60px;"></i>
      <br />
      <small>ئەم هەژمارە تایبەتیە</small>
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
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <!-- check ==1 -->
          <div class="modal-body" v-if="check==1">
            <p class="h4 text-right" data-dismiss="modal" @click="resetInfComm">
              <i class="fas fa-times-square"></i>
            </p>
            <div class="inp">
              <i class="fas fa-comment"></i>
              <input
                type="text"
                name="comment"
                v-model="form1.comment"
                placeholder="..."
                :class="{'is-invalid':form1.errors.has('comment')}"
              />
            </div>
            <button
              class="mt-2 btn-sm btn-block btnBlue"
              v-if="form1.comment !=''"
              @click="insertComment"
            >
              <i class="fad fa-arrow-down"></i>
            </button>
          </div>
          <div class="modal-footer" v-if="check==1">
            <div class="col-12 p-0 mt-2">
              <div
                class="col-12 ml-0 text-left row border-bottom"
                v-for="(comment,index) in comments"
                :key="index"
              >
                <div class="col-12 mt-1 p-0 text-center mx-auto">
                  <router-link :to="{name:'peopleProfile',params:{name:comment.user.name}}">
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
                  </router-link>
                </div>
                <div class="col-12 p-0">
                  <p class="text-center">{{comment.comment}}</p>
                </div>
                <small class="col-12 text-right">{{comment.created_at|time}}</small>
                   <p class="text-left text-danger" @click="deleteComment(comment)"
                  v-if="comment.user.id==currentUser.id">
                    <i class="fas fa-trash"></i>
                    </p>
              </div>
              <infinite-loading @infinite="showCommet" ref="infiniteLoading">
                <div slot="no-more"></div>
              </infinite-loading>
            </div>
          </div>

          <!-- check ==2 -->
          <like
            :check="check"
            :likes="likes"
            :pagelike="pagelike"
            @resetInf="resetInf"
            v-if="check==2"
          >
            <infinite-loading @infinite="loadLike" ref="infLike">
              <div slot="no-more"></div>
              <div slot="no-results"></div>
            </infinite-loading>
          </like>

          <!-- check 4 -->

          <followers
            :allFollowers="allFollowers"
            :pageFollower="pageFollower"
            :check="check"
            v-if="check==4"
            @restInfFollower="resetInfFollower"
          >
            <infinite-loading @infinite="loadFollowers" ref="infFollower">
              <div slot="no-more"></div>
              <div slot="no-results"></div>
            </infinite-loading>
          </followers>

          <!-- check=5 -->
          <following
            :allFollowing="allFollowing"
            :pageFollowing="pageFollowing"
            :check="check"
            v-if="check==5"
            @restInfFollowing="resetInfFollowing"
          >
            <infinite-loading @infinite="loadFollowing" ref="infFollowing">
              <div slot="no-more"></div>
              <div slot="no-results"></div>
            </infinite-loading>
          </following>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import like from "./modal/like.vue";
import followers from "./modal/followers.vue";
import following from "./modal/following.vue";
import posts from "./posts.vue";
export default {
  data() {
    return {
      name: this.$route.params.name,
      user: [],
      checkPrivate: 1,
      checkSendFollow: 0,
      countFollower: "",
      countFollowing: "",
      posts: [],
      comments: [],
      likes: [],
      currentUser: "",
      pageComment: 1,
      pagelike: 1,
      page: 1,
      check: 0,
      form1: new Form({
        comment: "",
        post_id: ""
      }),
      pageIdLike: "",
      allFollowers: [],
      pageFollower: 1,
      allFollowing: [],
      pageFollowing: 1
    };
  },
  watch: {
    $route(to, from) {
      this.name = to.params.name;
      $("#modal").modal("hide");
      this.user.length = 0;
      this.posts.length = 0;
      this.page = 1;
      this.pageComment = 1;
      this.pageIdLike = 1;
      this.pageFollower = 1;
      this.pageFollowing = 1;
      this.load();
      this.loadposts();
    }
  },
  methods: {
    load() {
      axios.get("/api/peopleProfile/" + this.name).then(data => {
        if(data.data[0] == null){
          this.$router.push('/home');
        }
        this.user = data.data[0];
        this.countFollower = data.data[1];
        this.currentUser = data.data[2];
        this.countFollowing = data.data[3];
        if (data.data[2].name == data.data[0].name) {
          this.$router.push("/profile");
        }
        this.checkFollow(data.data[0].id);
      });
    },
    checkFollow(id) {
      axios.get("/api/checkFollow/" + id).then(data => {
        this.checkPrivate = data.data[0];
        this.checkSendFollow = data.data[1];
      });
    },
    loadposts($state) {
      axios
        .get("/api/peoplePost/" + this.name, {
          params: {
            page: this.page
          }
        })
        .then(response => {
          let posts = response.data.data;
          if (posts.length) {
            this.posts = this.posts.concat(posts);
            $state.loaded();
          } else {
            $state.complete();
          }
        })
        .catch(error => {
          console.log(error.response);
        });
      this.page += 1;
    },
    forms() {
      this.form1.clear();
      this.form1.reset();
    },
    //edit
    //comment
    comment(post) {
      this.forms();
      this.form1.post_id = post.id;
      if (this.comments.length != 0) {
        this.resetInfComm();
      }
      this.check = 1;
      this.pageComment = 1;
      $("#modal").modal("show");
      this.showCommet();
    },
    showCommet($state) {
      axios
        .get("/api/comment/" + this.form1.post_id, {
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
      this.form1
        .post("/api/comment")
        .then(() => {
          this.$Progress.finish();
           this.form1.reset();
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
      this.$refs.infiniteLoading.stateChanger.reset();
      this.comments.length = 0;
    },

    //likes
    like(post) {
      this.check = 2;
      this.pagelike = 1;
      this.resetInf();
      this.pageIdLike = post.id;
      this.loadLike();
      $("#modal").modal("show");
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
          if (likes.length > 0) {
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
      this.likes.length = 0;
    },

    //follow
    follow($id) {
      axios
        .post("/api/follow", {
          id: $id
        })
        .then(() => {
          if (this.checkPrivate == 1) {
            this.checkSendFollow = 1;
          } else {
            this.checkSendFollow = 2;
            this.load();
          }
        });
    },
    unfollow($id) {
      axios
        .post("/api/follow", {
          id: $id
        })
        .then(() => {
          this.checkSendFollow = 0;
          this.load();
        });
    },
    followers() {
      this.check = 4;
      this.pageFollower = 1;
      this.allFollowers.length = 0;
      $("#modal").modal("show");
      this.loadFollowers();
    },
    following() {
      this.check = 5;
      this.pageFollowing = 1;
      this.allFollowing.length = 0;
      $("#modal").modal("show");
      this.loadFollowing();
    },
    loadFollowers($state) {
      axios
        .get("/api/getPeopleFollowers/" + this.user.id, {
          params: {
            page: this.pageFollower
          }
        })
        .then(data => {
          let followers = data.data.data;
          if (followers.length) {
            this.pageFollower += 1;
            this.allFollowers = this.allFollowers.concat(followers);
            $state.loaded();
          } else {
            $state.complete();
          }
        })
        .catch(err => {
          console.log(err.response);
        });
    },
    loadFollowing($state) {
      axios
        .get("/api/getPeopleFollowing/" + this.user.id, {
          params: {
            page: this.pageFollowing
          }
        })
        .then(data => {
          let following = data.data.data;
          if (following.length) {
            this.allFollowing = this.allFollowing.concat(following);
            $state.loaded();
          } else {
            $state.complete();
          }
        })
        .catch(err => {
          console.log(err.response);
        });     
        this.pageFollowing++;

    },
    resetInfFollower() {
      this.$refs.infFollower.stateChanger.reset();
    },
    resetInfFollowing() {
      this.$refs.infFollowing.stateChanger.reset();
    }
  },
  created() {
    this.load();
    this.loadposts();
  },

  components: {
    like,
    posts,
    followers,
    following
  }
};
</script>

<style>
.badge {
  padding: 7px;
}
</style>