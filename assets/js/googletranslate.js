const googleTranslateConfig = {
    /* Original language */
    lang: "",

};

$(function() {
    let script = document.createElement("script");
    script.src = `//translate.google.com/translate_a/element.js?cb=TranslateWidgetIsLoaded`;
    document.getElementsByTagName("head")[0].appendChild(script);
});

function TranslateWidgetIsLoaded() {
    TranslateInit(googleTranslateConfig);
}

function TranslateInit(config) {
    var config = {
        langFirstVisit: 'auto' // Hoặc ngôn ngữ mặc định bạn muốn
    };
    if (typeof config !== 'undefined' && typeof config.langFirstVisit !== 'undefined' && config.langFirstVisit && !$.cookie("googtrans")) {
        TranslateCookieHandler("/auto/" + config.langFirstVisit);
    }   

    let code = TranslateGetCode(config);

    TranslateHtmlHandler(code);

    if (code == config.lang) {
        TranslateCookieHandler(null, config.domain);
    }

    if (config.testWord) TranslateMutationObserver(config.testWord, code == config.lang);
    new google.translate.TranslateElement({
        pageLanguage: config.lang,
        multilanguagePage: true,
    });

    $("[data-google-lang]").click(function() {
        TranslateCookieHandler(
            "/auto/" + $(this).attr("data-google-lang"),
            config.domain
        );

        window.location.reload();
    });
}

function TranslateGetCode(config) {
    let lang =
        $.cookie("googtrans") != undefined && $.cookie("googtrans") != "null" ?
        $.cookie("googtrans") :
        config.lang;
    return lang.match(/(?!^\/)[^\/]*$/gm)[0];
}

function TranslateCookieHandler(val, domain) {
    $.cookie("googtrans", val, {
        domain: document.domain,
        path: '/'
    });
    $.cookie("googtrans", val, {
        domain: "." + document.domain,
        path: '/'
    });

    if (domain == "undefined") return;
    $.cookie("googtrans", val, {
        domain: domain,
        path: '/'
    });

    $.cookie("googtrans", val, {
        domain: "." + domain,
        path: '/'
    });
}

function TranslateHtmlHandler(code) {
    $('[data-google-lang="' + code + '"]').addClass("language__img_active");
}


function TranslateMutationObserver(word, isOrigin) {

    if (isOrigin) {
        document.dispatchEvent(new CustomEvent("FinishTranslate"));
    } else {

        let div = document.createElement('div');
        div.id = 'googleTranslateTestWord';
        div.innerHTML = word;
        div.style.display = 'none';
        document.body.prepend(div);

        let observer = new MutationObserver(() => {
            document.dispatchEvent(new CustomEvent("FinishTranslate"));
            observer.disconnect();
        });

        observer.observe(div, {
            childList: false,
            subtree: true,
            characterDataOldValue: true
        });
    }
}