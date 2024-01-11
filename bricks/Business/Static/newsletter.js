(function (){
    const btn = $sel(".sub-news-letter")
    const captchaFieldStyle = (
        `font-weight: 400;
        font-size: 14px;
        line-height: 1.3;
        color: var(--gray-2);
        background-color: var(--black-4);
        width: 100%;
        border: none;
        border-radius: 0;
        outline: none;
        padding: 21px 50px 21px 15px;`
    )

    if(!btn)
        return;

    $on(btn, "click", (e, btn) => {
        e.preventDefault()

        const data = $getForm(btn, true).object

        osModal({
            head: "Verify Submission",
            foot: "",
            body: (
                `<form>
                    <img src="${captchaImgSrc}" class="captcha-holder img-fluid d-table mx-auto mb-4" style="transform: scale(2)" alt="Code" />
                    <input class="form-control newsletter-captcha-field mb-2" placeholder="Enter Code" name="captcha" required style="${captchaFieldStyle}" />
                    <button class="btn btn-outline-secondary add-to-newsletter w-100 btn-lg">Submit</button>
                </form>`
            ),
            then: () => {
                $on($sel(".add-to-newsletter"), "click", (e) => {
                    e.preventDefault()

                    data.captcha = $sel(".newsletter-captcha-field").value.trim()

                    if(data.captcha === "")
                        return osNote("You cannot submit an empty field, please type what you see")

                    $curl($lay.src.serve + "client/subscribe-newsletter",{
                        preload: $preloader,
                        data: data
                    }).finally(() => $preloader('hide'))
                        .then(res => {
                            if(res.code !== 1) {
                                reloadCaptcha()
                                return osNote(res.msg, "warn")
                            }

                            aMsg(`<div class="text-theme fw-500">${res.msg}</div>`, {
                                onClose: () => btn.closest("form").reset()
                            })
                        })
                })
            }
        })
    });
})();