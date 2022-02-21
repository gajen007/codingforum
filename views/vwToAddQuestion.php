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
<div class="card border-dark" style="margin:1%;position:absolute;top:7.5%;width:98%" id="mainDiv">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <textarea v-on:keyup="scan()" style="resize: none;" v-model="questionText" class="form-control" rows="10" placeholder="உங்களின் கேள்வி இயன்றவரை சுருக்கமாகவும் எழுத்துப்பிழைகள் இல்லாமலும் இருக்க வேண்டும்."></textarea>
            </div>
        </div>
    </div>
    <div class="card-footer" align="center"><button v-on:click="addQuestion" type="button" class="btn btn-sm bg-dark text-white">சேர்க்கவும்</button></div>
</div>
<script type="text/javascript">
    const componentVar = {
        props:{},
        data() {
            return { questionText:"" }
        },
        methods:{
            scan:function(){
                fetch("<?php echo base_url('index.php/admin/scan?'); ?>typed="+encodeURI(this.questionText),{
                    method:'GET',
                    mode: 'no-cors',
                    cache: 'no-cache'})
                .then(response => {
                    if (response.status == 200) {
                        return response.json();            
                    }
                    else {
                        alert('Backend Error..!');
                        console.log(response);
                    }
                })
                .then(data => {
                    if (data.result) {
                        alert(data.message);
                    }
                })
                .catch(() => {
                    console.log("Network connection error");
                });
            },
            addQuestion:function(){
              if (this.questionText==="") {
                alert("மன்னிக்கவும்; உங்களின் கேள்வி வெறுமையாக இருக்கக்கூடாது!");
            }
            else{
                let questionData=new FormData();
                questionData.append("userName","<?php echo $username; ?>");
                questionData.append("questionText",this.questionText);
                fetch("<?php echo base_url('index.php/question/addNewQuestion'); ?>",{
                  method:'POST',
                  body: questionData,
                  mode: 'no-cors',
                  cache: 'no-cache'})
                .then(response => {
                  if (response.status == 200) {
                    return response.json();            
                }
                else {
                    alert('Backend Error..!');
                    console.log(response);
                }
            })
                .then(data => {
                  alert(data.message);
                  window.location.reload();
              })
                .catch(() => {
                  (console.log("Network connection error"));
                  alert("Reloading"); window.location.reload();
              });
            }
        }
    },
    mounted(){}
}
const app = Vue.createApp(componentVar)
app.mount('#mainDiv')
</script>