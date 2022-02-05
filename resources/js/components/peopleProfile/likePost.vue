<template>
  <div>
    <i class="fal fa-heart heartBeat" @click="insertLike" v-if="check==false"></i>
    <i class="fas fa-heart text-danger animated heartBeat" @click="deleteLike" v-if="check"></i>
    <span @click="like(post)">{{numberLike}}</span>
  </div>
</template>
<script>
export default {
  data() {
    return {
      likes: [],
      check: false,
      numberLike: 0
    };
  },
  props: ["post"],
  methods: {
    load() {
      axios.get("/api/likes/" + this.post.id).then(data => {
        this.likes = data.data[0];
        this.check = data.data[1];
        this.numberLike = data.data[2];
      });
    },
    insertLike() {
      this.numberLike++;
      this.check = true;
      axios
        .post("/api/likes", { id: this.post.id })
        .then(data => {})
        .catch(() => {});
    },
    deleteLike() {
      this.numberLike--;
      this.check = false;
      axios
        .post("/api/likes", { id: this.post.id })
        .then(data => {})
        .catch(() => {});
    },
    like(post) {
      this.$emit("like", post);
    },
  },

  created() {
    this.load();
  }
};
</script>
<style>
i {
  cursor: pointer;
  font-size: 17px;
}
</style>