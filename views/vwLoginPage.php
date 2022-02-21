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
<div id="mainDiv" style="position:absolute;top:7.5%;width:100%;"><div class='row' style="margin:1%;"><div class='col-lg-12'>
  <div class="card border-primary">
    <div class="card-header bg-primary text-white" align="center">Login (அங்கத்தவர்களுக்கு)</div>
    <form v-on:submit.prevent="loginFunction">
      <div class="card-body">
        <div class="row" style="margin:1%">
          <div class="col-lg-6">Email</div>
          <div class="col-lg-6"><input v-model="username" required type="email" class="form-control" placeholder="Email"/></div>
        </div>
        <div class="row" style="margin:1%">
          <div class="col-lg-6">Password</div>
          <div class="col-lg-6"><input v-model="password" required type="password" class="form-control" placeholder="Password"/></div>
        </div>
      </div>
      <div class="card-footer bg-light">
        <div class="row">
          <div class="col-lg-6" align="center"><button tyle="margin:1%" type="submit" class="btn btn-sm bg-primary text-white">Login</button></div>
          <div class="col-lg-6" align="center"><button v-on:click="loginHelp" style="margin:1%" type="button" class="btn btn-sm bg-secondary text-white">Help</button></div>
        </div>
      </div>
    </form>
  </div>
</div></div><div class='row' style="margin:1%"><div class='col-lg-12'>
  <div class="card border-success">
    <div class="card-header bg-success text-white" align="center">SignUp (புதியவர்களுக்கு)</div>
    <form v-on:submit.prevent="signUpFunction">
      <div class="card-body">
        <div class="row" style="margin:1%">
          <div class="col-lg-6">Email</div>
          <div class="col-lg-6"><input v-model="usernameSignUp" required type="email" class="form-control" placeholder="Email"/></div>
        </div>
        <div class="row" style="margin:1%">
          <div class="col-lg-6">Password</div>
          <div class="col-lg-6"><input v-model="passwordSignUp" required type="password" class="form-control" placeholder="Password"/></div>
        </div>
      </div>
      <div class="card-footer bg-light">
        <div class="row">
          <div class="col-lg-6" align="center"><button style="margin:1%" type="submit" class="btn btn-sm bg-success text-white">Sign Up</button></div>
          <div class="col-lg-6" align="center"><button v-on:click="signUpHelp" style="margin:1%" type="button" class="btn btn-sm bg-info text-white">Help</button></div>
        </div>
      </div>
    </form>
  </div>
</div></div></div>
<script type="text/javascript">
  const componentVar = {
    props:{},
    data() {
      return { username:"", password:"", usernameSignUp:"", passwordSignUp:"" }
    },
    methods : {
      loginFunction : function() {
        if (this.username===""||this.password==="") {
          alert("மன்னிக்கவும்; படிவத்தை முழுமையாக நிரப்பவும்..!");
        }
        else{
          let loginData=new FormData();
          loginData.append("username",this.username);
          loginData.append("password",this.password);
          fetch("<?php echo base_url('index.php/login'); ?>",{
            method:'POST',
            body: loginData,
            mode: 'no-cors',
            cache: 'no-cache'})
          .then(response => {
            if (response.status == 200) { return response.json(); } else { alert('Backend Error..!'); console.log(response); } 
          }).then(data => { alert(data.message); window.location.reload(); })
          .catch(() => { (console.log("Network connection error")); alert("Reloading"); window.location.reload(); });
        }
      },
      signUpFunction : function() {
        if (this.usernameSignUp===""||this.passwordSignUp==="") {
          alert("மன்னிக்கவும்; படிவத்தை முழுமையாக நிரப்பவும்..!");
        }
        else{
          let signUpData=new FormData();
          signUpData.append("usernameSignUp",this.usernameSignUp);
          signUpData.append("passwordSignUp",this.passwordSignUp);
          fetch("<?php echo base_url('index.php/signup'); ?>",{
            method:'POST',
            body: signUpData,
            mode: 'no-cors',
            cache: 'no-cache'})
          .then(response => {
            if (response.status == 200) { return response.json(); } else { alert('Backend Error..!'); console.log(response); } 
          }).then(data => { alert(data.message); window.location.reload(); })
          .catch(() => { (console.log("Network connection error")); alert("Reloading"); window.location.reload(); });
        }
      },
      loginHelp : function() {
        window.location.href="<?php echo base_url('index.php/general/toResetPassword'); ?>";
      },
      signUpHelp : function() {
        alert("gajen007@gmail.comஇற்கு மின்னஞ்சல் அனுப்புங்கள்..!");
      },
    }
  }
  const app = Vue.createApp(componentVar)
  app.mount('#mainDiv')
</script>
