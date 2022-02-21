<script src="<?php echo base_url('public/vue/vue@3.2.2'); ?>" crossorigin="anonymous"></script>
<style type="text/css">
  body { 
    background: url("<?php echo base_url('public/images/bg.jpg'); ?>") repeat; 
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
  }
</style>
<div id="mainDiv" style="margin: 1%;position:absolute;top:10%">
  <ol style="list-style:none;">
    <mycomponent
    v-for="notification in notifications"
    v-bind:myelement="notification"
    v-bind:key="notification.notificationid"
    ></mycomponent>
  </ol>
</div>

<script type="text/x-template" id="idForTemplate">
  <div v-on:click="clickToTarget(myelement.notificationid,myelement.targetid,myelement.targettype)" style='margin:1%' class="card border-dark">
    <div class="card-body" v-html="myelement.notificationtext"></div>
    <div class="card-footer bg-light text-dark" align="right">{{myelement.createdon}}</div>
  </div>
</script>

<script type="text/javascript">
  const componentVar = {
    data() {
      return {
        notifications: []
      }
    },
    mounted(){
      var innerThis=this;
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          var result=JSON.parse(xhttp.responseText);
          if (result.length==0) {
            document.getElementById("mainDiv").innerHTML="<h2 align='center'>அறிவிப்புகள் அனைத்தையும் பார்த்துவிட்டீர்கள்..!</h2>";
          }
          else{
            result.forEach(function(item){
              innerThis.notifications.push({
                "notificationid":item.notificationid,
                "notificationtext":item.notificationtext,
                "targetid":item.targetid,
                "targettype":item.targettype,
                "createdon":item.createdon
              });
            });
          }
        }
      };
      xhttp.open("GET", "<?php echo base_url('index.php/notifications/getNotificationsForUser'); ?>", true);
      xhttp.send();
    }
  }
  const app = Vue.createApp(componentVar)
  app.component('mycomponent', {
    props: ['myelement','notificationid','notificationtext','targetid','targettype','createdon'],
    template: "#idForTemplate",
    methods : {
      clickToTarget : function(notificationid,targetid,targettype){
       var toServer=new FormData();
       toServer.append("notificationid",notificationid);
       fetch("<?php echo base_url('index.php/notifications/makeNotificationSeen'); ?>",{
        method:'POST',
        body: toServer,
        mode: 'no-cors',
        cache: 'no-cache'})
       .then(response => {
        if (response.status == 200) { return response.json(); }
        else { alert('Backend Error..!'); console.log(response); }
      })
       .then(data => {
        if(targettype=="answer"){
          window.location.href="<?php echo base_url('index.php/answer/viewSingleAnswerWithQuestion?answerid='); ?>"+targetid;
        }
        else if(targettype=="question"){
          window.location.href="<?php echo base_url('index.php/question/viewSingleQuestionWithAnswers?questionid='); ?>"+targetid;        
        }
       })
       .catch(() => {
        alert("Network connection error");
      });
     },
   }
 });
  app.mount('#mainDiv')
</script>