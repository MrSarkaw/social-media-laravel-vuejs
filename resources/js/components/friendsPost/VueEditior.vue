<template>
  <div>
    <vue-avatar
      :width="300"
      :height="300"
      :border="1"
      ref="vueavatar"
      @vue-avatar-editor:image-ready="onImageReady"
    ></vue-avatar>
    <br />
    <vue-avatar-scale
      ref="vueavatarscale"
      @vue-avatar-editor-scale:change-scale="onChangeScale"
      :width="250"
      :min="1"
      :max="3"
      :step="0.02" 
    ></vue-avatar-scale>
    <br />
    <input 
    type="text" 
    class="form-control text-center" 
    placeholder="title ..." 
    v-model="title"
    v-if="image !=''">
    <br>
    <button v-on:click="saveClicked" v-if="image != ''" class="btn btn-success">بڵاوكردنەوە</button>
  </div>
</template>
<script>    
import Vue from 'vue'
import VueAvatar from '../src/components/VueAvatar.vue'
import VueAvatarScale from '../src/components/VueAvatarScale.vue'
export default {
    data(){
        return{
            image:'',
            title:''
        }
    },
  components: {
    VueAvatar,
    VueAvatarScale
  },
  methods:{
    onChangeScale (scale) {
        this.$refs.vueavatar.changeScale(scale);
        

    },
    saveClicked(){
      var img = this.$refs.vueavatar.getImageScaled()
      axios.post('/api/posts',{
              path:img.toDataURL(),
              title:this.title
      }).then(()=>{
        this.image='';
        this.$emit('modal');
        this.title='';
       Swal.fire(
          'بڵاوكرایەوە',
          '',
          'success'
        )
      })
    },
    onImageReady(scale){
      this.image=1;
      this.$refs.vueavatarscale.setScale(scale)
    }
  }
};
</script>