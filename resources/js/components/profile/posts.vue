<template>
  <div>
    <div class="row p-0 posts pb-2" v-if="posts.length > 0">
      <div class="col-10 mx-auto col-sm-4 col-lg-3" v-for="(post,index) in posts" :key="index">
        <div class="animated fadeIn divUserImg" >
          <img :src="'posts/'+post.path" alt style="width:100%" />
          <div class="row col-12 mx-auto p-0 ">
            <p class="text-light text-center mt-2 col-4 mx-auto" @click="like(post)">
              <i class="fas fa-heart text-danger"></i>
              {{post.likes.length}}
            </p>
            <p class="text-light text-center mt-2 col-4 mx-auto" @click="comment(post)">
              <i class="fas fa-comment-alt-smile"></i>
              {{post.comment.length}}
            </p>
            <p class="text-light text-center mt-2 col-4 mx-auto" @click="editPost(post)">
              <i class="fas fa-cog"></i>
            </p>
            <p class="col-12 p-0 text-light text-center">{{post.title}}</p>
          </div>
        </div>
      </div>
      <slot></slot>
    </div>
  </div>
</template>
<script>
export default {
    props:['posts'],
    methods:{
        like(post){
            this.$emit('like',post)
        },
         comment(post){
            this.$emit('comment',post)
        },
         editPost(post){
            this.$emit('editPost',post)
        },
    }
};
</script>

<style scoped>
 

i{
  cursor:pointer;
  font-size:17px;
}

</style>