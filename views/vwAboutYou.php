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
	<div class="card-header bg-dark text-white" align="center">உமது சுயவிபரம்</div>
	<div class="card-body">

		<div class="row" style="margin:1%">
			<div class="col-lg-12" align="center">
				<img v-on:click="changeAvatar(avatarURL)" :src=fetchedAvater class="img-thumbnail" style="width: auto; height: 100%; border-radius: 50%; border:solid 1px;" />
			</div>
		</div>

		<div class="row" style="margin:1%">
			<div class="col-lg-12" align="center">
				<div class="input-group">
					<span class="input-group-text">முதற்பெயர்</span><input type="text" v-model="firstName"  class="form-control">
				</div>
			</div>
		</div>
		<div class="row" style="margin:1%">
			<div class="col-lg-12" align="center">
				<div class="input-group">
					<span class="input-group-text">குடும்பப்பெயர்</span>&nbsp;<input v-model="lastName" type="text" class="form-control"/>
				</div>
			</div>
		</div>
		<div class="row" style="margin:1%">
			<div class="col-lg-12" align="center">
				<div class="input-group">
					<span class="input-group-text">உங்களைப்பற்றி</span>&nbsp;<textarea rows="10" v-model="aboutMe" v-html="aboutMe" type="text" class="form-control"></textarea>
				</div>
			</div>
		</div>
		<div class="row" style="margin:1%">
			<div class="col-lg-12" align="center">
				<div class="input-group">
					<span class="input-group-text">மின்னஞ்சல்</span>&nbsp;<input v-model="userEmail" type="email" class="form-control"/>
				</div>
			</div>
		</div>
		<div class="row" style="margin:1%">
			<div class="col-lg-12" align="center">
				<div class="input-group">
					<span class="input-group-text">கடவுச்சொல்</span>&nbsp;<input v-model="userPassword" type="password" class="form-control"/>
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer bg-default text-white" align="center"><button v-on:click="updateUserProfile" class="btn bg-dark text-white">இற்றைப்படுத்துக</button></div>
</div>
<script type="text/javascript">
	const componentVar = {
		props:{},
		data() {
			return {
				userID:"",
				firstName:"",
				lastName:"",
				userEmail:"",
				userPassword:"",
				aboutMe:"",
				prefix:"<?php echo base_url('images/userAvatars/'); ?>",
				avatarURL:"",
				fetchedAvater:"",
				defaultAvatars:["eight.png", "eleven.png", "fifteen.png", "five.png", "four.png", "fourteen.png", "nine.png", "one.png", "seven.png", "six.png", "sixteen.png", "ten.png", "thirteen.png", "three.png", "twelve.png", "two.png", "zero.png"],
				currentIndex:0
			}
		},
		methods:{
			changeAvatar(currentAvatar){
				var nextIndex=0;
				if (this.currentIndex==this.defaultAvatars.length-1) { nextIndex=0; }
				else{ nextIndex=parseInt(this.currentIndex)+1; }
				this.fetchedAvater=this.prefix+"/"+this.defaultAvatars[nextIndex];
				this.currentIndex=nextIndex;
				this.avatarURL=this.defaultAvatars[nextIndex];
			},
			updateUserProfile(){
				if (this.firstName!==""&&this.lastName!==""&&this.userEmail!==""&&this.userPassword!=="") {
					var profileData=new FormData();
					profileData.append("userid",this.userID);
					profileData.append("firstName",this.firstName);
					profileData.append("lastName",this.lastName);
					profileData.append("userEmail",this.userEmail);
					profileData.append("userPassword",this.userPassword);
					profileData.append("aboutMe",this.aboutMe);
					profileData.append("avatarURL",this.avatarURL);
					fetch("<?php echo base_url('index.php/user/updateUserProfile'); ?>",{
						method:'POST',
						body: profileData,
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
						alert(data.message); window.location.reload();
					})
					.catch(() => {
						(console.log("Network connection error"));
						alert("Reloading"); window.location.reload();
					});
				}
				else{
					alert("படிவத்தை முழுமையாக நிரப்பவும்..!");
				}
			}
		},
		mounted(){
			var innerThis=this;
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var data=JSON.parse(xhttp.responseText);
					innerThis.userID=data['id'];
					innerThis.firstName=data['firstname'];
					innerThis.lastName=data['lastname'];
					innerThis.userEmail=data['username'];
					innerThis.aboutMe=data['aboutme'];
					innerThis.avatarURL=data['avatarURL'];
					innerThis.fetchedAvater=innerThis.prefix+"/"+data['avatarURL'];
					innerThis.currentIndex=innerThis.defaultAvatars.indexOf(data['avatarURL']);
				}
			};
			xhttp.open("GET", "<?php echo base_url('index.php/user/deriveUserProfile?userName='.$username); ?>", true);
			xhttp.send();
		}
	}
	const app = Vue.createApp(componentVar)
	app.mount('#mainDiv')
</script>