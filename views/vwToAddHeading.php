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
            <div class="col-lg-12" style="margin:1%">
                <input required class="form-control" placeholder="சுருக்கமாக..." v-model="headingText" v-on:keyup="scan()"/>
            </div>
        </div>
    </div>
    <div class="card-footer" align="center"><button v-on:click="addHeading" type="button" class="btn btn-sm bg-dark text-white">சேர்க்கவும்</button></div>
</div>
<script type="text/javascript">
    const componentVar = {
        props:{},
        data() {
            return { headingText:"" }
        },
        methods:{
            scan:function(){
                fetch("<?php echo base_url('index.php/admin/scan?'); ?>typed="+encodeURI(this.headingText),{
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
            addHeading:function(){
              if (this.questionText==="") {
                alert("மன்னிக்கவும்; உங்களின் தலைப்பு வெறுமையாக இருக்கக்கூடாது!");
            }
            else{
                let headingData=new FormData();
                headingData.append("userName","<?php echo $username; ?>");
                headingData.append("headingText",this.headingText);
                fetch("<?php echo base_url('index.php/heading/addNewHeading'); ?>",{
                  method:'POST',
                  body: headingData,
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