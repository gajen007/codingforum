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
<div id="mainDiv" style="margin: 1%;position:absolute;top:7.5%">
  <ol style="list-style:none;">
    <mycomponent
    v-for="question in questions"
    v-bind:myelement="question"
    v-bind:key="question.studentid"
    ></mycomponent>
  </ol>
</div>

<script type="text/x-template" id="idForTemplate">
  <div v-on:click="clickToQuestion(myelement.questionid)" style='margin:1%' class="card border-dark">
    <div class="card-body">{{ myelement.questionText }}</div>
    <div class="card-footer bg-light text-dark">
      <button class="btn-sm btn border-secondary" v-bind:style="{fontSize:12+'px',fontStyle:italic,color:myelement.answersCountTextColor}">
      {{myelement.answersCountText}}</button>&nbsp;&nbsp;
      <button class="btn-sm btn border-secondary" v-bind:style="{fontSize:12+'px',fontStyle:italic,color:myelement.followersCountTextColor}">
      {{myelement.followersCountText}}</button>
    </div>
  </div>
</script>

<script type="text/javascript">
  const componentVar = {
    data() {
      return {
        questions: []
      }
    },
    mounted(){
      var innerThis=this;
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          var result=JSON.parse(xhttp.responseText);
          result.forEach(function(item){
            innerThis.questions.push({
              "questionid":item.questionid,
              "questionText":item.questionText,
              "answersCount":item.answersCount,
              "answersCountText":(parseInt(item.answersCount)>0)?((parseInt(item.answersCount)>1)?item.answersCount+" பதில்கள்":"ஒரு பதில்"):"இதுவரை எந்த பதிலும் இல்லை",
              "answersCountTextColor":(parseInt(item.answersCount)>0)?"red":"black",
              "followersCountText":(parseInt(item.followersCount)>0)?((parseInt(item.followersCount)>1)?item.followersCount+" பின்தொடர்பவர்கள்":"ஒருவர் பின்தொடர்கின்றார்"):"எவரும் பின்தொடரவில்லை",
              "followersCountTextColor":(parseInt(item.followersCount)>0)?"red":"black"
            });
          });
        }
      };
      xhttp.open("GET", "<?php echo base_url('index.php/question/getQuestionsForUser'); ?>", true);
      xhttp.send();
    }
  }
  const app = Vue.createApp(componentVar)

  app.component('mycomponent', {
    props: ['myelement','questionid','answersCount','answersCountText','answersCountTextColor','followersCountText','followersCountTextColor'],
    template: "#idForTemplate",
    methods : {
        clickToQuestion : function(questionid) {
            window.location.href="<?php echo base_url('index.php/question/viewSingleQuestionWithAnswers?questionid='); ?>"+questionid;
        },
    },
    mounted(){
      if (parseInt(this.answersCount)>0) {
        console.log("fucked");
      }
      else{

      }
    }
  });

  app.mount('#mainDiv')
</script>