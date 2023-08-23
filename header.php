<header class="contact">
        <nav class="motion" Style="width:100%;">
            <div class='logo'><a href="index.php"><img src="img/logo.png" alt='Cafe'></a></div>
            <div class='g_nav'>
                <div class='menu _click' v-on="click: toIntro" v-transition>はじめに</div>
                <div class='menu _click' v-on="click: toExp" v-transition>体験</div>
                <div class='menu'><a href="contact.php">お問い合わせ</a></div>
            </div>
            <div class='sign'>
                <!-- <div class='signup'>登録</div> -->
                <div class='signin _click' v-on="click: openModal">サインイン</div>
                <div class='sp _click' v-on="click: spMenu=!spMenu"><img src="img/menu.png" alt="スマホメニュー"></div>
                <div class='sp_nav' v-class="sp_nav_motion: position > 50" v-if="spMenu">
                    <div class='sp_signin _click' v-on="click: openModal">サインイン</div>
                    <div class='sp_menu _click' v-on="click: toIntro" v-transition>はじめに</div>
                    <div class='sp_menu _click' v-on="click: toExp" v-transition>体験</div>
                    <div class='sp_menu'><a href="contact.php">お問い合わせ</a></div>
                </div>
            </div>
        </nav>
</header>
