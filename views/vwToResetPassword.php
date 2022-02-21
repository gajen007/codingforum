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
<div class="card border-primary" style="margin:1%;position:absolute;top:7.5%;width:98%;" id="mainDiv">
	<div class="card-header bg-primary text-white" align="center">மின்னஞ்சல் அல்லது கடவுச்சொல் மறந்துவிட்டதா..?</div>
	<div class="card-body">
		<div class="row" style="margin-top:1%">
			<div class="col-lg-12">
				<ol style="list-style:none;">
					<li style="margin:1%;">
						<p align="justify">
						உங்களின் கடவுச்சொல் மட்டுமே உங்களுக்கு நினைவில் இல்லை என்றால், நீங்கள் தளத்தில் நுழைவதற்கு பாவித்த மின்னஞ்சல் முகவரியை கீழே உள்ள படிவத்தில் சமர்ப்பித்து உங்களின் புதிய தற்காலிக கடவுச்சொல்லை பெறலாம். பின்னர் அது மூலமாக உள்நுழைந்து உங்களின் கடவுச்சொல்லை மாற்றிக்கொள்ளலாம்.</p>
					</li>
					<li style="margin:1%;">
						<p align="justify">
						உங்களின் மின்னஞ்சல் முகவரியும் உங்களுக்கு நினைவில் இல்லை என்றால், நீங்கள் தளத்தின் நிர்வாகிகளுடன் மின்னஞ்சல் மூலம் தொடர்புகொள்ளலாம். அவர்கள் உங்களின் அடையாளத்தை உறுதிப்படுத்திய பின்னர், உங்களின் மின்னஞ்சல் மற்றும் தற்காலிக கடவுச்சொல் என்பவற்றை உங்களுக்கு அனுப்புவார்கள். பின்னர் அது மூலமாக உள்நுழைந்து உங்களின் கடவுச்சொல்லை மாற்றிக்கொள்ளலாம்.</p>
					</li>
					<li style="margin:1%;">
						<p align="justify">சில சந்தர்ப்பங்களில் எம்மால் உங்களுக்கு அனுப்பப்படும் மின்னஞ்சல்கள் உங்களின் மின்னஞ்சல் கணக்கின் Junk அல்லது Spam ஃபோல்டர்களினுள்ளும் செல்ல வாய்ப்பு உண்டு என்பதனால், தயவுசெய்து அந்த ஃபோல்டர்களின் உள்ளேயும் பாருங்கள்.</p>
					</li>
				</ol>
			</div>
		</div>
		<div class="row" style="margin-top:1%">
			<form v-on:submit.prevent="resetPassword">
				<div class="col-lg-12">
					<div class="input-group mb-12">
						<span class="input-group-text" id="basic-addon1">மின்னஞ்சல்</span>
						<input required type="email" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1" v-model="userEmail">
						<button type="submit" class="btn btn-sm bg-primary text-white">அனுப்புக</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	const componentVar = {
		props:{},
		data(){
			return {userEmail:""}
		},
		methods:{
			resetPassword:function(){
				var innerThis=this;
				var toServer=new FormData();
				toServer.append('userEmail',this.userEmail);
				fetch("<?php echo base_url('index.php/general/resetPassword'); ?>",{
					method:'POST',
					body: toServer,
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
					alert(data.message); window.location.href="<?php echo base_url('index.php'); ?>";
				})
				.catch(() => {
					(console.log("Network connection error"));
					alert("Reloading"); window.location.reload();
				});
			}
		}
	}
	const app = Vue.createApp(componentVar)
	app.mount('#mainDiv');
</script>