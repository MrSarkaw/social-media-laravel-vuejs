<template>
  <div>
      <small>كلیك لە وێنەكە بكە بۆ هەڵبژاردنی وێنەی تازە</small>
    <vue-avatar
      :width="200"
      :height="200"
      :border="1"
      :border-radius="100"
      ref="vueavatar"
      @vue-avatar-editor:image-ready="onImageReady"
      :image="'wallpaper/'+this.oldImg"
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
    <button v-on:click="saveClicked" class="btn btnBlue">تازەكردنەوە</button>
  </div>
</template>
<script>    
import Vue from 'vue'
import VueAvatar from '../src/components/VueAvatar.vue'
import VueAvatarScale from '../src/components/VueAvatarScale.vue'
export default {
    props:['oldImg'],
    data(){
        return{
            image:''
        }
    },
  components: {
    VueAvatar,
    VueAvatarScale
  },
  methods:{
    onChangeScale (scale) {
        this.$refs.vueavatar.changeScale(scale)
    },
    saveClicked(){
      var img = this.$refs.vueavatar.getImageScaled()
      axios.put('/api/updateImage',{
              path:img.toDataURL(),
      }).then(()=>{
           this.$emit('update');
      })
    },
    onImageReady(scale){
      this.$refs.vueavatarscale.setScale(scale)
    }
  }
};
</script>