<template>
  <div>
    <div class="row p-0 posts pb-2" v-if="posts.length > 0">
      <div class="col-10 mx-auto col-sm-4 col-lg-3" v-for="(post,index) in posts" :key="index">
        <div class="animated fadeInUp divUserImg">
          <img :src="'/posts/'+post.path" alt style="width:100%" />
          <div class="row">
            <p class="col-12 text-light text-center">
              {{post.title}}
            </p>
            <p class="text-light text-center mt-2 col-4 mx-auto">
                <likePost :post="post" @like="like"></likePost>
            </p>
            <p class="text-light text-center mt-2 col-4 mx-auto" @click="comment(post)">
              <i class="fas fa-comment-alt-smile"></i>
              {{post.comment.length}}
            </p>
          </div>
        </div>
      </div>
      <slot></slot>
    </div>
  </div>
</template>
<script>
import likePost from './likePost.vue'
export default {
  props: ["posts"],
  methods: {
    comment(post) {
      this.$emit("comment", post);
    },
    like(post) {
      this.$emit("like", post);
    },
  },
  components:{
    likePost
  },
  
};
</script>

<style scoped>


i {
  cursor: pointer;
  font-size: 17px;
}

</style>