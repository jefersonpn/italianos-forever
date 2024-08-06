<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <script>
        if (navigator.userAgent.match(/MSIE|Internet Explorer/i) || navigator.userAgent.match(/Trident\/7\..*?rv:11/i)) {
            var href = document.location.href;
            if (!href.match(/[?&]nowprocket/)) {
                if (href.indexOf("?") == -1) {
                    if (href.indexOf("#") == -1) {
                        document.location.href = href + "?nowprocket=1"
                    } else {
                        document.location.href = href.replace("#", "?nowprocket=1#")
                    }
                } else {
                    if (href.indexOf("#") == -1) {
                        document.location.href = href + "&nowprocket=1"
                    } else {
                        document.location.href = href.replace("#", "&nowprocket=1#")
                    }
                }
            }
        }
    </script>
    <script>
        class RocketLazyLoadScripts {
            constructor() {
                this.triggerEvents = ["keydown", "mousedown", "mousemove", "touchmove", "touchstart", "touchend",
                        "wheel"
                    ], this.userEventHandler = this._triggerListener.bind(this), this.touchStartHandler = this
                    ._onTouchStart.bind(this), this.touchMoveHandler = this._onTouchMove.bind(this), this
                    .touchEndHandler = this._onTouchEnd.bind(this), this.clickHandler = this._onClick.bind(this), this
                    .interceptedClicks = [], window.addEventListener("pageshow", (e => {
                        this.persisted = e.persisted
                    })), window.addEventListener("DOMContentLoaded", (() => {
                        this._preconnect3rdParties()
                    })), this.delayedScripts = {
                        normal: [],
                        async: [],
                        defer: []
                    }, this.allJQueries = []
            }
            _addUserInteractionListener(e) {
                document.hidden ? e._triggerListener() : (this.triggerEvents.forEach((t => window.addEventListener(t, e
                    .userEventHandler, {
                        passive: !0
                    }))), window.addEventListener("touchstart", e.touchStartHandler, {
                    passive: !0
                }), window.addEventListener("mousedown", e.touchStartHandler), document.addEventListener(
                    "visibilitychange", e.userEventHandler))
            }
            _removeUserInteractionListener() {
                this.triggerEvents.forEach((e => window.removeEventListener(e, this.userEventHandler, {
                    passive: !0
                }))), document.removeEventListener("visibilitychange", this.userEventHandler)
            }
            _onTouchStart(e) {
                "HTML" !== e.target.tagName && (window.addEventListener("touchend", this.touchEndHandler), window
                    .addEventListener("mouseup", this.touchEndHandler), window.addEventListener("touchmove", this
                        .touchMoveHandler, {
                            passive: !0
                        }), window.addEventListener("mousemove", this.touchMoveHandler), e.target.addEventListener(
                        "click", this.clickHandler), this._renameDOMAttribute(e.target, "onclick", "rocket-onclick")
                    )
            }
            _onTouchMove(e) {
                window.removeEventListener("touchend", this.touchEndHandler), window.removeEventListener("mouseup", this
                    .touchEndHandler), window.removeEventListener("touchmove", this.touchMoveHandler, {
                    passive: !0
                }), window.removeEventListener("mousemove", this.touchMoveHandler), e.target.removeEventListener(
                    "click", this.clickHandler), this._renameDOMAttribute(e.target, "rocket-onclick", "onclick")
            }
            _onTouchEnd(e) {
                window.removeEventListener("touchend", this.touchEndHandler), window.removeEventListener("mouseup", this
                    .touchEndHandler), window.removeEventListener("touchmove", this.touchMoveHandler, {
                    passive: !0
                }), window.removeEventListener("mousemove", this.touchMoveHandler)
            }
            _onClick(e) {
                e.target.removeEventListener("click", this.clickHandler), this._renameDOMAttribute(e.target,
                        "rocket-onclick", "onclick"), this.interceptedClicks.push(e), e.preventDefault(), e
                    .stopPropagation(), e.stopImmediatePropagation()
            }
            _replayClicks() {
                window.removeEventListener("touchstart", this.touchStartHandler, {
                    passive: !0
                }), window.removeEventListener("mousedown", this.touchStartHandler), this.interceptedClicks.forEach(
                    (e => {
                        e.target.dispatchEvent(new MouseEvent("click", {
                            view: e.view,
                            bubbles: !0,
                            cancelable: !0
                        }))
                    }))
            }
            _renameDOMAttribute(e, t, n) {
                e.hasAttribute && e.hasAttribute(t) && (event.target.setAttribute(n, event.target.getAttribute(t)),
                    event.target.removeAttribute(t))
            }
            _triggerListener() {
                this._removeUserInteractionListener(this), "loading" === document.readyState ? document
                    .addEventListener("DOMContentLoaded", this._loadEverythingNow.bind(this)) : this
                    ._loadEverythingNow()
            }
            _preconnect3rdParties() {
                let e = [];
                document.querySelectorAll("script[type=rocketlazyloadscript]").forEach((t => {
                        if (t.hasAttribute("src")) {
                            const n = new URL(t.src).origin;
                            n !== location.origin && e.push({
                                src: n,
                                crossOrigin: t.crossOrigin || "module" === t.getAttribute(
                                    "data-rocket-type")
                            })
                        }
                    })), e = [...new Map(e.map((e => [JSON.stringify(e), e]))).values()], this
                    ._batchInjectResourceHints(e, "preconnect")
            }
            async _loadEverythingNow() {
                this.lastBreath = Date.now(), this._delayEventListeners(), this._delayJQueryReady(this), this
                    ._handleDocumentWrite(), this._registerAllDelayedScripts(), this._preloadAllScripts(), await this
                    ._loadScriptsFromList(this.delayedScripts.normal), await this._loadScriptsFromList(this
                        .delayedScripts.defer), await this._loadScriptsFromList(this.delayedScripts.async);
                try {
                    await this._triggerDOMContentLoaded(), await this._triggerWindowLoad()
                } catch (e) {}
                window.dispatchEvent(new Event("rocket-allScriptsLoaded")), this._replayClicks()
            }
            _registerAllDelayedScripts() {
                document.querySelectorAll("script[type=rocketlazyloadscript]").forEach((e => {
                    e.hasAttribute("src") ? e.hasAttribute("async") && !1 !== e.async ? this.delayedScripts
                        .async.push(e) : e.hasAttribute("defer") && !1 !== e.defer || "module" === e
                        .getAttribute("data-rocket-type") ? this.delayedScripts.defer.push(e) : this
                        .delayedScripts.normal.push(e) : this.delayedScripts.normal.push(e)
                }))
            }
            async _transformScript(e) {
                return await this._littleBreath(), new Promise((t => {
                    const n = document.createElement("script");
                    [...e.attributes].forEach((e => {
                        let t = e.nodeName;
                        "type" !== t && ("data-rocket-type" === t && (t = "type"), n
                            .setAttribute(t, e.nodeValue))
                    })), e.hasAttribute("src") ? (n.addEventListener("load", t), n.addEventListener(
                        "error", t)) : (n.text = e.text, t());
                    try {
                        e.parentNode.replaceChild(n, e)
                    } catch (e) {
                        t()
                    }
                }))
            }
            async _loadScriptsFromList(e) {
                const t = e.shift();
                return t ? (await this._transformScript(t), this._loadScriptsFromList(e)) : Promise.resolve()
            }
            _preloadAllScripts() {
                this._batchInjectResourceHints([...this.delayedScripts.normal, ...this.delayedScripts.defer, ...this
                    .delayedScripts.async
                ], "preload")
            }
            _batchInjectResourceHints(e, t) {
                var n = document.createDocumentFragment();
                e.forEach((e => {
                    if (e.src) {
                        const i = document.createElement("link");
                        i.href = e.src, i.rel = t, "preconnect" !== t && (i.as = "script"), e
                            .getAttribute && "module" === e.getAttribute("data-rocket-type") && (i
                                .crossOrigin = !0), e.crossOrigin && (i.crossOrigin = e.crossOrigin), n
                            .appendChild(i)
                    }
                })), document.head.appendChild(n)
            }
            _delayEventListeners() {
                let e = {};

                function t(t, n) {
                    ! function(t) {
                        function n(n) {
                            return e[t].eventsToRewrite.indexOf(n) >= 0 ? "rocket-" + n : n
                        }
                        e[t] || (e[t] = {
                            originalFunctions: {
                                add: t.addEventListener,
                                remove: t.removeEventListener
                            },
                            eventsToRewrite: []
                        }, t.addEventListener = function() {
                            arguments[0] = n(arguments[0]), e[t].originalFunctions.add.apply(t, arguments)
                        }, t.removeEventListener = function() {
                            arguments[0] = n(arguments[0]), e[t].originalFunctions.remove.apply(t, arguments)
                        })
                    }(t), e[t].eventsToRewrite.push(n)
                }

                function n(e, t) {
                    let n = e[t];
                    Object.defineProperty(e, t, {
                        get: () => n || function() {},
                        set(i) {
                            e["rocket" + t] = n = i
                        }
                    })
                }
                t(document, "DOMContentLoaded"), t(window, "DOMContentLoaded"), t(window, "load"), t(window,
                    "pageshow"), t(document, "readystatechange"), n(document, "onreadystatechange"), n(window,
                    "onload"), n(window, "onpageshow")
            }
            _delayJQueryReady(e) {
                let t = window.jQuery;
                Object.defineProperty(window, "jQuery", {
                    get: () => t,
                    set(n) {
                        if (n && n.fn && !e.allJQueries.includes(n)) {
                            n.fn.ready = n.fn.init.prototype.ready = function(t) {
                                e.domReadyFired ? t.bind(document)(n) : document.addEventListener(
                                    "rocket-DOMContentLoaded", (() => t.bind(document)(n)))
                            };
                            const t = n.fn.on;
                            n.fn.on = n.fn.init.prototype.on = function() {
                                if (this[0] === window) {
                                    function e(e) {
                                        return e.split(" ").map((e => "load" === e || 0 === e.indexOf(
                                            "load.") ? "rocket-jquery-load" : e)).join(" ")
                                    }
                                    "string" == typeof arguments[0] || arguments[0] instanceof String ?
                                        arguments[0] = e(arguments[0]) : "object" == typeof arguments[
                                        0] && Object.keys(arguments[0]).forEach((t => {
                                            delete Object.assign(arguments[0], {
                                                [e(t)]: arguments[0][t]
                                            })[t]
                                        }))
                                }
                                return t.apply(this, arguments), this
                            }, e.allJQueries.push(n)
                        }
                        t = n
                    }
                })
            }
            async _triggerDOMContentLoaded() {
                this.domReadyFired = !0, await this._littleBreath(), document.dispatchEvent(new Event(
                        "rocket-DOMContentLoaded")), await this._littleBreath(), window.dispatchEvent(new Event(
                        "rocket-DOMContentLoaded")), await this._littleBreath(), document.dispatchEvent(new Event(
                        "rocket-readystatechange")), await this._littleBreath(), document.rocketonreadystatechange &&
                    document.rocketonreadystatechange()
            }
            async _triggerWindowLoad() {
                await this._littleBreath(), window.dispatchEvent(new Event("rocket-load")), await this._littleBreath(),
                    window.rocketonload && window.rocketonload(), await this._littleBreath(), this.allJQueries.forEach((
                        e => e(window).trigger("rocket-jquery-load"))), await this._littleBreath();
                const e = new Event("rocket-pageshow");
                e.persisted = this.persisted, window.dispatchEvent(e), await this._littleBreath(), window
                    .rocketonpageshow && window.rocketonpageshow({
                        persisted: this.persisted
                    })
            }
            _handleDocumentWrite() {
                const e = new Map;
                document.write = document.writeln = function(t) {
                    const n = document.currentScript,
                        i = document.createRange(),
                        r = n.parentElement;
                    let o = e.get(n);
                    void 0 === o && (o = n.nextSibling, e.set(n, o));
                    const s = document.createDocumentFragment();
                    i.setStart(s, 0), s.appendChild(i.createContextualFragment(t)), r.insertBefore(s, o)
                }
            }
            async _littleBreath() {
                Date.now() - this.lastBreath > 45 && (await this._requestAnimFrame(), this.lastBreath = Date.now())
            }
            async _requestAnimFrame() {
                return document.hidden ? new Promise((e => setTimeout(e))) : new Promise((e => requestAnimationFrame(
                    e)))
            }
            static run() {
                const e = new RocketLazyLoadScripts;
                e._addUserInteractionListener(e)
            }
        }
        RocketLazyLoadScripts.run();
    </script>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

    <script data-no-defer="1" data-ezscrex="false" data-cfasync="false" data-pagespeed-no-defer=""
        data-cookieconsent="ignore">
        const ctPublicFunctions = {
            "_ajax_nonce": "050d0a0f1a",
            "_rest_nonce": "9151c6eec9",
            "_ajax_url": "\/wp-admin\/admin-ajax.php",
            "_rest_url": "https:\/\/italianosforever.it\/wp-json\/",
            "data__cookies_type": "none",
            "data__ajax_type": "admin_ajax",
            "text__wait_for_decoding": "Decoding the contact data, let us a few seconds to finish. Anti-Spam by CleanTalk",
            "cookiePrefix": ""
        }
    </script>

    <script data-no-defer="1" data-ezscrex="false" data-cfasync="false" data-pagespeed-no-defer=""
        data-cookieconsent="ignore">
        const ctPublic = {
            "_ajax_nonce": "050d0a0f1a",
            "settings__forms__check_internal": "0",
            "settings__forms__check_external": "0",
            "settings__forms__search_test": "1",
            "blog_home": "https:\/\/italianosforever.it\/",
            "pixel__setting": "3",
            "pixel__enabled": true,
            "pixel__url": "https:\/\/moderate4-v4.cleantalk.org\/pixel\/21eb451fff8fd2db70ca0d84a5447726.gif",
            "data__email_check_before_post": "1",
            "data__cookies_type": "none",
            "data__key_is_ok": true,
            "data__visible_fields_required": true,
            "data__to_local_storage": {
                "apbct_cookies_test": "%7B%22cookies_names%22%3A%5B%22apbct_timestamp%22%2C%22apbct_site_landing_ts%22%5D%2C%22check_value%22%3A%2226475ebc5a0399b78c845dc75144b97d%22%7D",
                "ct_sfw_ip_wl": "7500e685a11690c05b35d2268e068d7c",
                "apbct_timestamp": "1722749060",
                "apbct_urls": "{\"italianosforever.it\/welcome-to-cloudways\/\":[1722590958,1722628006,1722664751,1722703651,1722749054],\"italianosforever.it\/category\/uncategorized\/\":[1722590961,1722628008,1722664753,1722703653,1722749056],\"italianosforever.it\/author\/rafael\/\":[1722590964,1722628010,1722664755,1722703655,1722749058],\"italianosforever.it\/author\/gustavosinnapse-com\/\":[1722590967,1722628012,1722664757,1722703657,1722749060],\"italianosforever.it\/\":[1722466537,1722505994,1722553430,1722628015,1722703659]}"
            },
            "wl_brandname": "Anti-Spam by CleanTalk",
            "wl_brandname_short": "CleanTalk",
            "ct_checkjs_key": "8a906cc221f122e855a8a6828cc7b25c385df57af1980f14b77d06872ead5509"
        }
    </script>

    <!-- This site is optimized with the Yoast SEO plugin v19.11 - https://yoast.com/wordpress/plugins/seo/ -->
    <title>Reconheça a sua cidadania italiana - Italianos Forever</title>
    <link rel="preload" as="font"
        href="https://italianosforever.it/wp-content/plugins/elementor/assets/lib/eicons/fonts/eicons.woff2?5.16.0"
        crossorigin="">
    <link rel="preload" as="font" href="https://italianosforever.it/wp-content/uploads/2022/12/Agne-Regular.ttf"
        crossorigin="">
    <link rel="preload" as="font"
        href="https://fonts.gstatic.com/s/notosans/v28/o-0NIpQlx3QUlC5A4PNjKhVVZNyB.woff2" crossorigin="">
    <link rel="preload" as="font"
        href="https://fonts.gstatic.com/s/notosans/v28/o-0NIpQlx3QUlC5A4PNjThZVZNyB.woff2" crossorigin="">
    <link rel="preload" as="font" href="https://fonts.gstatic.com/s/notosans/v28/o-0IIpQlx3QUlC5A4PNr5TRA.woff2"
        crossorigin="">
    <link rel="preload" as="font"
        href="https://fonts.gstatic.com/s/notosans/v28/o-0NIpQlx3QUlC5A4PNjFhdVZNyB.woff2" crossorigin="">
    <link rel="preload" as="font"
        href="https://fonts.gstatic.com/s/notosans/v28/o-0NIpQlx3QUlC5A4PNjOhBVZNyB.woff2" crossorigin="">
    <link rel="preload" as="font"
        href="https://fonts.gstatic.com/s/notosans/v28/o-0NIpQlx3QUlC5A4PNjXhFVZNyB.woff2" crossorigin="">
    <link rel="preload" as="font"
        href="https://fonts.gstatic.com/s/notosans/v28/o-0NIpQlx3QUlC5A4PNjQhJVZNyB.woff2" crossorigin="">
    <link rel="preload" as="font"
        href="https://italianosforever.it/wp-content/plugins/elementor/assets/lib/font-awesome/webfonts/fa-solid-900.woff2"
        crossorigin="">
    <link rel="preload" as="font"
        href="https://italianosforever.it/wp-content/plugins/elementor/assets/lib/font-awesome/webfonts/fa-brands-400.woff2"
        crossorigin="">
    <link rel="preload" as="font"
        href="https://italianosforever.it/wp-content/plugins/elementor/assets/lib/font-awesome/webfonts/fa-regular-400.woff2"
        crossorigin="">
    <style id="wpr-usedcss">
        :where(.wp-block-button__link) {
            border-radius: 9999px;
            box-shadow: none;
            padding: calc(.667em + 2px) calc(1.333em + 2px);
            text-decoration: none
        }

        :where(.wp-block-calendar table:not(.has-background) th) {
            background: #ddd
        }

        :where(.wp-block-columns.has-background) {
            padding: 1.25em 2.375em
        }

        :where(.wp-block-post-comments input[type=submit]) {
            border: none
        }

        :where(.wp-block-file__button) {
            border-radius: 2em;
            padding: .5em 1em
        }

        :where(.wp-block-file__button):is(a):active,
        :where(.wp-block-file__button):is(a):focus,
        :where(.wp-block-file__button):is(a):hover,
        :where(.wp-block-file__button):is(a):visited {
            box-shadow: none;
            color: #fff;
            opacity: .85;
            text-decoration: none
        }

        ul {
            box-sizing: border-box
        }

        :where(.wp-block-navigation.has-background .wp-block-navigation-item a:not(.wp-element-button)),
        :where(.wp-block-navigation.has-background .wp-block-navigation-submenu a:not(.wp-element-button)) {
            padding: .5em 1em
        }

        :where(.wp-block-navigation .wp-block-navigation__submenu-container .wp-block-navigation-item a:not(.wp-element-button)),
        :where(.wp-block-navigation .wp-block-navigation__submenu-container .wp-block-navigation-submenu a:not(.wp-element-button)),
        :where(.wp-block-navigation .wp-block-navigation__submenu-container .wp-block-navigation-submenu button.wp-block-navigation-item__content),
        :where(.wp-block-navigation .wp-block-navigation__submenu-container .wp-block-pages-list__item button.wp-block-navigation-item__content) {
            padding: .5em 1em
        }

        :where(p.has-text-color:not(.has-link-color)) a {
            color: inherit
        }

        :where(.wp-block-search__button) {
            border: 1px solid #ccc;
            padding: .375em .625em
        }

        :where(.wp-block-search__button-inside .wp-block-search__inside-wrapper) {
            border: 1px solid #949494;
            padding: 4px
        }

        :where(.wp-block-search__button-inside .wp-block-search__inside-wrapper) .wp-block-search__input {
            border: none;
            border-radius: 0;
            padding: 0 0 0 .25em
        }

        :where(.wp-block-search__button-inside .wp-block-search__inside-wrapper) .wp-block-search__input:focus {
            outline: 0
        }

        :where(.wp-block-search__button-inside .wp-block-search__inside-wrapper) :where(.wp-block-search__button) {
            padding: .125em .5em
        }

        :where(pre.wp-block-verse) {
            font-family: inherit
        }

        :root {
            --wp--preset--font-size--normal: 16px;
            --wp--preset--font-size--huge: 42px
        }

        .screen-reader-text {
            clip: rect(1px, 1px, 1px, 1px);
            word-wrap: normal !important;
            border: 0;
            -webkit-clip-path: inset(50%);
            clip-path: inset(50%);
            height: 1px;
            margin: -1px;
            overflow: hidden;
            padding: 0;
            position: absolute;
            width: 1px
        }

        .screen-reader-text:focus {
            clip: auto !important;
            background-color: #ddd;
            -webkit-clip-path: none;
            clip-path: none;
            color: #444;
            display: block;
            font-size: 1em;
            height: auto;
            left: 5px;
            line-height: normal;
            padding: 15px 23px 14px;
            text-decoration: none;
            top: 5px;
            width: auto;
            z-index: 100000
        }

        html :where(.has-border-color) {
            border-style: solid
        }

        html :where([style*=border-top-color]) {
            border-top-style: solid
        }

        html :where([style*=border-right-color]) {
            border-right-style: solid
        }

        html :where([style*=border-bottom-color]) {
            border-bottom-style: solid
        }

        html :where([style*=border-left-color]) {
            border-left-style: solid
        }

        html :where([style*=border-width]) {
            border-style: solid
        }

        html :where([style*=border-top-width]) {
            border-top-style: solid
        }

        html :where([style*=border-right-width]) {
            border-right-style: solid
        }

        html :where([style*=border-bottom-width]) {
            border-bottom-style: solid
        }

        html :where([style*=border-left-width]) {
            border-left-style: solid
        }

        html :where(img[class*=wp-image-]) {
            height: auto;
            max-width: 100%
        }

        html :where(.is-position-sticky) {
            --wp-admin--admin-bar--position-offset: var(--wp-admin--admin-bar--height, 0px)
        }

        @media screen and (max-width:600px) {
            html :where(.is-position-sticky) {
                --wp-admin--admin-bar--position-offset: 0px
            }
        }

        body {
            --wp--preset--color--black: #000000;
            --wp--preset--color--cyan-bluish-gray: #abb8c3;
            --wp--preset--color--white: #ffffff;
            --wp--preset--color--pale-pink: #f78da7;
            --wp--preset--color--vivid-red: #cf2e2e;
            --wp--preset--color--luminous-vivid-orange: #ff6900;
            --wp--preset--color--luminous-vivid-amber: #fcb900;
            --wp--preset--color--light-green-cyan: #7bdcb5;
            --wp--preset--color--vivid-green-cyan: #00d084;
            --wp--preset--color--pale-cyan-blue: #8ed1fc;
            --wp--preset--color--vivid-cyan-blue: #0693e3;
            --wp--preset--color--vivid-purple: #9b51e0;
            --wp--preset--gradient--vivid-cyan-blue-to-vivid-purple: linear-gradient(135deg, rgba(6, 147, 227, 1) 0%, rgb(155, 81, 224) 100%);
            --wp--preset--gradient--light-green-cyan-to-vivid-green-cyan: linear-gradient(135deg, rgb(122, 220, 180) 0%, rgb(0, 208, 130) 100%);
            --wp--preset--gradient--luminous-vivid-amber-to-luminous-vivid-orange: linear-gradient(135deg, rgba(252, 185, 0, 1) 0%, rgba(255, 105, 0, 1) 100%);
            --wp--preset--gradient--luminous-vivid-orange-to-vivid-red: linear-gradient(135deg, rgba(255, 105, 0, 1) 0%, rgb(207, 46, 46) 100%);
            --wp--preset--gradient--very-light-gray-to-cyan-bluish-gray: linear-gradient(135deg, rgb(238, 238, 238) 0%, rgb(169, 184, 195) 100%);
            --wp--preset--gradient--cool-to-warm-spectrum: linear-gradient(135deg, rgb(74, 234, 220) 0%, rgb(151, 120, 209) 20%, rgb(207, 42, 186) 40%, rgb(238, 44, 130) 60%, rgb(251, 105, 98) 80%, rgb(254, 248, 76) 100%);
            --wp--preset--gradient--blush-light-purple: linear-gradient(135deg, rgb(255, 206, 236) 0%, rgb(152, 150, 240) 100%);
            --wp--preset--gradient--blush-bordeaux: linear-gradient(135deg, rgb(254, 205, 165) 0%, rgb(254, 45, 45) 50%, rgb(107, 0, 62) 100%);
            --wp--preset--gradient--luminous-dusk: linear-gradient(135deg, rgb(255, 203, 112) 0%, rgb(199, 81, 192) 50%, rgb(65, 88, 208) 100%);
            --wp--preset--gradient--pale-ocean: linear-gradient(135deg, rgb(255, 245, 203) 0%, rgb(182, 227, 212) 50%, rgb(51, 167, 181) 100%);
            --wp--preset--gradient--electric-grass: linear-gradient(135deg, rgb(202, 248, 128) 0%, rgb(113, 206, 126) 100%);
            --wp--preset--gradient--midnight: linear-gradient(135deg, rgb(2, 3, 129) 0%, rgb(40, 116, 252) 100%);
            --wp--preset--duotone--dark-grayscale: url('index_1.html#wp-duotone-dark-grayscale');
            --wp--preset--duotone--grayscale: url('index_1.html#wp-duotone-grayscale');
            --wp--preset--duotone--purple-yellow: url('index_1.html#wp-duotone-purple-yellow');
            --wp--preset--duotone--blue-red: url('index_1.html#wp-duotone-blue-red');
            --wp--preset--duotone--midnight: url('index_1.html#wp-duotone-midnight');
            --wp--preset--duotone--magenta-yellow: url('index_1.html#wp-duotone-magenta-yellow');
            --wp--preset--duotone--purple-green: url('index_1.html#wp-duotone-purple-green');
            --wp--preset--duotone--blue-orange: url('index_1.html#wp-duotone-blue-orange');
            --wp--preset--font-size--small: 13px;
            --wp--preset--font-size--medium: 20px;
            --wp--preset--font-size--large: 36px;
            --wp--preset--font-size--x-large: 42px;
            --wp--preset--spacing--20: 0.44rem;
            --wp--preset--spacing--30: 0.67rem;
            --wp--preset--spacing--40: 1rem;
            --wp--preset--spacing--50: 1.5rem;
            --wp--preset--spacing--60: 2.25rem;
            --wp--preset--spacing--70: 3.38rem;
            --wp--preset--spacing--80: 5.06rem;
            --wp--preset--shadow--natural: 6px 6px 9px rgba(0, 0, 0, .2);
            --wp--preset--shadow--deep: 12px 12px 50px rgba(0, 0, 0, .4);
            --wp--preset--shadow--sharp: 6px 6px 0px rgba(0, 0, 0, .2);
            --wp--preset--shadow--outlined: 6px 6px 0px -3px rgba(255, 255, 255, 1), 6px 6px rgba(0, 0, 0, 1);
            --wp--preset--shadow--crisp: 6px 6px 0px rgba(0, 0, 0, 1)
        }

        :where(.is-layout-flex) {
            gap: .5em
        }

        :where(.wp-block-columns.is-layout-flex) {
            gap: 2em
        }

        .rs-p-wp-fix {
            display: none !important;
            margin: 0 !important;
            height: 0 !important
        }

        .wp-block-themepunch-revslider {
            position: relative
        }

        rs-modal-cover {
            width: 100%;
            height: 100%;
            z-index: 0;
            background: 0 0;
            position: absolute;
            top: 0;
            left: 0;
            cursor: pointer;
            pointer-events: auto
        }

        body>rs-modal-cover {
            position: fixed;
            z-index: 9999995 !important
        }

        rs-sbg-px {
            pointer-events: none
        }

        .rs-forceuntouchable,
        .rs-forceuntouchable * {
            pointer-events: none !important
        }

        .rs-forcehidden *{visibility:hidden!important}.rs_splitted_lines{display:block;white-space:nowrap!important}.rs-go-fullscreen{position:fixed!important;width:100%!important;height:100%!important;top:0!important;left:0!important;z-index:9999999!important;background:#fff}.rtl{direction:rtl}[class*=" revicon-"]:before,
        [class^=revicon-]:before {
            font-family: revicons;
            font-style: normal;
            font-weight: 400;
            speak: none;
            display: inline-block;
            text-decoration: inherit;
            width: 1em;
            margin-right: .2em;
            text-align: center;
            font-variant: normal;
            text-transform: none;
            line-height: 1em;
            margin-left: .2em
        }

        .revicon-right-dir:before {
            content: '\e818'
        }

        rs-module-wrap {
            visibility: hidden
        }

        rs-module-wrap,
        rs-module-wrap * {
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent
        }

        rs-module-wrap {
            position: relative;
            z-index: 1;
            width: 100%;
            display: block
        }

        .rs-fixedscrollon rs-module-wrap {
            position: fixed !important;
            top: 0 !important;
            z-index: 1000;
            left: 0 !important
        }

        .rs-stickyscrollon rs-module-wrap {
            position: sticky !important;
            top: 0;
            z-index: 1000
        }

        .rs-stickyscrollon {
            overflow: visible !important
        }

        rs-sbg,
        rs-sbg-effectwrap {
            display: block;
            pointer-events: none
        }

        rs-sbg-effectwrap {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%
        }

        rs-sbg-px,
        rs-sbg-wrap {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 0;
            width: 100%;
            height: 100%;
            display: block
        }

        a.rs-layer,
        a.rs-layer:-webkit-any-link {
            text-decoration: none
        }

        .rs-forceoverflow,
        .rs-forceoverflow rs-module-wrap,
        .rs-forceoverflow rs-slide,
        .rs-forceoverflow rs-slides {
            overflow: visible !important
        }

        rs-slide,
        rs-slide:before,
        rs-slides {
            position: absolute;
            text-indent: 0;
            top: 0;
            left: 0
        }

        rs-slide,
        rs-slide:before {
            display: block;
            visibility: hidden
        }

        .rs-layer .rs-toggled-content {
            display: none
        }

        .rs-tc-active.rs-layer>.rs-toggled-content {
            display: block
        }

        .rs-layer-video {
            overflow: hidden
        }

        .rs_html5vidbasicstyles {
            position: relative;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden
        }

        rs-layer-wrap,
        rs-module-wrap {
            -moz-user-select: none;
            -khtml-user-select: none;
            -webkit-user-select: none;
            -o-user-select: none
        }

        .rs-svg svg {
            width: 100%;
            height: 100%;
            position: relative;
            vertical-align: top
        }

        .rs-layer :not(.rs-wtbindex),
        .rs-layer:not(.rs-wtbindex),
        rs-layer:not(.rs-wtbindex) {
            outline: 0 !important
        }

        rs-carousel-space {
            clear: both;
            display: block;
            width: 100%;
            height: 0;
            position: relative
        }

        rs-px-mask {
            overflow: hidden;
            display: block;
            width: 100%;
            height: 100%;
            position: relative
        }

        .tp-blockmask_in,
        .tp-blockmask_out {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #fff;
            z-index: 1000;
            transform: scaleX(0) scaleY(0)
        }

        rs-zone {
            position: absolute;
            width: 100%;
            left: 0;
            box-sizing: border-box;
            min-height: 50px;
            font-size: 0px;
            pointer-events: none
        }

        rs-row-wrap {
            display: block;
            visibility: hidden
        }

        rs-layer-wrap,
        rs-parallax-wrap {
            display: block
        }

        rs-layer-wrap {
            transform-style: flat
        }

        .safarifix rs-layer-wrap {
            perspective: 1000000
        }

        @-moz-document url-prefix() {
            rs-layer-wrap {
                perspective: none
            }
        }

        rs-fullwidth-wrap {
            position: relative;
            width: 100%;
            height: auto;
            display: block;
            overflow: visible;
            max-width: none !important
        }

        .rev_row_zone_middle {
            top: 50%;
            transform: perspective(1px) translateY(-50%)
        }

        rs-column-wrap .rs-parallax-wrap {
            vertical-align: top
        }

        .rs-layer img,
        rs-layer img {
            vertical-align: top
        }

        rs-column-wrap {
            display: table-cell;
            position: relative;
            vertical-align: top;
            height: auto;
            box-sizing: border-box;
            font-size: 0px
        }

        rs-column-bg {
            position: absolute;
            z-index: 0;
            box-sizing: border-box;
            width: 100%;
            height: 100%
        }

        .rs-pelock * {
            pointer-events: none !important
        }

        .rev_break_columns {
            display: block !important
        }

        .rev_break_columns rs-column-wrap.rs-parallax-wrap {
            display: block !important;
            width: 100% !important
        }

        .rev_break_columns rs-column-wrap.rs-parallax-wrap.rs-layer-hidden,
        .rs-layer-audio.rs-layer-hidden,
        .rs-layer.rs-layer-hidden,
        .rs-parallax-wrap.rs-layer-hidden,
        .tp-forcenotvisible,
        rs-column-wrap.rs-layer-hidden,
        rs-row-wrap.rs-layer-hidden {
            visibility: hidden !important;
            display: none !important
        }

        .rs-layer.rs-nointeraction,
        rs-layer.rs-nointeraction {
            pointer-events: none !important
        }

        rs-static-layers {
            position: absolute;
            z-index: 101;
            top: 0;
            left: 0;
            display: block;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden
        }

        .rs-layer rs-fcr {
            width: 0;
            height: 0;
            border-left: 40px solid transparent;
            border-right: 0px solid transparent;
            border-top: 40px solid #00a8ff;
            position: absolute;
            right: 100%;
            top: 0
        }

        .rs-layer rs-fcrt {
            width: 0;
            height: 0;
            border-left: 40px solid transparent;
            border-right: 0px solid transparent;
            border-bottom: 40px solid #00a8ff;
            position: absolute;
            right: 100%;
            top: 0
        }

        .rs-layer rs-bcr {
            width: 0;
            height: 0;
            border-left: 0 solid transparent;
            border-right: 40px solid transparent;
            border-bottom: 40px solid #00a8ff;
            position: absolute;
            left: 100%;
            top: 0
        }

        rs-bgvideo {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            display: block
        }

        .rs-layer.rs-fsv {
            top: 0;
            left: 0;
            position: absolute;
            width: 100%;
            height: 100%
        }

        .rs-layer.rs-fsv audio,
        .rs-layer.rs-fsv iframe,
        .rs-layer.rs-fsv iframe audio,
        .rs-layer.rs-fsv iframe video,
        .rs-layer.rs-fsv video {
            width: 100%;
            height: 100%
        }

        .rs-fsv video {
            background: #000
        }

        .fullcoveredvideo rs-poster {
            background-position: center center;
            background-size: cover;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0
        }

        .tp-video-play-button {
            background: #000;
            background: rgba(0, 0, 0, .3);
            border-radius: 5px;
            position: absolute;
            top: 50%;
            left: 50%;
            color: #fff;
            z-index: 3;
            margin-top: -25px;
            margin-left: -25px;
            line-height: 50px !important;
            text-align: center;
            cursor: pointer;
            width: 50px;
            height: 50px;
            box-sizing: border-box;
            display: inline-block;
            vertical-align: top;
            z-index: 4;
            opacity: 0;
            transition: opacity .3s ease-out !important
        }

        .rs-ISM .tp-video-play-button {
            opacity: 1;
            transition: none !important;
            z-index: 6
        }

        .rs-audio .tp-video-play-button {
            display: none !important
        }

        .tp-video-play-button i {
            width: 50px;
            height: 50px;
            display: inline-block;
            text-align: center !important;
            vertical-align: top;
            line-height: 50px !important;
            font-size: 30px !important
        }

        .rs-layer:hover .tp-video-play-button {
            opacity: 1;
            display: block;
            z-index: 6
        }

        .rs-layer .tp-revstop {
            display: none;
            width: 15px;
            border-right: 5px solid #fff !important;
            border-left: 5px solid #fff !important;
            transform: translateX(50%) translateY(50%);
            height: 20px;
            margin-left: 11px !important;
            margin-top: 5px !important
        }

        .videoisplaying .revicon-right-dir {
            display: none
        }

        .videoisplaying .tp-revstop {
            display: block
        }

        .videoisplaying .tp-video-play-button {
            display: none
        }

        .fullcoveredvideo .tp-video-play-button {
            display: none !important
        }

        .rs-fsv .rs-fsv audio {
            object-fit: contain !important
        }

        .rs-fsv .rs-fsv video {
            object-fit: contain !important
        }

        @supports not (-ms-high-contrast:none) {
            .rs-fsv .fullcoveredvideo audio {
                object-fit: cover !important
            }

            .rs-fsv .fullcoveredvideo video {
                object-fit: cover !important
            }
        }

        .rs-fullvideo-cover {
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            position: absolute;
            background: 0 0;
            z-index: 5
        }

        rs-bgvideo audio::-webkit-media-controls,
        rs-bgvideo video::-webkit-media-controls,
        rs-bgvideo video::-webkit-media-controls-start-playback-button {
            display: none !important
        }

        rs-dotted {
            background-repeat: repeat;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 3;
            display: block;
            pointer-events: none
        }

        rs-sbg-wrap rs-dotted {
            z-index: 31
        }

        rs-progress {
            visibility: hidden;
            position: absolute;
            z-index: 200;
            width: 100%;
            height: 100%
        }

        .rs-progress-bar,
        rs-progress-bar {
            display: block;
            z-index: 20;
            box-sizing: border-box;
            background-clip: content-box;
            position: absolute;
            vertical-align: top;
            line-height: 0;
            width: 100%;
            height: 100%
        }

        rs-progress-bgs {
            display: block;
            z-index: 15;
            box-sizing: border-box;
            width: 100%;
            position: absolute;
            height: 100%;
            top: 0;
            left: 0
        }

        rs-progress-bg {
            display: block;
            background-clip: content-box;
            position: absolute;
            width: 100%;
            height: 100%
        }

        rs-progress-gap {
            display: block;
            background-clip: content-box;
            position: absolute;
            width: 100%;
            height: 100%
        }

        .rs-layer img {
            background: 0 0
        }

        .rs-layer.slidelink {
            cursor: pointer;
            width: 100%;
            height: 100%
        }

        .rs-layer.slidelink a {
            width: 100%;
            height: 100%;
            display: block
        }

        .rs-layer.slidelink a div {
            width: 3000px;
            height: 1500px;
            background: url(images/coloredbg.png)
        }

        .rs-layer.slidelink a span {
            background: url(images/coloredbg.png);
            width: 100%;
            height: 100%;
            display: block
        }

        rs-layer iframe {
            visibility: hidden
        }

        rs-layer.rs-ii-o iframe {
            visibility: visible
        }

        rs-layer input[type=email],
        rs-layer input[type=number],
        rs-layer input[type=password],
        rs-layer input[type=range],
        rs-layer input[type=search],
        rs-layer input[type=tel],
        rs-layer input[type=text],
        rs-layer input[type=time],
        rs-layer input[type=url] {
            display: inline-block
        }

        rs-layer input::placeholder {
            vertical-align: middle;
            line-height: inherit !important
        }

        a.rs-layer {
            transition: none
        }

        rs-bullet,
        rs-navmask,
        rs-tab,
        rs-thumb {
            display: block
        }

        .tp-bullets.navbar {
            border: none;
            min-height: 0;
            margin: 0;
            border-radius: 0
        }

        .tp-bullets,
        .tp-tabs,
        .tp-thumbs {
            position: absolute;
            display: block;
            z-index: 1000;
            top: 0;
            left: 0
        }

        .tp-tab,
        .tp-thumb {
            cursor: pointer;
            position: absolute;
            opacity: .5;
            box-sizing: border-box
        }

        .tp-arr-imgholder,
        .tp-tab-image,
        .tp-thumb-image,
        rs-poster {
            background-position: center center;
            background-size: cover;
            width: 100%;
            height: 100%;
            display: block;
            position: absolute;
            top: 0;
            left: 0
        }

        rs-poster {
            cursor: pointer;
            z-index: 3
        }

        .tp-tab.rs-touchhover,
        .tp-tab.selected,
        .tp-thumb.rs-touchhover,
        .tp-thumb.selected {
            opacity: 1
        }

        .tp-tab-mask,
        .tp-thumb-mask {
            box-sizing: border-box !important
        }

        .tp-tabs,
        .tp-thumbs {
            box-sizing: content-box !important
        }

        .tp-bullet {
            width: 15px;
            height: 15px;
            position: absolute;
            background: #fff;
            background: rgba(255, 255, 255, .3);
            cursor: pointer
        }

        .tp-bullet.rs-touchhover,
        .tp-bullet.selected {
            background: #fff
        }

        .tparrows {
            cursor: pointer;
            background: #000;
            background: rgba(0, 0, 0, .5);
            width: 40px;
            height: 40px;
            position: absolute;
            display: block;
            z-index: 1000
        }

        .tparrows.rs-touchhover {
            background: #000
        }

        .tparrows:before {
            font-family: revicons;
            font-size: 15px;
            color: #fff;
            display: block;
            line-height: 40px;
            text-align: center
        }

        .tparrows.tp-leftarrow:before {
            content: '\e824'
        }

        body.rtl .rs-pzimg {
            left: 0 !important
        }

        .rs_fake_cube {
            transform-style: preserve-3d
        }

        .rs_fake_cube,
        .rs_fake_cube_wall {
            position: absolute;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            left: 0;
            top: 0;
            z-index: 0
        }

        rs-sbg canvas {
            overflow: hidden;
            z-index: 5;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden
        }

        .dddwrappershadow {
            box-shadow: 0 45px 100px rgba(0, 0, 0, .4)
        }

        .dddwrapper {
            transform-style: flat;
            perspective: 10000px
        }

        .RSscrollbar-measure {
            width: 100px;
            height: 100px;
            overflow: scroll;
            position: absolute;
            top: -9999px
        }

        html {
            line-height: 1.15;
            -webkit-text-size-adjust: 100%
        }

        *,
        :after,
        :before {
            -webkit-box-sizing: border-box;
            box-sizing: border-box
        }

        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #333;
            background-color: #fff;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale
        }

        h1,
        h2,
        h3,
        h4,
        h6 {
            margin-top: .5rem;
            margin-bottom: 1rem;
            font-family: inherit;
            font-weight: 500;
            line-height: 1.2;
            color: inherit
        }

        h1 {
            font-size: 2.5rem
        }

        h2 {
            font-size: 2rem
        }

        h3 {
            font-size: 1.75rem
        }

        h4 {
            font-size: 1.5rem
        }

        h6 {
            font-size: 1rem
        }

        p {
            margin-top: 0;
            margin-bottom: .9rem
        }

        a {
            background-color: transparent;
            text-decoration: none;
            color: #c36
        }

        a:active,
        a:hover {
            color: #336
        }

        a:not([href]):not([tabindex]),
        a:not([href]):not([tabindex]):focus,
        a:not([href]):not([tabindex]):hover {
            color: inherit;
            text-decoration: none
        }

        a:not([href]):not([tabindex]):focus {
            outline: 0
        }

        b,
        strong {
            font-weight: bolder
        }

        code {
            font-family: monospace, monospace;
            font-size: 1em
        }

        sub {
            font-size: 75%;
            line-height: 0;
            position: relative;
            vertical-align: baseline
        }

        sub {
            bottom: -.25em
        }

        img {
            border-style: none;
            height: auto;
            max-width: 100%
        }

        details {
            display: block
        }

        summary {
            display: list-item
        }

        [hidden],
        template {
            display: none
        }

        @media print {

            *,
            :after,
            :before {
                background: 0 0 !important;
                color: #000 !important;
                -webkit-box-shadow: none !important;
                box-shadow: none !important;
                text-shadow: none !important
            }

            a,
            a:visited {
                text-decoration: underline
            }

            a[href]:after {
                content: " (" attr(href) ")"
            }

            a[href^="#"]:after,
            a[href^="javascript:"]:after {
                content: ""
            }

            img,
            tr {
                page-break-inside: avoid
            }

            h2,
            h3,
            p {
                orphans: 3;
                widows: 3
            }

            h2,
            h3 {
                page-break-after: avoid
            }
        }

        label {
            display: inline-block;
            line-height: 1;
            vertical-align: middle
        }

        button,
        input,
        optgroup,
        select,
        textarea {
            font-family: inherit;
            font-size: 1rem;
            line-height: 1.5;
            margin: 0
        }

        input[type=email],
        input[type=number],
        input[type=password],
        input[type=search],
        input[type=tel],
        input[type=text],
        input[type=url],
        select,
        textarea {
            width: 100%;
            border: 1px solid #666;
            border-radius: 3px;
            padding: .5rem 1rem;
            -webkit-transition: .3s;
            -o-transition: .3s;
            transition: all .3s
        }

        input[type=email]:focus,
        input[type=number]:focus,
        input[type=password]:focus,
        input[type=search]:focus,
        input[type=tel]:focus,
        input[type=text]:focus,
        input[type=url]:focus,
        select:focus,
        textarea:focus {
            border-color: #333
        }

        button,
        input {
            overflow: visible
        }

        button,
        select {
            text-transform: none
        }

        [type=button],
        [type=reset],
        [type=submit],
        button {
            width: auto;
            -webkit-appearance: button
        }

        [type=button]::-moz-focus-inner,
        [type=reset]::-moz-focus-inner,
        [type=submit]::-moz-focus-inner,
        button::-moz-focus-inner {
            border-style: none;
            padding: 0
        }

        [type=button]:-moz-focusring,
        [type=reset]:-moz-focusring,
        [type=submit]:-moz-focusring,
        button:-moz-focusring {
            outline: ButtonText dotted 1px
        }

        [type=button],
        [type=submit],
        button {
            display: inline-block;
            font-weight: 400;
            color: #c36;
            text-align: center;
            white-space: nowrap;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-color: transparent;
            border: 1px solid #c36;
            padding: .5rem 1rem;
            font-size: 1rem;
            border-radius: 3px;
            -webkit-transition: .3s;
            -o-transition: .3s;
            transition: all .3s
        }

        [type=button]:focus,
        [type=submit]:focus,
        button:focus {
            outline: -webkit-focus-ring-color auto 5px
        }

        [type=button]:focus,
        [type=button]:hover,
        [type=submit]:focus,
        [type=submit]:hover,
        button:focus,
        button:hover {
            color: #fff;
            background-color: #c36;
            text-decoration: none
        }

        [type=button]:not(:disabled),
        [type=submit]:not(:disabled),
        button:not(:disabled) {
            cursor: pointer
        }

        fieldset {
            padding: .35em .75em .625em
        }

        legend {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            color: inherit;
            display: table;
            max-width: 100%;
            padding: 0;
            white-space: normal
        }

        progress {
            vertical-align: baseline
        }

        textarea {
            overflow: auto;
            resize: vertical
        }

        [type=checkbox],
        [type=radio] {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            padding: 0
        }

        [type=number]::-webkit-inner-spin-button,
        [type=number]::-webkit-outer-spin-button {
            height: auto
        }

        [type=search] {
            -webkit-appearance: textfield;
            outline-offset: -2px
        }

        [type=search]::-webkit-search-decoration {
            -webkit-appearance: none
        }

        ::-webkit-file-upload-button {
            -webkit-appearance: button;
            font: inherit
        }

        select {
            display: block
        }

        table {
            background-color: transparent;
            width: 100%;
            margin-bottom: 15px;
            font-size: .9em;
            border-spacing: 0;
            border-collapse: collapse
        }

        table td {
            padding: 15px;
            line-height: 1.5;
            vertical-align: top;
            border: 1px solid hsla(0, 0%, 50.2%, .5019607843137255)
        }

        table tbody>tr:nth-child(odd)>td {
            background-color: hsla(0, 0%, 50.2%, .07058823529411765)
        }

        table tbody tr:hover>td {
            background-color: hsla(0, 0%, 50.2%, .10196078431372549)
        }

        table tbody+tbody {
            border-top: 2px solid hsla(0, 0%, 50.2%, .5019607843137255)
        }

        dl,
        dt,
        li,
        ul {
            margin-top: 0;
            margin-bottom: 0;
            border: 0;
            outline: 0;
            font-size: 100%;
            vertical-align: baseline;
            background: 0 0
        }

        .sticky {
            position: relative;
            display: block
        }

        .hide {
            display: none !important
        }

        .screen-reader-text {
            clip: rect(1px, 1px, 1px, 1px);
            height: 1px;
            overflow: hidden;
            position: absolute !important;
            width: 1px;
            word-wrap: normal !important
        }

        .screen-reader-text:focus {
            background-color: #eee;
            clip: auto !important;
            -webkit-clip-path: none;
            clip-path: none;
            color: #333;
            display: block;
            font-size: 1rem;
            height: auto;
            left: 5px;
            line-height: normal;
            padding: 15px 23px 14px;
            text-decoration: none;
            top: 5px;
            width: auto;
            z-index: 100000
        }

        .site-header {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            padding-top: 1rem;
            padding-bottom: 1rem;
            position: relative
        }

        .site-navigation-toggle-holder {
            -webkit-box-pack: end;
            -ms-flex-pack: end;
            justify-content: flex-end;
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            max-width: 20%;
            padding: 8px 15px
        }

        .site-navigation-toggle-holder,
        .site-navigation-toggle-holder .site-navigation-toggle {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center
        }

        .site-navigation-toggle-holder .site-navigation-toggle {
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            font-size: 22px;
            padding: .25em;
            cursor: pointer;
            border: 0 solid;
            border-radius: 3px;
            background-color: rgba(0, 0, 0, .05);
            color: #494c4f
        }

        .site-navigation-toggle-holder.elementor-active .site-navigation-toggle i:before {
            content: "\e87f"
        }

        .site-navigation-dropdown {
            margin-top: 10px;
            -webkit-transition: max-height .3s, -webkit-transform .3s;
            transition: max-height .3s, -webkit-transform .3s;
            -o-transition: max-height .3s, transform .3s;
            transition: max-height .3s, transform .3s;
            transition: max-height .3s, transform .3s, -webkit-transform .3s;
            -webkit-transform-origin: top;
            -ms-transform-origin: top;
            transform-origin: top;
            position: absolute;
            bottom: 0;
            left: 0;
            z-index: 10000;
            width: 100%
        }

        .site-navigation-toggle-holder:not(.elementor-active)+.site-navigation-dropdown {
            -webkit-transform: scaleY(0);
            -ms-transform: scaleY(0);
            transform: scaleY(0);
            max-height: 0
        }

        .site-navigation-toggle-holder.elementor-active+.site-navigation-dropdown {
            -webkit-transform: scaleY(1);
            -ms-transform: scaleY(1);
            transform: scaleY(1);
            max-height: 100vh
        }

        .site-navigation-dropdown ul {
            padding: 0
        }

        .site-navigation-dropdown ul.menu {
            position: absolute;
            width: 100%;
            padding: 0;
            margin: 0;
            background: #fff
        }

        .site-navigation-dropdown ul.menu li {
            display: block;
            width: 100%;
            position: relative
        }

        .site-navigation-dropdown ul.menu li a {
            display: block;
            padding: 20px;
            background: #fff;
            color: #55595c;
            -webkit-box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .10196078431372549);
            box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .10196078431372549)
        }

        .site-navigation-dropdown ul.menu>li li {
            -webkit-transition: max-height .3s, -webkit-transform .3s;
            transition: max-height .3s, -webkit-transform .3s;
            -o-transition: max-height .3s, transform .3s;
            transition: max-height .3s, transform .3s;
            transition: max-height .3s, transform .3s, -webkit-transform .3s;
            -webkit-transform-origin: top;
            -ms-transform-origin: top;
            transform-origin: top;
            -webkit-transform: scaleY(0);
            -ms-transform: scaleY(0);
            transform: scaleY(0);
            max-height: 0
        }

        .site-navigation-dropdown ul.menu li.elementor-active>ul>li {
            -webkit-transform: scaleY(1);
            -ms-transform: scaleY(1);
            transform: scaleY(1);
            max-height: 100vh
        }

        .site-header:not(.dynamic-header) {
            margin-right: auto;
            margin-left: auto;
            width: 100%
        }

        @media (max-width:575px) {
            .site-header:not(.dynamic-header) {
                padding-right: 10px;
                padding-left: 10px
            }
        }

        @media (min-width:576px) {
            .site-header:not(.dynamic-header) {
                max-width: 500px
            }
        }

        @media (min-width:768px) {
            .site-header:not(.dynamic-header) {
                max-width: 600px
            }
        }

        @media (min-width:992px) {
            .site-header:not(.dynamic-header) {
                max-width: 800px
            }
        }

        @media (min-width:1200px) {
            .site-header:not(.dynamic-header) {
                max-width: 1140px
            }
        }

        .site-header+.elementor {
            min-height: calc(100vh - 320px)
        }

        .dialog-widget-content {
            background-color: #fff;
            position: absolute;
            border-radius: 3px;
            -webkit-box-shadow: 2px 8px 23px 3px rgba(0, 0, 0, .2);
            box-shadow: 2px 8px 23px 3px rgba(0, 0, 0, .2);
            overflow: hidden
        }

        .dialog-message {
            font-size: 12px;
            line-height: 1.5;
            -webkit-box-sizing: border-box;
            box-sizing: border-box
        }

        .dialog-type-lightbox {
            position: fixed;
            height: 100%;
            width: 100%;
            bottom: 0;
            left: 0;
            background-color: rgba(0, 0, 0, .8);
            z-index: 9999;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none
        }

        .dialog-type-lightbox .dialog-widget-content {
            margin: auto;
            width: 375px
        }

        .dialog-type-lightbox .dialog-header {
            font-size: 15px;
            color: #495157;
            padding: 30px 0 10px;
            font-weight: 500
        }

        .dialog-type-lightbox .dialog-message {
            padding: 0 30px 30px;
            min-height: 50px
        }

        .dialog-type-lightbox:not(.elementor-popup-modal) .dialog-header,
        .dialog-type-lightbox:not(.elementor-popup-modal) .dialog-message {
            text-align: center
        }

        .dialog-type-lightbox .dialog-buttons-wrapper {
            border-top: 1px solid #e6e9ec;
            text-align: center
        }

        .dialog-type-lightbox .dialog-buttons-wrapper>.dialog-button {
            font-family: Roboto, Arial, Helvetica, Verdana, sans-serif;
            width: 50%;
            border: none;
            background: 0 0;
            color: #6d7882;
            font-size: 15px;
            cursor: pointer;
            padding: 13px 0;
            outline: 0
        }

        .dialog-type-lightbox .dialog-buttons-wrapper>.dialog-button:hover {
            background-color: #f4f6f7
        }

        .dialog-type-lightbox .dialog-buttons-wrapper>.dialog-button.dialog-ok {
            color: #b01b1b
        }

        .dialog-type-lightbox .dialog-buttons-wrapper>.dialog-button.dialog-take_over {
            color: #39b54a
        }

        .dialog-type-lightbox .dialog-buttons-wrapper>.dialog-button:active {
            background-color: rgba(230, 233, 236, .5)
        }

        .dialog-type-lightbox .dialog-buttons-wrapper>.dialog-button::-moz-focus-inner {
            border: 0
        }

        .dialog-close-button {
            cursor: pointer;
            position: absolute;
            margin-top: 15px;
            right: 15px;
            font-size: 15px;
            line-height: 1
        }

        .dialog-close-button:not(:hover) {
            opacity: .4
        }

        .dialog-alert-widget .dialog-buttons-wrapper>button {
            width: 100%
        }

        .dialog-confirm-widget .dialog-button:first-child {
            border-right: 1px solid #e6e9ec
        }

        #elementor-change-exit-preference-dialog .dialog-message a {
            cursor: pointer
        }

        #elementor-change-exit-preference-dialog .dialog-message>div {
            margin-bottom: 10px
        }

        #elementor-change-exit-preference-dialog .dialog-ok {
            color: #39b54a
        }

        #e-experiments-dependency-dialog .dialog-confirm-header {
            font-weight: 600
        }

        #e-experiments-dependency-dialog .dialog-ok {
            color: #39b54a
        }

        .dialog-prevent-scroll {
            overflow: hidden;
            max-height: 100vh
        }

        @media (min-width:1024px) {
            body.admin-bar .dialog-lightbox-widget {
                height: calc(100vh - 32px)
            }
        }

        .elementor-aspect-ratio-219 .elementor-fit-aspect-ratio {
            padding-bottom: 42.8571%
        }

        .elementor-aspect-ratio-169 .elementor-fit-aspect-ratio {
            padding-bottom: 56.25%
        }

        .elementor-aspect-ratio-43 .elementor-fit-aspect-ratio {
            padding-bottom: 75%
        }

        .elementor-aspect-ratio-32 .elementor-fit-aspect-ratio {
            padding-bottom: 66.6666%
        }

        .elementor-aspect-ratio-11 .elementor-fit-aspect-ratio {
            padding-bottom: 100%
        }

        .elementor-aspect-ratio-916 .elementor-fit-aspect-ratio {
            padding-bottom: 177.8%
        }

        .flatpickr-calendar {
            width: 280px
        }

        .elementor-templates-modal .dialog-widget-content {
            font-family: Roboto, Arial, Helvetica, Verdana, sans-serif;
            background-color: #f1f3f5;
            width: 100%
        }

        @media (max-width:1439px) {
            .elementor-templates-modal .dialog-widget-content {
                max-width: 990px
            }
        }

        @media (min-width:1440px) {
            .elementor-templates-modal .dialog-widget-content {
                max-width: 1200px
            }
        }

        .elementor-templates-modal .dialog-header {
            padding: 0;
            z-index: 1
        }

        .elementor-templates-modal .dialog-buttons-wrapper,
        .elementor-templates-modal .dialog-header {
            background-color: #fff;
            -webkit-box-shadow: 0 0 8px rgba(0, 0, 0, .1);
            box-shadow: 0 0 8px rgba(0, 0, 0, .1);
            position: relative
        }

        .elementor-templates-modal .dialog-buttons-wrapper {
            border: none;
            display: none;
            -webkit-box-pack: end;
            -ms-flex-pack: end;
            justify-content: flex-end;
            padding: 5px
        }

        .elementor-templates-modal .dialog-buttons-wrapper .elementor-button {
            height: 40px;
            margin-left: 5px
        }

        .elementor-templates-modal .dialog-buttons-wrapper .elementor-button-success {
            padding: 12px 36px;
            color: #fff;
            width: auto;
            font-size: 15px
        }

        .elementor-templates-modal .dialog-buttons-wrapper .elementor-button-success:hover {
            background-color: #39b54a
        }

        .elementor-templates-modal .dialog-message {
            height: 750px;
            max-height: 85vh;
            overflow-y: scroll;
            padding-top: 25px
        }

        .elementor-templates-modal .dialog-content {
            height: 100%
        }

        .elementor-templates-modal .dialog-loading {
            display: none
        }

        .elementor-hidden {
            display: none
        }

        .elementor-screen-only,
        .screen-reader-text,
        .screen-reader-text span {
            position: absolute;
            top: -10000em;
            width: 1px;
            height: 1px;
            margin: -1px;
            padding: 0;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            border: 0
        }

        #e-enable-unfiltered-files-dialog-import-template .dialog-confirm-ok {
            color: #39b54a
        }

        #e-enable-unfiltered-files-dialog-import-template .dialog-confirm-cancel {
            color: #b01b1b
        }

        .elementor {
            -webkit-hyphens: manual;
            -ms-hyphens: manual;
            hyphens: manual
        }

        .elementor *,
        .elementor :after,
        .elementor :before {
            -webkit-box-sizing: border-box;
            box-sizing: border-box
        }

        .elementor a {
            -webkit-box-shadow: none;
            box-shadow: none;
            text-decoration: none
        }

        .elementor img {
            height: auto;
            max-width: 100%;
            border: none;
            border-radius: 0;
            -webkit-box-shadow: none;
            box-shadow: none
        }

        .elementor embed,
        .elementor iframe,
        .elementor object,
        .elementor video {
            max-width: 100%;
            width: 100%;
            margin: 0;
            line-height: 1;
            border: none
        }

        .elementor .elementor-background-video-container {
            height: 100%;
            width: 100%;
            top: 0;
            left: 0;
            position: absolute;
            overflow: hidden;
            z-index: 0;
            direction: ltr
        }

        .elementor .elementor-background-video-container {
            -webkit-transition: opacity 1s;
            -o-transition: opacity 1s;
            transition: opacity 1s;
            pointer-events: none
        }

        .elementor .elementor-background-video-container.elementor-loading {
            opacity: 0
        }

        .elementor .elementor-background-video-embed {
            max-width: none
        }

        .elementor .elementor-background-video-embed,
        .elementor .elementor-background-video-hosted {
            position: absolute;
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%)
        }

        .elementor .elementor-background-slideshow {
            height: 100%;
            width: 100%;
            top: 0;
            left: 0;
            position: absolute
        }

        .elementor .elementor-background-slideshow {
            z-index: 0
        }

        .elementor .elementor-background-slideshow__slide__image {
            width: 100%;
            height: 100%;
            background-position: 50%;
            background-size: cover
        }

        .elementor-widget-wrap>.elementor-element.elementor-absolute {
            position: absolute
        }

        .elementor-widget-wrap>.elementor-element.elementor-fixed {
            position: fixed
        }

        .elementor-widget-wrap .elementor-element.elementor-widget__width-auto,
        .elementor-widget-wrap .elementor-element.elementor-widget__width-initial {
            max-width: 100%
        }

        .elementor-element {
            --flex-direction: initial;
            --flex-wrap: initial;
            --justify-content: initial;
            --align-items: initial;
            --align-content: initial;
            --gap: initial;
            --flex-basis: initial;
            --flex-grow: initial;
            --flex-shrink: initial;
            --order: initial;
            --align-self: initial;
            -ms-flex-preferred-size: var(--flex-basis);
            flex-basis: var(--flex-basis);
            -webkit-box-flex: var(--flex-grow);
            -ms-flex-positive: var(--flex-grow);
            flex-grow: var(--flex-grow);
            -ms-flex-negative: var(--flex-shrink);
            flex-shrink: var(--flex-shrink);
            -webkit-box-ordinal-group: var(--order);
            -ms-flex-order: var(--order);
            order: var(--order);
            -ms-flex-item-align: var(--align-self);
            align-self: var(--align-self)
        }

        .elementor-element.elementor-absolute,
        .elementor-element.elementor-fixed {
            z-index: 1
        }

        .elementor-element:where(.e-con-full, .elementor-widget) {
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: var(--flex-direction);
            flex-direction: var(--flex-direction);
            -ms-flex-wrap: var(--flex-wrap);
            flex-wrap: var(--flex-wrap);
            -webkit-box-pack: var(--justify-content);
            -ms-flex-pack: var(--justify-content);
            justify-content: var(--justify-content);
            -webkit-box-align: var(--align-items);
            -ms-flex-align: var(--align-items);
            align-items: var(--align-items);
            -ms-flex-line-pack: var(--align-content);
            align-content: var(--align-content);
            gap: var(--gap)
        }

        .elementor-invisible {
            visibility: hidden
        }

        .elementor-align-left {
            text-align: left
        }

        .elementor-align-left .elementor-button {
            width: auto
        }

        .elementor-ken-burns {
            -webkit-transition-property: -webkit-transform;
            transition-property: -webkit-transform;
            -o-transition-property: transform;
            transition-property: transform;
            transition-property: transform, -webkit-transform;
            -webkit-transition-duration: 10s;
            -o-transition-duration: 10s;
            transition-duration: 10s;
            -webkit-transition-timing-function: linear;
            -o-transition-timing-function: linear;
            transition-timing-function: linear
        }

        .elementor-ken-burns--out {
            -webkit-transform: scale(1.3);
            -ms-transform: scale(1.3);
            transform: scale(1.3)
        }

        .elementor-ken-burns--active {
            -webkit-transition-duration: 20s;
            -o-transition-duration: 20s;
            transition-duration: 20s
        }

        .elementor-ken-burns--active.elementor-ken-burns--out {
            -webkit-transform: scale(1);
            -ms-transform: scale(1);
            transform: scale(1)
        }

        .elementor-ken-burns--active.elementor-ken-burns--in {
            -webkit-transform: scale(1.3);
            -ms-transform: scale(1.3);
            transform: scale(1.3)
        }

        :root {
            --page-title-display: block
        }

        .elementor-section {
            position: relative
        }

        .elementor-section .elementor-container {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            margin-right: auto;
            margin-left: auto;
            position: relative
        }

        @media (max-width:1024px) {
            body.admin-bar .dialog-type-lightbox {
                position: sticky;
                height: 100vh
            }

            .elementor-section .elementor-container {
                -ms-flex-wrap: wrap;
                flex-wrap: wrap
            }
        }

        .elementor-section.elementor-section-stretched {
            position: relative;
            width: 100%
        }

        .elementor-widget-wrap {
            position: relative;
            width: 100%;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            -ms-flex-line-pack: start;
            align-content: flex-start
        }

        .elementor:not(.elementor-bc-flex-widget) .elementor-widget-wrap {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex
        }

        .elementor-widget-wrap>.elementor-element {
            width: 100%
        }

        .elementor-widget-wrap.e-swiper-container {
            width: calc(100% - (var(--e-column-margin-left, 0px) + var(--e-column-margin-right, 0px)))
        }

        .elementor-widget {
            position: relative
        }

        .elementor-widget:not(:last-child).elementor-absolute,
        .elementor-widget:not(:last-child).elementor-widget__width-auto,
        .elementor-widget:not(:last-child).elementor-widget__width-initial {
            margin-bottom: 0
        }

        .elementor-column {
            min-height: 1px
        }

        .elementor-column,
        .elementor-column-wrap {
            position: relative;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex
        }

        .elementor-column-wrap {
            width: 100%
        }

        .elementor-column-gap-default>.elementor-column>.elementor-element-populated {
            padding: 10px
        }

        .elementor-inner-section .elementor-column-gap-no .elementor-element-populated {
            padding: 0
        }

        @media (min-width:768px) {
            .elementor-column.elementor-col-25 {
                width: 25%
            }

            .elementor-column.elementor-col-30 {
                width: 30%
            }

            .elementor-column.elementor-col-33 {
                width: 33.333%
            }

            .elementor-column.elementor-col-50 {
                width: 50%
            }

            .elementor-column.elementor-col-100 {
                width: 100%
            }
        }

        @media (max-width:767px) {
            table table {
                font-size: .8em
            }

            table table td {
                padding: 7px;
                line-height: 1.3
            }

            .elementor-widget-wrap .elementor-element.elementor-widget-mobile__width-initial {
                max-width: 100%
            }

            .elementor-column.elementor-sm-50 {
                width: 50%
            }

            .elementor-column {
                width: 100%
            }
        }

        .elementor-grid {
            display: grid;
            grid-column-gap: var(--grid-column-gap);
            grid-row-gap: var(--grid-row-gap)
        }

        .elementor-grid .elementor-grid-item {
            min-width: 0
        }

        .elementor-grid-0 .elementor-grid {
            display: inline-block;
            width: 100%;
            word-spacing: var(--grid-column-gap);
            margin-bottom: calc(-1 * var(--grid-row-gap))
        }

        .elementor-grid-0 .elementor-grid .elementor-grid-item {
            display: inline-block;
            margin-bottom: var(--grid-row-gap);
            word-break: break-word
        }

        @media (min-width:1281px) {
            #elementor-device-mode:after {
                content: "desktop"
            }
        }

        @media (min-width:-1px) {
            #elementor-device-mode:after {
                content: "widescreen"
            }

            .elementor-widget:not(.elementor-widescreen-align-right) .elementor-icon-list-item:after {
                left: 0
            }

            .elementor-widget:not(.elementor-widescreen-align-left) .elementor-icon-list-item:after {
                right: 0
            }
        }

        @media (max-width:1280px) {
            #elementor-device-mode:after {
                content: "laptop"
            }

            .elementor-widget:not(.elementor-laptop-align-right) .elementor-icon-list-item:after {
                left: 0
            }

            .elementor-widget:not(.elementor-laptop-align-left) .elementor-icon-list-item:after {
                right: 0
            }
        }

        @media (max-width:-1px) {
            #elementor-device-mode:after {
                content: "tablet_extra"
            }
        }

        @media (max-width:1024px) {
            #elementor-device-mode:after {
                content: "tablet"
            }
        }

        @media (max-width:-1px) {
            #elementor-device-mode:after {
                content: "mobile_extra"
            }

            .elementor-widget:not(.elementor-tablet_extra-align-right) .elementor-icon-list-item:after {
                left: 0
            }

            .elementor-widget:not(.elementor-tablet_extra-align-left) .elementor-icon-list-item:after {
                right: 0
            }
        }

        .elementor-form-fields-wrapper {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap
        }

        .elementor-form-fields-wrapper.elementor-labels-above .elementor-field-group .elementor-field-subgroup,
        .elementor-form-fields-wrapper.elementor-labels-above .elementor-field-group>.elementor-select-wrapper,
        .elementor-form-fields-wrapper.elementor-labels-above .elementor-field-group>input,
        .elementor-form-fields-wrapper.elementor-labels-above .elementor-field-group>textarea {
            -ms-flex-preferred-size: 100%;
            flex-basis: 100%;
            max-width: 100%
        }

        .elementor-form-fields-wrapper.elementor-labels-inline>.elementor-field-group .elementor-select-wrapper,
        .elementor-form-fields-wrapper.elementor-labels-inline>.elementor-field-group>input {
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1
        }

        .elementor-field-group {
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center
        }

        .elementor-field-group.elementor-field-type-submit {
            -webkit-box-align: end;
            -ms-flex-align: end;
            align-items: flex-end
        }

        .elementor-field-group .elementor-field-textual {
            width: 100%;
            max-width: 100%;
            border: 1px solid #818a91;
            background-color: transparent;
            color: #373a3c;
            vertical-align: middle;
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1
        }

        .elementor-field-group .elementor-field-textual:focus {
            -webkit-box-shadow: 0 0 0 1px rgba(0, 0, 0, .1) inset;
            box-shadow: inset 0 0 0 1px rgba(0, 0, 0, .1);
            outline: 0
        }

        .elementor-field-group .elementor-field-textual::-webkit-input-placeholder {
            color: inherit;
            font-family: inherit;
            opacity: .6
        }

        .elementor-field-group .elementor-field-textual:-ms-input-placeholder {
            color: inherit;
            font-family: inherit;
            opacity: .6
        }

        .elementor-field-group .elementor-field-textual:-moz-placeholder,
        .elementor-field-group .elementor-field-textual::-moz-placeholder {
            color: inherit;
            font-family: inherit;
            opacity: .6
        }

        .elementor-field-group .elementor-field-textual::-ms-input-placeholder {
            color: inherit;
            font-family: inherit;
            opacity: .6
        }

        .elementor-field-group .elementor-field-textual::placeholder {
            color: inherit;
            font-family: inherit;
            opacity: .6
        }

        .elementor-field-label {
            cursor: pointer
        }

        .elementor-field-textual {
            line-height: 1.4;
            font-size: 15px;
            min-height: 40px;
            padding: 5px 14px;
            border-radius: 3px
        }

        .elementor-button-align-stretch .elementor-field-type-submit:not(.e-form__buttons__wrapper) .elementor-button {
            -ms-flex-preferred-size: 100%;
            flex-basis: 100%
        }

        .elementor-button-align-stretch .e-form__buttons__wrapper {
            -ms-flex-preferred-size: 50%;
            flex-basis: 50%;
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1
        }

        .elementor-button-align-stretch .e-form__buttons__wrapper__button {
            -ms-flex-preferred-size: 100%;
            flex-basis: 100%
        }

        .elementor-button-align-center .e-form__buttons {
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center
        }

        .elementor-button-align-start .e-form__buttons {
            -webkit-box-pack: start;
            -ms-flex-pack: start;
            justify-content: flex-start
        }

        .elementor-button-align-end .e-form__buttons {
            -webkit-box-pack: end;
            -ms-flex-pack: end;
            justify-content: flex-end
        }

        .elementor-button-align-center .elementor-field-type-submit:not(.e-form__buttons__wrapper) .elementor-button,
        .elementor-button-align-end .elementor-field-type-submit:not(.e-form__buttons__wrapper) .elementor-button,
        .elementor-button-align-start .elementor-field-type-submit:not(.e-form__buttons__wrapper) .elementor-button {
            -ms-flex-preferred-size: initial;
            flex-basis: auto
        }

        .elementor-button-align-center .e-form__buttons__wrapper,
        .elementor-button-align-end .e-form__buttons__wrapper,
        .elementor-button-align-start .e-form__buttons__wrapper {
            -webkit-box-flex: initial;
            -ms-flex-positive: initial;
            flex-grow: 0
        }

        .elementor-button-align-center .e-form__buttons__wrapper,
        .elementor-button-align-center .e-form__buttons__wrapper__button,
        .elementor-button-align-end .e-form__buttons__wrapper,
        .elementor-button-align-end .e-form__buttons__wrapper__button,
        .elementor-button-align-start .e-form__buttons__wrapper,
        .elementor-button-align-start .e-form__buttons__wrapper__button {
            -ms-flex-preferred-size: initial;
            flex-basis: auto
        }

        @media screen and (max-width:1024px) {
            .elementor-tablet-button-align-stretch .elementor-field-type-submit:not(.e-form__buttons__wrapper) .elementor-button {
                -ms-flex-preferred-size: 100%;
                flex-basis: 100%
            }

            .elementor-tablet-button-align-stretch .e-form__buttons__wrapper {
                -ms-flex-preferred-size: 50%;
                flex-basis: 50%;
                -webkit-box-flex: 1;
                -ms-flex-positive: 1;
                flex-grow: 1
            }

            .elementor-tablet-button-align-stretch .e-form__buttons__wrapper__button {
                -ms-flex-preferred-size: 100%;
                flex-basis: 100%
            }

            .elementor-tablet-button-align-center .e-form__buttons {
                -webkit-box-pack: center;
                -ms-flex-pack: center;
                justify-content: center
            }

            .elementor-tablet-button-align-start .e-form__buttons {
                -webkit-box-pack: start;
                -ms-flex-pack: start;
                justify-content: flex-start
            }

            .elementor-tablet-button-align-end .e-form__buttons {
                -webkit-box-pack: end;
                -ms-flex-pack: end;
                justify-content: flex-end
            }

            .elementor-tablet-button-align-center .elementor-field-type-submit:not(.e-form__buttons__wrapper) .elementor-button,
            .elementor-tablet-button-align-end .elementor-field-type-submit:not(.e-form__buttons__wrapper) .elementor-button,
            .elementor-tablet-button-align-start .elementor-field-type-submit:not(.e-form__buttons__wrapper) .elementor-button {
                -ms-flex-preferred-size: initial;
                flex-basis: auto
            }

            .elementor-tablet-button-align-center .e-form__buttons__wrapper,
            .elementor-tablet-button-align-end .e-form__buttons__wrapper,
            .elementor-tablet-button-align-start .e-form__buttons__wrapper {
                -webkit-box-flex: initial;
                -ms-flex-positive: initial;
                flex-grow: 0
            }

            .elementor-tablet-button-align-center .e-form__buttons__wrapper,
            .elementor-tablet-button-align-center .e-form__buttons__wrapper__button,
            .elementor-tablet-button-align-end .e-form__buttons__wrapper,
            .elementor-tablet-button-align-end .e-form__buttons__wrapper__button,
            .elementor-tablet-button-align-start .e-form__buttons__wrapper,
            .elementor-tablet-button-align-start .e-form__buttons__wrapper__button {
                -ms-flex-preferred-size: initial;
                flex-basis: auto
            }
        }

        @media screen and (max-width:767px) {
            .elementor-mobile-button-align-stretch .elementor-field-type-submit:not(.e-form__buttons__wrapper) .elementor-button {
                -ms-flex-preferred-size: 100%;
                flex-basis: 100%
            }

            .elementor-mobile-button-align-stretch .e-form__buttons__wrapper {
                -ms-flex-preferred-size: 50%;
                flex-basis: 50%;
                -webkit-box-flex: 1;
                -ms-flex-positive: 1;
                flex-grow: 1
            }

            .elementor-mobile-button-align-stretch .e-form__buttons__wrapper__button {
                -ms-flex-preferred-size: 100%;
                flex-basis: 100%
            }

            .elementor-mobile-button-align-center .e-form__buttons {
                -webkit-box-pack: center;
                -ms-flex-pack: center;
                justify-content: center
            }

            .elementor-mobile-button-align-start .e-form__buttons {
                -webkit-box-pack: start;
                -ms-flex-pack: start;
                justify-content: flex-start
            }

            .elementor-mobile-button-align-end .e-form__buttons {
                -webkit-box-pack: end;
                -ms-flex-pack: end;
                justify-content: flex-end
            }

            .elementor-mobile-button-align-center .elementor-field-type-submit:not(.e-form__buttons__wrapper) .elementor-button,
            .elementor-mobile-button-align-end .elementor-field-type-submit:not(.e-form__buttons__wrapper) .elementor-button,
            .elementor-mobile-button-align-start .elementor-field-type-submit:not(.e-form__buttons__wrapper) .elementor-button {
                -ms-flex-preferred-size: initial;
                flex-basis: auto
            }

            .elementor-mobile-button-align-center .e-form__buttons__wrapper,
            .elementor-mobile-button-align-end .e-form__buttons__wrapper,
            .elementor-mobile-button-align-start .e-form__buttons__wrapper {
                -webkit-box-flex: initial;
                -ms-flex-positive: initial;
                flex-grow: 0
            }

            .elementor-mobile-button-align-center .e-form__buttons__wrapper,
            .elementor-mobile-button-align-center .e-form__buttons__wrapper__button,
            .elementor-mobile-button-align-end .e-form__buttons__wrapper,
            .elementor-mobile-button-align-end .e-form__buttons__wrapper__button,
            .elementor-mobile-button-align-start .e-form__buttons__wrapper,
            .elementor-mobile-button-align-start .e-form__buttons__wrapper__button {
                -ms-flex-preferred-size: initial;
                flex-basis: auto
            }
        }

        .elementor-error .elementor-field {
            border-color: #d9534f
        }

        .elementor-message {
            margin: 10px 0;
            font-size: 1em;
            line-height: 1
        }

        .elementor-message:before {
            content: "\e90e";
            display: inline-block;
            font-family: eicons;
            font-weight: 400;
            font-style: normal;
            vertical-align: middle;
            margin-right: 5px
        }

        .elementor-form .elementor-button {
            padding-top: 0;
            padding-bottom: 0;
            border: none
        }

        .elementor-form .elementor-button>span {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center
        }

        .elementor-form .elementor-button.elementor-size-xs {
            min-height: 33px
        }

        .elementor-form .elementor-button.elementor-size-sm {
            min-height: 40px
        }

        .elementor-form .elementor-button.elementor-size-md {
            min-height: 47px
        }

        .elementor-form .elementor-button.elementor-size-lg {
            min-height: 59px
        }

        .elementor-form .elementor-button.elementor-size-xl {
            min-height: 72px
        }

        .elementor-element .elementor-widget-container {
            -webkit-transition: background .3s, border .3s, border-radius .3s, -webkit-box-shadow .3s;
            transition: background .3s, border .3s, border-radius .3s, -webkit-box-shadow .3s;
            -o-transition: background .3s, border .3s, border-radius .3s, box-shadow .3s;
            transition: background .3s, border .3s, border-radius .3s, box-shadow .3s;
            transition: background .3s, border .3s, border-radius .3s, box-shadow .3s, -webkit-box-shadow .3s
        }

        .elementor-button {
            display: inline-block;
            line-height: 1;
            background-color: #818a91;
            font-size: 15px;
            padding: 12px 24px;
            border-radius: 3px;
            color: #fff;
            fill: #fff;
            text-align: center;
            -webkit-transition: .3s;
            -o-transition: .3s;
            transition: all .3s
        }

        .elementor-button:focus,
        .elementor-button:hover,
        .elementor-button:visited {
            color: #fff
        }

        .elementor-button-content-wrapper {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center
        }

        .elementor-button-icon {
            -webkit-box-flex: 0;
            -ms-flex-positive: 0;
            flex-grow: 0;
            -webkit-box-ordinal-group: 6;
            -ms-flex-order: 5;
            order: 5
        }

        .elementor-button-icon svg {
            width: 1em;
            height: auto
        }

        .elementor-button-icon .e-font-icon-svg {
            height: 1em
        }

        .elementor-button-text {
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            -webkit-box-ordinal-group: 11;
            -ms-flex-order: 10;
            order: 10;
            display: inline-block
        }

        .elementor-button span {
            text-decoration: inherit
        }

        .elementor-icon {
            display: inline-block;
            line-height: 1;
            -webkit-transition: .3s;
            -o-transition: .3s;
            transition: all .3s;
            color: #818a91;
            font-size: 50px;
            text-align: center
        }

        .elementor-icon:hover {
            color: #818a91
        }

        .elementor-icon i,
        .elementor-icon svg {
            width: 1em;
            height: 1em;
            position: relative;
            display: block
        }

        .elementor-icon i:before,
        .elementor-icon svg:before {
            position: absolute;
            left: 50%;
            -webkit-transform: translateX(-50%);
            -ms-transform: translateX(-50%);
            transform: translateX(-50%)
        }

        .swiper-container {
            margin-left: auto;
            margin-right: auto;
            position: relative;
            overflow: hidden;
            z-index: 1
        }

        .swiper-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            z-index: 1;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-transition-property: -webkit-transform;
            transition-property: -webkit-transform;
            -o-transition-property: transform;
            transition-property: transform;
            transition-property: transform, -webkit-transform;
            -webkit-box-sizing: content-box;
            box-sizing: content-box
        }

        .swiper-wrapper {
            -webkit-transform: translateZ(0);
            transform: translateZ(0)
        }

        .swiper-slide {
            -ms-flex-negative: 0;
            flex-shrink: 0;
            width: 100%;
            height: 100%;
            position: relative
        }

        .swiper-lazy-preloader {
            width: 42px;
            height: 42px;
            position: absolute;
            left: 50%;
            top: 50%;
            margin-left: -21px;
            margin-top: -21px;
            z-index: 10;
            -webkit-transform-origin: 50%;
            -ms-transform-origin: 50%;
            transform-origin: 50%;
            -webkit-animation: 1s steps(12) infinite swiper-preloader-spin;
            animation: 1s steps(12) infinite swiper-preloader-spin
        }

        .swiper-lazy-preloader:after {
            display: block;
            content: "";
            width: 100%;
            height: 100%;
            background-size: 100%;
            background: url("data:image/svg+xml;charset=utf-8,%3Csvg viewBox='0 0 120 120' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'%3E%3Cdefs%3E%3Cpath id='a' stroke='%236c6c6c' stroke-width='11' stroke-linecap='round' d='M60 7v20'/%3E%3C/defs%3E%3Cuse xlink:href='%23a' opacity='.27'/%3E%3Cuse xlink:href='%23a' opacity='.27' transform='rotate(30 60 60)'/%3E%3Cuse xlink:href='%23a' opacity='.27' transform='rotate(60 60 60)'/%3E%3Cuse xlink:href='%23a' opacity='.27' transform='rotate(90 60 60)'/%3E%3Cuse xlink:href='%23a' opacity='.27' transform='rotate(120 60 60)'/%3E%3Cuse xlink:href='%23a' opacity='.27' transform='rotate(150 60 60)'/%3E%3Cuse xlink:href='%23a' opacity='.37' transform='rotate(180 60 60)'/%3E%3Cuse xlink:href='%23a' opacity='.46' transform='rotate(210 60 60)'/%3E%3Cuse xlink:href='%23a' opacity='.56' transform='rotate(240 60 60)'/%3E%3Cuse xlink:href='%23a' opacity='.66' transform='rotate(270 60 60)'/%3E%3Cuse xlink:href='%23a' opacity='.75' transform='rotate(300 60 60)'/%3E%3Cuse xlink:href='%23a' opacity='.85' transform='rotate(330 60 60)'/%3E%3C/svg%3E") 50% no-repeat
        }

        @-webkit-keyframes swiper-preloader-spin {
            to {
                -webkit-transform: rotate(1turn);
                transform: rotate(1turn)
            }
        }

        @keyframes swiper-preloader-spin {
            to {
                -webkit-transform: rotate(1turn);
                transform: rotate(1turn)
            }
        }

        .elementor-lightbox .dialog-header {
            display: none
        }

        .elementor-lightbox .dialog-widget-content {
            background: 0 0;
            -webkit-box-shadow: none;
            box-shadow: none;
            width: 100%;
            height: 100%
        }

        .elementor-lightbox .dialog-message {
            -webkit-animation-duration: .3s;
            animation-duration: .3s
        }

        .elementor-lightbox .dialog-message:not(.elementor-fit-aspect-ratio) {
            height: 100%
        }

        .elementor-lightbox .dialog-message.dialog-lightbox-message {
            padding: 0
        }

        .elementor-lightbox .dialog-lightbox-close-button {
            cursor: pointer;
            position: absolute;
            font-size: var(--lightbox-header-icons-size);
            right: .75em;
            margin-top: 13px;
            padding: .25em;
            z-index: 2;
            line-height: 1;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex
        }

        .elementor-lightbox .dialog-lightbox-close-button svg {
            height: 1em;
            width: 1em
        }

        .elementor-lightbox .dialog-lightbox-close-button {
            color: var(--lightbox-ui-color);
            -webkit-transition: .3s;
            -o-transition: .3s;
            transition: all .3s;
            opacity: 1
        }

        .elementor-lightbox .dialog-lightbox-close-button svg {
            fill: var(--lightbox-ui-color)
        }

        .elementor-lightbox .dialog-lightbox-close-button:hover {
            color: var(--lightbox-ui-color-hover)
        }

        .elementor-lightbox .dialog-lightbox-close-button:hover svg {
            fill: var(--lightbox-ui-color-hover)
        }

        .animated {
            -webkit-animation-duration: 1.25s;
            animation-duration: 1.25s
        }

        .animated.reverse {
            animation-direction: reverse;
            -webkit-animation-fill-mode: forwards;
            animation-fill-mode: forwards
        }

        @media (prefers-reduced-motion:reduce) {
            .animated {
                -webkit-animation: none;
                animation: none
            }
        }

        #wpadminbar * {
            font-style: normal
        }

        .elementor-post__thumbnail__link {
            -webkit-transition: none;
            -o-transition: none;
            transition: none
        }

        .elementor .elementor-element ul.elementor-icon-list-items,
        .elementor-edit-area .elementor-element ul.elementor-icon-list-items {
            padding: 0
        }

        @media (max-width:767px) {
            #elementor-device-mode:after {
                content: "mobile"
            }

            .elementor .elementor-hidden-mobile {
                display: none
            }

            .elementor-widget:not(.elementor-mobile-align-right) .elementor-icon-list-item:after {
                left: 0
            }

            .elementor-widget:not(.elementor-mobile-align-left) .elementor-icon-list-item:after {
                right: 0
            }
        }

        @media (min-width:768px) and (max-width:1024px) {
            .elementor-lightbox .elementor-aspect-ratio-916 .elementor-video-container {
                width: 70%
            }

            .elementor .elementor-hidden-tablet {
                display: none
            }
        }

        @media (min-width:1025px) and (max-width:1280px) {
            .elementor .elementor-hidden-laptop {
                display: none
            }
        }

        @media (min-width:1281px) and (max-width:99999px) {
            .elementor .elementor-hidden-desktop {
                display: none
            }
        }

        .elementor-kit-7 {
            --e-global-color-primary: #000000;
            --e-global-color-secondary: #54595F;
            --e-global-color-text: #7A7A7A;
            --e-global-color-accent: #3F7853;
            --e-global-color-d32e871: #A6351B;
            --e-global-typography-primary-font-family: "Noto Sans";
            --e-global-typography-primary-font-weight: 600;
            --e-global-typography-secondary-font-family: "Noto Sans";
            --e-global-typography-secondary-font-weight: 400;
            --e-global-typography-text-font-family: "Noto Sans";
            --e-global-typography-text-font-weight: 400;
            --e-global-typography-accent-font-family: "Noto Sans";
            --e-global-typography-accent-font-weight: 500;
            font-family: "Noto Sans", Sans-serif
        }

        .elementor-kit-7 a {
            font-family: "Noto Sans", Sans-serif
        }

        .elementor-kit-7 h1 {
            font-family: "Noto Sans", Sans-serif
        }

        .elementor-kit-7 h2 {
            font-family: "Noto Sans", Sans-serif
        }

        .elementor-kit-7 h3 {
            font-family: "Noto Sans", Sans-serif
        }

        .elementor-kit-7 h4 {
            font-family: "Noto Sans", Sans-serif
        }

        .elementor-section.elementor-section-boxed>.elementor-container {
            max-width: 1140px
        }

        .elementor-widget:not(:last-child) {
            margin-bottom: 20px
        }

        .elementor-element {
            --widgets-spacing: 20px
        }

        .site-header {
            padding-right: 0;
            padding-left: 0
        }

        @media(max-width:1024px) {
            .elementor-section.elementor-section-boxed>.elementor-container {
                max-width: 1024px
            }
        }

        @media(max-width:767px) {
            .elementor-section.elementor-section-boxed>.elementor-container {
                max-width: 767px
            }
        }

        @font-face {
            font-display: swap;
            font-family: eicons;
            src: url(fonts/eicons.eot);
            src: url(fonts/eicons.eot#iefix) format("embedded-opentype"), url(fonts/eicons.woff2) format("woff2"), url(fonts/eicons.woff) format("woff"), url(fonts/eicons.ttf) format("truetype"), url(images/eicons.svg#eicon) format("svg");
            font-weight: 400;
            font-style: normal
        }

        [class*=" eicon-"],
        [class^=eicon] {
            display: inline-block;
            font-family: eicons;
            font-size: inherit;
            font-weight: 400;
            font-style: normal;
            font-variant: normal;
            line-height: 1;
            text-rendering: auto;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale
        }

        .eicon-menu-bar:before {
            content: '\e816'
        }

        .eicon-close:before {
            content: '\e87f'
        }

        .elementor-location-footer:before,
        .elementor-location-header:before {
            content: "";
            display: table;
            clear: both
        }

        .elementor-sticky--active {
            z-index: 99
        }

        [data-elementor-type=popup]:not(.elementor-edit-area) {
            display: none
        }

        .elementor-popup-modal {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            pointer-events: none;
            background-color: transparent;
            -webkit-user-select: auto;
            -moz-user-select: auto;
            -ms-user-select: auto;
            user-select: auto
        }

        .elementor-popup-modal .dialog-buttons-wrapper,
        .elementor-popup-modal .dialog-header {
            display: none
        }

        .elementor-popup-modal .dialog-close-button {
            display: none;
            top: 20px;
            margin-top: 0;
            right: 20px;
            opacity: 1;
            z-index: 9999;
            pointer-events: all
        }

        .elementor-popup-modal .dialog-close-button svg {
            fill: #373a3c;
            height: 1em;
            width: 1em
        }

        .elementor-popup-modal .dialog-widget-content {
            width: auto;
            overflow: visible;
            max-width: 100%;
            max-height: 100%;
            border-radius: 0;
            -webkit-box-shadow: none;
            box-shadow: none;
            pointer-events: all
        }

        .elementor-popup-modal .dialog-message {
            width: 640px;
            max-width: 100vw;
            max-height: 100vh;
            padding: 0;
            overflow: auto;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex
        }

        .elementor-popup-modal .elementor {
            width: 100%
        }

        .elementor-motion-effects-element,
        .elementor-motion-effects-layer {
            -webkit-transition-property: opacity, -webkit-transform;
            transition-property: opacity, -webkit-transform;
            -o-transition-property: transform, opacity;
            transition-property: transform, opacity;
            transition-property: transform, opacity, -webkit-transform;
            -webkit-transition-timing-function: cubic-bezier(0, .33, .07, 1.03);
            -o-transition-timing-function: cubic-bezier(0, .33, .07, 1.03);
            transition-timing-function: cubic-bezier(0, .33, .07, 1.03);
            -webkit-transition-duration: 1s;
            -o-transition-duration: 1s;
            transition-duration: 1s
        }

        .elementor-motion-effects-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            -webkit-transform-origin: var(--e-transform-origin-y) var(--e-transform-origin-x);
            -ms-transform-origin: var(--e-transform-origin-y) var(--e-transform-origin-x);
            transform-origin: var(--e-transform-origin-y) var(--e-transform-origin-x)
        }

        .elementor-motion-effects-layer {
            position: absolute;
            top: 0;
            left: 0;
            background-repeat: no-repeat;
            background-size: cover
        }

        .elementor-motion-effects-perspective {
            -webkit-perspective: 1200px;
            perspective: 1200px
        }

        .elementor-widget-heading .elementor-heading-title {
            color: var(--e-global-color-primary);
            font-family: var(--e-global-typography-primary-font-family), Sans-serif;
            font-weight: var(--e-global-typography-primary-font-weight)
        }

        .elementor-widget-text-editor {
            color: var(--e-global-color-text);
            font-family: var(--e-global-typography-text-font-family), Sans-serif;
            font-weight: var(--e-global-typography-text-font-weight)
        }

        .elementor-widget-button .elementor-button {
            font-family: var(--e-global-typography-accent-font-family), Sans-serif;
            font-weight: var(--e-global-typography-accent-font-weight);
            background-color: var(--e-global-color-accent)
        }

        .elementor-widget-divider {
            --divider-color: var(--e-global-color-secondary)
        }

        .elementor-widget-icon-list .elementor-icon-list-item:not(:last-child):after {
            border-color: var(--e-global-color-text)
        }

        .elementor-widget-icon-list .elementor-icon-list-icon i {
            color: var(--e-global-color-primary)
        }

        .elementor-widget-icon-list .elementor-icon-list-icon svg {
            fill: var(--e-global-color-primary)
        }

        .elementor-widget-icon-list .elementor-icon-list-text {
            color: var(--e-global-color-secondary)
        }

        .elementor-widget-icon-list .elementor-icon-list-item>.elementor-icon-list-text,
        .elementor-widget-icon-list .elementor-icon-list-item>a {
            font-family: var(--e-global-typography-text-font-family), Sans-serif;
            font-weight: var(--e-global-typography-text-font-weight)
        }

        .elementor-widget-form .elementor-field-group>label {
            color: var(--e-global-color-text)
        }

        .elementor-widget-form .elementor-field-group>label {
            font-family: var(--e-global-typography-text-font-family), Sans-serif;
            font-weight: var(--e-global-typography-text-font-weight)
        }

        .elementor-widget-form .elementor-field-group .elementor-field {
            color: var(--e-global-color-text)
        }

        .elementor-widget-form .elementor-field-group .elementor-field {
            font-family: var(--e-global-typography-text-font-family), Sans-serif;
            font-weight: var(--e-global-typography-text-font-weight)
        }

        .elementor-widget-form .elementor-button {
            font-family: var(--e-global-typography-accent-font-family), Sans-serif;
            font-weight: var(--e-global-typography-accent-font-weight)
        }

        .elementor-widget-form .e-form__buttons__wrapper__button-next {
            background-color: var(--e-global-color-accent)
        }

        .elementor-widget-form .elementor-button[type=submit] {
            background-color: var(--e-global-color-accent)
        }

        .elementor-widget-form .e-form__buttons__wrapper__button-previous {
            background-color: var(--e-global-color-accent)
        }

        .elementor-widget-form .elementor-message {
            font-family: var(--e-global-typography-text-font-family), Sans-serif;
            font-weight: var(--e-global-typography-text-font-weight)
        }

        .elementor-widget-form .e-form__indicators__indicator,
        .elementor-widget-form .e-form__indicators__indicator__label {
            font-family: var(--e-global-typography-accent-font-family), Sans-serif;
            font-weight: var(--e-global-typography-accent-font-weight)
        }

        .elementor-widget-form {
            --e-form-steps-indicator-inactive-primary-color: var(--e-global-color-text);
            --e-form-steps-indicator-active-primary-color: var(--e-global-color-accent);
            --e-form-steps-indicator-completed-primary-color: var(--e-global-color-accent);
            --e-form-steps-indicator-progress-color: var(--e-global-color-accent);
            --e-form-steps-indicator-progress-background-color: var(--e-global-color-text);
            --e-form-steps-indicator-progress-meter-color: var(--e-global-color-text)
        }

        .elementor-widget-form .e-form__indicators__indicator__progress__meter {
            font-family: var(--e-global-typography-accent-font-family), Sans-serif;
            font-weight: var(--e-global-typography-accent-font-weight)
        }

        .elementor-widget-login .elementor-form-fields-wrapper label {
            color: var(--e-global-color-text);
            font-family: var(--e-global-typography-text-font-family), Sans-serif;
            font-weight: var(--e-global-typography-text-font-weight)
        }

        .elementor-widget-nav-menu .elementor-nav-menu .elementor-item {
            font-family: var(--e-global-typography-primary-font-family), Sans-serif;
            font-weight: var(--e-global-typography-primary-font-weight)
        }

        .elementor-widget-nav-menu .elementor-nav-menu--main .elementor-item {
            color: var(--e-global-color-text);
            fill: var(--e-global-color-text)
        }

        .elementor-widget-nav-menu .elementor-nav-menu--main .elementor-item.elementor-item-active,
        .elementor-widget-nav-menu .elementor-nav-menu--main .elementor-item.highlighted,
        .elementor-widget-nav-menu .elementor-nav-menu--main .elementor-item:focus,
        .elementor-widget-nav-menu .elementor-nav-menu--main .elementor-item:hover {
            color: var(--e-global-color-accent);
            fill: var(--e-global-color-accent)
        }

        .elementor-widget-nav-menu .elementor-nav-menu--main:not(.e--pointer-framed) .elementor-item:after,
        .elementor-widget-nav-menu .elementor-nav-menu--main:not(.e--pointer-framed) .elementor-item:before {
            background-color: var(--e-global-color-accent)
        }

        .elementor-widget-nav-menu {
            --e-nav-menu-divider-color: var(--e-global-color-text)
        }

        .elementor-widget-nav-menu .elementor-nav-menu--dropdown .elementor-item {
            font-family: var(--e-global-typography-accent-font-family), Sans-serif;
            font-weight: var(--e-global-typography-accent-font-weight)
        }

        .elementor-widget-search-form .elementor-lightbox .dialog-lightbox-close-button,
        .elementor-widget-search-form .elementor-lightbox .dialog-lightbox-close-button:hover {
            color: var(--e-global-color-text);
            fill: var(--e-global-color-text)
        }

        .elementor-8 .elementor-element.elementor-element-7a3d40e>.elementor-container>.elementor-column>.elementor-widget-wrap {
            align-content: center;
            align-items: center
        }

        .elementor-8 .elementor-element.elementor-element-7a3d40e:not(.elementor-motion-effects-element-type-background),
        .elementor-8 .elementor-element.elementor-element-7a3d40e>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-image: url("images/background-banner-1.png");
            background-position: top right;
            background-repeat: no-repeat;
            background-size: contain
        }

        .elementor-8 .elementor-element.elementor-element-7a3d40e {
            transition: background .3s, border .3s, border-radius .3s, box-shadow .3s
        }

        .elementor-8 .elementor-element.elementor-element-66bc1da {
            text-align: left;
            width: auto;
            max-width: auto
        }

        .elementor-8 .elementor-element.elementor-element-66bc1da img {
            width: 47%
        }

        .elementor-8 .elementor-element.elementor-element-66bc1da>.elementor-widget-container {
            margin: 0 0 -34px -57px
        }

        .elementor-8 .elementor-element.elementor-element-1feaa80 .elementor-heading-title {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 49px;
            font-weight: 200
        }

        .elementor-8 .elementor-element.elementor-element-90e8d54 {
            --divider-border-style: solid;
            --divider-color: var(--e-global-color-accent);
            --divider-border-width: 2.5px
        }

        .elementor-8 .elementor-element.elementor-element-90e8d54 .elementor-divider-separator {
            width: 32%
        }

        .elementor-8 .elementor-element.elementor-element-90e8d54 .elementor-divider {
            padding-top: 2px;
            padding-bottom: 2px
        }

        .elementor-8 .elementor-element.elementor-element-d688ed0 {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 25px;
            font-weight: 400
        }

        .elementor-8 .elementor-element.elementor-element-86eb668 .elementor-button {
            font-family: "Noto Sans", Sans-serif;
            font-size: 17px;
            font-weight: 500;
            border-radius: 20px 20px 20px 20px;
            padding: 10px 40px
        }

        .elementor-8 .elementor-element.elementor-element-722f52b>.elementor-element-populated {
            transition: background .3s, border .3s, border-radius .3s, box-shadow .3s
        }

        .elementor-8 .elementor-element.elementor-element-28b1dbe {
            --spacer-size: 79vh
        }

        .elementor-8 .elementor-element.elementor-element-049a226:not(.elementor-motion-effects-element-type-background),
        .elementor-8 .elementor-element.elementor-element-049a226>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: #f7f8fa
        }

        .elementor-8 .elementor-element.elementor-element-049a226 {
            transition: background .3s, border .3s, border-radius .3s, box-shadow .3s;
            margin-top: 0;
            margin-bottom: 0;
            padding: 0 0 30px
        }

        .elementor-8 .elementor-element.elementor-element-21646fc img {
            width: 100%
        }

        .elementor-8 .elementor-element.elementor-element-21646fc {
            width: 21%;
            max-width: 21%;
            top: 19px;
            z-index: 99999
        }

        body:not(.rtl) .elementor-8 .elementor-element.elementor-element-21646fc {
            left: 972px
        }

        body.rtl .elementor-8 .elementor-element.elementor-element-21646fc {
            right: 972px
        }

        .elementor-8 .elementor-element.elementor-element-be8dd25>.elementor-container {
            min-height: 471px
        }

        .elementor-8 .elementor-element.elementor-element-be8dd25:not(.elementor-motion-effects-element-type-background),
        .elementor-8 .elementor-element.elementor-element-be8dd25>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: #fff;
            background-image: url("images/Grupo-133.png");
            background-position: bottom left;
            background-repeat: no-repeat;
            background-size: 72% auto
        }

        .elementor-8 .elementor-element.elementor-element-be8dd25 {
            border-radius: 30px 30px 30px 30px
        }

        .elementor-8 .elementor-element.elementor-element-be8dd25 {
            transition: background .3s, border .3s, border-radius .3s, box-shadow .3s;
            margin-top: -53px;
            margin-bottom: 0;
            padding: 0 50px
        }

        .elementor-8 .elementor-element.elementor-element-5489aff.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: center;
            align-items: center
        }

        .elementor-8 .elementor-element.elementor-element-5489aff.elementor-column>.elementor-widget-wrap {
            justify-content: center
        }

        .elementor-8 .elementor-element.elementor-element-5489aff>.elementor-element-populated {
            padding: 0 90px 0 0
        }

        .elementor-8 .elementor-element.elementor-element-0cf6ee8 .elementor-heading-title {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 21px;
            font-weight: 800;
            letter-spacing: .9px
        }

        .elementor-8 .elementor-element.elementor-element-eb0ecf4 {
            --divider-border-style: solid;
            --divider-color: var(--e-global-color-accent);
            --divider-border-width: 2.5px
        }

        .elementor-8 .elementor-element.elementor-element-eb0ecf4 .elementor-divider-separator {
            width: 8%
        }

        .elementor-8 .elementor-element.elementor-element-eb0ecf4 .elementor-divider {
            padding-top: 2px;
            padding-bottom: 2px
        }

        .elementor-8 .elementor-element.elementor-element-5b95a81 {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 17px;
            font-weight: 400;
            line-height: 22px
        }

        .elementor-8 .elementor-element.elementor-element-9b7a3cb {
            transition: background .3s, border .3s, border-radius .3s, box-shadow .3s;
            padding: 0
        }

        .elementor-8 .elementor-element.elementor-element-ec21a9c:not(.elementor-motion-effects-element-type-background)>.elementor-widget-wrap,
        .elementor-8 .elementor-element.elementor-element-ec21a9c>.elementor-widget-wrap>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-image: url("images/acesso-1.png");
            background-position: 0 -63px;
            background-repeat: no-repeat;
            background-size: 56% auto
        }

        .elementor-8 .elementor-element.elementor-element-ec21a9c>.elementor-element-populated {
            transition: background .3s, border .3s, border-radius .3s, box-shadow .3s;
            padding: 80px 0 60px
        }

        .elementor-8 .elementor-element.elementor-element-241df42 {
            text-align: center
        }

        .elementor-8 .elementor-element.elementor-element-241df42 .elementor-heading-title {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 21px;
            font-weight: 800;
            letter-spacing: .9px
        }

        .elementor-8 .elementor-element.elementor-element-873a192>.elementor-container>.elementor-column>.elementor-widget-wrap {
            align-content: space-between;
            align-items: space-between
        }

        .elementor-8 .elementor-element.elementor-element-873a192 {
            margin-top: 30px;
            margin-bottom: 130px
        }

        .elementor-8 .elementor-element.elementor-element-1ee2976.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-1ee2976.elementor-column>.elementor-widget-wrap {
            justify-content: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-541e4fc img {
            width: 21%
        }

        .elementor-8 .elementor-element.elementor-element-9b0cf73 {
            text-align: center
        }

        .elementor-8 .elementor-element.elementor-element-9b0cf73 .elementor-heading-title {
            color: #071934;
            font-family: "Noto Sans", Sans-serif;
            font-size: 18px;
            font-weight: 300;
            line-height: 26px
        }

        .elementor-8 .elementor-element.elementor-element-26d7591.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-26d7591.elementor-column>.elementor-widget-wrap {
            justify-content: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-cf0604a img {
            width: 27%
        }

        .elementor-8 .elementor-element.elementor-element-02d68bd {
            text-align: center
        }

        .elementor-8 .elementor-element.elementor-element-02d68bd .elementor-heading-title {
            color: #071934;
            font-family: "Noto Sans", Sans-serif;
            font-size: 18px;
            font-weight: 300;
            line-height: 26px
        }

        .elementor-8 .elementor-element.elementor-element-29d4937.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-29d4937.elementor-column>.elementor-widget-wrap {
            justify-content: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-e187464 img {
            width: 21%
        }

        .elementor-8 .elementor-element.elementor-element-b4e352f {
            text-align: center
        }

        .elementor-8 .elementor-element.elementor-element-b4e352f .elementor-heading-title {
            color: #071934;
            font-family: "Noto Sans", Sans-serif;
            font-size: 18px;
            font-weight: 300;
            line-height: 26px
        }

        .elementor-8 .elementor-element.elementor-element-5e1058e.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-5e1058e.elementor-column>.elementor-widget-wrap {
            justify-content: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-3bea086 img {
            width: 27%
        }

        .elementor-8 .elementor-element.elementor-element-e505df0 {
            text-align: center
        }

        .elementor-8 .elementor-element.elementor-element-e505df0 .elementor-heading-title {
            color: #071934;
            font-family: "Noto Sans", Sans-serif;
            font-size: 18px;
            font-weight: 300;
            line-height: 26px
        }

        .elementor-8 .elementor-element.elementor-element-62aa79a>.elementor-container {
            max-width: 1920px
        }

        .elementor-8 .elementor-element.elementor-element-62aa79a>.elementor-container>.elementor-column>.elementor-widget-wrap {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-62aa79a {
            transition: background .3s, border .3s, border-radius .3s, box-shadow .3s;
            z-index: 2
        }

        .elementor-8 .elementor-element.elementor-element-87e1c28 {
            --divider-border-style: solid;
            --divider-color: var(--e-global-color-accent);
            --divider-border-width: 2.5px
        }

        .elementor-8 .elementor-element.elementor-element-87e1c28 .elementor-divider-separator {
            width: 100%
        }

        .elementor-8 .elementor-element.elementor-element-87e1c28 .elementor-divider {
            padding-top: 15px;
            padding-bottom: 15px
        }

        .elementor-8 .elementor-element.elementor-element-08e1fd2.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: center;
            align-items: center
        }

        .elementor-8 .elementor-element.elementor-element-08e1fd2.elementor-column>.elementor-widget-wrap {
            justify-content: flex-end
        }

        .elementor-8 .elementor-element.elementor-element-08e1fd2>.elementor-element-populated {
            padding: 10px
        }

        .elementor-8 .elementor-element.elementor-element-08a75a2 {
            text-align: left
        }

        .elementor-8 .elementor-element.elementor-element-08a75a2 .elementor-heading-title {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 21px;
            font-weight: 800;
            letter-spacing: .9px
        }

        .elementor-8 .elementor-element.elementor-element-9e97b0c {
            --divider-border-style: solid;
            --divider-color: var(--e-global-color-d32e871);
            --divider-border-width: 2.5px
        }

        .elementor-8 .elementor-element.elementor-element-9e97b0c .elementor-divider-separator {
            width: 100%;
            margin: 0 auto;
            margin-left: 0
        }

        .elementor-8 .elementor-element.elementor-element-9e97b0c .elementor-divider {
            text-align: left;
            padding-top: 15px;
            padding-bottom: 15px
        }

        .elementor-8 .elementor-element.elementor-element-c4dcb7a {
            z-index: 1
        }

        .elementor-8 .elementor-element.elementor-element-fa62198>.elementor-element-populated {
            margin: -141px 0 0;
            --e-column-margin-right: 0px;
            --e-column-margin-left: 0px
        }

        .elementor-8 .elementor-element.elementor-element-fa62198 {
            z-index: 1
        }

        .elementor-8 .elementor-element.elementor-element-8d074bd {
            text-align: left
        }

        .elementor-8 .elementor-element.elementor-element-8d074bd img {
            width: 89%
        }

        .elementor-8 .elementor-element.elementor-element-469cee5 {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 17px;
            font-weight: 400;
            line-height: 22px
        }

        .elementor-8 .elementor-element.elementor-element-1f5eb44 {
            z-index: 1
        }

        .elementor-8 .elementor-element.elementor-element-c5172f8>.elementor-element-populated {
            margin: -141px 0 0;
            --e-column-margin-right: 0px;
            --e-column-margin-left: 0px
        }

        .elementor-8 .elementor-element.elementor-element-c5172f8 {
            z-index: 1
        }

        .elementor-8 .elementor-element.elementor-element-109e4b9 {
            text-align: left
        }

        .elementor-8 .elementor-element.elementor-element-109e4b9 img {
            width: 89%
        }

        .elementor-8 .elementor-element.elementor-element-7ff9b97 {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 17px;
            font-weight: 400;
            line-height: 22px
        }

        .elementor-8 .elementor-element.elementor-element-9c10790 {
            transition: background .3s, border .3s, border-radius .3s, box-shadow .3s;
            padding: 0
        }

        .elementor-8 .elementor-element.elementor-element-54f1b6f:not(.elementor-motion-effects-element-type-background)>.elementor-widget-wrap,
        .elementor-8 .elementor-element.elementor-element-54f1b6f>.elementor-widget-wrap>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-image: url("images/etapas-1.png");
            background-position: 0 -39px;
            background-repeat: no-repeat;
            background-size: 56% auto
        }

        .elementor-8 .elementor-element.elementor-element-54f1b6f>.elementor-element-populated {
            transition: background .3s, border .3s, border-radius .3s, box-shadow .3s;
            padding: 100px 0
        }

        .elementor-8 .elementor-element.elementor-element-d9e88c2 {
            text-align: left
        }

        .elementor-8 .elementor-element.elementor-element-d9e88c2 .elementor-heading-title {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 21px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .9px
        }

        .elementor-8 .elementor-element.elementor-element-d9e88c2>.elementor-widget-container {
            padding: 0 0 0 70px
        }

        .elementor-8 .elementor-element.elementor-element-4aa2dfc {
            margin-top: 30px;
            margin-bottom: 30px
        }

        .elementor-8 .elementor-element.elementor-element-210bc54.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-8111c2e .elementor-heading-title {
            font-family: Agne, Sans-serif;
            font-size: 41px;
            font-weight: 300
        }

        .elementor-8 .elementor-element.elementor-element-7bdff58.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-85f4165 {
            --divider-border-style: solid;
            --divider-color: #000000;
            --divider-border-width: 2.5px
        }

        .elementor-8 .elementor-element.elementor-element-85f4165 .elementor-divider-separator {
            width: 100%
        }

        .elementor-8 .elementor-element.elementor-element-85f4165 .elementor-divider {
            padding-top: 15px;
            padding-bottom: 15px
        }

        .elementor-8 .elementor-element.elementor-element-2117d0c.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-70acf7c {
            text-align: left
        }

        .elementor-8 .elementor-element.elementor-element-70acf7c .elementor-heading-title {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 15px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .9px
        }

        .elementor-8 .elementor-element.elementor-element-70acf7c>.elementor-widget-container {
            margin: 0 0 -13px;
            padding: 0
        }

        .elementor-8 .elementor-element.elementor-element-d664109 {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 15px;
            font-weight: 400
        }

        .elementor-8 .elementor-element.elementor-element-3c11857.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-2c6043c .elementor-heading-title {
            font-family: Agne, Sans-serif;
            font-size: 41px;
            font-weight: 300
        }

        .elementor-8 .elementor-element.elementor-element-dbdae3f.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-6d38493 {
            --divider-border-style: solid;
            --divider-color: #000000;
            --divider-border-width: 2.5px
        }

        .elementor-8 .elementor-element.elementor-element-6d38493 .elementor-divider-separator {
            width: 100%
        }

        .elementor-8 .elementor-element.elementor-element-6d38493 .elementor-divider {
            padding-top: 15px;
            padding-bottom: 15px
        }

        .elementor-8 .elementor-element.elementor-element-4b6a7ff.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-c5b60bc {
            text-align: left
        }

        .elementor-8 .elementor-element.elementor-element-c5b60bc .elementor-heading-title {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 15px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .9px
        }

        .elementor-8 .elementor-element.elementor-element-c5b60bc>.elementor-widget-container {
            margin: 0 0 -13px;
            padding: 0
        }

        .elementor-8 .elementor-element.elementor-element-c44d4a9 {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 15px;
            font-weight: 400
        }

        .elementor-8 .elementor-element.elementor-element-83e4fe8.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-bcb7760 .elementor-heading-title {
            font-family: Agne, Sans-serif;
            font-size: 41px;
            font-weight: 300
        }

        .elementor-8 .elementor-element.elementor-element-b0ff5f1.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-6752ded {
            --divider-border-style: solid;
            --divider-color: #000000;
            --divider-border-width: 2.5px
        }

        .elementor-8 .elementor-element.elementor-element-6752ded .elementor-divider-separator {
            width: 100%
        }

        .elementor-8 .elementor-element.elementor-element-6752ded .elementor-divider {
            padding-top: 15px;
            padding-bottom: 15px
        }

        .elementor-8 .elementor-element.elementor-element-1a43099.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-5ff46a4 {
            text-align: left
        }

        .elementor-8 .elementor-element.elementor-element-5ff46a4 .elementor-heading-title {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 15px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .9px
        }

        .elementor-8 .elementor-element.elementor-element-5ff46a4>.elementor-widget-container {
            margin: 0 0 -13px;
            padding: 0
        }

        .elementor-8 .elementor-element.elementor-element-2c4fa8d {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 15px;
            font-weight: 400
        }

        .elementor-8 .elementor-element.elementor-element-a8ea25c {
            margin-top: 30px;
            margin-bottom: 30px
        }

        .elementor-8 .elementor-element.elementor-element-4f0b6c6.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-c0a1063 .elementor-heading-title {
            font-family: Agne, Sans-serif;
            font-size: 41px;
            font-weight: 300
        }

        .elementor-8 .elementor-element.elementor-element-64572fd.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-7f64080 {
            --divider-border-style: solid;
            --divider-color: #000000;
            --divider-border-width: 2.5px
        }

        .elementor-8 .elementor-element.elementor-element-7f64080 .elementor-divider-separator {
            width: 100%
        }

        .elementor-8 .elementor-element.elementor-element-7f64080 .elementor-divider {
            padding-top: 15px;
            padding-bottom: 15px
        }

        .elementor-8 .elementor-element.elementor-element-6bc76de.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-abdde37 {
            text-align: left
        }

        .elementor-8 .elementor-element.elementor-element-abdde37 .elementor-heading-title {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 15px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .9px
        }

        .elementor-8 .elementor-element.elementor-element-abdde37>.elementor-widget-container {
            margin: 0 0 -13px;
            padding: 0
        }

        .elementor-8 .elementor-element.elementor-element-32e513e {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 15px;
            font-weight: 400
        }

        .elementor-8 .elementor-element.elementor-element-e73e1ce.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-882659c .elementor-heading-title {
            font-family: Agne, Sans-serif;
            font-size: 41px;
            font-weight: 300
        }

        .elementor-8 .elementor-element.elementor-element-df23d16.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-b3c32bc {
            --divider-border-style: solid;
            --divider-color: #000000;
            --divider-border-width: 2.5px
        }

        .elementor-8 .elementor-element.elementor-element-b3c32bc .elementor-divider-separator {
            width: 100%
        }

        .elementor-8 .elementor-element.elementor-element-b3c32bc .elementor-divider {
            padding-top: 15px;
            padding-bottom: 15px
        }

        .elementor-8 .elementor-element.elementor-element-9b0a7fe.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-876ded5 {
            text-align: left
        }

        .elementor-8 .elementor-element.elementor-element-876ded5 .elementor-heading-title {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 15px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .9px
        }

        .elementor-8 .elementor-element.elementor-element-876ded5>.elementor-widget-container {
            margin: 0 0 -13px;
            padding: 0
        }

        .elementor-8 .elementor-element.elementor-element-d910578 {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 15px;
            font-weight: 400
        }

        .elementor-8 .elementor-element.elementor-element-a43bfc7.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-13247e9 .elementor-heading-title {
            font-family: Agne, Sans-serif;
            font-size: 41px;
            font-weight: 300
        }

        .elementor-8 .elementor-element.elementor-element-30839f0.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-327f0e2 {
            --divider-border-style: solid;
            --divider-color: #000000;
            --divider-border-width: 2.5px
        }

        .elementor-8 .elementor-element.elementor-element-327f0e2 .elementor-divider-separator {
            width: 100%
        }

        .elementor-8 .elementor-element.elementor-element-327f0e2 .elementor-divider {
            padding-top: 15px;
            padding-bottom: 15px
        }

        .elementor-8 .elementor-element.elementor-element-06149e0.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-03d66e6 {
            text-align: left
        }

        .elementor-8 .elementor-element.elementor-element-03d66e6 .elementor-heading-title {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 15px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .9px
        }

        .elementor-8 .elementor-element.elementor-element-03d66e6>.elementor-widget-container {
            margin: 0 0 -13px;
            padding: 0
        }

        .elementor-8 .elementor-element.elementor-element-572d34a {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 15px;
            font-weight: 400
        }

        .elementor-8 .elementor-element.elementor-element-b8794ce {
            margin-top: 30px;
            margin-bottom: 30px
        }

        .elementor-8 .elementor-element.elementor-element-48fe636.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-a0b0761 .elementor-heading-title {
            font-family: Agne, Sans-serif;
            font-size: 41px;
            font-weight: 300
        }

        .elementor-8 .elementor-element.elementor-element-3e96cda.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-88de682 {
            --divider-border-style: solid;
            --divider-color: #000000;
            --divider-border-width: 2.5px
        }

        .elementor-8 .elementor-element.elementor-element-88de682 .elementor-divider-separator {
            width: 100%
        }

        .elementor-8 .elementor-element.elementor-element-88de682 .elementor-divider {
            padding-top: 15px;
            padding-bottom: 15px
        }

        .elementor-8 .elementor-element.elementor-element-9f28625.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-b370e54 {
            text-align: left
        }

        .elementor-8 .elementor-element.elementor-element-b370e54 .elementor-heading-title {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 15px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .9px
        }

        .elementor-8 .elementor-element.elementor-element-b370e54>.elementor-widget-container {
            margin: 0 0 -13px;
            padding: 0
        }

        .elementor-8 .elementor-element.elementor-element-8173b35 {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 15px;
            font-weight: 400
        }

        .elementor-8 .elementor-element.elementor-element-773c659.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-f7ed7b4 .elementor-heading-title {
            font-family: Agne, Sans-serif;
            font-size: 41px;
            font-weight: 300
        }

        .elementor-8 .elementor-element.elementor-element-e90d6cd.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-09b2ae2 {
            --divider-border-style: solid;
            --divider-color: #000000;
            --divider-border-width: 2.5px
        }

        .elementor-8 .elementor-element.elementor-element-09b2ae2 .elementor-divider-separator {
            width: 100%
        }

        .elementor-8 .elementor-element.elementor-element-09b2ae2 .elementor-divider {
            padding-top: 15px;
            padding-bottom: 15px
        }

        .elementor-8 .elementor-element.elementor-element-1199cbe.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-3388a85 {
            text-align: left
        }

        .elementor-8 .elementor-element.elementor-element-3388a85 .elementor-heading-title {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 15px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .9px
        }

        .elementor-8 .elementor-element.elementor-element-3388a85>.elementor-widget-container {
            margin: 0 0 -13px;
            padding: 0
        }

        .elementor-8 .elementor-element.elementor-element-b181332 {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 15px;
            font-weight: 400
        }

        .elementor-8 .elementor-element.elementor-element-8f63fc9:not(.elementor-motion-effects-element-type-background),
        .elementor-8 .elementor-element.elementor-element-8f63fc9>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-image: url("images/Grupo-108.png");
            background-position: 94px 1px;
            background-repeat: no-repeat;
            background-size: 20% auto
        }

        .elementor-8 .elementor-element.elementor-element-8f63fc9 {
            transition: background .3s, border .3s, border-radius .3s, box-shadow .3s
        }

        .elementor-8 .elementor-element.elementor-element-e82c935.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-5b8de70 {
            text-align: left
        }

        .elementor-8 .elementor-element.elementor-element-5b8de70 .elementor-heading-title {
            color: var(--e-global-color-accent);
            font-family: Agne, Sans-serif;
            font-size: 18px;
            font-weight: 300;
            letter-spacing: .9px
        }

        .elementor-8 .elementor-element.elementor-element-5b8de70>.elementor-widget-container {
            padding: 0
        }

        .elementor-8 .elementor-element.elementor-element-fe57896>.elementor-container {
            max-width: 1920px
        }

        .elementor-8 .elementor-element.elementor-element-fe57896>.elementor-container>.elementor-column>.elementor-widget-wrap {
            align-content: flex-start;
            align-items: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-fe57896 {
            transition: background .3s, border .3s, border-radius .3s, box-shadow .3s;
            padding: 0;
            z-index: 2
        }

        .elementor-8 .elementor-element.elementor-element-d4be4c4.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-end;
            align-items: flex-end
        }

        .elementor-8 .elementor-element.elementor-element-d4be4c4>.elementor-element-populated {
            padding: 100px 0
        }

        .elementor-8 .elementor-element.elementor-element-eaca9d8 {
            --divider-border-style: solid;
            --divider-color: var(--e-global-color-accent);
            --divider-border-width: 2.5px;
            z-index: 2
        }

        .elementor-8 .elementor-element.elementor-element-eaca9d8 .elementor-divider-separator {
            width: 100%
        }

        .elementor-8 .elementor-element.elementor-element-eaca9d8 .elementor-divider {
            padding-top: 15px;
            padding-bottom: 15px
        }

        .elementor-8 .elementor-element.elementor-element-eaca9d8>.elementor-widget-container {
            margin: 0 -16px 0 0
        }

        .elementor-8 .elementor-element.elementor-element-11099a5.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-end;
            align-items: flex-end
        }

        .elementor-8 .elementor-element.elementor-element-11099a5.elementor-column>.elementor-widget-wrap {
            justify-content: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-11099a5:not(.elementor-motion-effects-element-type-background)>.elementor-widget-wrap,
        .elementor-8 .elementor-element.elementor-element-11099a5>.elementor-widget-wrap>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-image: url("images/parla-1.png");
            background-position: 0 -31px;
            background-repeat: no-repeat;
            background-size: contain
        }

        .elementor-8 .elementor-element.elementor-element-11099a5>.elementor-element-populated {
            transition: background .3s, border .3s, border-radius .3s, box-shadow .3s;
            margin: 0;
            --e-column-margin-right: 0px;
            --e-column-margin-left: 0px;
            padding: 100px 10px
        }

        .elementor-8 .elementor-element.elementor-element-a78a72e {
            text-align: left
        }

        .elementor-8 .elementor-element.elementor-element-a78a72e .elementor-heading-title {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 21px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .9px
        }

        .elementor-8 .elementor-element.elementor-element-a78a72e>.elementor-widget-container {
            padding: 0 0 0 42px
        }

        .elementor-8 .elementor-element.elementor-element-f47ba8e.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: flex-end;
            align-items: flex-end
        }

        .elementor-8 .elementor-element.elementor-element-f47ba8e>.elementor-element-populated {
            padding: 100px 0
        }

        .elementor-8 .elementor-element.elementor-element-e8321b1 {
            --divider-border-style: solid;
            --divider-color: var(--e-global-color-d32e871);
            --divider-border-width: 2.5px
        }

        .elementor-8 .elementor-element.elementor-element-e8321b1 .elementor-divider-separator {
            width: 100%;
            margin: 0 auto;
            margin-left: 0
        }

        .elementor-8 .elementor-element.elementor-element-e8321b1 .elementor-divider {
            text-align: left;
            padding-top: 15px;
            padding-bottom: 15px
        }

        .elementor-8 .elementor-element.elementor-element-2c4a3d0.elementor-column>.elementor-widget-wrap {
            justify-content: flex-start
        }

        .elementor-8 .elementor-element.elementor-element-2c4a3d0>.elementor-element-populated {
            margin: 50px 50px 50px 0;
            --e-column-margin-right: 050px;
            --e-column-margin-left: 0px
        }

        .elementor-8 .elementor-element.elementor-element-78c5e82:not(.elementor-motion-effects-element-type-background),
        .elementor-8 .elementor-element.elementor-element-78c5e82>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: #f8f8f8
        }

        .elementor-8 .elementor-element.elementor-element-78c5e82 {
            border-radius: 30px 30px 30px 30px
        }

        .elementor-8 .elementor-element.elementor-element-78c5e82 {
            transition: background .3s, border .3s, border-radius .3s, box-shadow .3s
        }

        .elementor-8 .elementor-element.elementor-element-d225f85>.elementor-element-populated {
            padding: 50px 30px
        }

        .elementor-8 .elementor-element.elementor-element-e99d4ac {
            text-align: left
        }

        .elementor-8 .elementor-element.elementor-element-e99d4ac .elementor-heading-title {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 16px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .9px
        }

        .elementor-8 .elementor-element.elementor-element-e99d4ac>.elementor-widget-container {
            padding: 0
        }

        .elementor-8 .elementor-element.elementor-element-79d9e2f img {
            width: 45%
        }

        .elementor-8 .elementor-element.elementor-element-79d9e2f {
            width: 30.126%;
            max-width: 30.126%;
            top: 25px
        }

        body:not(.rtl) .elementor-8 .elementor-element.elementor-element-79d9e2f {
            left: -37px
        }

        body.rtl .elementor-8 .elementor-element.elementor-element-79d9e2f {
            right: -37px
        }

        .elementor-8 .elementor-element.elementor-element-38ffc2f:not(.elementor-motion-effects-element-type-background),
        .elementor-8 .elementor-element.elementor-element-38ffc2f>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: #f8f8f8
        }

        .elementor-8 .elementor-element.elementor-element-38ffc2f {
            border-radius: 30px 30px 30px 30px
        }

        .elementor-8 .elementor-element.elementor-element-38ffc2f {
            transition: background .3s, border .3s, border-radius .3s, box-shadow .3s
        }

        .elementor-8 .elementor-element.elementor-element-b8141d5>.elementor-element-populated {
            padding: 50px
        }

        .elementor-8 .elementor-element.elementor-element-255d9e0 {
            text-align: left
        }

        .elementor-8 .elementor-element.elementor-element-255d9e0 .elementor-heading-title {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 16px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .9px
        }

        .elementor-8 .elementor-element.elementor-element-255d9e0>.elementor-widget-container {
            padding: 0
        }

        .elementor-8 .elementor-element.elementor-element-ffdc4b4 img {
            width: 45%
        }

        .elementor-8 .elementor-element.elementor-element-ffdc4b4 {
            width: 28%;
            max-width: 28%;
            top: 65px
        }

        body:not(.rtl) .elementor-8 .elementor-element.elementor-element-ffdc4b4 {
            left: -43px
        }

        body.rtl .elementor-8 .elementor-element.elementor-element-ffdc4b4 {
            right: -43px
        }

        .elementor-8 .elementor-element.elementor-element-92b90d7>.elementor-element-populated {
            margin: 50px 0 50px 50px;
            --e-column-margin-right: 0px;
            --e-column-margin-left: 50px
        }

        .elementor-8 .elementor-element.elementor-element-aa489cf:not(.elementor-motion-effects-element-type-background),
        .elementor-8 .elementor-element.elementor-element-aa489cf>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: #f8f8f8
        }

        .elementor-8 .elementor-element.elementor-element-aa489cf {
            border-radius: 30px 30px 30px 30px
        }

        .elementor-8 .elementor-element.elementor-element-aa489cf {
            transition: background .3s, border .3s, border-radius .3s, box-shadow .3s
        }

        .elementor-8 .elementor-element.elementor-element-28f1e62>.elementor-element-populated {
            padding: 50px
        }

        .elementor-8 .elementor-element.elementor-element-c6d3ca7 {
            text-align: left
        }

        .elementor-8 .elementor-element.elementor-element-c6d3ca7 .elementor-heading-title {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 16px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .9px
        }

        .elementor-8 .elementor-element.elementor-element-c6d3ca7>.elementor-widget-container {
            padding: 0
        }

        .elementor-8 .elementor-element.elementor-element-eef7013 img {
            width: 45%
        }

        .elementor-8 .elementor-element.elementor-element-eef7013 {
            width: 30.126%;
            max-width: 30.126%;
            top: 25px
        }

        body:not(.rtl) .elementor-8 .elementor-element.elementor-element-eef7013 {
            left: -37px
        }

        body.rtl .elementor-8 .elementor-element.elementor-element-eef7013 {
            right: -37px
        }

        .elementor-8 .elementor-element.elementor-element-2e882ca {
            margin-top: 0;
            margin-bottom: -237px;
            z-index: 9
        }

        .elementor-8 .elementor-element.elementor-element-25fd90e {
            text-align: left
        }

        .elementor-8 .elementor-element.elementor-element-25fd90e .elementor-heading-title {
            color: #000;
            font-family: Agne, Sans-serif;
            font-size: 108px;
            font-weight: 800;
            text-transform: lowercase;
            letter-spacing: .9px
        }

        .elementor-8 .elementor-element.elementor-element-25fd90e>.elementor-widget-container {
            padding: 0
        }

        .elementor-8 .elementor-element.elementor-element-a3f32e8 {
            margin-top: 0;
            margin-bottom: 0;
            padding: 0
        }

        .elementor-8 .elementor-element.elementor-element-8aed156>.elementor-element-populated {
            margin: 0 0 0 -8px;
            --e-column-margin-right: 0px;
            --e-column-margin-left: -8px
        }

        .elementor-8 .elementor-element.elementor-element-13706a1 .elementor-heading-title {
            color: #000;
            font-family: Agne, Sans-serif;
            font-size: 21px;
            font-weight: 800;
            letter-spacing: .9px
        }

        .elementor-8 .elementor-element.elementor-element-13706a1>.elementor-widget-container {
            padding: 0
        }

        .elementor-8 .elementor-element.elementor-element-ee31981 {
            --divider-border-style: solid;
            --divider-color: #000000;
            --divider-border-width: 2.5px
        }

        .elementor-8 .elementor-element.elementor-element-ee31981 .elementor-divider-separator {
            width: 100%
        }

        .elementor-8 .elementor-element.elementor-element-ee31981 .elementor-divider {
            padding-top: 15px;
            padding-bottom: 15px
        }

        .elementor-8 .elementor-element.elementor-element-f9a93e7 {
            text-align: left
        }

        .elementor-8 .elementor-element.elementor-element-f9a93e7 .elementor-heading-title {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 15px;
            font-weight: 400;
            text-transform: uppercase;
            letter-spacing: .9px
        }

        .elementor-8 .elementor-element.elementor-element-f9a93e7>.elementor-widget-container {
            padding: 0
        }

        .elementor-8 .elementor-element.elementor-element-677515b .elementor-field-group {
            padding-right: calc(10px/2);
            padding-left: calc(10px/2);
            margin-bottom: 10px
        }

        .elementor-8 .elementor-element.elementor-element-677515b .elementor-form-fields-wrapper {
            margin-left: calc(-10px/2);
            margin-right: calc(-10px/2);
            margin-bottom: -10px
        }

        body .elementor-8 .elementor-element.elementor-element-677515b .elementor-labels-above .elementor-field-group>label {
            padding-bottom: 0
        }

        .elementor-8 .elementor-element.elementor-element-677515b .elementor-field-group>label {
            color: var(--e-global-color-accent)
        }

        .elementor-8 .elementor-element.elementor-element-677515b .elementor-field-group>label {
            font-family: "Noto Sans", Sans-serif;
            font-size: 12px;
            font-weight: 400
        }

        .elementor-8 .elementor-element.elementor-element-677515b .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper) {
            background-color: #fff;
            border-color: #000;
            border-width: 0 0 2px
        }

        .elementor-8 .elementor-element.elementor-element-677515b .e-form__buttons__wrapper__button-next {
            color: #fff
        }

        .elementor-8 .elementor-element.elementor-element-677515b .elementor-button[type=submit] {
            color: #fff
        }

        .elementor-8 .elementor-element.elementor-element-677515b .elementor-button[type=submit] svg * {
            fill: #ffffff
        }

        .elementor-8 .elementor-element.elementor-element-677515b .e-form__buttons__wrapper__button-previous {
            color: #fff
        }

        .elementor-8 .elementor-element.elementor-element-677515b .e-form__buttons__wrapper__button-next:hover {
            color: #fff
        }

        .elementor-8 .elementor-element.elementor-element-677515b .elementor-button[type=submit]:hover {
            color: #fff
        }

        .elementor-8 .elementor-element.elementor-element-677515b .elementor-button[type=submit]:hover svg * {
            fill: #ffffff
        }

        .elementor-8 .elementor-element.elementor-element-677515b .e-form__buttons__wrapper__button-previous:hover {
            color: #fff
        }

        .elementor-8 .elementor-element.elementor-element-677515b .elementor-button {
            border-radius: 30px 30px 30px 30px
        }

        .elementor-8 .elementor-element.elementor-element-677515b .elementor-message {
            font-family: "Noto Sans", Sans-serif;
            font-weight: 400
        }

        .elementor-8 .elementor-element.elementor-element-677515b {
            --e-form-steps-indicators-spacing: 20px;
            --e-form-steps-indicator-padding: 30px;
            --e-form-steps-indicator-inactive-secondary-color: #ffffff;
            --e-form-steps-indicator-active-secondary-color: #ffffff;
            --e-form-steps-indicator-completed-secondary-color: #ffffff;
            --e-form-steps-divider-width: 1px;
            --e-form-steps-divider-gap: 10px
        }

        @media(max-width:1280px) and (min-width:768px) {
            .elementor-8 .elementor-element.elementor-element-433f175 {
                width: 55%
            }

            .elementor-8 .elementor-element.elementor-element-08e1fd2 {
                width: 39%
            }

            .elementor-8 .elementor-element.elementor-element-4ccaa76 {
                width: 10%
            }

            .elementor-8 .elementor-element.elementor-element-fa62198 {
                width: 48%
            }

            .elementor-8 .elementor-element.elementor-element-c5172f8 {
                width: 48%
            }

            .elementor-8 .elementor-element.elementor-element-d4be4c4 {
                width: 4%
            }

            .elementor-8 .elementor-element.elementor-element-11099a5 {
                width: 43%
            }

            .elementor-8 .elementor-element.elementor-element-f47ba8e {
                width: 52%
            }
        }

        @media(max-width:1280px) {
            body:not(.rtl) .elementor-8 .elementor-element.elementor-element-21646fc {
                left: 780px
            }

            body.rtl .elementor-8 .elementor-element.elementor-element-21646fc {
                right: 780px
            }

            .elementor-8 .elementor-element.elementor-element-21646fc {
                top: -57px
            }

            .elementor-8 .elementor-element.elementor-element-ec21a9c:not(.elementor-motion-effects-element-type-background)>.elementor-widget-wrap,
            .elementor-8 .elementor-element.elementor-element-ec21a9c>.elementor-widget-wrap>.elementor-motion-effects-container>.elementor-motion-effects-layer {
                background-position: 39px -38px
            }

            .elementor-8 .elementor-element.elementor-element-3bea086 img {
                width: 61px
            }

            .elementor-8 .elementor-element.elementor-element-54f1b6f:not(.elementor-motion-effects-element-type-background)>.elementor-widget-wrap,
            .elementor-8 .elementor-element.elementor-element-54f1b6f>.elementor-widget-wrap>.elementor-motion-effects-container>.elementor-motion-effects-layer {
                background-position: 31px -15px
            }

            .elementor-8 .elementor-element.elementor-element-11099a5:not(.elementor-motion-effects-element-type-background)>.elementor-widget-wrap,
            .elementor-8 .elementor-element.elementor-element-11099a5>.elementor-widget-wrap>.elementor-motion-effects-container>.elementor-motion-effects-layer {
                background-position: initial;
                background-size: contain
            }

            .elementor-8 .elementor-element.elementor-element-a78a72e>.elementor-widget-container {
                padding: 2px 2px 2px 20px
            }

            .elementor-8 .elementor-element.elementor-element-79d9e2f {
                width: 50.15px;
                max-width: 50.15px;
                top: 42px
            }

            body:not(.rtl) .elementor-8 .elementor-element.elementor-element-79d9e2f {
                left: 6px
            }

            body.rtl .elementor-8 .elementor-element.elementor-element-79d9e2f {
                right: 6px
            }

            body:not(.rtl) .elementor-8 .elementor-element.elementor-element-ffdc4b4 {
                left: -34px
            }

            body.rtl .elementor-8 .elementor-element.elementor-element-ffdc4b4 {
                right: -34px
            }

            .elementor-8 .elementor-element.elementor-element-ffdc4b4 {
                top: 44px
            }

            .elementor-8 .elementor-element.elementor-element-eef7013 {
                width: 50.15px;
                max-width: 50.15px;
                top: 36px
            }

            body:not(.rtl) .elementor-8 .elementor-element.elementor-element-eef7013 {
                left: 3px
            }

            body.rtl .elementor-8 .elementor-element.elementor-element-eef7013 {
                right: 3px
            }
        }

        @media(max-width:1024px) {
            body:not(.rtl) .elementor-8 .elementor-element.elementor-element-21646fc {
                left: 560px
            }

            body.rtl .elementor-8 .elementor-element.elementor-element-21646fc {
                right: 560px
            }

            .elementor-8 .elementor-element.elementor-element-21646fc {
                top: -63px
            }

            .elementor-8 .elementor-element.elementor-element-ec21a9c:not(.elementor-motion-effects-element-type-background)>.elementor-widget-wrap,
            .elementor-8 .elementor-element.elementor-element-ec21a9c>.elementor-widget-wrap>.elementor-motion-effects-container>.elementor-motion-effects-layer {
                background-position: 0 0
            }

            .elementor-8 .elementor-element.elementor-element-54f1b6f:not(.elementor-motion-effects-element-type-background)>.elementor-widget-wrap,
            .elementor-8 .elementor-element.elementor-element-54f1b6f>.elementor-widget-wrap>.elementor-motion-effects-container>.elementor-motion-effects-layer {
                background-position: 0 0
            }

            .elementor-8 .elementor-element.elementor-element-8f63fc9:not(.elementor-motion-effects-element-type-background),
            .elementor-8 .elementor-element.elementor-element-8f63fc9>.elementor-motion-effects-container>.elementor-motion-effects-layer {
                background-position: 0 0
            }

            .elementor-8 .elementor-element.elementor-element-11099a5:not(.elementor-motion-effects-element-type-background)>.elementor-widget-wrap,
            .elementor-8 .elementor-element.elementor-element-11099a5>.elementor-widget-wrap>.elementor-motion-effects-container>.elementor-motion-effects-layer {
                background-position: 0 0
            }
        }

        @media(max-width:767px) {

            .elementor-8 .elementor-element.elementor-element-7a3d40e:not(.elementor-motion-effects-element-type-background),
            .elementor-8 .elementor-element.elementor-element-7a3d40e>.elementor-motion-effects-container>.elementor-motion-effects-layer {
                background-position: bottom center;
                background-size: 348px auto
            }

            .elementor-8 .elementor-element.elementor-element-8bde659 {
                width: 100%
            }

            .elementor-8 .elementor-element.elementor-element-1feaa80 .elementor-heading-title {
                font-size: 29px
            }

            .elementor-8 .elementor-element.elementor-element-d688ed0 {
                font-size: 16px
            }

            .elementor-8 .elementor-element.elementor-element-28b1dbe {
                --spacer-size: 44vh
            }

            .elementor-8 .elementor-element.elementor-element-63b3eb8 {
                width: 100%
            }

            body:not(.rtl) .elementor-8 .elementor-element.elementor-element-21646fc {
                left: 243px
            }

            body.rtl .elementor-8 .elementor-element.elementor-element-21646fc {
                right: 243px
            }

            .elementor-8 .elementor-element.elementor-element-21646fc {
                top: -337px
            }

            .elementor-8 .elementor-element.elementor-element-be8dd25>.elementor-container {
                max-width: 1600px
            }

            .elementor-8 .elementor-element.elementor-element-be8dd25:not(.elementor-motion-effects-element-type-background),
            .elementor-8 .elementor-element.elementor-element-be8dd25>.elementor-motion-effects-container>.elementor-motion-effects-layer {
                background-size: 303px auto
            }

            .elementor-8 .elementor-element.elementor-element-be8dd25 {
                padding: 20px
            }

            .elementor-8 .elementor-element.elementor-element-5489aff>.elementor-element-populated {
                padding: 20px
            }

            .elementor-8 .elementor-element.elementor-element-5b95a81>.elementor-widget-container {
                margin: 0;
                padding: 0 0 140px
            }

            .elementor-8 .elementor-element.elementor-element-ec21a9c:not(.elementor-motion-effects-element-type-background)>.elementor-widget-wrap,
            .elementor-8 .elementor-element.elementor-element-ec21a9c>.elementor-widget-wrap>.elementor-motion-effects-container>.elementor-motion-effects-layer {
                background-position: 0 15px;
                background-size: 306px auto
            }

            .elementor-8 .elementor-element.elementor-element-1ee2976 {
                width: 50%
            }

            .elementor-8 .elementor-element.elementor-element-26d7591 {
                width: 50%
            }

            .elementor-8 .elementor-element.elementor-element-29d4937 {
                width: 50%
            }

            .elementor-8 .elementor-element.elementor-element-5e1058e {
                width: 50%
            }

            .elementor-8 .elementor-element.elementor-element-08a75a2 {
                text-align: center
            }

            .elementor-8 .elementor-element.elementor-element-fa62198>.elementor-element-populated {
                margin: 20px;
                --e-column-margin-right: 20px;
                --e-column-margin-left: 20px
            }

            .elementor-8 .elementor-element.elementor-element-ac2c47f>.elementor-element-populated {
                padding: 30px
            }

            .elementor-8 .elementor-element.elementor-element-c5172f8>.elementor-element-populated {
                margin: 20px;
                --e-column-margin-right: 20px;
                --e-column-margin-left: 20px
            }

            .elementor-8 .elementor-element.elementor-element-d6ea2a0>.elementor-element-populated {
                padding: 30px
            }

            .elementor-8 .elementor-element.elementor-element-54f1b6f:not(.elementor-motion-effects-element-type-background)>.elementor-widget-wrap,
            .elementor-8 .elementor-element.elementor-element-54f1b6f>.elementor-widget-wrap>.elementor-motion-effects-container>.elementor-motion-effects-layer {
                background-position: 0 0;
                background-size: 341px auto
            }

            .elementor-8 .elementor-element.elementor-element-54f1b6f>.elementor-element-populated {
                padding: 50px
            }

            .elementor-8 .elementor-element.elementor-element-d9e88c2 {
                text-align: center
            }

            .elementor-8 .elementor-element.elementor-element-d9e88c2>.elementor-widget-container {
                padding: 0
            }

            .elementor-8 .elementor-element.elementor-element-8f63fc9:not(.elementor-motion-effects-element-type-background),
            .elementor-8 .elementor-element.elementor-element-8f63fc9>.elementor-motion-effects-container>.elementor-motion-effects-layer {
                background-position: 0 0
            }

            .elementor-8 .elementor-element.elementor-element-d4be4c4>.elementor-element-populated {
                padding: 0
            }

            .elementor-8 .elementor-element.elementor-element-eaca9d8 .elementor-divider-separator {
                width: 92%
            }

            .elementor-8 .elementor-element.elementor-element-11099a5:not(.elementor-motion-effects-element-type-background)>.elementor-widget-wrap,
            .elementor-8 .elementor-element.elementor-element-11099a5>.elementor-widget-wrap>.elementor-motion-effects-container>.elementor-motion-effects-layer {
                background-position: 0 0;
                background-size: contain
            }

            .elementor-8 .elementor-element.elementor-element-11099a5>.elementor-element-populated {
                padding: 50px
            }

            .elementor-8 .elementor-element.elementor-element-a78a72e {
                text-align: center
            }

            .elementor-8 .elementor-element.elementor-element-f47ba8e>.elementor-element-populated {
                padding: 0
            }

            .elementor-8 .elementor-element.elementor-element-e8321b1 .elementor-divider-separator {
                width: 96%
            }

            .elementor-8 .elementor-element.elementor-element-2c4a3d0 {
                width: 100%
            }

            .elementor-8 .elementor-element.elementor-element-2c4a3d0>.elementor-element-populated {
                margin: 20px;
                --e-column-margin-right: 20px;
                --e-column-margin-left: 20px
            }

            .elementor-8 .elementor-element.elementor-element-79d9e2f {
                width: 91.337px;
                max-width: 91.337px;
                top: -11px
            }

            body:not(.rtl) .elementor-8 .elementor-element.elementor-element-79d9e2f {
                left: -5px
            }

            body.rtl .elementor-8 .elementor-element.elementor-element-79d9e2f {
                right: -5px
            }

            .elementor-8 .elementor-element.elementor-element-2b7e653>.elementor-element-populated {
                margin: 20px;
                --e-column-margin-right: 20px;
                --e-column-margin-left: 20px
            }

            .elementor-8 .elementor-element.elementor-element-ffdc4b4 img {
                width: 94%
            }

            .elementor-8 .elementor-element.elementor-element-ffdc4b4 {
                width: 97.875px;
                max-width: 97.875px;
                top: -32px
            }

            body:not(.rtl) .elementor-8 .elementor-element.elementor-element-ffdc4b4 {
                left: -9px
            }

            body.rtl .elementor-8 .elementor-element.elementor-element-ffdc4b4 {
                right: -9px
            }

            .elementor-8 .elementor-element.elementor-element-92b90d7>.elementor-element-populated {
                margin: 20px;
                --e-column-margin-right: 20px;
                --e-column-margin-left: 20px
            }

            .elementor-8 .elementor-element.elementor-element-eef7013 {
                width: 91.337px;
                max-width: 91.337px;
                top: -27px
            }

            body:not(.rtl) .elementor-8 .elementor-element.elementor-element-eef7013 {
                left: 6px
            }

            body.rtl .elementor-8 .elementor-element.elementor-element-eef7013 {
                right: 6px
            }

            .elementor-8 .elementor-element.elementor-element-2e882ca {
                margin-top: 100px;
                margin-bottom: 0
            }

            .elementor-8 .elementor-element.elementor-element-25fd90e .elementor-heading-title {
                font-size: 69px
            }

            .elementor-8 .elementor-element.elementor-element-8aed156>.elementor-element-populated {
                margin: 0;
                --e-column-margin-right: 0px;
                --e-column-margin-left: 0px
            }

            .elementor-8 .elementor-element.elementor-element-677515b>.elementor-widget-container {
                padding: 10px
            }
        }

        @media(min-width:768px) {
            .elementor-8 .elementor-element.elementor-element-8bde659 {
                width: 44.411%
            }

            .elementor-8 .elementor-element.elementor-element-722f52b {
                width: 55.589%
            }

            .elementor-8 .elementor-element.elementor-element-93c128d {
                width: 49.804%
            }

            .elementor-8 .elementor-element.elementor-element-5489aff {
                width: 50.152%
            }

            .elementor-8 .elementor-element.elementor-element-433f175 {
                width: 51%
            }

            .elementor-8 .elementor-element.elementor-element-08e1fd2 {
                width: 28.302%
            }

            .elementor-8 .elementor-element.elementor-element-4ccaa76 {
                width: 20.345%
            }

            .elementor-8 .elementor-element.elementor-element-fa62198 {
                width: 52%
            }

            .elementor-8 .elementor-element.elementor-element-ac2c47f {
                width: 47.826%
            }

            .elementor-8 .elementor-element.elementor-element-c5172f8 {
                width: 52%
            }

            .elementor-8 .elementor-element.elementor-element-d6ea2a0 {
                width: 47.826%
            }

            .elementor-8 .elementor-element.elementor-element-210bc54 {
                width: 17.497%
            }

            .elementor-8 .elementor-element.elementor-element-7bdff58 {
                width: 22.456%
            }

            .elementor-8 .elementor-element.elementor-element-2117d0c {
                width: 59.709%
            }

            .elementor-8 .elementor-element.elementor-element-3c11857 {
                width: 21.927%
            }

            .elementor-8 .elementor-element.elementor-element-dbdae3f {
                width: 19.123%
            }

            .elementor-8 .elementor-element.elementor-element-4b6a7ff {
                width: 58.612%
            }

            .elementor-8 .elementor-element.elementor-element-83e4fe8 {
                width: 18.763%
            }

            .elementor-8 .elementor-element.elementor-element-b0ff5f1 {
                width: 17.857%
            }

            .elementor-8 .elementor-element.elementor-element-1a43099 {
                width: 63.042%
            }

            .elementor-8 .elementor-element.elementor-element-4f0b6c6 {
                width: 18.763%
            }

            .elementor-8 .elementor-element.elementor-element-64572fd {
                width: 22.287%
            }

            .elementor-8 .elementor-element.elementor-element-6bc76de {
                width: 58.612%
            }

            .elementor-8 .elementor-element.elementor-element-e73e1ce {
                width: 20.661%
            }

            .elementor-8 .elementor-element.elementor-element-df23d16 {
                width: 20.389%
            }

            .elementor-8 .elementor-element.elementor-element-9b0a7fe {
                width: 58.612%
            }

            .elementor-8 .elementor-element.elementor-element-a43bfc7 {
                width: 19.147%
            }

            .elementor-8 .elementor-element.elementor-element-30839f0 {
                width: 18.567%
            }

            .elementor-8 .elementor-element.elementor-element-06149e0 {
                width: 61.948%
            }

            .elementor-8 .elementor-element.elementor-element-48fe636 {
                width: 19.079%
            }

            .elementor-8 .elementor-element.elementor-element-3e96cda {
                width: 21.971%
            }

            .elementor-8 .elementor-element.elementor-element-9f28625 {
                width: 58.612%
            }

            .elementor-8 .elementor-element.elementor-element-773c659 {
                width: 20.978%
            }

            .elementor-8 .elementor-element.elementor-element-e90d6cd {
                width: 20.072%
            }

            .elementor-8 .elementor-element.elementor-element-1199cbe {
                width: 58.612%
            }

            .elementor-8 .elementor-element.elementor-element-5f98544 {
                width: 37.918%
            }

            .elementor-8 .elementor-element.elementor-element-e82c935 {
                width: 62.08%
            }

            .elementor-8 .elementor-element.elementor-element-d4be4c4 {
                width: 9%
            }

            .elementor-8 .elementor-element.elementor-element-11099a5 {
                width: 35.647%
            }

            .elementor-8 .elementor-element.elementor-element-f47ba8e {
                width: 55%
            }

            .elementor-8 .elementor-element.elementor-element-b7fd1e8 {
                width: 44.561%
            }

            .elementor-8 .elementor-element.elementor-element-b1f80b4 {
                width: 55.439%
            }

            .elementor-8 .elementor-element.elementor-element-8aed156 {
                width: 21.855%
            }

            .elementor-8 .elementor-element.elementor-element-02ef8a0 {
                width: 15.747%
            }

            .elementor-8 .elementor-element.elementor-element-6750b4c {
                width: 62.062%
            }
        }

        .elementor-8 .elementor-element.elementor-element-1feaa80 b {
            font-weight: 800
        }

        .elementor-8 .elementor-element.elementor-element-5b95a81 li::marker {
            font-size: .7rem;
            font-weight: bolder
        }

        .elementor-8 .elementor-element.elementor-element-5b95a81 ul {
            margin: 0;
            padding-left: 15px
        }

        .elementor-8 .elementor-element.elementor-element-9b0cf73 b {
            font-weight: 800
        }

        .elementor-8 .elementor-element.elementor-element-02d68bd b {
            font-weight: 800
        }

        .elementor-8 .elementor-element.elementor-element-b4e352f b {
            font-weight: 800
        }

        .elementor-8 .elementor-element.elementor-element-e505df0 b {
            font-weight: 800
        }

        .elementor-8 .elementor-element.elementor-element-469cee5 li::marker {
            font-size: .7rem;
            font-weight: bolder
        }

        .elementor-8 .elementor-element.elementor-element-469cee5 ul {
            margin: 0;
            padding-left: 15px
        }

        .elementor-8 .elementor-element.elementor-element-7ff9b97 li::marker {
            font-size: .7rem;
            font-weight: bolder
        }

        .elementor-8 .elementor-element.elementor-element-7ff9b97 ul {
            margin: 0;
            padding-left: 15px
        }

        .elementor-field-type-upload label {
            background: 0 0 !important;
            color: #3f7853 !important;
            font-size: 11px !important;
            padding: 13px 30px !important;
            margin: 5px 0 0 !important;
            border: 2px solid #3f7853;
            border-radius: 30px
        }

        .elementor-field-type-upload label+input {
            display: none
        }

        .elementor-field-type-upload label::before {
            content: "";
            padding: 10px;
            background-repeat: no-repeat;
            background-position: 0 12px;
            background-image: url("images/Grupo-100.png");
            background-size: 15px
        }

        .elementor-8 .elementor-element.elementor-element-677515b ::placeholder {
            padding: 0;
            margin: 0;
            color: #000 !important;
            opacity: 1
        }

        .elementor-8 .elementor-element.elementor-element-677515b input {
            padding: 0 !important
        }

        .elementor-8 .elementor-element.elementor-element-677515b textarea {
            padding: 10px 0 !important
        }

        @media (max-width:666px) {
            .elementor-field-type-upload label {
                background: 0 0 !important;
                color: #3f7853 !important;
                font-size: 9px !important;
                padding: 15px 10px !important;
                margin: 5px 0 0 !important;
                border: 2px solid #3f7853;
                border-radius: 30px
            }
        }

        @font-face {
            font-family: Agne;
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: url('fonts/Agne-Regular.ttf') format('truetype')
        }

        .elementor-12 .elementor-element.elementor-element-beb0bb2:not(.elementor-motion-effects-element-type-background),
        .elementor-12 .elementor-element.elementor-element-beb0bb2>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: #fff
        }

        .elementor-12 .elementor-element.elementor-element-beb0bb2 {
            transition: background .3s, border .3s, border-radius .3s, box-shadow .3s
        }

        .elementor-12 .elementor-element.elementor-element-7477741.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: center;
            align-items: center
        }

        .elementor-12 .elementor-element.elementor-element-7477741>.elementor-element-populated {
            padding: 20px
        }

        .elementor-12 .elementor-element.elementor-element-7cc612a img {
            width: 100%
        }

        .elementor-12 .elementor-element.elementor-element-064a297.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: center;
            align-items: center
        }

        .elementor-12 .elementor-element.elementor-element-6411232 .elementor-menu-toggle {
            margin: 0 auto;
            background-color: #fff
        }

        .elementor-12 .elementor-element.elementor-element-6411232 .elementor-nav-menu .elementor-item {
            font-family: "Noto Sans", Sans-serif;
            font-size: 12px;
            font-weight: 400;
            text-transform: uppercase
        }

        .elementor-12 .elementor-element.elementor-element-6411232 .elementor-nav-menu--main .elementor-item {
            color: #000;
            fill: #000000
        }

        .elementor-12 .elementor-element.elementor-element-6411232 {
            --e-nav-menu-divider-content: "";
            --e-nav-menu-divider-style: solid
        }

        .elementor-12 .elementor-element.elementor-element-6411232 .elementor-menu-toggle,
        .elementor-12 .elementor-element.elementor-element-6411232 .elementor-nav-menu--dropdown a {
            color: #000
        }

        .elementor-12 .elementor-element.elementor-element-6411232 .elementor-nav-menu--dropdown {
            background-color: #fff
        }

        .elementor-12 .elementor-element.elementor-element-6411232 .elementor-menu-toggle:hover,
        .elementor-12 .elementor-element.elementor-element-6411232 .elementor-nav-menu--dropdown a.elementor-item-active,
        .elementor-12 .elementor-element.elementor-element-6411232 .elementor-nav-menu--dropdown a.highlighted,
        .elementor-12 .elementor-element.elementor-element-6411232 .elementor-nav-menu--dropdown a:hover {
            color: #a6351b
        }

        .elementor-12 .elementor-element.elementor-element-6411232 .elementor-nav-menu--dropdown a.elementor-item-active,
        .elementor-12 .elementor-element.elementor-element-6411232 .elementor-nav-menu--dropdown a.highlighted,
        .elementor-12 .elementor-element.elementor-element-6411232 .elementor-nav-menu--dropdown a:hover {
            background-color: #fff
        }

        .elementor-12 .elementor-element.elementor-element-6411232 .elementor-nav-menu--dropdown a.elementor-item-active {
            color: #a6351b;
            background-color: #fff
        }

        .elementor-12 .elementor-element.elementor-element-6411232 .elementor-nav-menu--dropdown .elementor-item {
            font-family: "Noto Sans", Sans-serif;
            font-weight: 500
        }

        .elementor-12 .elementor-element.elementor-element-6411232 div.elementor-menu-toggle {
            color: #a6351b
        }

        .elementor-12 .elementor-element.elementor-element-6411232 div.elementor-menu-toggle svg {
            fill: #A6351B
        }

        @media(max-width:767px) {
            .elementor-12 .elementor-element.elementor-element-7477741 {
                width: 70%
            }

            .elementor-12 .elementor-element.elementor-element-064a297 {
                width: 30%
            }

            .elementor-12 .elementor-element.elementor-element-064a297.elementor-column>.elementor-widget-wrap {
                justify-content: center
            }

            .elementor-12 .elementor-element.elementor-element-6411232 {
                --nav-menu-icon-size: 27px
            }
        }

        @media(min-width:768px) {
            .elementor-12 .elementor-element.elementor-element-7477741 {
                width: 17.631%
            }

            .elementor-12 .elementor-element.elementor-element-064a297 {
                width: 82.369%
            }
        }

        @media(max-width:1024px) and (min-width:768px) {
            .elementor-12 .elementor-element.elementor-element-7477741 {
                width: 40%
            }

            .elementor-12 .elementor-element.elementor-element-064a297 {
                width: 30%
            }
        }

        .elementor-27 .elementor-element.elementor-element-e782e22:not(.elementor-motion-effects-element-type-background),
        .elementor-27 .elementor-element.elementor-element-e782e22>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-image: url("images/Grupo-102.png");
            background-position: top left;
            background-repeat: no-repeat;
            background-size: contain
        }

        .elementor-27 .elementor-element.elementor-element-e782e22 {
            transition: background .3s, border .3s, border-radius .3s, box-shadow .3s;
            padding: 300px 0 0
        }

        .elementor-27 .elementor-element.elementor-element-b1830e1 .elementor-heading-title {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 17px;
            font-weight: 600;
            text-transform: uppercase
        }

        .elementor-27 .elementor-element.elementor-element-b94a39f {
            --divider-border-style: solid;
            --divider-color: #000;
            --divider-border-width: 2.5px
        }

        .elementor-27 .elementor-element.elementor-element-b94a39f .elementor-divider-separator {
            width: 69%
        }

        .elementor-27 .elementor-element.elementor-element-b94a39f .elementor-divider {
            padding-top: 2px;
            padding-bottom: 2px
        }

        .elementor-27 .elementor-element.elementor-element-36a9ae7 {
            color: #1a1a1a;
            font-family: "Noto Sans", Sans-serif;
            font-size: 14px;
            font-weight: 400
        }

        .elementor-27 .elementor-element.elementor-element-27db4b1 .elementor-heading-title {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 17px;
            font-weight: 600;
            text-transform: uppercase
        }

        .elementor-27 .elementor-element.elementor-element-bee15fd {
            --divider-border-style: solid;
            --divider-color: #000;
            --divider-border-width: 2.5px
        }

        .elementor-27 .elementor-element.elementor-element-bee15fd .elementor-divider-separator {
            width: 69%
        }

        .elementor-27 .elementor-element.elementor-element-bee15fd .elementor-divider {
            padding-top: 2px;
            padding-bottom: 2px
        }

        .elementor-27 .elementor-element.elementor-element-f32a2ad .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child) {
            padding-bottom: calc(11px/2)
        }

        .elementor-27 .elementor-element.elementor-element-f32a2ad .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:first-child) {
            margin-top: calc(11px/2)
        }

        .elementor-27 .elementor-element.elementor-element-f32a2ad .elementor-icon-list-icon i {
            color: #000
        }

        .elementor-27 .elementor-element.elementor-element-f32a2ad .elementor-icon-list-icon svg {
            fill: #000000
        }

        .elementor-27 .elementor-element.elementor-element-f32a2ad {
            --e-icon-list-icon-size: 14px;
            --e-icon-list-icon-align: center;
            --e-icon-list-icon-margin: 0 calc(var(--e-icon-list-icon-size, 1em) * 0.125)
        }

        .elementor-27 .elementor-element.elementor-element-f32a2ad .elementor-icon-list-text {
            color: #000
        }

        .elementor-27 .elementor-element.elementor-element-f32a2ad .elementor-icon-list-item>.elementor-icon-list-text,
        .elementor-27 .elementor-element.elementor-element-f32a2ad .elementor-icon-list-item>a {
            font-family: "Noto Sans", Sans-serif;
            font-size: 14px;
            font-weight: 400
        }

        .elementor-27 .elementor-element.elementor-element-edfef98 {
            text-align: left
        }

        .elementor-27 .elementor-element.elementor-element-edfef98 .elementor-heading-title {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-weight: 400
        }

        .elementor-27 .elementor-element.elementor-element-edfef98>.elementor-widget-container {
            padding: 0
        }

        .elementor-27 .elementor-element.elementor-element-2c0336b {
            text-align: left
        }

        .elementor-27 .elementor-element.elementor-element-2c0336b img {
            width: 38%
        }

        .elementor-27 .elementor-element.elementor-element-2c0336b>.elementor-widget-container {
            padding: 0 0 0 70px
        }

        .elementor-27 .elementor-element.elementor-element-969d19a img {
            width: 100%
        }

        .elementor-27 .elementor-element.elementor-element-969d19a {
            width: 3%;
            max-width: 3%;
            bottom: 30px
        }

        body:not(.rtl) .elementor-27 .elementor-element.elementor-element-969d19a {
            right: 30px
        }

        body.rtl .elementor-27 .elementor-element.elementor-element-969d19a {
            left: 30px
        }

        .elementor-27 .elementor-element.elementor-element-456cb83 .elementor-heading-title {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-size: 17px;
            font-weight: 600;
            text-transform: uppercase
        }

        .elementor-27 .elementor-element.elementor-element-af72f94 {
            --divider-border-style: solid;
            --divider-color: #000;
            --divider-border-width: 2.5px
        }

        .elementor-27 .elementor-element.elementor-element-af72f94 .elementor-divider-separator {
            width: 69%
        }

        .elementor-27 .elementor-element.elementor-element-af72f94 .elementor-divider {
            padding-top: 2px;
            padding-bottom: 2px
        }

        .elementor-27 .elementor-element.elementor-element-167367f {
            --grid-template-columns: repeat(0, auto);
            --icon-size: 23px;
            --grid-column-gap: 5px;
            --grid-row-gap: 0px
        }

        .elementor-27 .elementor-element.elementor-element-167367f .elementor-widget-container {
            text-align: left
        }

        .elementor-27 .elementor-element.elementor-element-167367f .elementor-social-icon {
            background-color: #fff
        }

        .elementor-27 .elementor-element.elementor-element-167367f .elementor-social-icon i {
            color: #000
        }

        .elementor-27 .elementor-element.elementor-element-167367f .elementor-social-icon svg {
            fill: #000000
        }

        .elementor-27 .elementor-element.elementor-element-2e99a5d {
            text-align: center
        }

        .elementor-27 .elementor-element.elementor-element-2e99a5d .elementor-heading-title {
            color: #000;
            font-family: "Noto Sans", Sans-serif;
            font-weight: 400
        }

        .elementor-27 .elementor-element.elementor-element-2e99a5d>.elementor-widget-container {
            padding: 30px 0 0
        }

        .elementor-27 .elementor-element.elementor-element-304c519:not(.elementor-motion-effects-element-type-background),
        .elementor-27 .elementor-element.elementor-element-304c519>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: #000
        }

        .elementor-27 .elementor-element.elementor-element-304c519 {
            transition: background .3s, border .3s, border-radius .3s, box-shadow .3s;
            padding: 10px 0
        }

        .elementor-27 .elementor-element.elementor-element-c943f70 {
            text-align: left
        }

        .elementor-27 .elementor-element.elementor-element-c943f70 img {
            width: 39%
        }

        .elementor-27 .elementor-element.elementor-element-c943f70>.elementor-widget-container {
            margin: -79px 0 -20px -11px
        }

        .elementor-27 .elementor-element.elementor-element-312887a.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated {
            align-content: center;
            align-items: center
        }

        .elementor-27 .elementor-element.elementor-element-0cb622d img {
            width: 63%
        }

        .elementor-27 .elementor-element.elementor-element-969ca03 {
            text-align: right
        }

        .elementor-27 .elementor-element.elementor-element-969ca03 img {
            width: 68%
        }

        .elementor-27 .elementor-element.elementor-element-969ca03>.elementor-widget-container {
            margin: -36px 0 0
        }

        @media(min-width:1281px) {

            .elementor-8 .elementor-element.elementor-element-7a3d40e:not(.elementor-motion-effects-element-type-background),
            .elementor-8 .elementor-element.elementor-element-7a3d40e>.elementor-motion-effects-container>.elementor-motion-effects-layer {
                background-attachment: scroll
            }

            .elementor-8 .elementor-element.elementor-element-be8dd25:not(.elementor-motion-effects-element-type-background),
            .elementor-8 .elementor-element.elementor-element-be8dd25>.elementor-motion-effects-container>.elementor-motion-effects-layer {
                background-attachment: scroll
            }

            .elementor-8 .elementor-element.elementor-element-ec21a9c:not(.elementor-motion-effects-element-type-background)>.elementor-widget-wrap,
            .elementor-8 .elementor-element.elementor-element-ec21a9c>.elementor-widget-wrap>.elementor-motion-effects-container>.elementor-motion-effects-layer {
                background-attachment: scroll
            }

            .elementor-8 .elementor-element.elementor-element-54f1b6f:not(.elementor-motion-effects-element-type-background)>.elementor-widget-wrap,
            .elementor-8 .elementor-element.elementor-element-54f1b6f>.elementor-widget-wrap>.elementor-motion-effects-container>.elementor-motion-effects-layer {
                background-attachment: scroll
            }

            .elementor-8 .elementor-element.elementor-element-8f63fc9:not(.elementor-motion-effects-element-type-background),
            .elementor-8 .elementor-element.elementor-element-8f63fc9>.elementor-motion-effects-container>.elementor-motion-effects-layer {
                background-attachment: scroll
            }

            .elementor-8 .elementor-element.elementor-element-11099a5:not(.elementor-motion-effects-element-type-background)>.elementor-widget-wrap,
            .elementor-8 .elementor-element.elementor-element-11099a5>.elementor-widget-wrap>.elementor-motion-effects-container>.elementor-motion-effects-layer {
                background-attachment: scroll
            }

            .elementor-27 .elementor-element.elementor-element-e782e22:not(.elementor-motion-effects-element-type-background),
            .elementor-27 .elementor-element.elementor-element-e782e22>.elementor-motion-effects-container>.elementor-motion-effects-layer {
                background-attachment: scroll
            }
        }

        @media(max-width:1280px) {
            .elementor-27 .elementor-element.elementor-element-304c519 {
                margin-top: 0;
                margin-bottom: 0
            }

            .elementor-27 .elementor-element.elementor-element-d2e5df0>.elementor-element-populated {
                margin: 0;
                --e-column-margin-right: 0px;
                --e-column-margin-left: 0px
            }

            .elementor-27 .elementor-element.elementor-element-c943f70>.elementor-widget-container {
                margin: -43px 0 -43px -11px
            }

            .elementor-27 .elementor-element.elementor-element-312887a>.elementor-element-populated {
                margin: 0 0 -11px;
                --e-column-margin-right: 0px;
                --e-column-margin-left: 0px
            }
        }

        @media(max-width:1024px) {
            .elementor-27 .elementor-element.elementor-element-c943f70 img {
                width: 60%
            }

            .elementor-27 .elementor-element.elementor-element-c943f70>.elementor-widget-container {
                margin: -43px 0 -21px -12px
            }
        }

        @media(max-width:767px) {
            .elementor-27 .elementor-element.elementor-element-e782e22 {
                padding: 100px 0 0
            }

            .elementor-27 .elementor-element.elementor-element-edfef98 .elementor-heading-title {
                font-size: 13px
            }

            .elementor-27 .elementor-element.elementor-element-edfef98>.elementor-widget-container {
                margin: 0
            }

            .elementor-27 .elementor-element.elementor-element-2c0336b {
                text-align: left
            }

            .elementor-27 .elementor-element.elementor-element-2c0336b>.elementor-widget-container {
                padding: 0
            }

            .elementor-27 .elementor-element.elementor-element-969d19a {
                width: 60px;
                max-width: 60px
            }

            .elementor-27 .elementor-element.elementor-element-2e99a5d .elementor-heading-title {
                font-size: 13px
            }

            .elementor-27 .elementor-element.elementor-element-2e99a5d>.elementor-widget-container {
                margin: 0 0 30px
            }

            .elementor-27 .elementor-element.elementor-element-304c519 {
                margin-top: 0;
                margin-bottom: 0;
                padding: 0
            }

            .elementor-27 .elementor-element.elementor-element-d2e5df0 {
                width: 33%
            }

            .elementor-27 .elementor-element.elementor-element-d2e5df0>.elementor-element-populated {
                margin: 0 0 -10px;
                --e-column-margin-right: 0px;
                --e-column-margin-left: 0px
            }

            .elementor-27 .elementor-element.elementor-element-c943f70 img {
                width: 100%
            }

            .elementor-27 .elementor-element.elementor-element-c943f70>.elementor-widget-container {
                margin: -28px 0 0 -18px
            }

            .elementor-27 .elementor-element.elementor-element-312887a {
                width: 33%
            }

            .elementor-27 .elementor-element.elementor-element-0cb622d img {
                width: 100%
            }

            .elementor-27 .elementor-element.elementor-element-217972c {
                width: 33%
            }

            .elementor-27 .elementor-element.elementor-element-969ca03 img {
                width: 57%
            }

            .elementor-27 .elementor-element.elementor-element-969ca03>.elementor-widget-container {
                margin: -18px 0 0
            }
        }

        .elementor-27 .elementor-element.elementor-element-969d19a {
            animation: .7s infinite pulse
        }

        @font-face {
            font-family: 'Noto Sans';
            font-style: normal;
            font-weight: 200;
            font-display: swap;
            src: url(fonts/o-0NIpQlx3QUlC5A4PNjKhVVZNyB.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD
        }

        @font-face {
            font-family: 'Noto Sans';
            font-style: normal;
            font-weight: 300;
            font-display: swap;
            src: url(fonts/o-0NIpQlx3QUlC5A4PNjThZVZNyB.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD
        }

        @font-face {
            font-family: 'Noto Sans';
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: url(fonts/o-0IIpQlx3QUlC5A4PNr5TRA.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD
        }

        @font-face {
            font-family: 'Noto Sans';
            font-style: normal;
            font-weight: 500;
            font-display: swap;
            src: url(fonts/o-0NIpQlx3QUlC5A4PNjFhdVZNyB.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD
        }

        @font-face {
            font-family: 'Noto Sans';
            font-style: normal;
            font-weight: 600;
            font-display: swap;
            src: url(fonts/o-0NIpQlx3QUlC5A4PNjOhBVZNyB.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD
        }

        @font-face {
            font-family: 'Noto Sans';
            font-style: normal;
            font-weight: 700;
            font-display: swap;
            src: url(fonts/o-0NIpQlx3QUlC5A4PNjXhFVZNyB.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD
        }

        @font-face {
            font-family: 'Noto Sans';
            font-style: normal;
            font-weight: 800;
            font-display: swap;
            src: url(fonts/o-0NIpQlx3QUlC5A4PNjQhJVZNyB.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD
        }

        .fab,
        .far,
        .fas {
            -moz-osx-font-smoothing: grayscale;
            -webkit-font-smoothing: antialiased;
            display: inline-block;
            font-style: normal;
            font-variant: normal;
            text-rendering: auto;
            line-height: 1
        }

        .fa-envelope:before {
            content: "\f0e0"
        }

        .fa-facebook:before {
            content: "\f09a"
        }

        .fa-instagram:before {
            content: "\f16d"
        }

        .fa-mobile-alt:before {
            content: "\f3cd"
        }

        .fa-whatsapp:before {
            content: "\f232"
        }

        @font-face {
            font-family: "Font Awesome 5 Free";
            font-style: normal;
            font-weight: 900;
            font-display: swap;
            src: url(fonts/fa-solid-900.eot);
            src: url(fonts/fa-solid-900.eot#iefix) format("embedded-opentype"), url(fonts/fa-solid-900.woff2) format("woff2"), url(fonts/fa-solid-900.woff) format("woff"), url(fonts/fa-solid-900.ttf) format("truetype"), url(images/fa-solid-900.svg#fontawesome) format("svg")
        }

        .fas {
            font-family: "Font Awesome 5 Free";
            font-weight: 900
        }

        @font-face {
            font-family: "Font Awesome 5 Brands";
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: url(fonts/fa-brands-400.eot);
            src: url(fonts/fa-brands-400.eot#iefix) format("embedded-opentype"), url(fonts/fa-brands-400.woff2) format("woff2"), url(fonts/fa-brands-400.woff) format("woff"), url(fonts/fa-brands-400.ttf) format("truetype"), url(images/fa-brands-400.svg#fontawesome) format("svg")
        }

        .fab {
            font-family: "Font Awesome 5 Brands";
            font-weight: 400
        }

        @font-face {
            font-family: "Font Awesome 5 Free";
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: url(fonts/fa-regular-400.eot);
            src: url(fonts/fa-regular-400.eot#iefix) format("embedded-opentype"), url(fonts/fa-regular-400.woff2) format("woff2"), url(fonts/fa-regular-400.woff) format("woff"), url(fonts/fa-regular-400.ttf) format("truetype"), url(images/fa-regular-400.svg#fontawesome) format("svg")
        }

        .far {
            font-family: "Font Awesome 5 Free";
            font-weight: 400
        }

        .elementor-widget-image {
            text-align: center
        }

        .elementor-widget-image a {
            display: inline-block
        }

        .elementor-widget-image a img[src$=".svg"] {
            width: 48px
        }

        .elementor-widget-image img {
            vertical-align: middle;
            display: inline-block
        }

        .elementor-item:after,
        .elementor-item:before {
            display: block;
            position: absolute;
            -webkit-transition: .3s;
            -o-transition: .3s;
            transition: .3s;
            -webkit-transition-timing-function: cubic-bezier(.58, .3, .005, 1);
            -o-transition-timing-function: cubic-bezier(.58, .3, .005, 1);
            transition-timing-function: cubic-bezier(.58, .3, .005, 1)
        }

        .elementor-item:not(:hover):not(:focus):not(.elementor-item-active):not(.highlighted):after,
        .elementor-item:not(:hover):not(:focus):not(.elementor-item-active):not(.highlighted):before {
            opacity: 0
        }

        .elementor-item-active:after,
        .elementor-item-active:before,
        .elementor-item.highlighted:after,
        .elementor-item.highlighted:before,
        .elementor-item:focus:after,
        .elementor-item:focus:before,
        .elementor-item:hover:after,
        .elementor-item:hover:before {
            -webkit-transform: scale(1);
            -ms-transform: scale(1);
            transform: scale(1)
        }

        .elementor-nav-menu--main .elementor-nav-menu a {
            -webkit-transition: .4s;
            -o-transition: .4s;
            transition: .4s
        }

        .elementor-nav-menu--main .elementor-nav-menu a,
        .elementor-nav-menu--main .elementor-nav-menu a.highlighted,
        .elementor-nav-menu--main .elementor-nav-menu a:focus,
        .elementor-nav-menu--main .elementor-nav-menu a:hover {
            padding: 13px 20px
        }

        .elementor-nav-menu--main .elementor-nav-menu a.current {
            background: #373a3c;
            color: #fff
        }

        .elementor-nav-menu--main .elementor-nav-menu a.disabled {
            background: #55595c;
            color: #a1a6a9
        }

        .elementor-nav-menu--main .elementor-nav-menu ul {
            position: absolute;
            width: 12em;
            border-width: 0;
            border-style: solid;
            padding: 0
        }

        .elementor-nav-menu--main .elementor-nav-menu span.scroll-up {
            position: absolute;
            display: none;
            visibility: hidden;
            overflow: hidden;
            background: #fff;
            height: 20px
        }

        .elementor-nav-menu--main .elementor-nav-menu--dropdown .sub-arrow i {
            -webkit-transform: rotate(-90deg);
            -ms-transform: rotate(-90deg);
            transform: rotate(-90deg)
        }

        .elementor-nav-menu--main .elementor-nav-menu--dropdown .sub-arrow .e-font-icon-svg {
            height: 1em;
            width: 1em
        }

        .elementor-nav-menu--layout-horizontal {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex
        }

        .elementor-nav-menu--layout-horizontal .elementor-nav-menu {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap
        }

        .elementor-nav-menu--layout-horizontal .elementor-nav-menu a {
            white-space: nowrap;
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1
        }

        .elementor-nav-menu--layout-horizontal .elementor-nav-menu>li {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex
        }

        .elementor-nav-menu--layout-horizontal .elementor-nav-menu>li ul {
            top: 100% !important
        }

        .elementor-nav-menu--layout-horizontal .elementor-nav-menu>li:not(:first-child)>a {
            -webkit-margin-start: var(--e-nav-menu-horizontal-menu-item-margin);
            margin-inline-start: var(--e-nav-menu-horizontal-menu-item-margin)
        }

        .elementor-nav-menu--layout-horizontal .elementor-nav-menu>li:not(:first-child)>.scroll-up,
        .elementor-nav-menu--layout-horizontal .elementor-nav-menu>li:not(:first-child)>ul {
            left: var(--e-nav-menu-horizontal-menu-item-margin) !important
        }

        .elementor-nav-menu--layout-horizontal .elementor-nav-menu>li:not(:last-child)>a {
            -webkit-margin-end: var(--e-nav-menu-horizontal-menu-item-margin);
            margin-inline-end: var(--e-nav-menu-horizontal-menu-item-margin)
        }

        .elementor-nav-menu--layout-horizontal .elementor-nav-menu>li:not(:last-child):after {
            content: var(--e-nav-menu-divider-content, none);
            height: var(--e-nav-menu-divider-height, 35%);
            border-left: var(--e-nav-menu-divider-width, 2px) var(--e-nav-menu-divider-style, solid) var(--e-nav-menu-divider-color, #000);
            border-bottom-color: var(--e-nav-menu-divider-color, #000);
            border-right-color: var(--e-nav-menu-divider-color, #000);
            border-top-color: var(--e-nav-menu-divider-color, #000);
            -ms-flex-item-align: center;
            align-self: center
        }

        .elementor-nav-menu__align-right .elementor-nav-menu {
            margin-left: auto
        }

        .elementor-nav-menu__align-right .elementor-nav-menu {
            -webkit-box-pack: end;
            -ms-flex-pack: end;
            justify-content: flex-end
        }

        .elementor-widget-nav-menu:not(.elementor-nav-menu--toggle) .elementor-menu-toggle {
            display: none
        }

        .elementor-widget-nav-menu .elementor-widget-container {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column
        }

        .elementor-nav-menu {
            position: relative;
            z-index: 2
        }

        .elementor-nav-menu:after {
            content: "\00a0";
            display: block;
            height: 0;
            font: 0/0 serif;
            clear: both;
            visibility: hidden;
            overflow: hidden
        }

        .elementor-nav-menu,
        .elementor-nav-menu li,
        .elementor-nav-menu ul {
            display: block;
            list-style: none;
            margin: 0;
            padding: 0;
            line-height: normal;
            -webkit-tap-highlight-color: transparent
        }

        .elementor-nav-menu ul {
            display: none
        }

        .elementor-nav-menu ul ul a,
        .elementor-nav-menu ul ul a:active,
        .elementor-nav-menu ul ul a:focus,
        .elementor-nav-menu ul ul a:hover {
            border-left: 16px solid transparent
        }

        .elementor-nav-menu ul ul ul a,
        .elementor-nav-menu ul ul ul a:active,
        .elementor-nav-menu ul ul ul a:focus,
        .elementor-nav-menu ul ul ul a:hover {
            border-left: 24px solid transparent
        }

        .elementor-nav-menu ul ul ul ul a,
        .elementor-nav-menu ul ul ul ul a:active,
        .elementor-nav-menu ul ul ul ul a:focus,
        .elementor-nav-menu ul ul ul ul a:hover {
            border-left: 32px solid transparent
        }

        .elementor-nav-menu ul ul ul ul ul a,
        .elementor-nav-menu ul ul ul ul ul a:active,
        .elementor-nav-menu ul ul ul ul ul a:focus,
        .elementor-nav-menu ul ul ul ul ul a:hover {
            border-left: 40px solid transparent
        }

        .elementor-nav-menu a,
        .elementor-nav-menu li {
            position: relative
        }

        .elementor-nav-menu li {
            border-width: 0
        }

        .elementor-nav-menu a {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center
        }

        .elementor-nav-menu a,
        .elementor-nav-menu a:focus,
        .elementor-nav-menu a:hover {
            padding: 10px 20px;
            line-height: 20px
        }

        .elementor-nav-menu a.current {
            background: #373a3c;
            color: #fff
        }

        .elementor-nav-menu a.disabled {
            cursor: not-allowed;
            color: #a1a6a9
        }

        .elementor-nav-menu .sub-arrow {
            line-height: 1;
            padding: 10px 0 10px 10px;
            margin-top: -10px;
            margin-bottom: -10px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center
        }

        .elementor-nav-menu .sub-arrow i {
            pointer-events: none
        }

        .elementor-nav-menu .sub-arrow .e-font-icon-svg {
            height: 1em;
            width: 1em
        }

        .elementor-nav-menu--dropdown .elementor-item.elementor-item-active,
        .elementor-nav-menu--dropdown .elementor-item.highlighted,
        .elementor-nav-menu--dropdown .elementor-item:focus,
        .elementor-nav-menu--dropdown .elementor-item:hover {
            background-color: #55595c;
            color: #fff
        }

        .elementor-menu-toggle {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            font-size: var(--nav-menu-icon-size, 22px);
            padding: .25em;
            cursor: pointer;
            border: 0 solid;
            border-radius: 3px;
            background-color: rgba(0, 0, 0, .05);
            color: #494c4f
        }

        .elementor-menu-toggle.elementor-active .elementor-menu-toggle__icon--open,
        .elementor-menu-toggle:not(.elementor-active) .elementor-menu-toggle__icon--close {
            display: none
        }

        .elementor-menu-toggle .e-font-icon-svg {
            fill: #494c4f;
            height: 1em;
            width: 1em
        }

        .elementor-menu-toggle svg {
            width: 1em;
            height: auto;
            fill: var(--nav-menu-icon-color, currentColor)
        }

        span.elementor-menu-toggle__icon--close,
        span.elementor-menu-toggle__icon--open {
            line-height: 1
        }

        .elementor-nav-menu--dropdown {
            background-color: #fff;
            font-size: 13px
        }

        .elementor-nav-menu--dropdown.elementor-nav-menu__container {
            margin-top: 10px;
            -webkit-transition: max-height .3s, -webkit-transform .3s;
            transition: max-height .3s, -webkit-transform .3s;
            -o-transition: max-height .3s, transform .3s;
            transition: max-height .3s, transform .3s;
            transition: max-height .3s, transform .3s, -webkit-transform .3s;
            -webkit-transform-origin: top;
            -ms-transform-origin: top;
            transform-origin: top;
            overflow-y: hidden
        }

        .elementor-nav-menu--dropdown a {
            color: #494c4f
        }

        .elementor-nav-menu--dropdown a.current {
            background: #373a3c;
            color: #fff
        }

        .elementor-nav-menu--dropdown a.disabled {
            color: #b3b2b2
        }

        ul.elementor-nav-menu--dropdown a,
        ul.elementor-nav-menu--dropdown a:focus,
        ul.elementor-nav-menu--dropdown a:hover {
            text-shadow: none;
            border-left: 8px solid transparent
        }

        .elementor-nav-menu--toggle .elementor-menu-toggle:not(.elementor-active)+.elementor-nav-menu__container {
            -webkit-transform: scaleY(0);
            -ms-transform: scaleY(0);
            transform: scaleY(0);
            max-height: 0
        }

        .elementor-nav-menu--toggle .elementor-menu-toggle.elementor-active+.elementor-nav-menu__container {
            -webkit-transform: scaleY(1);
            -ms-transform: scaleY(1);
            transform: scaleY(1);
            max-height: 100vh
        }

        .elementor-nav-menu--stretch .elementor-nav-menu__container.elementor-nav-menu--dropdown {
            position: absolute;
            z-index: 9997
        }

        @media (min-width:1025px) {

            .elementor-nav-menu--dropdown-tablet .elementor-menu-toggle,
            .elementor-nav-menu--dropdown-tablet .elementor-nav-menu--dropdown {
                display: none
            }
        }

        @media (max-width:1024px) {
            .elementor-nav-menu--dropdown-tablet .elementor-nav-menu--main {
                display: none
            }

            .elementor-widget:not(.elementor-tablet-align-right) .elementor-icon-list-item:after {
                left: 0
            }

            .elementor-widget:not(.elementor-tablet-align-left) .elementor-icon-list-item:after {
                right: 0
            }
        }

        .elementor-heading-title {
            padding: 0;
            margin: 0;
            line-height: 1
        }

        .elementor-widget-heading .elementor-heading-title[class*=elementor-size-]>a {
            color: inherit;
            font-size: inherit;
            line-height: inherit
        }

        .elementor-widget-divider {
            --divider-border-style: none;
            --divider-border-width: 1px;
            --divider-color: #2c2c2c;
            --divider-icon-size: 20px;
            --divider-element-spacing: 10px;
            --divider-pattern-height: 24px;
            --divider-pattern-size: 20px;
            --divider-pattern-url: none;
            --divider-pattern-repeat: repeat-x
        }

        .elementor-widget-divider .elementor-divider {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex
        }

        .elementor-widget-divider .elementor-icon {
            font-size: var(--divider-icon-size)
        }

        .elementor-widget-divider .elementor-divider-separator {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            margin: 0;
            direction: ltr
        }

        .elementor-widget-divider:not(.elementor-widget-divider--view-line_text):not(.elementor-widget-divider--view-line_icon) .elementor-divider-separator {
            border-top: var(--divider-border-width) var(--divider-border-style) var(--divider-color)
        }

        .elementor-widget-text-editor:not(.elementor-drop-cap-view-default) .elementor-drop-cap {
            margin-top: 8px
        }

        .elementor-widget-text-editor:not(.elementor-drop-cap-view-default) .elementor-drop-cap-letter {
            width: 1em;
            height: 1em
        }

        .elementor-widget-text-editor .elementor-drop-cap {
            float: left;
            text-align: center;
            line-height: 1;
            font-size: 50px
        }

        .elementor-widget-text-editor .elementor-drop-cap-letter {
            display: inline-block
        }

        .elementor-column .elementor-spacer-inner {
            height: var(--spacer-size)
        }

        .elementor-button.elementor-hidden,
        .elementor-hidden {
            display: none
        }

        .e-form__step {
            width: 100%
        }

        .e-form__step:not(.elementor-hidden) {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap
        }

        .e-form__buttons {
            -ms-flex-wrap: wrap;
            flex-wrap: wrap
        }

        .e-form__buttons,
        .e-form__buttons__wrapper {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex
        }

        .e-form__indicators {
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            -ms-flex-wrap: nowrap;
            flex-wrap: nowrap;
            font-size: 13px;
            margin-bottom: var(--e-form-steps-indicators-spacing)
        }

        .e-form__indicators,
        .e-form__indicators__indicator {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center
        }

        .e-form__indicators__indicator {
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -ms-flex-preferred-size: 0;
            flex-basis: 0;
            padding: 0 var(--e-form-steps-divider-gap)
        }

        .e-form__indicators__indicator__progress {
            width: 100%;
            position: relative;
            background-color: var(--e-form-steps-indicator-progress-background-color);
            border-radius: var(--e-form-steps-indicator-progress-border-radius);
            overflow: hidden
        }

        .e-form__indicators__indicator__progress__meter {
            width: var(--e-form-steps-indicator-progress-meter-width, 0);
            height: var(--e-form-steps-indicator-progress-height);
            line-height: var(--e-form-steps-indicator-progress-height);
            padding-right: 15px;
            border-radius: var(--e-form-steps-indicator-progress-border-radius);
            background-color: var(--e-form-steps-indicator-progress-color);
            color: var(--e-form-steps-indicator-progress-meter-color);
            text-align: right;
            -webkit-transition: width .1s linear;
            -o-transition: width .1s linear;
            transition: width .1s linear
        }

        .e-form__indicators__indicator:first-child {
            padding-left: 0
        }

        .e-form__indicators__indicator:last-child {
            padding-right: 0
        }

        .e-form__indicators__indicator--state-inactive {
            color: var(--e-form-steps-indicator-inactive-primary-color, #c2cbd2)
        }

        .e-form__indicators__indicator--state-inactive [class*=indicator--shape-]:not(.e-form__indicators__indicator--shape-none) {
            background-color: var(--e-form-steps-indicator-inactive-secondary-color, #fff)
        }

        .e-form__indicators__indicator--state-inactive object,
        .e-form__indicators__indicator--state-inactive svg {
            fill: var(--e-form-steps-indicator-inactive-primary-color, #c2cbd2)
        }

        .e-form__indicators__indicator--state-active {
            color: var(--e-form-steps-indicator-active-primary-color, #39b54a);
            border-color: var(--e-form-steps-indicator-active-secondary-color, #fff)
        }

        .e-form__indicators__indicator--state-active [class*=indicator--shape-]:not(.e-form__indicators__indicator--shape-none) {
            background-color: var(--e-form-steps-indicator-active-secondary-color, #fff)
        }

        .e-form__indicators__indicator--state-active object,
        .e-form__indicators__indicator--state-active svg {
            fill: var(--e-form-steps-indicator-active-primary-color, #39b54a)
        }

        .e-form__indicators__indicator--state-completed {
            color: var(--e-form-steps-indicator-completed-secondary-color, #fff)
        }

        .e-form__indicators__indicator--state-completed [class*=indicator--shape-]:not(.e-form__indicators__indicator--shape-none) {
            background-color: var(--e-form-steps-indicator-completed-primary-color, #39b54a)
        }

        .e-form__indicators__indicator--state-completed .e-form__indicators__indicator__label {
            color: var(--e-form-steps-indicator-completed-primary-color, #39b54a)
        }

        .e-form__indicators__indicator--state-completed .e-form__indicators__indicator--shape-none {
            color: var(--e-form-steps-indicator-completed-primary-color, #39b54a);
            background-color: initial
        }

        .e-form__indicators__indicator--state-completed object,
        .e-form__indicators__indicator--state-completed svg {
            fill: var(--e-form-steps-indicator-completed-secondary-color, #fff)
        }

        .e-form__indicators__indicator__icon {
            width: var(--e-form-steps-indicator-padding, 30px);
            height: var(--e-form-steps-indicator-padding, 30px);
            font-size: var(--e-form-steps-indicator-icon-size);
            border-width: 1px;
            border-style: solid;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            overflow: hidden;
            margin-bottom: 10px
        }

        .e-form__indicators__indicator__icon img,
        .e-form__indicators__indicator__icon object,
        .e-form__indicators__indicator__icon svg {
            width: var(--e-form-steps-indicator-icon-size);
            height: auto
        }

        .e-form__indicators__indicator__icon .e-font-icon-svg {
            height: 1em
        }

        .e-form__indicators__indicator__number {
            width: var(--e-form-steps-indicator-padding, 30px);
            height: var(--e-form-steps-indicator-padding, 30px);
            border-width: 1px;
            border-style: solid;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            margin-bottom: 10px
        }

        .e-form__indicators__indicator--shape-circle {
            border-radius: 50%
        }

        .e-form__indicators__indicator--shape-square {
            border-radius: 0
        }

        .e-form__indicators__indicator--shape-rounded {
            border-radius: 5px
        }

        .e-form__indicators__indicator--shape-none {
            border: 0
        }

        .e-form__indicators__indicator__label {
            text-align: center
        }

        .e-form__indicators__indicator__separator {
            width: 100%;
            height: var(--e-form-steps-divider-width);
            background-color: #c2cbd2
        }

        .e-form__indicators--type-icon,
        .e-form__indicators--type-icon_text,
        .e-form__indicators--type-number,
        .e-form__indicators--type-number_text {
            -webkit-box-align: start;
            -ms-flex-align: start;
            align-items: flex-start
        }

        .e-form__indicators--type-icon .e-form__indicators__indicator__separator,
        .e-form__indicators--type-icon_text .e-form__indicators__indicator__separator,
        .e-form__indicators--type-number .e-form__indicators__indicator__separator,
        .e-form__indicators--type-number_text .e-form__indicators__indicator__separator {
            margin-top: calc(var(--e-form-steps-indicator-padding, 30px)/ 2 - var(--e-form-steps-divider-width, 1px)/ 2)
        }

        .elementor-button .elementor-form-spinner {
            -webkit-box-ordinal-group: 4;
            -ms-flex-order: 3;
            order: 3
        }

        .elementor-form .elementor-button>span {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center
        }

        .elementor-form .elementor-button .elementor-button-text {
            white-space: normal;
            -webkit-box-flex: 0;
            -ms-flex-positive: 0;
            flex-grow: 0
        }

        .elementor-form .elementor-button svg {
            height: auto
        }

        .elementor-form .elementor-button .e-font-icon-svg {
            height: 1em
        }

        .elementor-widget .elementor-icon-list-items {
            list-style-type: none;
            margin: 0;
            padding: 0
        }

        .elementor-widget .elementor-icon-list-item {
            margin: 0;
            padding: 0;
            position: relative
        }

        .elementor-widget .elementor-icon-list-item:after {
            position: absolute;
            bottom: 0;
            width: 100%
        }

        .elementor-widget .elementor-icon-list-item,
        .elementor-widget .elementor-icon-list-item a {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            font-size: inherit
        }

        .elementor-widget .elementor-icon-list-icon+.elementor-icon-list-text {
            -ms-flex-item-align: center;
            align-self: center;
            padding-left: 5px
        }

        .elementor-widget .elementor-icon-list-icon {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex
        }

        .elementor-widget .elementor-icon-list-icon svg {
            width: var(--e-icon-list-icon-size, 1em);
            height: var(--e-icon-list-icon-size, 1em)
        }

        .elementor-widget .elementor-icon-list-icon i {
            width: 1.25em;
            font-size: var(--e-icon-list-icon-size)
        }

        .elementor-widget.elementor-widget-icon-list .elementor-icon-list-icon {
            text-align: var(--e-icon-list-icon-align)
        }

        .elementor-widget.elementor-widget-icon-list .elementor-icon-list-icon svg {
            margin: var(--e-icon-list-icon-margin, 0 calc(var(--e-icon-list-icon-size, 1em) * .25) 0 0)
        }

        .elementor-widget.elementor-list-item-link-full_width a {
            width: 100%
        }

        .elementor-widget.elementor-align-left .elementor-icon-list-item,
        .elementor-widget.elementor-align-left .elementor-icon-list-item a {
            -webkit-box-pack: start;
            -ms-flex-pack: start;
            justify-content: flex-start;
            text-align: left
        }

        .elementor-widget:not(.elementor-align-right) .elementor-icon-list-item:after {
            left: 0
        }

        .elementor-widget:not(.elementor-align-left) .elementor-icon-list-item:after {
            right: 0
        }

        @media (max-width:-1px) {
            .elementor-widget:not(.elementor-mobile_extra-align-right) .elementor-icon-list-item:after {
                left: 0
            }

            .elementor-widget:not(.elementor-mobile_extra-align-left) .elementor-icon-list-item:after {
                right: 0
            }
        }

        .elementor-widget-social-icons.elementor-grid-0 .elementor-widget-container {
            line-height: 1;
            font-size: 0
        }

        .elementor-widget-social-icons:not(.elementor-grid-0):not(.elementor-grid-tablet-0):not(.elementor-grid-mobile-0) .elementor-grid {
            display: inline-grid
        }

        .elementor-widget-social-icons .elementor-grid {
            grid-column-gap: var(--grid-column-gap, 5px);
            grid-row-gap: var(--grid-row-gap, 5px);
            grid-template-columns: var(--grid-template-columns);
            -webkit-box-pack: var(--justify-content, center);
            -ms-flex-pack: var(--justify-content, center);
            justify-content: var(--justify-content, center);
            justify-items: var(--justify-content, center)
        }

        .elementor-icon.elementor-social-icon {
            font-size: var(--icon-size, 25px);
            line-height: var(--icon-size, 25px);
            width: calc(var(--icon-size, 25px) + (2 * var(--icon-padding, .5em)));
            height: calc(var(--icon-size, 25px) + (2 * var(--icon-padding, .5em)))
        }

        .elementor-social-icon {
            --e-social-icon-icon-color: #fff;
            display: -webkit-inline-box;
            display: -ms-inline-flexbox;
            display: inline-flex;
            background-color: #818a91;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            text-align: center;
            cursor: pointer
        }

        .elementor-social-icon i {
            color: var(--e-social-icon-icon-color)
        }

        .elementor-social-icon svg {
            fill: var(--e-social-icon-icon-color)
        }

        .elementor-social-icon:last-child {
            margin: 0
        }

        .elementor-social-icon:hover {
            opacity: .9;
            color: #fff
        }

        .elementor-social-icon-facebook {
            background-color: #3b5998
        }

        .elementor-social-icon-instagram {
            background-color: #262626
        }

        .elementor-shape-rounded .elementor-icon.elementor-social-icon {
            border-radius: 10%
        }
    </style>
    <meta name="description"
        content="Reconheça a sua cidadania italiana! Se você é descendente de italianosé seu direito ser reconhecido">
    <link rel="canonical" href="https://italianosforever.it/">
    <meta property="og:locale" content="pt_BR">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Reconheça a sua cidadania italiana - Italianos Forever">
    <meta property="og:description"
        content="Reconheça a sua cidadania italiana! Se você é descendente de italianosé seu direito ser reconhecido">
    <meta property="og:url" content="https://italianosforever.it/">
    <meta property="og:site_name" content="italianosforever.it">
    <meta property="article:modified_time" content="2023-03-22T18:05:10+00:00">
    <meta property="og:image" content="images/Grupo-122.png">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:label1" content="Est. tempo de leitura">
    <meta name="twitter:data1" content="5 minutos">
    <script type="application/ld+json" class="yoast-schema-graph">{"@context":"https://schema.org","@graph":[{"@type":"WebPage","@id":"https://italianosforever.it/","url":"https://italianosforever.it/","name":"Reconheça a sua cidadania italiana - Italianos Forever","isPartOf":{"@id":"https://italianosforever.it/#website"},"primaryImageOfPage":{"@id":"https://italianosforever.it/#primaryimage"},"image":{"@id":"https://italianosforever.it/#primaryimage"},"thumbnailUrl":"https://italianosforever.it/wp-content/uploads/2022/12/Grupo-122.png","datePublished":"2022-12-07T13:41:15+00:00","dateModified":"2023-03-22T18:05:10+00:00","description":"Reconheça a sua cidadania italiana! Se você é descendente de italianosé seu direito ser reconhecido","breadcrumb":{"@id":"https://italianosforever.it/#breadcrumb"},"inLanguage":"pt-BR","potentialAction":[{"@type":"ReadAction","target":["https://italianosforever.it/"]}]},{"@type":"ImageObject","inLanguage":"pt-BR","@id":"https://italianosforever.it/#primaryimage","url":"https://italianosforever.it/wp-content/uploads/2022/12/Grupo-122.png","contentUrl":"https://italianosforever.it/wp-content/uploads/2022/12/Grupo-122.png","width":443,"height":210},{"@type":"BreadcrumbList","@id":"https://italianosforever.it/#breadcrumb","itemListElement":[{"@type":"ListItem","position":1,"name":"Home"}]},{"@type":"WebSite","@id":"https://italianosforever.it/#website","url":"https://italianosforever.it/","name":"italianosforever.it","description":"","potentialAction":[{"@type":"SearchAction","target":{"@type":"EntryPoint","urlTemplate":"https://italianosforever.it/?s={search_term_string}"},"query-input":"required name=search_term_string"}],"inLanguage":"pt-BR"}]}</script>
    <!-- / Yoast SEO plugin. -->


    <link rel="dns-prefetch" href="//moderate.cleantalk.org">

    <link rel="alternate" type="application/rss+xml" title="Feed para italianosforever.it »"
        href="https://italianosforever.it/feed/">
    <link rel="alternate" type="application/rss+xml" title="Feed de comentários para italianosforever.it »"
        href="https://italianosforever.it/comments/feed/">
    <style id="wp-emoji-styles-inline-css"></style>

    <style id="classic-theme-styles-inline-css"></style>
    <style id="global-styles-inline-css"></style>


    <style id="rs-plugin-settings-inline-css">
        #rs-demo-id {}
    </style>










    <style id="rocket-lazyload-inline-css">
        .rll-youtube-player {
            position: relative;
            padding-bottom: 56.23%;
            height: 0;
            overflow: hidden;
            max-width: 100%;
        }

        .rll-youtube-player:focus-within {
            outline: 2px solid currentColor;
            outline-offset: 5px;
        }

        .rll-youtube-player iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 100;
            background: 0 0
        }

        .rll-youtube-player img {
            bottom: 0;
            display: block;
            left: 0;
            margin: auto;
            max-width: 100%;
            width: 100%;
            position: absolute;
            right: 0;
            top: 0;
            border: none;
            height: auto;
            -webkit-transition: .4s all;
            -moz-transition: .4s all;
            transition: .4s all
        }

        .rll-youtube-player img:hover {
            -webkit-filter: brightness(75%)
        }

        .rll-youtube-player .play {
            height: 100%;
            width: 100%;
            left: 0;
            top: 0;
            position: absolute;
            background: url(images/youtube.png) no-repeat center;
            background-color: transparent !important;
            cursor: pointer;
            border: none;
        }
    </style>





    <script type="rocketlazyloadscript" src="js/jquery.min.js" id="jquery-core-js" defer=""></script>
    <script type="rocketlazyloadscript" src="js/jquery-migrate.min.js" id="jquery-migrate-js" defer=""></script>
    <script data-pagespeed-no-defer="" src="js/apbct-public-bundle.min.js" id="ct_public_functions-js" defer=""></script>
    <script type="rocketlazyloadscript" data-minify="1" src="js/ct-bot-detector-wrapper.js" id="ct_bot_detector-js" defer=""></script>
    <script type="rocketlazyloadscript" src="js/rbtools.min.js" id="tp-tools-js" defer=""></script>
    <script type="rocketlazyloadscript" src="js/rs6.min.js" id="revmin-js" defer=""></script>
    <link rel="https://api.w.org/" href="https://italianosforever.it/wp-json/">
    <link rel="alternate" title="JSON" type="application/json"
        href="https://italianosforever.it/wp-json/wp/v2/pages/8">
    <link rel="EditURI" type="application/rsd+xml" title="RSD"
        href="https://italianosforever.it/xmlrpc.php?rsd">
    <meta name="generator" content="WordPress 6.6.1">
    <link rel="shortlink" href="https://italianosforever.it/">
    <link rel="alternate" title="oEmbed (JSON)" type="application/json+oembed"
        href="https://italianosforever.it/wp-json/oembed/1.0/embed?url=https%3A%2F%2Fitalianosforever.it%2F">
    <link rel="alternate" title="oEmbed (XML)" type="text/xml+oembed"
        href="https://italianosforever.it/wp-json/oembed/1.0/embed?url=https%3A%2F%2Fitalianosforever.it%2F&amp;format=xml">
    <!-- FAVHERO FAVICON START --><!-- For iPad with high-resolution Retina display running iOS ≥ 7: -->
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="images/favicon-152.png"><!-- Standard: -->
    <link rel="icon" sizes="152x152" href="images/favicon-152.png">
    <meta name="msapplication-TileColor" content="#FFFFFF">
    <meta name="msapplication-TileImage"
        content="https://italianosforever.it/wp-content/uploads/2022/12/favicon-144.png">
    <!-- For iPad with high-resolution Retina display running iOS ≤ 6: -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/favicon-144.png">
    <!-- For iPhone with high-resolution Retina display running iOS ≥ 7: -->
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="images/favicon-120.png">
    <!-- For first- and second-generation iPad: -->
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/favicon-72.png">
    <!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
    <link rel="apple-touch-icon-precomposed" href="images/favicon-57.png">
    <!-- FAVHERO FAVICON END --><!-- HFCM by 99 Robots - Snippet # 1: Google Script Global e TAG para GA4 -->
    <!-- Google tag (gtag.js) -->
    <script type="rocketlazyloadscript" async="" src="https://www.googletagmanager.com/gtag/js?id=UA-254294077-1"></script>
    <script type="rocketlazyloadscript">
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-254294077-1');
    </script>

    <!-- Google Tag Manager -->
    <script type="rocketlazyloadscript">(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-P8MDN4S');</script>
    <!-- End Google Tag Manager -->

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P8MDN4S" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <!-- /end HFCM by 99 Robots -->
    <meta name="generator"
        content="Powered by Slider Revolution 6.4.11 - responsive, Mobile-Friendly Slider Plugin for WordPress with comfortable drag and drop interface.">
    <script type="rocketlazyloadscript" data-rocket-type="text/javascript">function setREVStartSize(e){
			//window.requestAnimationFrame(function() {
				window.RSIW = window.RSIW===undefined ? window.innerWidth : window.RSIW;
				window.RSIH = window.RSIH===undefined ? window.innerHeight : window.RSIH;
				try {
					var pw = document.getElementById(e.c).parentNode.offsetWidth,
						newh;
					pw = pw===0 || isNaN(pw) ? window.RSIW : pw;
					e.tabw = e.tabw===undefined ? 0 : parseInt(e.tabw);
					e.thumbw = e.thumbw===undefined ? 0 : parseInt(e.thumbw);
					e.tabh = e.tabh===undefined ? 0 : parseInt(e.tabh);
					e.thumbh = e.thumbh===undefined ? 0 : parseInt(e.thumbh);
					e.tabhide = e.tabhide===undefined ? 0 : parseInt(e.tabhide);
					e.thumbhide = e.thumbhide===undefined ? 0 : parseInt(e.thumbhide);
					e.mh = e.mh===undefined || e.mh=="" || e.mh==="auto" ? 0 : parseInt(e.mh,0);
					if(e.layout==="fullscreen" || e.l==="fullscreen")
						newh = Math.max(e.mh,window.RSIH);
					else{
						e.gw = Array.isArray(e.gw) ? e.gw : [e.gw];
						for (var i in e.rl) if (e.gw[i]===undefined || e.gw[i]===0) e.gw[i] = e.gw[i-1];
						e.gh = e.el===undefined || e.el==="" || (Array.isArray(e.el) && e.el.length==0)? e.gh : e.el;
						e.gh = Array.isArray(e.gh) ? e.gh : [e.gh];
						for (var i in e.rl) if (e.gh[i]===undefined || e.gh[i]===0) e.gh[i] = e.gh[i-1];

						var nl = new Array(e.rl.length),
							ix = 0,
							sl;
						e.tabw = e.tabhide>=pw ? 0 : e.tabw;
						e.thumbw = e.thumbhide>=pw ? 0 : e.thumbw;
						e.tabh = e.tabhide>=pw ? 0 : e.tabh;
						e.thumbh = e.thumbhide>=pw ? 0 : e.thumbh;
						for (var i in e.rl) nl[i] = e.rl[i]<window.RSIW ? 0 : e.rl[i];
						sl = nl[0];
						for (var i in nl) if (sl>nl[i] && nl[i]>0) { sl = nl[i]; ix=i;}
						var m = pw>(e.gw[ix]+e.tabw+e.thumbw) ? 1 : (pw-(e.tabw+e.thumbw)) / (e.gw[ix]);
						newh =  (e.gh[ix] * m) + (e.tabh + e.thumbh);
					}
					if(window.rs_init_css===undefined) window.rs_init_css = document.head.appendChild(document.createElement("style"));
					document.getElementById(e.c).height = newh+"px";
					window.rs_init_css.innerHTML += "#"+e.c+"_wrapper { height: "+newh+"px }";
				} catch(e){
					console.log("Failure at Presize of Slider:" + e)
				}
			//});
		  };</script>
    <noscript>
        <style id="rocket-lazyload-nojs-css">
            .rll-youtube-player,
            [data-lazy-src] {
                display: none !important;
            }
        </style>
    </noscript>
</head>

<body
    class="home page-template page-template-elementor_header_footer page page-id-8 elementor-default elementor-template-full-width elementor-kit-7 elementor-page elementor-page-8">

    <a class="skip-link screen-reader-text" href="#content">
        Skip to content</a>

    <div data-elementor-type="header" data-elementor-id="12"
        class="elementor elementor-12 elementor-location-header">
        <section
            class="elementor-section elementor-top-section elementor-element elementor-element-beb0bb2 elementor-section-boxed elementor-section-height-default elementor-section-height-default"
            data-id="beb0bb2" data-element_type="section" id="header"
            data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;sticky&quot;:&quot;top&quot;,&quot;sticky_on&quot;:[&quot;desktop&quot;,&quot;laptop&quot;],&quot;sticky_offset&quot;:0,&quot;sticky_effects_offset&quot;:0}">
            <div class="elementor-container elementor-column-gap-default">
                <div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-7477741"
                    data-id="7477741" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-7cc612a elementor-widget elementor-widget-image"
                            data-id="7cc612a" data-element_type="widget" data-widget_type="image.default">
                            <div class="elementor-widget-container">
                                <style></style> <a href="/">
                                    <img width="749" height="168"
                                        src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20749%20168'%3E%3C/svg%3E"
                                        class="attachment-large size-large" alt=""
                                        data-lazy-srcset="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-68-2.png 749w, https://italianosforever.it/wp-content/uploads/2022/12/Grupo-68-2-300x67.png 300w"
                                        data-lazy-sizes="(max-width: 749px) 100vw, 749px"
                                        data-lazy-src="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-68-2.png"><noscript><img
                                            width="749" height="168"
                                            src="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-68-2.png"
                                            class="attachment-large size-large" alt=""
                                            srcset="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-68-2.png 749w, https://italianosforever.it/wp-content/uploads/2022/12/Grupo-68-2-300x67.png 300w"
                                            sizes="(max-width: 749px) 100vw, 749px" /></noscript> </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-064a297"
                    data-id="064a297" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-6411232 elementor-nav-menu__align-right elementor-nav-menu--stretch elementor-nav-menu--dropdown-tablet elementor-nav-menu__text-align-aside elementor-nav-menu--toggle elementor-nav-menu--burger elementor-widget elementor-widget-nav-menu"
                            data-id="6411232" data-element_type="widget"
                            data-settings="{&quot;full_width&quot;:&quot;stretch&quot;,&quot;layout&quot;:&quot;horizontal&quot;,&quot;submenu_icon&quot;:{&quot;value&quot;:&quot;<i class=\&quot;fas fa-caret-down\&quot;><\/i>&quot;,&quot;library&quot;:&quot;fa-solid&quot;},&quot;toggle&quot;:&quot;burger&quot;}"
                            data-widget_type="nav-menu.default">
                            <div class="elementor-widget-container">
                                <nav migration_allowed="1" migrated="0" role="navigation"
                                    class="elementor-nav-menu--main elementor-nav-menu__container elementor-nav-menu--layout-horizontal e--pointer-none">
                                    <ul id="menu-1-6411232" class="elementor-nav-menu">
                                        <li
                                            class="menu-item menu-item-type-custom menu-item-object-custom menu-item-14">
                                            <a href="#sobre" class="elementor-item elementor-item-anchor">Sobre
                                                Nós</a></li>
                                        <li
                                            class="menu-item menu-item-type-custom menu-item-object-custom menu-item-15">
                                            <a href="#cidadania"
                                                class="elementor-item elementor-item-anchor">Cidadania Italiana</a>
                                        </li>
                                        <li
                                            class="menu-item menu-item-type-custom menu-item-object-custom menu-item-16">
                                            <a href="#contato"
                                                class="elementor-item elementor-item-anchor">Contato</a></li>
                                    </ul>
                                </nav>
                                <div class="elementor-menu-toggle" role="button" tabindex="0"
                                    aria-label="Alternar menu" aria-expanded="false">
                                    <i aria-hidden="true" role="presentation"
                                        class="elementor-menu-toggle__icon--open eicon-menu-bar"></i><i
                                        aria-hidden="true" role="presentation"
                                        class="elementor-menu-toggle__icon--close eicon-close"></i> <span
                                        class="elementor-screen-only">Menu</span>
                                </div>
                                <nav class="elementor-nav-menu--dropdown elementor-nav-menu__container"
                                    role="navigation" aria-hidden="true">
                                    <ul id="menu-2-6411232" class="elementor-nav-menu">
                                        <li
                                            class="menu-item menu-item-type-custom menu-item-object-custom menu-item-14">
                                            <a href="#sobre" class="elementor-item elementor-item-anchor"
                                                tabindex="-1">Sobre Nós</a></li>
                                        <li
                                            class="menu-item menu-item-type-custom menu-item-object-custom menu-item-15">
                                            <a href="#cidadania" class="elementor-item elementor-item-anchor"
                                                tabindex="-1">Cidadania Italiana</a></li>
                                        <li
                                            class="menu-item menu-item-type-custom menu-item-object-custom menu-item-16">
                                            <a href="#contato" class="elementor-item elementor-item-anchor"
                                                tabindex="-1">Contato</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div data-elementor-type="wp-post" data-elementor-id="8" class="elementor elementor-8">
        <section
            class="elementor-section elementor-top-section elementor-element elementor-element-7a3d40e elementor-section-content-middle elementor-section-boxed elementor-section-height-default elementor-section-height-default"
            data-id="7a3d40e" data-element_type="section"
            data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
            <div class="elementor-container elementor-column-gap-default">
                <div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-8bde659"
                    data-id="8bde659" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-66bc1da elementor-widget__width-auto elementor-widget elementor-widget-image"
                            data-id="66bc1da" data-element_type="widget" data-widget_type="image.default">
                            <div class="elementor-widget-container">
                                <img decoding="async" width="443" height="210"
                                    src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20443%20210'%3E%3C/svg%3E"
                                    class="attachment-large size-large" alt=""
                                    data-lazy-srcset="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-122.png 443w, https://italianosforever.it/wp-content/uploads/2022/12/Grupo-122-300x142.png 300w"
                                    data-lazy-sizes="(max-width: 443px) 100vw, 443px"
                                    data-lazy-src="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-122.png"><noscript><img
                                        decoding="async" width="443" height="210"
                                        src="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-122.png"
                                        class="attachment-large size-large" alt=""
                                        srcset="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-122.png 443w, https://italianosforever.it/wp-content/uploads/2022/12/Grupo-122-300x142.png 300w"
                                        sizes="(max-width: 443px) 100vw, 443px" /></noscript>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-1feaa80 elementor-widget elementor-widget-heading"
                            data-id="1feaa80" data-element_type="widget" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <style></style>
                                <h1 class="elementor-heading-title elementor-size-default">Reconheça a sua<br><b>
                                        cidadania italiana </b></h1>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-90e8d54 elementor-widget-divider--view-line elementor-widget elementor-widget-divider"
                            data-id="90e8d54" data-element_type="widget" data-widget_type="divider.default">
                            <div class="elementor-widget-container">
                                <style></style>
                                <div class="elementor-divider">
                                    <span class="elementor-divider-separator">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-d688ed0 elementor-widget elementor-widget-text-editor"
                            data-id="d688ed0" data-element_type="widget" data-widget_type="text-editor.default">
                            <div class="elementor-widget-container">
                                <style></style>
                                <p>Se você é descendente de italianos<br>é seu direito ser reconhecido</p>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-86eb668 elementor-widget elementor-widget-button"
                            data-id="86eb668" data-element_type="widget" data-widget_type="button.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-button-wrapper">
                                    <a href="#cidadania"
                                        class="elementor-button-link elementor-button elementor-size-sm"
                                        role="button">
                                        <span class="elementor-button-content-wrapper">
                                            <span class="elementor-button-text">SAIBA MAIS</span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-722f52b"
                    data-id="722f52b" data-element_type="column"
                    data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-28b1dbe elementor-widget elementor-widget-spacer"
                            data-id="28b1dbe" data-element_type="widget" data-widget_type="spacer.default">
                            <div class="elementor-widget-container">
                                <style></style>
                                <div class="elementor-spacer">
                                    <div class="elementor-spacer-inner"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section
            class="elementor-section elementor-top-section elementor-element elementor-element-049a226 elementor-section-boxed elementor-section-height-default elementor-section-height-default"
            data-id="049a226" data-element_type="section" id="sobre"
            data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
            <div class="elementor-container elementor-column-gap-default">
                <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-63b3eb8"
                    data-id="63b3eb8" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-21646fc elementor-widget__width-initial elementor-absolute elementor-hidden-tablet elementor-hidden-mobile elementor-widget elementor-widget-image"
                            data-id="21646fc" data-element_type="widget"
                            data-settings="{&quot;_position&quot;:&quot;absolute&quot;}"
                            data-widget_type="image.default">
                            <div class="elementor-widget-container">
                                <img decoding="async" width="295" height="80"
                                    src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20295%2080'%3E%3C/svg%3E"
                                    class="attachment-large size-large" alt=""
                                    data-lazy-src="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-131.png"><noscript><img
                                        decoding="async" width="295" height="80"
                                        src="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-131.png"
                                        class="attachment-large size-large" alt="" /></noscript>
                            </div>
                        </div>
                        <section
                            class="elementor-section elementor-inner-section elementor-element elementor-element-be8dd25 elementor-section-height-min-height elementor-section-boxed elementor-section-height-default"
                            data-id="be8dd25" data-element_type="section"
                            data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                            <div class="elementor-container elementor-column-gap-default">
                                <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-93c128d"
                                    data-id="93c128d" data-element_type="column">
                                    <div class="elementor-widget-wrap">
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-5489aff"
                                    data-id="5489aff" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-0cf6ee8 elementor-widget elementor-widget-heading"
                                            data-id="0cf6ee8" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <h2 class="elementor-heading-title elementor-size-default">SOBRE NÓS
                                                </h2>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-eb0ecf4 elementor-widget-divider--view-line elementor-widget elementor-widget-divider"
                                            data-id="eb0ecf4" data-element_type="widget"
                                            data-widget_type="divider.default">
                                            <div class="elementor-widget-container">
                                                <div class="elementor-divider">
                                                    <span class="elementor-divider-separator">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-5b95a81 elementor-widget elementor-widget-text-editor"
                                            data-id="5b95a81" data-element_type="widget"
                                            data-widget_type="text-editor.default">
                                            <div class="elementor-widget-container">
                                                <ul>
                                                    <li>&nbsp; Temos sede na Itália<br><br></li>
                                                    <li>&nbsp; Mais de 20 anos de experiência<br><br></li>
                                                    <li>&nbsp; Atendemos a mais de 30.000 brasileiros<br><br></li>
                                                    <li>&nbsp; Somos membro do Conselho do Cidadão <br>&nbsp; do
                                                        Consulado Brasileiro em Milão.</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </section>
        <section
            class="elementor-section elementor-top-section elementor-element elementor-element-9b7a3cb elementor-section-boxed elementor-section-height-default elementor-section-height-default"
            data-id="9b7a3cb" data-element_type="section" id="cidadania"
            data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
            <div class="elementor-container elementor-column-gap-default">
                <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-ec21a9c"
                    data-id="ec21a9c" data-element_type="column"
                    data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-241df42 elementor-widget elementor-widget-heading"
                            data-id="241df42" data-element_type="widget" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <h2 class="elementor-heading-title elementor-size-default">VANTAGENS DE RECONHECER A
                                    SUA CIDADANIA ITALIANA
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section
            class="elementor-section elementor-top-section elementor-element elementor-element-873a192 elementor-section-content-space-between elementor-section-boxed elementor-section-height-default elementor-section-height-default"
            data-id="873a192" data-element_type="section">
            <div class="elementor-container elementor-column-gap-default">
                <div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-1ee2976"
                    data-id="1ee2976" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-541e4fc elementor-widget elementor-widget-image"
                            data-id="541e4fc" data-element_type="widget" data-widget_type="image.default">
                            <div class="elementor-widget-container">
                                <img decoding="async" width="89" height="81"
                                    src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2089%2081'%3E%3C/svg%3E"
                                    class="attachment-large size-large" alt=""
                                    data-lazy-src="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-85.png"><noscript><img
                                        decoding="async" width="89" height="81"
                                        src="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-85.png"
                                        class="attachment-large size-large" alt="" /></noscript>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-9b0cf73 elementor-widget elementor-widget-heading"
                            data-id="9b0cf73" data-element_type="widget" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <h2 class="elementor-heading-title elementor-size-default"><b>Circule com
                                        facilidade</b> <br>
                                    em vários países do mundo</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-26d7591"
                    data-id="26d7591" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-cf0604a elementor-widget elementor-widget-image"
                            data-id="cf0604a" data-element_type="widget" data-widget_type="image.default">
                            <div class="elementor-widget-container">
                                <img decoding="async" width="87" height="63"
                                    src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2087%2063'%3E%3C/svg%3E"
                                    class="attachment-large size-large" alt=""
                                    data-lazy-src="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-83.png"><noscript><img
                                        decoding="async" width="87" height="63"
                                        src="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-83.png"
                                        class="attachment-large size-large" alt="" /></noscript>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-02d68bd elementor-widget elementor-widget-heading"
                            data-id="02d68bd" data-element_type="widget" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <h2 class="elementor-heading-title elementor-size-default"><b>Acesse as melhores
                                        universidades</b><br>
                                    com menor investimento</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-29d4937"
                    data-id="29d4937" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-e187464 elementor-widget elementor-widget-image"
                            data-id="e187464" data-element_type="widget" data-widget_type="image.default">
                            <div class="elementor-widget-container">
                                <img decoding="async" width="87" height="85"
                                    src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2087%2085'%3E%3C/svg%3E"
                                    class="attachment-large size-large" alt=""
                                    data-lazy-src="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-71.png"><noscript><img
                                        decoding="async" width="87" height="85"
                                        src="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-71.png"
                                        class="attachment-large size-large" alt="" /></noscript>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-b4e352f elementor-widget elementor-widget-heading"
                            data-id="b4e352f" data-element_type="widget" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <h2 class="elementor-heading-title elementor-size-default"><b>Tenha mais
                                        credibilidade</b><br>
                                    para desenvolver negócios
                                    com vários países</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-5e1058e"
                    data-id="5e1058e" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-3bea086 elementor-widget elementor-widget-image"
                            data-id="3bea086" data-element_type="widget" data-widget_type="image.default">
                            <div class="elementor-widget-container">
                                <img decoding="async" width="88" height="74"
                                    src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2088%2074'%3E%3C/svg%3E"
                                    class="attachment-large size-large" alt=""
                                    data-lazy-src="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-67.png"><noscript><img
                                        decoding="async" width="88" height="74"
                                        src="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-67.png"
                                        class="attachment-large size-large" alt="" /></noscript>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-e505df0 elementor-widget elementor-widget-heading"
                            data-id="e505df0" data-element_type="widget" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <h2 class="elementor-heading-title elementor-size-default"><b>Abra sua empresa</b><br>
                                    de forma rápida e
                                    sem complicações</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section
            class="elementor-section elementor-top-section elementor-element elementor-element-62aa79a elementor-section-content-top elementor-section-boxed elementor-section-height-default elementor-section-height-default"
            data-id="62aa79a" data-element_type="section"
            data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
            <div class="elementor-container elementor-column-gap-no">
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-433f175"
                    data-id="433f175" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-87e1c28 elementor-widget-divider--view-line elementor-widget elementor-widget-divider"
                            data-id="87e1c28" data-element_type="widget" data-widget_type="divider.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-divider">
                                    <span class="elementor-divider-separator">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-08e1fd2"
                    data-id="08e1fd2" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-08a75a2 elementor-widget elementor-widget-heading"
                            data-id="08a75a2" data-element_type="widget" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <h2 class="elementor-heading-title elementor-size-default">CONSULTORIA PARA O
                                    RECONHECIMENTO
                                    DA CIDADANIA ITALIANA</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-4ccaa76"
                    data-id="4ccaa76" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-9e97b0c elementor-widget-divider--view-line elementor-widget elementor-widget-divider"
                            data-id="9e97b0c" data-element_type="widget" data-widget_type="divider.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-divider">
                                    <span class="elementor-divider-separator">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section
            class="elementor-section elementor-top-section elementor-element elementor-element-c4dcb7a elementor-section-boxed elementor-section-height-default elementor-section-height-default"
            data-id="c4dcb7a" data-element_type="section">
            <div class="elementor-container elementor-column-gap-default">
                <div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-fa62198"
                    data-id="fa62198" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-8d074bd elementor-widget elementor-widget-image"
                            data-id="8d074bd" data-element_type="widget" data-widget_type="image.default">
                            <div class="elementor-widget-container">
                                <img decoding="async" width="800" height="828"
                                    src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20800%20828'%3E%3C/svg%3E"
                                    class="attachment-large size-large" alt=""
                                    data-lazy-srcset="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-1322-989x1024.png 989w, https://italianosforever.it/wp-content/uploads/2022/12/Grupo-1322-290x300.png 290w, https://italianosforever.it/wp-content/uploads/2022/12/Grupo-1322-768x795.png 768w, https://italianosforever.it/wp-content/uploads/2022/12/Grupo-1322.png 1022w"
                                    data-lazy-sizes="(max-width: 800px) 100vw, 800px"
                                    data-lazy-src="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-1322-989x1024.png"><noscript><img
                                        decoding="async" width="800" height="828"
                                        src="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-1322-989x1024.png"
                                        class="attachment-large size-large" alt=""
                                        srcset="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-1322-989x1024.png 989w, https://italianosforever.it/wp-content/uploads/2022/12/Grupo-1322-290x300.png 290w, https://italianosforever.it/wp-content/uploads/2022/12/Grupo-1322-768x795.png 768w, https://italianosforever.it/wp-content/uploads/2022/12/Grupo-1322.png 1022w"
                                        sizes="(max-width: 800px) 100vw, 800px" /></noscript>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-ac2c47f"
                    data-id="ac2c47f" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-469cee5 elementor-widget elementor-widget-text-editor"
                            data-id="469cee5" data-element_type="widget" data-widget_type="text-editor.default">
                            <div class="elementor-widget-container">
                                <ul>
                                    <li>Atendimento personalizado<br><br></li>
                                    <li>Comunicação continuada do status de cada etapa<br><br></li>
                                    <li>Consultoria profissional<br><br></li>
                                    <li>Consultoria na solicitação para o cônjuge</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section
            class="elementor-section elementor-top-section elementor-element elementor-element-1f5eb44 elementor-hidden-desktop elementor-hidden-laptop elementor-hidden-tablet elementor-hidden-mobile elementor-section-boxed elementor-section-height-default elementor-section-height-default"
            data-id="1f5eb44" data-element_type="section">
            <div class="elementor-container elementor-column-gap-default">
                <div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-c5172f8"
                    data-id="c5172f8" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-109e4b9 elementor-widget elementor-widget-image"
                            data-id="109e4b9" data-element_type="widget" data-widget_type="image.default">
                            <div class="elementor-widget-container">
                                <img decoding="async" width="800" height="828"
                                    src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20800%20828'%3E%3C/svg%3E"
                                    class="attachment-large size-large" alt=""
                                    data-lazy-srcset="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-1322-989x1024.png 989w, https://italianosforever.it/wp-content/uploads/2022/12/Grupo-1322-290x300.png 290w, https://italianosforever.it/wp-content/uploads/2022/12/Grupo-1322-768x795.png 768w, https://italianosforever.it/wp-content/uploads/2022/12/Grupo-1322.png 1022w"
                                    data-lazy-sizes="(max-width: 800px) 100vw, 800px"
                                    data-lazy-src="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-1322-989x1024.png"><noscript><img
                                        decoding="async" width="800" height="828"
                                        src="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-1322-989x1024.png"
                                        class="attachment-large size-large" alt=""
                                        srcset="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-1322-989x1024.png 989w, https://italianosforever.it/wp-content/uploads/2022/12/Grupo-1322-290x300.png 290w, https://italianosforever.it/wp-content/uploads/2022/12/Grupo-1322-768x795.png 768w, https://italianosforever.it/wp-content/uploads/2022/12/Grupo-1322.png 1022w"
                                        sizes="(max-width: 800px) 100vw, 800px" /></noscript>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-d6ea2a0"
                    data-id="d6ea2a0" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-7ff9b97 elementor-widget elementor-widget-text-editor"
                            data-id="7ff9b97" data-element_type="widget" data-widget_type="text-editor.default">
                            <div class="elementor-widget-container">
                                <ul>
                                    <li>&nbsp; Agilidade, transparência<br>&nbsp; e atendimento personalizado<br><br>
                                    </li>
                                    <li>&nbsp; Executamos todo o processo<br><br></li>
                                    <li>&nbsp; Comunicação do status<br>&nbsp; de cada etapa<br><br></li>
                                    <li>&nbsp; Relacionamento com órgãos <br>&nbsp; públicos italianos e
                                        exterior<br><br></li>
                                    <li>&nbsp; Reconhecimento pelo<br>&nbsp; governo italiano<br><br></li>
                                    <li>&nbsp; Toda a ajuda que você precisa<br><br></li>
                                    <li>&nbsp; &nbsp;Processo de concessão da<br>&nbsp; &nbsp;cidadania para o cônjuge
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section
            class="elementor-section elementor-top-section elementor-element elementor-element-9c10790 elementor-hidden-desktop elementor-hidden-laptop elementor-hidden-tablet elementor-hidden-mobile elementor-section-boxed elementor-section-height-default elementor-section-height-default"
            data-id="9c10790" data-element_type="section"
            data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
            <div class="elementor-container elementor-column-gap-default">
                <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-54f1b6f"
                    data-id="54f1b6f" data-element_type="column"
                    data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-d9e88c2 elementor-widget elementor-widget-heading"
                            data-id="d9e88c2" data-element_type="widget" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <h2 class="elementor-heading-title elementor-size-default">Passo a passo</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section
            class="elementor-section elementor-top-section elementor-element elementor-element-4aa2dfc elementor-hidden-desktop elementor-hidden-laptop elementor-hidden-tablet elementor-hidden-mobile elementor-section-boxed elementor-section-height-default elementor-section-height-default"
            data-id="4aa2dfc" data-element_type="section">
            <div class="elementor-container elementor-column-gap-default">
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-462f5f8"
                    data-id="462f5f8" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <section
                            class="elementor-section elementor-inner-section elementor-element elementor-element-5d875a1 elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                            data-id="5d875a1" data-element_type="section">
                            <div class="elementor-container elementor-column-gap-default">
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-210bc54"
                                    data-id="210bc54" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-8111c2e elementor-widget elementor-widget-heading"
                                            data-id="8111c2e" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <span class="elementor-heading-title elementor-size-default">01</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-7bdff58"
                                    data-id="7bdff58" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-85f4165 elementor-widget-divider--view-line elementor-widget elementor-widget-divider"
                                            data-id="85f4165" data-element_type="widget"
                                            data-widget_type="divider.default">
                                            <div class="elementor-widget-container">
                                                <div class="elementor-divider">
                                                    <span class="elementor-divider-separator">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-2117d0c"
                                    data-id="2117d0c" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-70acf7c elementor-widget elementor-widget-heading"
                                            data-id="70acf7c" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <h3 class="elementor-heading-title elementor-size-default">Busca do
                                                    antepassado</h3>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-d664109 elementor-widget elementor-widget-text-editor"
                                            data-id="d664109" data-element_type="widget"
                                            data-widget_type="text-editor.default">
                                            <div class="elementor-widget-container">
                                                <p>Documento essencial e pré-requisito para início do processo.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-299efa9"
                    data-id="299efa9" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <section
                            class="elementor-section elementor-inner-section elementor-element elementor-element-a8e0e00 elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                            data-id="a8e0e00" data-element_type="section">
                            <div class="elementor-container elementor-column-gap-default">
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-3c11857"
                                    data-id="3c11857" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-2c6043c elementor-widget elementor-widget-heading"
                                            data-id="2c6043c" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <span class="elementor-heading-title elementor-size-default">02</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-dbdae3f"
                                    data-id="dbdae3f" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-6d38493 elementor-widget-divider--view-line elementor-widget elementor-widget-divider"
                                            data-id="6d38493" data-element_type="widget"
                                            data-widget_type="divider.default">
                                            <div class="elementor-widget-container">
                                                <div class="elementor-divider">
                                                    <span class="elementor-divider-separator">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-4b6a7ff"
                                    data-id="4b6a7ff" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-c5b60bc elementor-widget elementor-widget-heading"
                                            data-id="c5b60bc" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <h3 class="elementor-heading-title elementor-size-default">Preparação
                                                    dos documentos </h3>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-c44d4a9 elementor-widget elementor-widget-text-editor"
                                            data-id="c44d4a9" data-element_type="widget"
                                            data-widget_type="text-editor.default">
                                            <div class="elementor-widget-container">
                                                <p>Emissão de certidões e procurações para o processo judicial.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-ffadaee"
                    data-id="ffadaee" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <section
                            class="elementor-section elementor-inner-section elementor-element elementor-element-3a27411 elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                            data-id="3a27411" data-element_type="section">
                            <div class="elementor-container elementor-column-gap-default">
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-83e4fe8"
                                    data-id="83e4fe8" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-bcb7760 elementor-widget elementor-widget-heading"
                                            data-id="bcb7760" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <span class="elementor-heading-title elementor-size-default">03</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-b0ff5f1"
                                    data-id="b0ff5f1" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-6752ded elementor-widget-divider--view-line elementor-widget elementor-widget-divider"
                                            data-id="6752ded" data-element_type="widget"
                                            data-widget_type="divider.default">
                                            <div class="elementor-widget-container">
                                                <div class="elementor-divider">
                                                    <span class="elementor-divider-separator">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-1a43099"
                                    data-id="1a43099" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-5ff46a4 elementor-widget elementor-widget-heading"
                                            data-id="5ff46a4" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <h3 class="elementor-heading-title elementor-size-default">
                                                    Preparação<br>
                                                    da petição </h3>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-2c4fa8d elementor-widget elementor-widget-text-editor"
                                            data-id="2c4fa8d" data-element_type="widget"
                                            data-widget_type="text-editor.default">
                                            <div class="elementor-widget-container">
                                                <p>Criação da petição pelos advogados e depósito no tribunal.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </section>
        <section
            class="elementor-section elementor-top-section elementor-element elementor-element-a8ea25c elementor-hidden-desktop elementor-hidden-laptop elementor-hidden-tablet elementor-hidden-mobile elementor-section-boxed elementor-section-height-default elementor-section-height-default"
            data-id="a8ea25c" data-element_type="section">
            <div class="elementor-container elementor-column-gap-default">
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-eea11a3"
                    data-id="eea11a3" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <section
                            class="elementor-section elementor-inner-section elementor-element elementor-element-961269a elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                            data-id="961269a" data-element_type="section">
                            <div class="elementor-container elementor-column-gap-default">
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-4f0b6c6"
                                    data-id="4f0b6c6" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-c0a1063 elementor-widget elementor-widget-heading"
                                            data-id="c0a1063" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <span class="elementor-heading-title elementor-size-default">04</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-64572fd"
                                    data-id="64572fd" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-7f64080 elementor-widget-divider--view-line elementor-widget elementor-widget-divider"
                                            data-id="7f64080" data-element_type="widget"
                                            data-widget_type="divider.default">
                                            <div class="elementor-widget-container">
                                                <div class="elementor-divider">
                                                    <span class="elementor-divider-separator">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-6bc76de"
                                    data-id="6bc76de" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-abdde37 elementor-widget elementor-widget-heading"
                                            data-id="abdde37" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <h3 class="elementor-heading-title elementor-size-default">Juiz fixa a
                                                    audiencia</h3>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-32e513e elementor-widget elementor-widget-text-editor"
                                            data-id="32e513e" data-element_type="widget"
                                            data-widget_type="text-editor.default">
                                            <div class="elementor-widget-container">
                                                <p>Nossa equipe acompanha no app italiano o passo a passo.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-1a1e2d0"
                    data-id="1a1e2d0" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <section
                            class="elementor-section elementor-inner-section elementor-element elementor-element-882ae54 elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                            data-id="882ae54" data-element_type="section">
                            <div class="elementor-container elementor-column-gap-default">
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-e73e1ce"
                                    data-id="e73e1ce" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-882659c elementor-widget elementor-widget-heading"
                                            data-id="882659c" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <span class="elementor-heading-title elementor-size-default">05</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-df23d16"
                                    data-id="df23d16" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-b3c32bc elementor-widget-divider--view-line elementor-widget elementor-widget-divider"
                                            data-id="b3c32bc" data-element_type="widget"
                                            data-widget_type="divider.default">
                                            <div class="elementor-widget-container">
                                                <div class="elementor-divider">
                                                    <span class="elementor-divider-separator">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-9b0a7fe"
                                    data-id="9b0a7fe" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-876ded5 elementor-widget elementor-widget-heading"
                                            data-id="876ded5" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <h3 class="elementor-heading-title elementor-size-default">Emissão<br>
                                                    da sentença</h3>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-d910578 elementor-widget elementor-widget-text-editor"
                                            data-id="d910578" data-element_type="widget"
                                            data-widget_type="text-editor.default">
                                            <div class="elementor-widget-container">
                                                <p>Espera o passado em julgado, desarquivar tudo do Tribunal.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-64cb0e7"
                    data-id="64cb0e7" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <section
                            class="elementor-section elementor-inner-section elementor-element elementor-element-f979348 elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                            data-id="f979348" data-element_type="section">
                            <div class="elementor-container elementor-column-gap-default">
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-a43bfc7"
                                    data-id="a43bfc7" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-13247e9 elementor-widget elementor-widget-heading"
                                            data-id="13247e9" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <span class="elementor-heading-title elementor-size-default">06</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-30839f0"
                                    data-id="30839f0" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-327f0e2 elementor-widget-divider--view-line elementor-widget elementor-widget-divider"
                                            data-id="327f0e2" data-element_type="widget"
                                            data-widget_type="divider.default">
                                            <div class="elementor-widget-container">
                                                <div class="elementor-divider">
                                                    <span class="elementor-divider-separator">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-06149e0"
                                    data-id="06149e0" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-03d66e6 elementor-widget elementor-widget-heading"
                                            data-id="03d66e6" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <h3 class="elementor-heading-title elementor-size-default">
                                                    Transcrições<br>
                                                    das certidões</h3>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-572d34a elementor-widget elementor-widget-text-editor"
                                            data-id="572d34a" data-element_type="widget"
                                            data-widget_type="text-editor.default">
                                            <div class="elementor-widget-container">
                                                <p>Envio ao Comune do antepassado para emissão da certidão italiana.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </section>
        <section
            class="elementor-section elementor-top-section elementor-element elementor-element-b8794ce elementor-hidden-desktop elementor-hidden-laptop elementor-hidden-tablet elementor-hidden-mobile elementor-section-boxed elementor-section-height-default elementor-section-height-default"
            data-id="b8794ce" data-element_type="section">
            <div class="elementor-container elementor-column-gap-default">
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-1d2023c"
                    data-id="1d2023c" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <section
                            class="elementor-section elementor-inner-section elementor-element elementor-element-e36bc08 elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                            data-id="e36bc08" data-element_type="section">
                            <div class="elementor-container elementor-column-gap-default">
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-48fe636"
                                    data-id="48fe636" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-a0b0761 elementor-widget elementor-widget-heading"
                                            data-id="a0b0761" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <span class="elementor-heading-title elementor-size-default">07</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-3e96cda"
                                    data-id="3e96cda" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-88de682 elementor-widget-divider--view-line elementor-widget elementor-widget-divider"
                                            data-id="88de682" data-element_type="widget"
                                            data-widget_type="divider.default">
                                            <div class="elementor-widget-container">
                                                <div class="elementor-divider">
                                                    <span class="elementor-divider-separator">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-9f28625"
                                    data-id="9f28625" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-b370e54 elementor-widget elementor-widget-heading"
                                            data-id="b370e54" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <h3 class="elementor-heading-title elementor-size-default">
                                                    Inscrição<br>
                                                    no AIRE</h3>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-8173b35 elementor-widget elementor-widget-text-editor"
                                            data-id="8173b35" data-element_type="widget"
                                            data-widget_type="text-editor.default">
                                            <div class="elementor-widget-container">
                                                <p>Residência do requerente no consulado do estado onde mora.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-9f15f35"
                    data-id="9f15f35" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <section
                            class="elementor-section elementor-inner-section elementor-element elementor-element-2670ea7 elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                            data-id="2670ea7" data-element_type="section">
                            <div class="elementor-container elementor-column-gap-default">
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-773c659"
                                    data-id="773c659" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-f7ed7b4 elementor-widget elementor-widget-heading"
                                            data-id="f7ed7b4" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <span class="elementor-heading-title elementor-size-default">08</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-e90d6cd"
                                    data-id="e90d6cd" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-09b2ae2 elementor-widget-divider--view-line elementor-widget elementor-widget-divider"
                                            data-id="09b2ae2" data-element_type="widget"
                                            data-widget_type="divider.default">
                                            <div class="elementor-widget-container">
                                                <div class="elementor-divider">
                                                    <span class="elementor-divider-separator">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-1199cbe"
                                    data-id="1199cbe" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-3388a85 elementor-widget elementor-widget-heading"
                                            data-id="3388a85" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <h3 class="elementor-heading-title elementor-size-default">Agendamento
                                                    e emissão passaporte</h3>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-b181332 elementor-widget elementor-widget-text-editor"
                                            data-id="b181332" data-element_type="widget"
                                            data-widget_type="text-editor.default">
                                            <div class="elementor-widget-container">
                                                <p>Etapa a ser realizada pelo requerente através de vídeo com o
                                                    consulado.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-3cc35c9"
                    data-id="3cc35c9" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <section
                            class="elementor-section elementor-inner-section elementor-element elementor-element-8f63fc9 elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                            data-id="8f63fc9" data-element_type="section"
                            data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                            <div class="elementor-container elementor-column-gap-default">
                                <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-5f98544"
                                    data-id="5f98544" data-element_type="column">
                                    <div class="elementor-widget-wrap">
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-e82c935"
                                    data-id="e82c935" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-5b8de70 elementor-widget elementor-widget-heading"
                                            data-id="5b8de70" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <h3 class="elementor-heading-title elementor-size-default">
                                                    Comunicamos<br>
                                                    o requerente <br>
                                                    pontualmente<br>
                                                    sobre a evolução<br>
                                                    de cada etapa<br></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </section>
        <section
            class="elementor-section elementor-top-section elementor-element elementor-element-fe57896 elementor-section-content-top elementor-section-boxed elementor-section-height-default elementor-section-height-default"
            data-id="fe57896" data-element_type="section"
            data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
            <div class="elementor-container elementor-column-gap-no">
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-d4be4c4"
                    data-id="d4be4c4" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-eaca9d8 elementor-widget-divider--view-line elementor-widget elementor-widget-divider"
                            data-id="eaca9d8" data-element_type="widget" data-widget_type="divider.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-divider">
                                    <span class="elementor-divider-separator">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-11099a5"
                    data-id="11099a5" data-element_type="column"
                    data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-a78a72e elementor-widget elementor-widget-heading"
                            data-id="a78a72e" data-element_type="widget" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <h2 class="elementor-heading-title elementor-size-default">O QUE DIZEM<br>
                                    NOSSOS CLIENTES</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-f47ba8e"
                    data-id="f47ba8e" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-e8321b1 elementor-widget-divider--view-line elementor-widget elementor-widget-divider"
                            data-id="e8321b1" data-element_type="widget" data-widget_type="divider.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-divider">
                                    <span class="elementor-divider-separator">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section
            class="elementor-section elementor-top-section elementor-element elementor-element-7809d83 elementor-section-boxed elementor-section-height-default elementor-section-height-default"
            data-id="7809d83" data-element_type="section">
            <div class="elementor-container elementor-column-gap-default">
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-2c4a3d0"
                    data-id="2c4a3d0" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <section
                            class="elementor-section elementor-inner-section elementor-element elementor-element-78c5e82 elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                            data-id="78c5e82" data-element_type="section"
                            data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                            <div class="elementor-container elementor-column-gap-default">
                                <div class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-d225f85"
                                    data-id="d225f85" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-e99d4ac elementor-widget elementor-widget-heading"
                                            data-id="e99d4ac" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <h4 class="elementor-heading-title elementor-size-default">Regina
                                                    Márcia Bertoncini Hennemann e Robson Avila Wolff</h4>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-2b77ffa elementor-widget elementor-widget-text-editor"
                                            data-id="2b77ffa" data-element_type="widget"
                                            data-widget_type="text-editor.default">
                                            <div class="elementor-widget-container">
                                                <p>A empresa nos foi apresentada em 2011 e a partir daí fizemos a
                                                    cidadania com toda orientação legal e acompanhamento do processo,
                                                    sempre com profissionalismo e qualidade nos serviços prestados.
                                                    Agradecemos a Ester e Giovanni, e sua equipe, pela atenção o carisma
                                                    e carinho que nos tratam até hoje.</p>
                                                <p>Regina Márcia Bertoncini Hennemann e Robson Avila Wolff</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <div class="elementor-element elementor-element-79d9e2f elementor-widget__width-initial elementor-absolute elementor-widget-mobile__width-initial elementor-widget-laptop__width-initial elementor-widget elementor-widget-image"
                            data-id="79d9e2f" data-element_type="widget"
                            data-settings="{&quot;_position&quot;:&quot;absolute&quot;}"
                            data-widget_type="image.default">
                            <div class="elementor-widget-container">
                                <img decoding="async" width="89" height="69"
                                    src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2089%2069'%3E%3C/svg%3E"
                                    class="attachment-large size-large" alt=""
                                    data-lazy-src="https://italianosforever.it/wp-content/uploads/2022/12/ativo1-1.png"><noscript><img
                                        decoding="async" width="89" height="69"
                                        src="https://italianosforever.it/wp-content/uploads/2022/12/ativo1-1.png"
                                        class="attachment-large size-large" alt="" /></noscript>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-2b7e653"
                    data-id="2b7e653" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <section
                            class="elementor-section elementor-inner-section elementor-element elementor-element-38ffc2f elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                            data-id="38ffc2f" data-element_type="section"
                            data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                            <div class="elementor-container elementor-column-gap-default">
                                <div class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-b8141d5"
                                    data-id="b8141d5" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-255d9e0 elementor-widget elementor-widget-heading"
                                            data-id="255d9e0" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <h4 class="elementor-heading-title elementor-size-default">Matheus
                                                    Tudela de Sà </h4>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-7c67458 elementor-widget elementor-widget-text-editor"
                                            data-id="7c67458" data-element_type="widget"
                                            data-widget_type="text-editor.default">
                                            <div class="elementor-widget-container">
                                                <p>Fui assistido em 2009 por Ester e Giovanni durante o mais importante
                                                    e complexo projeto da minha carreira : a expatriação. Com uma
                                                    abordagem firme e diligente me garantiram um accesso espontâneo à
                                                    minha cidadania. Nada melhor do que dispor de profissionais com
                                                    tradição e maestria.</p>
                                                <p>Sales Engineering Manager – Cella Retail Solutions</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <div class="elementor-element elementor-element-ffdc4b4 elementor-widget__width-initial elementor-absolute elementor-widget-mobile__width-initial elementor-widget elementor-widget-image"
                            data-id="ffdc4b4" data-element_type="widget"
                            data-settings="{&quot;_position&quot;:&quot;absolute&quot;}"
                            data-widget_type="image.default">
                            <div class="elementor-widget-container">
                                <img decoding="async" width="89" height="69"
                                    src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2089%2069'%3E%3C/svg%3E"
                                    class="attachment-large size-large" alt=""
                                    data-lazy-src="https://italianosforever.it/wp-content/uploads/2022/12/ativo1-1.png"><noscript><img
                                        decoding="async" width="89" height="69"
                                        src="https://italianosforever.it/wp-content/uploads/2022/12/ativo1-1.png"
                                        class="attachment-large size-large" alt="" /></noscript>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-92b90d7"
                    data-id="92b90d7" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <section
                            class="elementor-section elementor-inner-section elementor-element elementor-element-aa489cf elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                            data-id="aa489cf" data-element_type="section"
                            data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                            <div class="elementor-container elementor-column-gap-default">
                                <div class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-28f1e62"
                                    data-id="28f1e62" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-c6d3ca7 elementor-widget elementor-widget-heading"
                                            data-id="c6d3ca7" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <h4 class="elementor-heading-title elementor-size-default">Erika
                                                    Mendes Correia</h4>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-b1a2434 elementor-widget elementor-widget-text-editor"
                                            data-id="b1a2434" data-element_type="widget"
                                            data-widget_type="text-editor.default">
                                            <div class="elementor-widget-container">
                                                <p>Profissionais sérios, experientes, dedicados e apaixonados no suporte
                                                    ao reconhecimento da cidadania italiana. O processo ocorreu
                                                    tranquilamente de acordo com a descrição dos serviços prestados.
                                                    Super recomendo!</p>
                                                <p>Erika Mendes Correia – Clinical Trial Manager at Global Antibiotic
                                                    R&amp;D Partnership (GARDP) Genève</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <div class="elementor-element elementor-element-eef7013 elementor-widget__width-initial elementor-absolute elementor-widget-mobile__width-initial elementor-widget-laptop__width-initial elementor-widget elementor-widget-image"
                            data-id="eef7013" data-element_type="widget"
                            data-settings="{&quot;_position&quot;:&quot;absolute&quot;}"
                            data-widget_type="image.default">
                            <div class="elementor-widget-container">
                                <img decoding="async" width="89" height="69"
                                    src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2089%2069'%3E%3C/svg%3E"
                                    class="attachment-large size-large" alt=""
                                    data-lazy-src="https://italianosforever.it/wp-content/uploads/2022/12/ativo1-1.png"><noscript><img
                                        decoding="async" width="89" height="69"
                                        src="https://italianosforever.it/wp-content/uploads/2022/12/ativo1-1.png"
                                        class="attachment-large size-large" alt="" /></noscript>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section
            class="elementor-section elementor-top-section elementor-element elementor-element-2e882ca elementor-section-boxed elementor-section-height-default elementor-section-height-default"
            data-id="2e882ca" data-element_type="section" id="contato">
            <div class="elementor-container elementor-column-gap-default">
                <div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-b7fd1e8"
                    data-id="b7fd1e8" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-25fd90e elementor-widget elementor-widget-heading"
                            data-id="25fd90e" data-element_type="widget" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <h2 class="elementor-heading-title elementor-size-default">CONTATO</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-b1f80b4"
                    data-id="b1f80b4" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <section
                            class="elementor-section elementor-inner-section elementor-element elementor-element-a3f32e8 elementor-hidden-desktop elementor-hidden-laptop elementor-hidden-tablet elementor-hidden-mobile elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                            data-id="a3f32e8" data-element_type="section">
                            <div class="elementor-container elementor-column-gap-default">
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-8aed156"
                                    data-id="8aed156" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-13706a1 elementor-widget elementor-widget-heading"
                                            data-id="13706a1" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <h2 class="elementor-heading-title elementor-size-default">Importante
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-02ef8a0"
                                    data-id="02ef8a0" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-ee31981 elementor-widget-divider--view-line elementor-widget elementor-widget-divider"
                                            data-id="ee31981" data-element_type="widget"
                                            data-widget_type="divider.default">
                                            <div class="elementor-widget-container">
                                                <div class="elementor-divider">
                                                    <span class="elementor-divider-separator">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-6750b4c elementor-hidden-desktop elementor-hidden-laptop elementor-hidden-tablet elementor-hidden-mobile"
                                    data-id="6750b4c" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-f9a93e7 elementor-widget elementor-widget-heading"
                                            data-id="f9a93e7" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <h3 class="elementor-heading-title elementor-size-default">O processo
                                                    completo requer um
                                                    <br><b>investimento a partir de R$ 30.000,00</b>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <div data-wpl_tracker="{&quot;gtag&quot;:true,&quot;gtag_category&quot;:&quot;conversao&quot;,&quot;gtag_action&quot;:&quot;enviado&quot;,&quot;gtag_label&quot;:&quot;sucesso&quot;}"
                            class="events-tracker-for-elementor elementor-element elementor-element-677515b elementor-button-align-stretch elementor-widget elementor-widget-form"
                            data-id="677515b" data-element_type="widget"
                            data-settings="{&quot;button_width&quot;:&quot;30&quot;,&quot;button_width_mobile&quot;:&quot;50&quot;,&quot;step_next_label&quot;:&quot;Next&quot;,&quot;step_previous_label&quot;:&quot;Previous&quot;,&quot;step_type&quot;:&quot;number_text&quot;,&quot;step_icon_shape&quot;:&quot;circle&quot;}"
                            data-widget_type="form.default">
                            <div class="elementor-widget-container">
                                <style></style>
                                <form class="elementor-form" method="post" id="form_contato"
                                    name="Form Contato">
                                    <input type="hidden" name="post_id" value="8">
                                    <input type="hidden" name="form_id" value="677515b">
                                    <input type="hidden" name="referer_title"
                                        value="Reconheça a sua cidadania italiana - Italianos Forever">

                                    <input type="hidden" name="queried_id" value="8">

                                    <div class="elementor-form-fields-wrapper elementor-labels-above">
                                        <div
                                            class="elementor-field-type-text elementor-field-group elementor-column elementor-field-group-name elementor-col-100 elementor-field-required">
                                            <label for="form-field-name" class="elementor-field-label">
                                                Seu nome* </label>
                                            <input size="1" type="text" name="form_fields[name]"
                                                id="form-field-name"
                                                class="elementor-field elementor-size-sm  elementor-field-textual"
                                                required="required" aria-required="true">
                                        </div>
                                        <div
                                            class="elementor-field-type-email elementor-field-group elementor-column elementor-field-group-email elementor-col-100 elementor-field-required">
                                            <label for="form-field-email" class="elementor-field-label">
                                                Seu e-mail* </label>
                                            <input size="1" type="email" name="form_fields[email]"
                                                id="form-field-email"
                                                class="elementor-field elementor-size-sm  elementor-field-textual"
                                                required="required" aria-required="true">
                                        </div>
                                        <div
                                            class="elementor-field-type-tel elementor-field-group elementor-column elementor-field-group-field_968a17c elementor-col-100 elementor-field-required">
                                            <label for="form-field-field_968a17c" class="elementor-field-label">
                                                Seu telefone* </label>
                                            <input size="1" type="tel" name="form_fields[field_968a17c]"
                                                id="form-field-field_968a17c"
                                                class="elementor-field elementor-size-sm  elementor-field-textual"
                                                required="required" aria-required="true"
                                                pattern="[0-9()#&amp;+*-=.]+"
                                                title="Apenas números e caracteres de telefone (#, -, *, etc.) são aceitos.">

                                        </div>
                                        <div
                                            class="elementor-field-type-textarea elementor-field-group elementor-column elementor-field-group-message elementor-col-100 elementor-field-required">
                                            <label for="form-field-message" class="elementor-field-label">
                                                Sua mensagem* </label>
                                            <textarea class="elementor-field-textual elementor-field  elementor-size-sm" name="form_fields[message]"
                                                id="form-field-message" rows="6" required="required" aria-required="true"></textarea>
                                        </div>
                                        <div
                                            class="elementor-field-type-upload elementor-field-group elementor-column elementor-field-group-field_5cf8dda elementor-col-33 elementor-sm-50">
                                            <label for="form-field-field_5cf8dda" class="elementor-field-label">
                                                ANEXAR ARQUIVO </label>
                                            <input type="file" name="form_fields[field_5cf8dda]"
                                                id="form-field-field_5cf8dda"
                                                class="elementor-field elementor-size-sm  elementor-upload-field">

                                        </div>
                                        <div
                                            class="elementor-field-group elementor-column elementor-field-type-submit elementor-col-30 e-form__buttons elementor-sm-50">
                                            <button type="submit" class="elementor-button elementor-size-sm">
                                                <span>
                                                    <span class=" elementor-button-icon">
                                                    </span>
                                                    <span class="elementor-button-text">Enviar</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div data-elementor-type="footer" data-elementor-id="27"
        class="elementor elementor-27 elementor-location-footer">
        <section
            class="elementor-section elementor-top-section elementor-element elementor-element-e782e22 elementor-section-boxed elementor-section-height-default elementor-section-height-default"
            data-id="e782e22" data-element_type="section"
            data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
            <div class="elementor-container elementor-column-gap-default">
                <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-d454180"
                    data-id="d454180" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <section
                            class="elementor-section elementor-inner-section elementor-element elementor-element-d712753 elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                            data-id="d712753" data-element_type="section">
                            <div class="elementor-container elementor-column-gap-default">
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-f58e558"
                                    data-id="f58e558" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-b1830e1 elementor-widget elementor-widget-heading"
                                            data-id="b1830e1" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <h3 class="elementor-heading-title elementor-size-default">Endereço
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-b94a39f elementor-widget-divider--view-line elementor-widget elementor-widget-divider"
                                            data-id="b94a39f" data-element_type="widget"
                                            data-widget_type="divider.default">
                                            <div class="elementor-widget-container">
                                                <div class="elementor-divider">
                                                    <span class="elementor-divider-separator">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-36a9ae7 elementor-widget elementor-widget-text-editor"
                                            data-id="36a9ae7" data-element_type="widget"
                                            data-widget_type="text-editor.default">
                                            <div class="elementor-widget-container">
                                                <p>Via Nazionale 192/C – 40051<br>Altedo di Malalbergo (Bologna)</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-70b6773"
                                    data-id="70b6773" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-27db4b1 elementor-widget elementor-widget-heading"
                                            data-id="27db4b1" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <h3 class="elementor-heading-title elementor-size-default">Contato
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-bee15fd elementor-widget-divider--view-line elementor-widget elementor-widget-divider"
                                            data-id="bee15fd" data-element_type="widget"
                                            data-widget_type="divider.default">
                                            <div class="elementor-widget-container">
                                                <div class="elementor-divider">
                                                    <span class="elementor-divider-separator">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-f32a2ad elementor-align-left elementor-icon-list--layout-traditional elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list"
                                            data-id="f32a2ad" data-element_type="widget"
                                            data-widget_type="icon-list.default">
                                            <div class="elementor-widget-container">
                                                <ul class="elementor-icon-list-items">
                                                    <li class="elementor-icon-list-item">
                                                        <span class="elementor-icon-list-icon">
                                                            <i aria-hidden="true" class="fas fa-mobile-alt"></i>
                                                        </span>
                                                        <span class="elementor-icon-list-text">Fixo: 051 581156 | Fax:
                                                            051 565550</span>
                                                    </li>
                                                    <li class="elementor-icon-list-item">
                                                        <a href="https://wa.me/393922984330" target="_blank">

                                                            <span class="elementor-icon-list-icon">
                                                                <i aria-hidden="true" class="fab fa-whatsapp"></i>
                                                            </span>
                                                            <span class="elementor-icon-list-text">WhatsApp:+39
                                                                392.2984330</span>
                                                        </a>
                                                    </li>
                                                    <li class="elementor-icon-list-item">
                                                        <a href="mailto:info@aibm.it">

                                                            <span class="elementor-icon-list-icon">
                                                                <i aria-hidden="true" class="far fa-envelope"></i>
                                                            </span>
                                                            <span class="elementor-icon-list-text">info@aibm.it</span>
                                                        </a>
                                                    </li>
                                                    <li class="elementor-icon-list-item">
                                                        <a href="mailto:contato@italianosforever.com">

                                                            <span class="elementor-icon-list-icon">
                                                                <i aria-hidden="true" class="far fa-envelope"></i>
                                                            </span>
                                                            <span
                                                                class="elementor-icon-list-text">contato@italianosforever.com</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-edfef98 elementor-widget elementor-widget-heading"
                                            data-id="edfef98" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <h6 class="elementor-heading-title elementor-size-default">Uma Empresa
                                                    do Grupo AIBM</h6>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-2c0336b elementor-widget elementor-widget-image"
                                            data-id="2c0336b" data-element_type="widget"
                                            data-widget_type="image.default">
                                            <div class="elementor-widget-container">
                                                <img width="429" height="201"
                                                    src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20429%20201'%3E%3C/svg%3E"
                                                    class="attachment-large size-large" alt=""
                                                    data-lazy-srcset="https://italianosforever.it/wp-content/uploads/2022/12/image_2023_02_01T17_57_39_230Z.png 429w, https://italianosforever.it/wp-content/uploads/2022/12/image_2023_02_01T17_57_39_230Z-300x141.png 300w"
                                                    data-lazy-sizes="(max-width: 429px) 100vw, 429px"
                                                    data-lazy-src="https://italianosforever.it/wp-content/uploads/2022/12/image_2023_02_01T17_57_39_230Z.png"><noscript><img
                                                        width="429" height="201"
                                                        src="https://italianosforever.it/wp-content/uploads/2022/12/image_2023_02_01T17_57_39_230Z.png"
                                                        class="attachment-large size-large" alt=""
                                                        srcset="https://italianosforever.it/wp-content/uploads/2022/12/image_2023_02_01T17_57_39_230Z.png 429w, https://italianosforever.it/wp-content/uploads/2022/12/image_2023_02_01T17_57_39_230Z-300x141.png 300w"
                                                        sizes="(max-width: 429px) 100vw, 429px" /></noscript>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-4bfdc80"
                                    data-id="4bfdc80" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div data-wpl_tracker="{&quot;gtag&quot;:true,&quot;gtag_category&quot;:&quot;conversao&quot;,&quot;gtag_action&quot;:&quot;clique&quot;,&quot;gtag_label&quot;:&quot;whatsapp&quot;}"
                                            class="events-tracker-for-elementor elementor-element elementor-element-969d19a elementor-widget__width-initial elementor-fixed elementor-widget elementor-widget-image"
                                            data-id="969d19a" data-element_type="widget"
                                            data-settings="{&quot;_position&quot;:&quot;fixed&quot;}"
                                            data-widget_type="image.default">
                                            <div class="elementor-widget-container">
                                                <a href="https://wa.me/393922984330" target="_blank">
                                                    <img width="512" height="512"
                                                        src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20512%20512'%3E%3C/svg%3E"
                                                        class="attachment-large size-large" alt=""
                                                        data-lazy-srcset="https://italianosforever.it/wp-content/uploads/2022/12/4494494.png 512w, https://italianosforever.it/wp-content/uploads/2022/12/4494494-300x300.png 300w, https://italianosforever.it/wp-content/uploads/2022/12/4494494-150x150.png 150w"
                                                        data-lazy-sizes="(max-width: 512px) 100vw, 512px"
                                                        data-lazy-src="https://italianosforever.it/wp-content/uploads/2022/12/4494494.png"><noscript><img
                                                            width="512" height="512"
                                                            src="https://italianosforever.it/wp-content/uploads/2022/12/4494494.png"
                                                            class="attachment-large size-large" alt=""
                                                            srcset="https://italianosforever.it/wp-content/uploads/2022/12/4494494.png 512w, https://italianosforever.it/wp-content/uploads/2022/12/4494494-300x300.png 300w, https://italianosforever.it/wp-content/uploads/2022/12/4494494-150x150.png 150w"
                                                            sizes="(max-width: 512px) 100vw, 512px" /></noscript> </a>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-456cb83 elementor-widget elementor-widget-heading"
                                            data-id="456cb83" data-element_type="widget"
                                            data-widget_type="heading.default">
                                            <div class="elementor-widget-container">
                                                <h3 class="elementor-heading-title elementor-size-default">redes
                                                    sociais</h3>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-af72f94 elementor-widget-divider--view-line elementor-widget elementor-widget-divider"
                                            data-id="af72f94" data-element_type="widget"
                                            data-widget_type="divider.default">
                                            <div class="elementor-widget-container">
                                                <div class="elementor-divider">
                                                    <span class="elementor-divider-separator">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-167367f e-grid-align-left elementor-shape-rounded elementor-grid-0 elementor-widget elementor-widget-social-icons"
                                            data-id="167367f" data-element_type="widget"
                                            data-widget_type="social-icons.default">
                                            <div class="elementor-widget-container">
                                                <style></style>
                                                <div class="elementor-social-icons-wrapper elementor-grid">
                                                    <span class="elementor-grid-item">
                                                        <a class="elementor-icon elementor-social-icon elementor-social-icon-facebook elementor-repeater-item-49f3e84"
                                                            href="https://www.facebook.com/italianosforever"
                                                            target="_blank">
                                                            <span class="elementor-screen-only">Facebook</span>
                                                            <i class="fab fa-facebook"></i> </a>
                                                    </span>
                                                    <span class="elementor-grid-item">
                                                        <a class="elementor-icon elementor-social-icon elementor-social-icon-instagram elementor-repeater-item-75b4ccf"
                                                            href="https://www.instagram.com/italianosforever/"
                                                            target="_blank">
                                                            <span class="elementor-screen-only">Instagram</span>
                                                            <i class="fab fa-instagram"></i> </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <div class="elementor-element elementor-element-2e99a5d elementor-widget elementor-widget-heading"
                            data-id="2e99a5d" data-element_type="widget" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <h6 class="elementor-heading-title elementor-size-default">Ass. Italo Brasiliani nel
                                    Mondo -&nbsp;P.Iva (CNPJ) 02674511205<br>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section
            class="elementor-section elementor-top-section elementor-element elementor-element-304c519 elementor-section-full_width elementor-section-height-default elementor-section-height-default"
            data-id="304c519" data-element_type="section"
            data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
            <div class="elementor-container elementor-column-gap-default">
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-d2e5df0"
                    data-id="d2e5df0" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-c943f70 elementor-widget elementor-widget-image"
                            data-id="c943f70" data-element_type="widget" data-widget_type="image.default">
                            <div class="elementor-widget-container">
                                <img width="599" height="364"
                                    src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20599%20364'%3E%3C/svg%3E"
                                    class="attachment-large size-large" alt=""
                                    data-lazy-srcset="https://italianosforever.it/wp-content/uploads/2022/12/italian-flag.png 599w, https://italianosforever.it/wp-content/uploads/2022/12/italian-flag-300x182.png 300w"
                                    data-lazy-sizes="(max-width: 599px) 100vw, 599px"
                                    data-lazy-src="https://italianosforever.it/wp-content/uploads/2022/12/italian-flag.png"><noscript><img
                                        width="599" height="364"
                                        src="https://italianosforever.it/wp-content/uploads/2022/12/italian-flag.png"
                                        class="attachment-large size-large" alt=""
                                        srcset="https://italianosforever.it/wp-content/uploads/2022/12/italian-flag.png 599w, https://italianosforever.it/wp-content/uploads/2022/12/italian-flag-300x182.png 300w"
                                        sizes="(max-width: 599px) 100vw, 599px" /></noscript>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-312887a"
                    data-id="312887a" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-0cb622d elementor-widget elementor-widget-image"
                            data-id="0cb622d" data-element_type="widget" data-widget_type="image.default">
                            <div class="elementor-widget-container">
                                <a href="https://sinnapse.com" target="_blank">
                                    <img width="223" height="41"
                                        src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20223%2041'%3E%3C/svg%3E"
                                        class="attachment-full size-full" alt=""
                                        data-lazy-src="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-58.png"><noscript><img
                                            width="223" height="41"
                                            src="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-58.png"
                                            class="attachment-full size-full" alt="" /></noscript> </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-217972c"
                    data-id="217972c" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-969ca03 elementor-widget elementor-widget-image"
                            data-id="969ca03" data-element_type="widget" data-widget_type="image.default">
                            <div class="elementor-widget-container">
                                <a href="#header">
                                    <img width="48" height="48"
                                        src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2048%2048'%3E%3C/svg%3E"
                                        class="attachment-large size-large" alt=""
                                        data-lazy-src="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-59.png"><noscript><img
                                            width="48" height="48"
                                            src="https://italianosforever.it/wp-content/uploads/2022/12/Grupo-59.png"
                                            class="attachment-large size-large" alt="" /></noscript> </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script type="rocketlazyloadscript" id="rocket-browser-checker-js-after">
"use strict";var _createClass=function(){function defineProperties(target,props){for(var i=0;i<props.length;i++){var descriptor=props[i];descriptor.enumerable=descriptor.enumerable||!1,descriptor.configurable=!0,"value"in descriptor&&(descriptor.writable=!0),Object.defineProperty(target,descriptor.key,descriptor)}}return function(Constructor,protoProps,staticProps){return protoProps&&defineProperties(Constructor.prototype,protoProps),staticProps&&defineProperties(Constructor,staticProps),Constructor}}();function _classCallCheck(instance,Constructor){if(!(instance instanceof Constructor))throw new TypeError("Cannot call a class as a function")}var RocketBrowserCompatibilityChecker=function(){function RocketBrowserCompatibilityChecker(options){_classCallCheck(this,RocketBrowserCompatibilityChecker),this.passiveSupported=!1,this._checkPassiveOption(this),this.options=!!this.passiveSupported&&options}return _createClass(RocketBrowserCompatibilityChecker,[{key:"_checkPassiveOption",value:function(self){try{var options={get passive(){return!(self.passiveSupported=!0)}};window.addEventListener("test",null,options),window.removeEventListener("test",null,options)}catch(err){self.passiveSupported=!1}}},{key:"initRequestIdleCallback",value:function(){!1 in window&&(window.requestIdleCallback=function(cb){var start=Date.now();return setTimeout(function(){cb({didTimeout:!1,timeRemaining:function(){return Math.max(0,50-(Date.now()-start))}})},1)}),!1 in window&&(window.cancelIdleCallback=function(id){return clearTimeout(id)})}},{key:"isDataSaverModeOn",value:function(){return"connection"in navigator&&!0===navigator.connection.saveData}},{key:"supportsLinkPrefetch",value:function(){var elem=document.createElement("link");return elem.relList&&elem.relList.supports&&elem.relList.supports("prefetch")&&window.IntersectionObserver&&"isIntersecting"in IntersectionObserverEntry.prototype}},{key:"isSlowConnection",value:function(){return"connection"in navigator&&"effectiveType"in navigator.connection&&("2g"===navigator.connection.effectiveType||"slow-2g"===navigator.connection.effectiveType)}}]),RocketBrowserCompatibilityChecker}();
</script>
    <script id="rocket-preload-links-js-extra">
        var RocketPreloadLinksConfig = {
            "excludeUris": "\/(?:.+\/)?feed(?:\/(?:.+\/?)?)?$|\/(?:.+\/)?embed\/|\/(index\\.php\/)?wp\\-json(\/.*|$)|\/refer\/|\/go\/|\/recommend\/|\/recommends\/",
            "usesTrailingSlash": "1",
            "imageExt": "jpg|jpeg|gif|png|tiff|bmp|webp|avif|pdf|doc|docx|xls|xlsx|php",
            "fileExt": "jpg|jpeg|gif|png|tiff|bmp|webp|avif|pdf|doc|docx|xls|xlsx|php|html|htm",
            "siteUrl": "https:\/\/italianosforever.it",
            "onHoverDelay": "100",
            "rateThrottle": "3"
        };
    </script>
    <script type="rocketlazyloadscript" id="rocket-preload-links-js-after">
(function() {
"use strict";var r="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},e=function(){function i(e,t){for(var n=0;n<t.length;n++){var i=t[n];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(e,i.key,i)}}return function(e,t,n){return t&&i(e.prototype,t),n&&i(e,n),e}}();function i(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}var t=function(){function n(e,t){i(this,n),this.browser=e,this.config=t,this.options=this.browser.options,this.prefetched=new Set,this.eventTime=null,this.threshold=1111,this.numOnHover=0}return e(n,[{key:"init",value:function(){!this.browser.supportsLinkPrefetch()||this.browser.isDataSaverModeOn()||this.browser.isSlowConnection()||(this.regex={excludeUris:RegExp(this.config.excludeUris,"i"),images:RegExp(".("+this.config.imageExt+")$","i"),fileExt:RegExp(".("+this.config.fileExt+")$","i")},this._initListeners(this))}},{key:"_initListeners",value:function(e){-1<this.config.onHoverDelay&&document.addEventListener("mouseover",e.listener.bind(e),e.listenerOptions),document.addEventListener("mousedown",e.listener.bind(e),e.listenerOptions),document.addEventListener("touchstart",e.listener.bind(e),e.listenerOptions)}},{key:"listener",value:function(e){var t=e.target.closest("a"),n=this._prepareUrl(t);if(null!==n)switch(e.type){case"mousedown":case"touchstart":this._addPrefetchLink(n);break;case"mouseover":this._earlyPrefetch(t,n,"mouseout")}}},{key:"_earlyPrefetch",value:function(t,e,n){var i=this,r=setTimeout(function(){if(r=null,0===i.numOnHover)setTimeout(function(){return i.numOnHover=0},1e3);else if(i.numOnHover>i.config.rateThrottle)return;i.numOnHover++,i._addPrefetchLink(e)},this.config.onHoverDelay);t.addEventListener(n,function e(){t.removeEventListener(n,e,{passive:!0}),null!==r&&(clearTimeout(r),r=null)},{passive:!0})}},{key:"_addPrefetchLink",value:function(i){return this.prefetched.add(i.href),new Promise(function(e,t){var n=document.createElement("link");n.rel="prefetch",n.href=i.href,n.onload=e,n.onerror=t,document.head.appendChild(n)}).catch(function(){})}},{key:"_prepareUrl",value:function(e){if(null===e||"object"!==(void 0===e?"undefined":r(e))||!1 in e||-1===["http:","https:"].indexOf(e.protocol))return null;var t=e.href.substring(0,this.config.siteUrl.length),n=this._getPathname(e.href,t),i={original:e.href,protocol:e.protocol,origin:t,pathname:n,href:t+n};return this._isLinkOk(i)?i:null}},{key:"_getPathname",value:function(e,t){var n=t?e.substring(this.config.siteUrl.length):e;return n.startsWith("/")||(n="/"+n),this._shouldAddTrailingSlash(n)?n+"/":n}},{key:"_shouldAddTrailingSlash",value:function(e){return this.config.usesTrailingSlash&&!e.endsWith("/")&&!this.regex.fileExt.test(e)}},{key:"_isLinkOk",value:function(e){return null!==e&&"object"===(void 0===e?"undefined":r(e))&&(!this.prefetched.has(e.href)&&e.origin===this.config.siteUrl&&-1===e.href.indexOf("?")&&-1===e.href.indexOf("#")&&!this.regex.excludeUris.test(e.href)&&!this.regex.images.test(e.href))}}],[{key:"run",value:function(){"undefined"!=typeof RocketPreloadLinksConfig&&new n(new RocketBrowserCompatibilityChecker({capture:!0,passive:!0}),RocketPreloadLinksConfig).init()}}]),n}();t.run();
}());
</script>
    <script type="rocketlazyloadscript" src="js/hello-frontend.min.js" id="hello-theme-frontend-js" defer=""></script>
    <script type="rocketlazyloadscript" src="js/wpmssab.min.js" id="wpmssab-js" defer=""></script>
    <script type="rocketlazyloadscript" src="js/SmoothScroll.min.js" id="SmoothScroll-js" defer=""></script>
    <script type="rocketlazyloadscript" src="js/wpmss.min.js" id="wpmss-js" defer=""></script>
    <script type="rocketlazyloadscript" src="js/jquery.smartmenus.min.js" id="smartmenus-js" defer=""></script>
    <script type="rocketlazyloadscript" src="js/webpack.runtime.min.js" id="elementor-webpack-runtime-js" defer=""></script>
    <script type="rocketlazyloadscript" src="js/frontend-modules.min.js" id="elementor-frontend-modules-js" defer=""></script>
    <script type="rocketlazyloadscript" src="js/waypoints.min.js" id="elementor-waypoints-js" defer=""></script>
    <script type="rocketlazyloadscript" src="js/core.min.js" id="jquery-ui-core-js" defer=""></script>
    <script type="rocketlazyloadscript" id="elementor-frontend-js-before">
var elementorFrontendConfig = {"environmentMode":{"edit":false,"wpPreview":false,"isScriptDebug":false},"i18n":{"shareOnFacebook":"Compartilhar no Facebook","shareOnTwitter":"Compartilhar no Twitter","pinIt":"Fixar","download":"Baixar","downloadImage":"Baixar imagem","fullscreen":"Tela cheia","zoom":"Zoom","share":"Compartilhar","playVideo":"Reproduzir v\u00eddeo","previous":"Anterior","next":"Pr\u00f3ximo","close":"Fechar"},"is_rtl":false,"breakpoints":{"xs":0,"sm":480,"md":768,"lg":1025,"xl":1440,"xxl":1600},"responsive":{"breakpoints":{"mobile":{"label":"Celular","value":767,"default_value":767,"direction":"max","is_enabled":true},"mobile_extra":{"label":"Celular extra","value":880,"default_value":880,"direction":"max","is_enabled":false},"tablet":{"label":"Tablet","value":1024,"default_value":1024,"direction":"max","is_enabled":true},"tablet_extra":{"label":"Tablet extra","value":1200,"default_value":1200,"direction":"max","is_enabled":false},"laptop":{"label":"Laptop","value":1280,"default_value":1366,"direction":"max","is_enabled":true},"widescreen":{"label":"Widescreen","value":2400,"default_value":2400,"direction":"min","is_enabled":false}}},"version":"3.8.1","is_static":false,"experimentalFeatures":{"e_dom_optimization":true,"e_optimized_assets_loading":true,"e_optimized_css_loading":true,"a11y_improvements":true,"additional_custom_breakpoints":true,"e_import_export":true,"e_hidden_wordpress_widgets":true,"theme_builder_v2":true,"hello-theme-header-footer":true,"landing-pages":true,"elements-color-picker":true,"favorite-widgets":true,"admin-top-bar":true,"page-transitions":true,"notes":true,"form-submissions":true,"e_scroll_snap":true},"urls":{"assets":"https:\/\/italianosforever.it\/wp-content\/plugins\/elementor\/assets\/"},"settings":{"page":[],"editorPreferences":[]},"kit":{"active_breakpoints":["viewport_mobile","viewport_tablet","viewport_laptop"],"viewport_laptop":1280,"global_image_lightbox":"yes","lightbox_enable_counter":"yes","lightbox_enable_fullscreen":"yes","lightbox_enable_zoom":"yes","lightbox_enable_share":"yes","lightbox_title_src":"title","lightbox_description_src":"description","hello_header_logo_type":"title","hello_header_menu_layout":"horizontal","hello_footer_logo_type":"logo"},"post":{"id":8,"title":"Reconhe%C3%A7a%20a%20sua%20cidadania%20italiana%20-%20Italianos%20Forever","excerpt":"","featuredImage":false}};
</script>
    <script type="rocketlazyloadscript" src="js/frontend.min.js" id="elementor-frontend-js" defer=""></script>
    <script type="rocketlazyloadscript" data-minify="1" src="js/app.js" id="events_tracker_for_elementor_app-js" defer=""></script>
    <script type="rocketlazyloadscript" src="js/webpack-pro.runtime.min.js" id="elementor-pro-webpack-runtime-js" defer=""></script>
    <script type="rocketlazyloadscript" src="js/hooks.min.js" id="wp-hooks-js"></script>
    <script type="rocketlazyloadscript" src="js/i18n.min.js" id="wp-i18n-js"></script>
    <script type="rocketlazyloadscript" id="wp-i18n-js-after">
wp.i18n.setLocaleData( { 'text direction\u0004ltr': [ 'ltr' ] } );
</script>
    <script type="rocketlazyloadscript" id="elementor-pro-frontend-js-before">
var ElementorProFrontendConfig = {"ajaxurl":"https:\/\/italianosforever.it\/wp-admin\/admin-ajax.php","nonce":"4c0ba1b781","urls":{"assets":"https:\/\/italianosforever.it\/wp-content\/plugins\/elementor-pro\/assets\/","rest":"https:\/\/italianosforever.it\/wp-json\/"},"shareButtonsNetworks":{"facebook":{"title":"Facebook","has_counter":true},"twitter":{"title":"Twitter"},"linkedin":{"title":"LinkedIn","has_counter":true},"pinterest":{"title":"Pinterest","has_counter":true},"reddit":{"title":"Reddit","has_counter":true},"vk":{"title":"VK","has_counter":true},"odnoklassniki":{"title":"OK","has_counter":true},"tumblr":{"title":"Tumblr"},"digg":{"title":"Digg"},"skype":{"title":"Skype"},"stumbleupon":{"title":"StumbleUpon","has_counter":true},"mix":{"title":"Mix"},"telegram":{"title":"Telegram"},"pocket":{"title":"Pocket","has_counter":true},"xing":{"title":"XING","has_counter":true},"whatsapp":{"title":"WhatsApp"},"email":{"title":"Email"},"print":{"title":"Print"}},"facebook_sdk":{"lang":"pt_BR","app_id":""},"lottie":{"defaultAnimationUrl":"https:\/\/italianosforever.it\/wp-content\/plugins\/elementor-pro\/modules\/lottie\/assets\/animations\/default.json"}};
</script>
    <script type="rocketlazyloadscript" src="js/frontend.min_1.js" id="elementor-pro-frontend-js" defer=""></script>
    <script type="rocketlazyloadscript" src="js/elements-handlers.min.js" id="pro-elements-handlers-js" defer=""></script>
    <script type="rocketlazyloadscript" src="js/jquery.sticky.min.js" id="e-sticky-js" defer=""></script>
    <script>
        window.lazyLoadOptions = [{
            elements_selector: "img[data-lazy-src],.rocket-lazyload,iframe[data-lazy-src]",
            data_src: "lazy-src",
            data_srcset: "lazy-srcset",
            data_sizes: "lazy-sizes",
            class_loading: "lazyloading",
            class_loaded: "lazyloaded",
            threshold: 300,
            callback_loaded: function(element) {
                if (element.tagName === "IFRAME" && element.dataset.rocketLazyload == "fitvidscompatible") {
                    if (element.classList.contains("lazyloaded")) {
                        if (typeof window.jQuery != "undefined") {
                            if (jQuery.fn.fitVids) {
                                jQuery(element).parent().fitVids()
                            }
                        }
                    }
                }
            }
        }, {
            elements_selector: ".rocket-lazyload",
            data_src: "lazy-src",
            data_srcset: "lazy-srcset",
            data_sizes: "lazy-sizes",
            class_loading: "lazyloading",
            class_loaded: "lazyloaded",
            threshold: 300,
        }];
        window.addEventListener('LazyLoad::Initialized', function(e) {
            var lazyLoadInstance = e.detail.instance;
            if (window.MutationObserver) {
                var observer = new MutationObserver(function(mutations) {
                    var image_count = 0;
                    var iframe_count = 0;
                    var rocketlazy_count = 0;
                    mutations.forEach(function(mutation) {
                        for (var i = 0; i < mutation.addedNodes.length; i++) {
                            if (typeof mutation.addedNodes[i].getElementsByTagName !== 'function') {
                                continue
                            }
                            if (typeof mutation.addedNodes[i].getElementsByClassName !==
                                'function') {
                                continue
                            }
                            images = mutation.addedNodes[i].getElementsByTagName('img');
                            is_image = mutation.addedNodes[i].tagName == "IMG";
                            iframes = mutation.addedNodes[i].getElementsByTagName('iframe');
                            is_iframe = mutation.addedNodes[i].tagName == "IFRAME";
                            rocket_lazy = mutation.addedNodes[i].getElementsByClassName(
                                'rocket-lazyload');
                            image_count += images.length;
                            iframe_count += iframes.length;
                            rocketlazy_count += rocket_lazy.length;
                            if (is_image) {
                                image_count += 1
                            }
                            if (is_iframe) {
                                iframe_count += 1
                            }
                        }
                    });
                    if (image_count > 0 || iframe_count > 0 || rocketlazy_count > 0) {
                        lazyLoadInstance.update()
                    }
                });
                var b = document.getElementsByTagName("body")[0];
                var config = {
                    childList: !0,
                    subtree: !0
                };
                observer.observe(b, config)
            }
        }, !1)
    </script>
    <script data-no-minify="1" async="" src="js/lazyload.min.js"></script>
    <script>
        function lazyLoadThumb(e) {
            var t =
                '<img data-lazy-src="https://i.ytimg.com/vi/ID/hqdefault.jpg" alt="" width="480" height="360"><noscript><img src="https://i.ytimg.com/vi/ID/hqdefault.jpg" alt="" width="480" height="360"></noscript>',
                a = '<button class="play" aria-label="play Youtube video"></button>';
            return t.replace("ID", e) + a
        }

        function lazyLoadYoutubeIframe() {
            var e = document.createElement("iframe"),
                t = "ID?autoplay=1";
            t += 0 === this.parentNode.dataset.query.length ? '' : '&' + this.parentNode.dataset.query;
            e.setAttribute("src", t.replace("ID", this.parentNode.dataset.src)), e.setAttribute("frameborder", "0"), e
                .setAttribute("allowfullscreen", "1"), e.setAttribute("allow",
                    "accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"), this.parentNode.parentNode
                .replaceChild(e, this.parentNode)
        }
        document.addEventListener("DOMContentLoaded", function() {
            var e, t, p, a = document.getElementsByClassName("rll-youtube-player");
            for (t = 0; t < a.length; t++) e = document.createElement("div"), e.setAttribute("data-id", a[t].dataset
                .id), e.setAttribute("data-query", a[t].dataset.query), e.setAttribute("data-src", a[t].dataset
                .src), e.innerHTML = lazyLoadThumb(a[t].dataset.id), a[t].appendChild(e), p = e.querySelector(
                '.play'), p.onclick = lazyLoadYoutubeIframe
        });
    </script>
    <script>
        class RocketElementorAnimation {
            constructor() {
                this.deviceMode = document.createElement("span"), this.deviceMode.id = "elementor-device-mode", this
                    .deviceMode.setAttribute("class", "elementor-screen-only"), document.body.appendChild(this
                        .deviceMode)
            }
            _detectAnimations() {
                let t = getComputedStyle(this.deviceMode, ":after").content.replace(/"/g, "");
                this.animationSettingKeys = this._listAnimationSettingsKeys(t), document.querySelectorAll(
                    ".elementor-invisible[data-settings]").forEach(t => {
                    const e = t.getBoundingClientRect();
                    if (e.bottom >= 0 && e.top <= window.innerHeight) try {
                        this._animateElement(t)
                    } catch (t) {}
                })
            }
            _animateElement(t) {
                const e = JSON.parse(t.dataset.settings),
                    i = e._animation_delay || e.animation_delay || 0,
                    n = e[this.animationSettingKeys.find(t => e[t])];
                if ("none" === n) return void t.classList.remove("elementor-invisible");
                t.classList.remove(n), this.currentAnimation && t.classList.remove(this.currentAnimation), this
                    .currentAnimation = n;
                let s = setTimeout(() => {
                    t.classList.remove("elementor-invisible"), t.classList.add("animated", n), this
                        ._removeAnimationSettings(t, e)
                }, i);
                window.addEventListener("rocket-startLoading", function() {
                    clearTimeout(s)
                })
            }
            _listAnimationSettingsKeys(t = "mobile") {
                const e = [""];
                switch (t) {
                    case "mobile":
                        e.unshift("_mobile");
                    case "tablet":
                        e.unshift("_tablet");
                    case "desktop":
                        e.unshift("_desktop")
                }
                const i = [];
                return ["animation", "_animation"].forEach(t => {
                    e.forEach(e => {
                        i.push(t + e)
                    })
                }), i
            }
            _removeAnimationSettings(t, e) {
                this._listAnimationSettingsKeys().forEach(t => delete e[t]), t.dataset.settings = JSON.stringify(e)
            }
            static run() {
                const t = new RocketElementorAnimation;
                requestAnimationFrame(t._detectAnimations.bind(t))
            }
        }
        document.addEventListener("DOMContentLoaded", RocketElementorAnimation.run);
    </script>


</body>

</html>
<!-- This website is like a Rocket, isn't it? Performance optimized by WP Rocket. Learn more: https://wp-rocket.me - Debug: cached@1722749063 -->
