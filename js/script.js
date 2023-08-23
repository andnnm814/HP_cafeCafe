Vue.component('open-modal', {
	template: `
	<div id="overlay">
		<div id="signin_box" v-on="click: stopEvent">
			<h2>ログイン</h2>
			<form action="" method="post">
			<dl>
				<dd><input type="text" name="name" placeholder="メールアドレス"></dd>
				<dd><input type="password" name="pass" placeholder="パスワード"></dd>
				<dd><button type="submit">送　信</button></dd>
			</dl>
			<dl class="sns">
				<dd><button name="twitter"><img src="img/twitter.png"></button></dd>
				<dd><button name="facebook"><img src="img/fb.png"></button></dd>
				<dd><button name="google"><img src="img/google.png"></button></dd>
				<dd><button name="apple"><img src="img/apple.png"></button></dd>
			</dl>
			</form>
		</div>
	</div>
	`,
	methods :{
		stopEvent: function() {
			event.stopPropagation()
		}
	}
 });

new Vue({
	el: "#app",
	data: {
		position: 0,
		spMenu: false,
		spMenuFlg: false,
		showContent: false,
	},
	methods:{
		toTop: function() {
			$("html,body").animate({scrollTop:0},"slow");
		},
		toIntro: function() {
			$("html,body").animate({scrollTop:$("#cafe_intro").offset().top},"slow");
		},
		toExp: function() {
			$("html,body").animate({scrollTop:$("#cafe_exp").offset().top},"slow");
		},
		closeMenu: function() {
			if(this.spMenuFlg) {
				this.spMenu = false;
				this.spMenuFlg = false;
			} 
			if(this.spMenu) {
				this.spMenuFlg = true;
			}
		},
		openModal: function() {
			this.showContent = true;
			$("#signin_box").animate({marginTop:0,opacity:1},500);
		},
		closeModal: function() {
			$("#signin_box").animate({marginTop:'50vh',opacity:0},500);
			this.showContent = false;
		}
	},
	ready: function() {
		var self = this;
		document.onscroll = function(e) {
			self.position = document.documentElement.scrollTop || document.body.scrollTop;
		}
	}
});


