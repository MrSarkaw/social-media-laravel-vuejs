//users
import publics from './components/publicPost/public.vue'
import publicPost from './components/publicPost/publicPost.vue'
//friends
import friends from './components/friendsPost/friends.vue'
import friendsPost from './components/friendsPost/friendsPost.vue'
//profile
import profile from './components/profile/profile.vue'
import profileShow from './components/profile/profileShow.vue'
import detailsPosts from './components/profile/detailsPosts.vue'
import profileSetting from './components/profile/profileSetting.vue'
//profilePoeple
import peopleProfile from './components/peopleProfile/peopleProfile.vue'
//notification
import notification from './components/notification/notification.vue'
import index from './components/index.vue'
//chat
import chat from './components/chat/chat.vue'
import peopleforchat from './components/chat/peopleforchat.vue'
import message from './components/chat/message.vue'
import oldMessage from './components/chat/oldMessage.vue'

export const routes=[
    
    {path:'/public',component:publics,children:[
            {path:'',component:publicPost},
        ]},
    {path:'/home',component:friends,children:[
            {path:'',component:friendsPost},
        ]},
     {path:'/profile',component:profile,children:[
            {path:'/',component:profileShow},
            {path:'/postDetails/:id',component:detailsPosts,name:'detailsPosts'},
            {path:'/profileSetting',component:profileSetting}
        ]},
    {path:'/:name/profile',component:peopleProfile,name:'peopleProfile'},
    {path:'/notification',component:notification},
    {path:"/chat",component:chat,children:[
        {path:'/',component:peopleforchat},
        {path:'/messages',component:oldMessage},
        {path:':name/message',component:message,name:'message'}
    ]}
   
  
]