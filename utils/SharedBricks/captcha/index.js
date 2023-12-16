const captchaImgSrc = $lay.src.custom_root + "js/captcha/img.php"

function reloadCaptcha(){
    $loop($sela(".captcha-holder"), captcha => {
        captcha.src = captchaImgSrc + "?t=" + Math.random() + new Date()
    })
}