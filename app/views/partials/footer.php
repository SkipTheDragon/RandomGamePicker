<?php if(!isset($_COOKIE['allow_cookies'])) { ?>
<div id="cookies" style="">
    <div class="info">We use only essential cookies so we can make the website work. We don't store any kind of information about you at this moment.</div>
    <div class="buttons">
        <div onclick="setCookie('allow_cookies','1','60')" class="button-accept">Okay</div>
       <!-- <div class="button-manage">Manage</div> -->
    </div>
</div>
<?php } ?>
<script>
    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        hide();
    }
    function hide() {
        document.getElementById('cookies').style.display = 'none';
    }
</script>
</body>

</html>