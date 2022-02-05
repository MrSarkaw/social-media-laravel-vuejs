<template>
  <div>
    <br />
    <div class="col-10 col-sm-6 col-md-3 mx-auto p-0 divUserImg">
      <img :src="'/posts/'+post.path" class="col-12 p-0" />
      <p class="text-light text-center mt-2">{{post.title}}</p>
      <p class="ml-2 text-light d-inline" @click="showLike" 
      v-if="post.likes && post.likes.length >=0">
        <i class="fas fa-heart text-danger" ></i>
        {{post.likes.length}}
      </p>
      <p class="ml-2 text-light d-inline float-right mr-2" @click="editPost">
        <i class="fas fa-cog text-light"></i>
      </p>
    </div>
<!-- comment -->
    <div class="col-12">
      <div
        class="col-12 col-sm-10 border-bottom col-md-5 col-lg-4 mx-auto mt-3 text-light"
        v-for="(comment,index) in comments"
        :key="index"
      >
        <div class="col-12 p-2">
          <div class="col-12">
            <div class="col-6 mx-auto text-center">
              <router-link :to="{name:'peopleProfile',params:{name:comment.user.name}}">
              <img
                :src="'/wallpaper/'+comment.user.path"
                class="col-9 col-md-6 rounded-circle"
                v-if="comment.user.path != null"
              />
              <img
                src="/wallpaper/images.png"
                class="col-9 col-md-6 rounded-circle"
                v-if="comment.user.path == null"
              />
              <p class="text-center">{{comment.user.name}}</p>
              </router-link>
            </div>
          </div>
          <div class="col-12 p-3 divComment">
            <p class="text-center">{{comment.comment}}</p>
          </div>
          <button class="btn mt-2 mb-2 btn-sm btn-danger" @click="deleteComment(comment)">
            <i class="fas fas fa-trash" ></i>
          </button>
        </div>
      </div>
      <infinite-loading @infinite="loadComment">
        <div slot="no-more"></div>
        <div slot="no-results"></div>
      </infinite-loading>
    </div>

    <div class="modal fade" id="modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <!-- like -->
          <div class="modal-body" v-if="check==false">
            
            <p v-if="likes.length==0" class="text-center">هیچ لایكێك بوونی نییە</p>
              <div class="col-12 likeSec">
                <div
                  class="col-12 mx-auto text-center p-2 mt-2 divLike"
                  v-for="(like,index) in likes"
                  :key="index"
                >
                  <div class="col-8 col-sm-8 mx-auto text-center" @click="changeRoute(like.user.name)">
                    <img src="/wallpaper/images.png" class="col-3 p-0 rounded-circle" 
                        v-if="like.user.path ==null"/>
                    <img :src="'/wallpaper/'+like.user.path" class="col-3 p-0 rounded-circle" 
                        v-if="like.user.path !=null"/>
                      <p class="d-inline">{{like.user.name}}</p>
                  </div>
                </div>
              </div>
            <infinite-loading @infinite="loadLike">
              <div slot="no-more"></div>
              <div slot="no-results"></div>
            </infinite-loading>
          </div>
          <!-- edit -->
          <div class="modal-body" v-if="check">
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
             
              <button class=" btn-sm btn-block btnBlue mt-2" @click="updatePost">
              <i class="fad fa-pen"></i>  تازەكردنەوە
              </button>
            </div>
            <hr class="bg-light" />
            <button class=" btn-block btnBlue btn-sm" @click="deletePost">
               <i class="fas fa-trash text-danger"></i>  سڕینەوە
              </button>
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
      post_id: this.$route.params.id,
      post: {},
      pageLike:1,
      form: new Form({
        title: ""
      }),
      likes: [],
      comments: [],
      page: 1,
      check: true
    };
  },
  methods: {
    loadPost() {
      axios.get("/api/posts/" + this.post_id).then(response => {
        this.post = response.data;
       
      }).catch(()=>{
        $this.router.push('/profile');
      });
    },
    loadLike($state) {
      axios.get("/api/peopleWhoLike/" + this.post_id,{
        params:{
          page:this.pageLike
        }
      }).then(response => {
        let likes = response.data.data;
        if(likes.length){
          this.likes=this.likes.concat(likes);
          $state.loaded();
        }else{
          $state.complete();
        }
      }).catch((err)=>{
        console.log(err.response);
      });
      this.pageLike++;
    },
    loadComment($state) {
      axios
        .get("/api/comment/" + this.post_id, {
          params: {
            page: this.page
          }
        })
        .then(response => {
          let comments = response.data.data;
          if (comments.length) {
           
            this.comments = this.comments.concat(comments);
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
    showLike() {
      this.check = false;
      $("#modal").modal("show");
          this.loadLike();
    },
    editPost() {
      this.form.fill(this.post);
      this.check = true;
      $("#modal").modal("show");
    },
    deletePost() {
      Swal.fire({
        title: "دڵنیای ئەتەوێت بیسڕیتەوە ؟",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "بەلێ",
        cancelButtonText: "نەخێر"
      }).then(result => {
        if (result.value) {
          axios.delete('/api/posts/'+this.post_id)
          .then(()=>{
             $("#modal").modal("hide");
            this.$router.push('/profile');
          })
        }
      });
    },
    deleteComment(comment) {
      this.$Progress.start();
      axios.delete('/api/comment/'+comment.id).then(()=>{
      this.$Progress.finish();
       this.comments = this.comments.filter(function (item) {
            return comment.id != item.id;
        });
      });
    },
    updatePost() {
      this.$Progress.start();
      this.form
        .put("/api/posts/" + this.post_id)
        .then(() => {
          this.$Progress.finish();
          this.loadPost();
          $("#modal").modal("hide");
        })
        .catch(() => {});
    },
    changeRoute(name){
      $('#modal').modal('hide');
      this.$router.push({name:'peopleProfile',params:{name:name}});
    }
  },
  created() {
    this.loadPost();
    this.loadComment();
  }
};
</script>

<style scoped>
 
  .divComment {
    box-shadow: 3px 3px 10px #000000 inset,
    -2px -2px 3px rgba(182, 182, 182, 0.289) inset;
    border-radius: 20px;
    background: #4c86c7;
  }
  a{
    color:white;
  }
</style>