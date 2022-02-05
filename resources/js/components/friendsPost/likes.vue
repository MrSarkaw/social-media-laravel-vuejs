<template>
  <div class="float-left col-5">
    <i class="fal fa-heart heartBeat" v-if="check==false" @click="insertLike"></i>
    <i class="fas fa-heart text-danger animated heartBeat" v-if="check" @click="deleteLike"></i>
    <span @click="showLike">{{numberLike}}</span>
  </div>
</template>
<script>
export default {
  props: ["id"],
  data() {
    return {
      likes: [],
      check: false,
      numberLike: 0
    };
  },
  methods: {
    loadLike() {
      axios.get("/api/likes/" + this.id).then(data => {
        this.check = data.data[1];
        this.numberLike = data.data[2];
      });
    },
    insertLike() {
      this.numberLike++;
      this.check = true;
      axios
        .post("/api/likes", { id: this.id })
        .then(data => {})
        .catch(() => {});
    },
    deleteLike() {
      this.numberLike--;
      this.check = false;
      axios
        .post("/api/likes", { id: this.id })
        .then(data => {})
        .catch(() => {});
    },
    showLike(){
        this.$emit('likes',this.id);
        }
           
  },
  created() {
    this.loadLike();
  }
};
</script>