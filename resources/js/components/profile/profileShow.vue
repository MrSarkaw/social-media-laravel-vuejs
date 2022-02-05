<template>
  <div>
    <!-- head -->
    <div class="col-11 mx-auto p-0">
      <div class="col-10 col-md-6 col-lg-4 mx-auto divs text-center">
        <img
          :src="'wallpaper/'+user.path"
          alt
          class="p-1 ImgShadow col-4 rounded-circle"
          v-if="user.path !=null"
        />
        <img
          src="wallpaper/images.png"
          alt
          class="p-1 col-4 ImgShadow rounded-circle"
          v-if="user.path==null"
        />
        <p class="text-center text-light mt-3 font-weight-bold">{{user.name}}</p>

        <button class="btn btn-sm mt-2 btnWhite" @click="followers">
          {{countFollower}} followers
          <i class="fad fa-users"></i>
        </button>
        <button class="btn btn-sm mt-2 btnWhite" @click="following">
          {{countFollowing}} following
          <i class="fad fa-users"></i>
        </button>
        <br />
        <router-link to="/profileSetting">
          <i class="fas fa-cog text-light" style="position:absolute;top:10px;right:20px"></i>
        </router-link>
        <i
          class="fad fa-image-polaroid text-light"
          @click="ChangeProfile"
          style="position:absolute;top:10px;left:20px;font-size:25px"
        ></i>
        <br />
        <small class="text-light" v-if="user.posts && user.posts.length > 0">
          {{user.posts.length}} بڵاوكراوە
          <i class="fad fa-images"></i>
        </small>
        <small
          class="text-light"
          v-if="user.posts && user.posts.length == 0"
        >هیچ بڵاو كراوەیەكت نییە</small>

        <div class="col-12 mt-3 text-light">{{user.bio}}</div>
      </div>
    </div>
    <!-- body -->
    <posts :posts="posts" @like="like" @comment="comment" @editPost="editPost">
      <infinite-loading @infinite="loadposts" ref="infLike">
        <div slot="spinner"></div>
        <div slot="no-more"></div>
        <div slot="no-results"></div>
      </infinite-loading>
    </posts>

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
          <!-- check ==0 -->
          <div class="modal-body" v-if="check==0">
            <p class="h4 text-right" data-dismiss="modal">
              <i class="fas fa-times-square"></i>
            </p>
            <div class="form-group">
              <div class="inp">
                <input
                  type="text"
                  name="title"
                  placeholder="..."
                  v-model="form.title"
                  :class="{'is-nvalid':form.errors.has('title')}"
                />
                <has-error :form="form" field="title"></has-error>
              </div>

              <button class="btn-sm btn-block btnBlue mt-2" @click="updatePost">
                <i class="fad fa-pen"></i> تازەكردنەوە
              </button>
            </div>
            <hr class="bg-light" />
            <button class="btn-block btnBlue btn-sm" @click="deletePost">
              <i class="fas fa-trash text-danger"></i> سڕینەوە
            </button>
          </div>
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
                :class="{'is-invalid':form.errors.has('comment')}"
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
                <div
                  class="col-12 mt-1 p-0 text-center mx-auto"
                  @click="changeRoute(comment.user.name)"
                >
                  <img
                    src="/wallpaper/images.png"
                    class="col-4 col-md-3 rounded-circle"
                    v-if="comment.user.path==null"
                    @click="changeRoute(comment.user.name)"
                  />
                  <img
                    :src="'/wallpaper/'+comment.user.path"
                    class="col-4 col-md-3 rounded-circle"
                    v-if="comment.user.path!=null"
                    @click="changeRoute(comment.user.name)"
                  />
                  <p class>{{comment.user.name}}</p>
                </div>
                <div class="col-12 p-0">
                  <p class="text-center">{{comment.comment}}</p>
                </div>
                <small class="col-12 text-right">{{comment.created_at|time}}</small>
                <p @click="deleteComment(comment)">
                  <i class="fas fa-trash mb-2"></i>
                </p>
              </div>
              <infinite-loading @infinite="showCommet" ref="infiniteLoading">
                <div slot="no-more"></div>
                <div slot="no-results"></div>
              </infinite-loading>
            </div>
          </div>

          <!-- check ==2 -->
          <like
            :check="check"
            :likes="likes"
            :pagelike="pagelike"
            @resetInf="resetInf"
            @name="changeRoute"
            v-if="check==2"
          >
            <infinite-loading @infinite="loadLike" ref="infLike">
              <div slot="no-more"></div>
              <div slot="no-results"></div>
            </infinite-loading>
          </like>

          <!-- check ==3 -->
          <div class="modal-body" v-if="check==3">
            <p class="h4 text-right" data-dismiss="modal">
              <i class="fas fa-times-square"></i>
            </p>
            <div class="col-12 mx-auto text-center">
              <VueEditior :oldImg="user.path" @update="changedProfile"></VueEditior>
            </div>
          </div>

          <!-- check =4 -->

          <followers
            :allFollowers="allFollowers"
            :pageFollower="pageFollower"
            :check="check"
            v-if="check==4"
            @restInfFollower="resetInfFollower"
            @name="changeRoute"
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
            @name="changeRoute"
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
import VueEditior from "./VueEditior.vue";
export default {
  data() {
    return {
      user: [],
      countFollower: "",
      countFollowing: "",
      posts: [],
      comments: [],
      likes: [],
      pageComment: 1,
      pagelike: 1,
      page: 1,
      check: 0,
      form: new Form({
        title: "",
        id: ""
      }),
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
  methods: {
    load() {
      axios.get("api/profile").then(data => {
        this.user = data.data[0][0];
        this.countFollower = data.data[1];
        this.countFollowing = data.data[2];
      });
    },
    loadposts($state) {
      axios
        .get("api/userPosts", {
          params: {
            page: this.page
          }
        })
        .then(response => {
          let posts = response.data.data;
          if (posts.length) {
            this.page += 1;
            this.posts = this.posts.concat(posts);
            $state.loaded();
          } else {
            $state.complete();
          }
        })
        .catch(error => {
          console.log(error.response);
        });
    },
    forms() {
      this.form.clear();
      this.form.reset();
      this.form1.clear();
      this.form1.reset();
    },
    //edit
    editPost(post) {
      this.forms();
      this.form.fill(post);
      this.check = 0;
      $("#modal").modal("show");
    },
    deletePost() {
      this.$Progress.start();
      this.form
        .delete("api/posts/" + this.form.id)
        .then(() => {
          $("#modal").modal("hide");
          this.$Progress.finish();
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "بەسەركەتوی سڕایەوە",
            showConfirmButton: false,
            timer: 2000
          });
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    updatePost() {
      this.$Progress.start();
      this.form
        .put("api/posts/" + this.form.id)
        .then(() => {
          $("#modal").modal("hide");
          this.$Progress.finish();
          post.$emit("updated");
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "بەسەركەتوی تازە كرایەوە",
            showConfirmButton: false,
            timer: 1500
          });
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
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
        .get("api/comment/" + this.form1.post_id, {
          params: {
            page: this.pageComment
          }
        })
        .then(response => {
          let comment = response.data["data"];
          if (comment.length > 0) {
            this.pageComment += 1;
            this.comments = this.comments.concat(comment);
            $state.loaded();
          } else {
            $state.complete();
          }
        })
        .catch(error => {
          console.log(error.response);
        });
    },

    insertComment() {
      $("#modal").modal("hide");
      this.$Progress.start();
      this.form1
        .post("/api/comment")
        .then(() => {
          this.$Progress.finish();
          this.showCommet();
          this.form1.reset();
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
        .get("api/peopleWhoLike/" + this.pageIdLike, {
          params: {
            page: this.pagelike
          }
        })
        .then(data => {
          let likes = data.data.data;
          if (likes.length > 0) {
            this.pagelike += 1;
            this.likes = this.likes.concat(likes);
            $state.loaded();
          } else {
            $state.complete();
          }
        })
        .catch(err => {
          console.log(err.response);
        });
    },
    resetInf() {
      this.$refs.infLike.stateChanger.reset();
      this.likes.length = 0;
      this.pagelike = 1;
    },
    //profile
    ChangeProfile() {
      this.check = 3;
      $("#modal").modal("show");
    },
    changedProfile() {
      this.load();
      $("#modal").modal("hide");
    },
    //changeRoute
    changeRoute($name) {
      $("#modal").modal("hide");
      this.$router.push($name + "/profile");
    },
    //follow
    //getFollowers
    followers() {
      this.check = 4;
      this.pageFollower = 1;
      $("#modal").modal("show");
      this.loadFollowers();
    },
    following() {
      this.check = 5;
      this.pageFollowing = 1;
      $("#modal").modal("show");
      this.loadFollowing();
    },
    loadFollowers($state) {
      axios
        .get("api/getFollowers", {
          params: {
            page: this.pageFollower
          }
        })
        .then(data => {
          let followers = data.data.data;
          if (followers.length) {
            this.pageFollower++;
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
        .get("api/getFollowing", {
          params: {
            page: this.pageFollowing
          }
        })
        .then(data => {
          let following = data.data.data;
          if (following.length) {
            this.pageFollowing++;
            this.allFollowing = this.allFollowing.concat(following);
            $state.loaded();
          } else {
            $state.complete();
          }
        })
        .catch(err => {
          console.log(err.response);
        });
    },
    resetInfFollower() {
      this.$refs.infFollower.stateChanger.reset();
      this.allFollowers.length = 0;
    },
    resetInfFollowing() {
      this.$refs.infFollowing.stateChanger.reset();
      this.allFollowing.length = 0;
    }
  },
  created() {
    this.load();
    this.loadposts();
  },
  components: {
    like,
    posts,
    VueEditior,
    followers,
    following
  }
};
</script>

<style scoped>
</style>