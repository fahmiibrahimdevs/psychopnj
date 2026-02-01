<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>Fahmi Ibrahim</title>
        <style>
            body {
                margin: 0;
                padding: 0;
                background: linear-gradient(to top left, #010022, black 150%);
                height: auto;
            }
        </style>
        <link rel="icon" type="image/png" href="http://192.168.18.104:8081/icons/MIDRAGON.png" />
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.7.2/css/all.css" />
        <link rel="stylesheet" href="http://192.168.18.104:8081/assets/katex/katex.min.css" />
        <link rel="stylesheet" href="https://unpkg.com/flowbite@1.4.7/dist/flowbite.min.css" />
        <!-- Livewire Styles -->
        <style>
            [wire\:loading][wire\:loading],
            [wire\:loading\.delay][wire\:loading\.delay],
            [wire\:loading\.inline-block][wire\:loading\.inline-block],
            [wire\:loading\.inline][wire\:loading\.inline],
            [wire\:loading\.block][wire\:loading\.block],
            [wire\:loading\.flex][wire\:loading\.flex],
            [wire\:loading\.table][wire\:loading\.table],
            [wire\:loading\.grid][wire\:loading\.grid],
            [wire\:loading\.inline-flex][wire\:loading\.inline-flex] {
                display: none;
            }
            [wire\:loading\.delay\.none][wire\:loading\.delay\.none],
            [wire\:loading\.delay\.shortest][wire\:loading\.delay\.shortest],
            [wire\:loading\.delay\.shorter][wire\:loading\.delay\.shorter],
            [wire\:loading\.delay\.short][wire\:loading\.delay\.short],
            [wire\:loading\.delay\.default][wire\:loading\.delay\.default],
            [wire\:loading\.delay\.long][wire\:loading\.delay\.long],
            [wire\:loading\.delay\.longer][wire\:loading\.delay\.longer],
            [wire\:loading\.delay\.longest][wire\:loading\.delay\.longest] {
                display: none;
            }
            [wire\:offline][wire\:offline] {
                display: none;
            }
            [wire\:dirty]:not(textarea):not(input):not(select) {
                display: none;
            }
            :root {
                --livewire-progress-bar-color: #2299dd;
            }
            [x-cloak] {
                display: none !important;
            }
        </style>
        <link rel="preload" as="style" href="http://192.168.18.104:8081/build/assets/app-CEHa84d7.css" />
        <link rel="stylesheet" href="http://192.168.18.104:8081/build/assets/app-CEHa84d7.css" data-navigate-track="reload" />
        <link rel="stylesheet" type="text/css" property="stylesheet" href="/_debugbar/assets/stylesheets?v=1712920837&theme=auto" data-turbolinks-eval="false" data-turbo-eval="false" />
        <script src="/_debugbar/assets/javascript?v=1712920837" data-turbolinks-eval="false" data-turbo-eval="false"></script>
        <script data-turbo-eval="false">
            jQuery.noConflict(true);
        </script>
        <script>
            Sfdump =
                window.Sfdump ||
                (function (doc) {
                    doc.documentElement.classList.add('sf-js-enabled');
                    var rxEsc = /([.*+?^${}()|\[\]\/\\])/g,
                        idRx = /\bsf-dump-\d+-ref[012]\w+\b/,
                        keyHint = 0 <= navigator.platform.toUpperCase().indexOf('MAC') ? 'Cmd' : 'Ctrl',
                        addEventListener = function (e, n, cb) {
                            e.addEventListener(n, cb, false);
                        };
                    if (!doc.addEventListener) {
                        addEventListener = function (element, eventName, callback) {
                            element.attachEvent('on' + eventName, function (e) {
                                e.preventDefault = function () {
                                    e.returnValue = false;
                                };
                                e.target = e.srcElement;
                                callback(e);
                            });
                        };
                    }
                    function toggle(a, recursive) {
                        var s = a.nextSibling || {},
                            oldClass = s.className,
                            arrow,
                            newClass;
                        if (/\bsf-dump-compact\b/.test(oldClass)) {
                            arrow = '▼';
                            newClass = 'sf-dump-expanded';
                        } else if (/\bsf-dump-expanded\b/.test(oldClass)) {
                            arrow = '▶';
                            newClass = 'sf-dump-compact';
                        } else {
                            return false;
                        }
                        if (doc.createEvent && s.dispatchEvent) {
                            var event = doc.createEvent('Event');
                            event.initEvent('sf-dump-expanded' === newClass ? 'sfbeforedumpexpand' : 'sfbeforedumpcollapse', true, false);
                            s.dispatchEvent(event);
                        }
                        a.lastChild.innerHTML = arrow;
                        s.className = s.className.replace(/\bsf-dump-(compact|expanded)\b/, newClass);
                        if (recursive) {
                            try {
                                a = s.querySelectorAll('.' + oldClass);
                                for (s = 0; s < a.length; ++s) {
                                    if (-1 == a[s].className.indexOf(newClass)) {
                                        a[s].className = newClass;
                                        a[s].previousSibling.lastChild.innerHTML = arrow;
                                    }
                                }
                            } catch (e) {}
                        }
                        return true;
                    }
                    function collapse(a, recursive) {
                        var s = a.nextSibling || {},
                            oldClass = s.className;
                        if (/\bsf-dump-expanded\b/.test(oldClass)) {
                            toggle(a, recursive);
                            return true;
                        }
                        return false;
                    }
                    function expand(a, recursive) {
                        var s = a.nextSibling || {},
                            oldClass = s.className;
                        if (/\bsf-dump-compact\b/.test(oldClass)) {
                            toggle(a, recursive);
                            return true;
                        }
                        return false;
                    }
                    function collapseAll(root) {
                        var a = root.querySelector('a.sf-dump-toggle');
                        if (a) {
                            collapse(a, true);
                            expand(a);
                            return true;
                        }
                        return false;
                    }
                    function reveal(node) {
                        var previous,
                            parents = [];
                        while ((node = node.parentNode || {}) && (previous = node.previousSibling) && 'A' === previous.tagName) {
                            parents.push(previous);
                        }
                        if (0 !== parents.length) {
                            parents.forEach(function (parent) {
                                expand(parent);
                            });
                            return true;
                        }
                        return false;
                    }
                    function highlight(root, activeNode, nodes) {
                        resetHighlightedNodes(root);
                        Array.from(nodes || []).forEach(function (node) {
                            if (!/\bsf-dump-highlight\b/.test(node.className)) {
                                node.className = node.className + ' sf-dump-highlight';
                            }
                        });
                        if (!/\bsf-dump-highlight-active\b/.test(activeNode.className)) {
                            activeNode.className = activeNode.className + ' sf-dump-highlight-active';
                        }
                    }
                    function resetHighlightedNodes(root) {
                        Array.from(root.querySelectorAll('.sf-dump-str, .sf-dump-key, .sf-dump-public, .sf-dump-protected, .sf-dump-private')).forEach(function (strNode) {
                            strNode.className = strNode.className.replace(/\bsf-dump-highlight\b/, '');
                            strNode.className = strNode.className.replace(/\bsf-dump-highlight-active\b/, '');
                        });
                    }
                    return function (root, x) {
                        root = doc.getElementById(root);
                        var indentRx = new RegExp('^(' + (root.getAttribute('data-indent-pad') || ' ').replace(rxEsc, '\\$1') + ')+', 'm'),
                            options = { maxDepth: 1, maxStringLength: 160, fileLinkFormat: false },
                            elt = root.getElementsByTagName('A'),
                            len = elt.length,
                            i = 0,
                            s,
                            h,
                            t = [];
                        while (i < len) t.push(elt[i++]);
                        for (i in x) {
                            options[i] = x[i];
                        }
                        function a(e, f) {
                            addEventListener(root, e, function (e, n) {
                                if ('A' == e.target.tagName) {
                                    f(e.target, e);
                                } else if ('A' == e.target.parentNode.tagName) {
                                    f(e.target.parentNode, e);
                                } else {
                                    n = /\bsf-dump-ellipsis\b/.test(e.target.className) ? e.target.parentNode : e.target;
                                    if ((n = n.nextElementSibling) && 'A' == n.tagName) {
                                        if (!/\bsf-dump-toggle\b/.test(n.className)) {
                                            n = n.nextElementSibling || n;
                                        }
                                        f(n, e, true);
                                    }
                                }
                            });
                        }
                        function isCtrlKey(e) {
                            return e.ctrlKey || e.metaKey;
                        }
                        function xpathString(str) {
                            var parts = str.match(/[^'"]+|['"]/g).map(function (part) {
                                if ("'" == part) {
                                    return '"\'"';
                                }
                                if ('"' == part) {
                                    return "'\"'";
                                }
                                return "'" + part + "'";
                            });
                            return 'concat(' + parts.join(',') + ", '')";
                        }
                        function xpathHasClass(className) {
                            return "contains(concat(' ', normalize-space(@class), ' '), ' " + className + " ')";
                        }
                        a('mouseover', function (a, e, c) {
                            if (c) {
                                e.target.style.cursor = 'pointer';
                            }
                        });
                        a('click', function (a, e, c) {
                            if (/\bsf-dump-toggle\b/.test(a.className)) {
                                e.preventDefault();
                                if (!toggle(a, isCtrlKey(e))) {
                                    var r = doc.getElementById(a.getAttribute('href').slice(1)),
                                        s = r.previousSibling,
                                        f = r.parentNode,
                                        t = a.parentNode;
                                    t.replaceChild(r, a);
                                    f.replaceChild(a, s);
                                    t.insertBefore(s, r);
                                    f = f.firstChild.nodeValue.match(indentRx);
                                    t = t.firstChild.nodeValue.match(indentRx);
                                    if (f && t && f[0] !== t[0]) {
                                        r.innerHTML = r.innerHTML.replace(new RegExp('^' + f[0].replace(rxEsc, '\\$1'), 'mg'), t[0]);
                                    }
                                    if (/\bsf-dump-compact\b/.test(r.className)) {
                                        toggle(s, isCtrlKey(e));
                                    }
                                }
                                if (c) {
                                } else if (doc.getSelection) {
                                    try {
                                        doc.getSelection().removeAllRanges();
                                    } catch (e) {
                                        doc.getSelection().empty();
                                    }
                                } else {
                                    doc.selection.empty();
                                }
                            } else if (/\bsf-dump-str-toggle\b/.test(a.className)) {
                                e.preventDefault();
                                e = a.parentNode.parentNode;
                                e.className = e.className.replace(/\bsf-dump-str-(expand|collapse)\b/, a.parentNode.className);
                            }
                        });
                        elt = root.getElementsByTagName('SAMP');
                        len = elt.length;
                        i = 0;
                        while (i < len) t.push(elt[i++]);
                        len = t.length;
                        for (i = 0; i < len; ++i) {
                            elt = t[i];
                            if ('SAMP' == elt.tagName) {
                                a = elt.previousSibling || {};
                                if ('A' != a.tagName) {
                                    a = doc.createElement('A');
                                    a.className = 'sf-dump-ref';
                                    elt.parentNode.insertBefore(a, elt);
                                } else {
                                    a.innerHTML += ' ';
                                }
                                a.title = (a.title ? a.title + '\n[' : '[') + keyHint + '+click] Expand all children';
                                a.innerHTML += elt.className == 'sf-dump-compact' ? '<span>▶</span>' : '<span>▼</span>';
                                a.className += ' sf-dump-toggle';
                                x = 1;
                                if ('sf-dump' != elt.parentNode.className) {
                                    x += elt.parentNode.getAttribute('data-depth') / 1;
                                }
                            } else if (/\bsf-dump-ref\b/.test(elt.className) && (a = elt.getAttribute('href'))) {
                                a = a.slice(1);
                                elt.className += ' sf-dump-hover';
                                elt.className += ' ' + a;
                                if (/[\[{]$/.test(elt.previousSibling.nodeValue)) {
                                    a = a != elt.nextSibling.id && doc.getElementById(a);
                                    try {
                                        s = a.nextSibling;
                                        elt.appendChild(a);
                                        s.parentNode.insertBefore(a, s);
                                        if (/^[@#]/.test(elt.innerHTML)) {
                                            elt.innerHTML += ' <span>▶</span>';
                                        } else {
                                            elt.innerHTML = '<span>▶</span>';
                                            elt.className = 'sf-dump-ref';
                                        }
                                        elt.className += ' sf-dump-toggle';
                                    } catch (e) {
                                        if ('&' == elt.innerHTML.charAt(0)) {
                                            elt.innerHTML = '…';
                                            elt.className = 'sf-dump-ref';
                                        }
                                    }
                                }
                            }
                        }
                        if (doc.evaluate && Array.from && root.children.length > 1) {
                            root.setAttribute('tabindex', 0);
                            SearchState = function () {
                                this.nodes = [];
                                this.idx = 0;
                            };
                            SearchState.prototype = {
                                next: function () {
                                    if (this.isEmpty()) {
                                        return this.current();
                                    }
                                    this.idx = this.idx < this.nodes.length - 1 ? this.idx + 1 : 0;
                                    return this.current();
                                },
                                previous: function () {
                                    if (this.isEmpty()) {
                                        return this.current();
                                    }
                                    this.idx = this.idx > 0 ? this.idx - 1 : this.nodes.length - 1;
                                    return this.current();
                                },
                                isEmpty: function () {
                                    return 0 === this.count();
                                },
                                current: function () {
                                    if (this.isEmpty()) {
                                        return null;
                                    }
                                    return this.nodes[this.idx];
                                },
                                reset: function () {
                                    this.nodes = [];
                                    this.idx = 0;
                                },
                                count: function () {
                                    return this.nodes.length;
                                },
                            };
                            function showCurrent(state) {
                                var currentNode = state.current(),
                                    currentRect,
                                    searchRect;
                                if (currentNode) {
                                    reveal(currentNode);
                                    highlight(root, currentNode, state.nodes);
                                    if ('scrollIntoView' in currentNode) {
                                        currentNode.scrollIntoView(true);
                                        currentRect = currentNode.getBoundingClientRect();
                                        searchRect = search.getBoundingClientRect();
                                        if (currentRect.top < searchRect.top + searchRect.height) {
                                            window.scrollBy(0, -(searchRect.top + searchRect.height + 5));
                                        }
                                    }
                                }
                                counter.textContent = (state.isEmpty() ? 0 : state.idx + 1) + ' of ' + state.count();
                            }
                            var search = doc.createElement('div');
                            search.className = 'sf-dump-search-wrapper sf-dump-search-hidden';
                            search.innerHTML = ' <input type="text" class="sf-dump-search-input"> <span class="sf-dump-search-count">0 of 0<\/span> <button type="button" class="sf-dump-search-input-previous" tabindex="-1"> <svg viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1683 1331l-166 165q-19 19-45 19t-45-19L896 965l-531 531q-19 19-45 19t-45-19l-166-165q-19-19-19-45.5t19-45.5l742-741q19-19 45-19t45 19l742 741q19 19 19 45.5t-19 45.5z"\/><\/svg> <\/button> <button type="button" class="sf-dump-search-input-next" tabindex="-1"> <svg viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1683 808l-742 741q-19 19-45 19t-45-19L109 808q-19-19-19-45.5t19-45.5l166-165q19-19 45-19t45 19l531 531 531-531q19-19 45-19t45 19l166 165q19 19 19 45.5t-19 45.5z"\/><\/svg> <\/button> ';
                            root.insertBefore(search, root.firstChild);
                            var state = new SearchState();
                            var searchInput = search.querySelector('.sf-dump-search-input');
                            var counter = search.querySelector('.sf-dump-search-count');
                            var searchInputTimer = 0;
                            var previousSearchQuery = '';
                            addEventListener(searchInput, 'keyup', function (e) {
                                var searchQuery = e.target.value;
                                /* Don't perform anything if the pressed key didn't change the query */ if (searchQuery === previousSearchQuery) {
                                    return;
                                }
                                previousSearchQuery = searchQuery;
                                clearTimeout(searchInputTimer);
                                searchInputTimer = setTimeout(function () {
                                    state.reset();
                                    collapseAll(root);
                                    resetHighlightedNodes(root);
                                    if ('' === searchQuery) {
                                        counter.textContent = '0 of 0';
                                        return;
                                    }
                                    var classMatches = ['sf-dump-str', 'sf-dump-key', 'sf-dump-public', 'sf-dump-protected', 'sf-dump-private'].map(xpathHasClass).join(' or ');
                                    var xpathResult = doc.evaluate('.//span[' + classMatches + '][contains(translate(child::text(), ' + xpathString(searchQuery.toUpperCase()) + ', ' + xpathString(searchQuery.toLowerCase()) + '), ' + xpathString(searchQuery.toLowerCase()) + ')]', root, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null);
                                    while ((node = xpathResult.iterateNext())) state.nodes.push(node);
                                    showCurrent(state);
                                }, 400);
                            });
                            Array.from(search.querySelectorAll('.sf-dump-search-input-next, .sf-dump-search-input-previous')).forEach(function (btn) {
                                addEventListener(btn, 'click', function (e) {
                                    e.preventDefault();
                                    -1 !== e.target.className.indexOf('next') ? state.next() : state.previous();
                                    searchInput.focus();
                                    collapseAll(root);
                                    showCurrent(state);
                                });
                            });
                            addEventListener(root, 'keydown', function (e) {
                                var isSearchActive = !/\bsf-dump-search-hidden\b/.test(search.className);
                                if ((114 === e.keyCode && !isSearchActive) || (isCtrlKey(e) && 70 === e.keyCode)) {
                                    /* F3 or CMD/CTRL + F */ if (70 === e.keyCode && document.activeElement === searchInput) {
                                        /* * If CMD/CTRL + F is hit while having focus on search input, * the user probably meant to trigger browser search instead. * Let the browser execute its behavior: */ return;
                                    }
                                    e.preventDefault();
                                    search.className = search.className.replace(/\bsf-dump-search-hidden\b/, '');
                                    searchInput.focus();
                                } else if (isSearchActive) {
                                    if (27 === e.keyCode) {
                                        /* ESC key */ search.className += ' sf-dump-search-hidden';
                                        e.preventDefault();
                                        resetHighlightedNodes(root);
                                        searchInput.value = '';
                                    } else if ((isCtrlKey(e) && 71 === e.keyCode) /* CMD/CTRL + G */ || 13 === e.keyCode /* Enter */ || 114 === e.keyCode /* F3 */) {
                                        e.preventDefault();
                                        e.shiftKey ? state.previous() : state.next();
                                        collapseAll(root);
                                        showCurrent(state);
                                    }
                                }
                            });
                        }
                        if (0 >= options.maxStringLength) {
                            return;
                        }
                        try {
                            elt = root.querySelectorAll('.sf-dump-str');
                            len = elt.length;
                            i = 0;
                            t = [];
                            while (i < len) t.push(elt[i++]);
                            len = t.length;
                            for (i = 0; i < len; ++i) {
                                elt = t[i];
                                s = elt.innerText || elt.textContent;
                                x = s.length - options.maxStringLength;
                                if (0 < x) {
                                    h = elt.innerHTML;
                                    elt[elt.innerText ? 'innerText' : 'textContent'] = s.substring(0, options.maxStringLength);
                                    elt.className += ' sf-dump-str-collapse';
                                    elt.innerHTML = '<span class=sf-dump-str-collapse>' + h + '<a class="sf-dump-ref sf-dump-str-toggle" title="Collapse"> ◀</a></span>' + '<span class=sf-dump-str-expand>' + elt.innerHTML + '<a class="sf-dump-ref sf-dump-str-toggle" title="' + x + ' remaining characters"> ▶</a></span>';
                                }
                            }
                        } catch (e) {}
                    };
                })(document);
        </script>
        <style>
            .sf-js-enabled .phpdebugbar pre.sf-dump .sf-dump-compact,
            .sf-js-enabled .sf-dump-str-collapse .sf-dump-str-collapse,
            .sf-js-enabled .sf-dump-str-expand .sf-dump-str-expand {
                display: none;
            }
            .sf-dump-hover:hover {
                background-color: #b729d9;
                color: #fff !important;
                border-radius: 2px;
            }
            .phpdebugbar pre.sf-dump {
                display: block;
                white-space: pre;
                padding: 5px;
                overflow: initial !important;
            }
            .phpdebugbar pre.sf-dump:after {
                content: '';
                visibility: hidden;
                display: block;
                height: 0;
                clear: both;
            }
            .phpdebugbar pre.sf-dump span {
                display: inline-flex;
            }
            .phpdebugbar pre.sf-dump a {
                text-decoration: none;
                cursor: pointer;
                border: 0;
                outline: none;
                color: inherit;
            }
            .phpdebugbar pre.sf-dump img {
                max-width: 50em;
                max-height: 50em;
                margin: 0.5em 0 0 0;
                padding: 0;
                background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAAAAAA6mKC9AAAAHUlEQVQY02O8zAABilCaiQEN0EeA8QuUcX9g3QEAAjcC5piyhyEAAAAASUVORK5CYII=) #d3d3d3;
            }
            .phpdebugbar pre.sf-dump .sf-dump-ellipsis {
                display: inline-block;
                overflow: visible;
                text-overflow: ellipsis;
                max-width: 5em;
                white-space: nowrap;
                overflow: hidden;
                vertical-align: top;
            }
            .phpdebugbar pre.sf-dump .sf-dump-ellipsis + .sf-dump-ellipsis {
                max-width: none;
            }
            .phpdebugbar pre.sf-dump code {
                display: inline;
                padding: 0;
                background: none;
            }
            .sf-dump-public.sf-dump-highlight,
            .sf-dump-protected.sf-dump-highlight,
            .sf-dump-private.sf-dump-highlight,
            .sf-dump-str.sf-dump-highlight,
            .sf-dump-key.sf-dump-highlight {
                background: rgba(111, 172, 204, 0.3);
                border: 1px solid #7da0b1;
                border-radius: 3px;
            }
            .sf-dump-public.sf-dump-highlight-active,
            .sf-dump-protected.sf-dump-highlight-active,
            .sf-dump-private.sf-dump-highlight-active,
            .sf-dump-str.sf-dump-highlight-active,
            .sf-dump-key.sf-dump-highlight-active {
                background: rgba(253, 175, 0, 0.4);
                border: 1px solid #ffa500;
                border-radius: 3px;
            }
            .phpdebugbar pre.sf-dump .sf-dump-search-hidden {
                display: none !important;
            }
            .phpdebugbar pre.sf-dump .sf-dump-search-wrapper {
                font-size: 0;
                white-space: nowrap;
                margin-bottom: 5px;
                display: flex;
                position: -webkit-sticky;
                position: sticky;
                top: 5px;
            }
            .phpdebugbar pre.sf-dump .sf-dump-search-wrapper > * {
                vertical-align: top;
                box-sizing: border-box;
                height: 21px;
                font-weight: normal;
                border-radius: 0;
                background: #fff;
                color: #757575;
                border: 1px solid #bbb;
            }
            .phpdebugbar pre.sf-dump .sf-dump-search-wrapper > input.sf-dump-search-input {
                padding: 3px;
                height: 21px;
                font-size: 12px;
                border-right: none;
                border-top-left-radius: 3px;
                border-bottom-left-radius: 3px;
                color: #000;
                min-width: 15px;
                width: 100%;
            }
            .phpdebugbar pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-next,
            .phpdebugbar pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-previous {
                background: #f2f2f2;
                outline: none;
                border-left: none;
                font-size: 0;
                line-height: 0;
            }
            .phpdebugbar pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-next {
                border-top-right-radius: 3px;
                border-bottom-right-radius: 3px;
            }
            .phpdebugbar pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-next > svg,
            .phpdebugbar pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-previous > svg {
                pointer-events: none;
                width: 12px;
                height: 12px;
            }
            .phpdebugbar pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-count {
                display: inline-block;
                padding: 0 5px;
                margin: 0;
                border-left: none;
                line-height: 21px;
                font-size: 12px;
            }
            .phpdebugbar pre.sf-dump,
            .phpdebugbar pre.sf-dump .sf-dump-default {
                word-wrap: break-word;
                white-space: pre-wrap;
                word-break: normal;
            }
            .phpdebugbar pre.sf-dump .sf-dump-num {
                font-weight: bold;
                color: #1299da;
            }
            .phpdebugbar pre.sf-dump .sf-dump-const {
                font-weight: bold;
            }
            .phpdebugbar pre.sf-dump .sf-dump-str {
                font-weight: bold;
                color: #3a9b26;
            }
            .phpdebugbar pre.sf-dump .sf-dump-note {
                color: #1299da;
            }
            .phpdebugbar pre.sf-dump .sf-dump-ref {
                color: #7b7b7b;
            }
            .phpdebugbar pre.sf-dump .sf-dump-public {
                color: #000000;
            }
            .phpdebugbar pre.sf-dump .sf-dump-protected {
                color: #000000;
            }
            .phpdebugbar pre.sf-dump .sf-dump-private {
                color: #000000;
            }
            .phpdebugbar pre.sf-dump .sf-dump-meta {
                color: #b729d9;
            }
            .phpdebugbar pre.sf-dump .sf-dump-key {
                color: #3a9b26;
            }
            .phpdebugbar pre.sf-dump .sf-dump-index {
                color: #1299da;
            }
            .phpdebugbar pre.sf-dump .sf-dump-ellipsis {
                color: #a0a000;
            }
            .phpdebugbar pre.sf-dump .sf-dump-ns {
                user-select: none;
            }
            .phpdebugbar pre.sf-dump .sf-dump-ellipsis-note {
                color: #1299da;
            }
        </style>
    </head>

    <body style="font-family: 'Inter', sans-serif; background-color: #010022" class="tw-text-white tw-tracking-normal">
        <!-- Mobile menu bar -->
        <div id="navbar" class="tw-fixed tw-w-full tw-mt-0 tw-top-0 tw-text-gray-100 tw-flex tw-justify-between lg:tw-hidden drop-shadow-lg tw-z-[20]">
            <!-- Logo -->
            <a href="http://192.168.18.104:8081" class="tw-p-4 tw-text-white tw-font-bold tw-flex">
                <img src="http://192.168.18.104:8081/icons/MIDRAGON.png" class="tw-w-5 tw-h-5" />
                <span class="tw-font-extrabold tw-uppercase tw-tracking-widest tw-ml-2">MIDRAGON</span>
            </a>
            <!-- Mobile menu button -->
            <button class="mobile-menu-button tw-p-4 focus:tw-outline-none md:tw-hidden">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <!-- Mobile menu bar -->

        <!-- Sidebar -->
        <div id="overlay" class="tw-fixed tw-inset-0 tw-bg-black tw-opacity-50 tw-hidden tw-z-40"></div>
        <div class="sidebar bg-slate-900 tw-w-64 lg:tw-hidden md:tw-hidden no-scrollbar tw-overflow-y-scroll tw-bg-slate-950 tw-h-screen tw-fixed tw-inset-y-0 tw-left-0 tw-transform -tw-translate-x-full tw-transition tw-duration-200 tw-ease-in-out md:tw-relative md:tw-translate-x-0 tw-z-50">
            <!-- logo -->
            <div class="tw-px-4 tw-pt-4 tw-flex tw-justify-center">
                <a href="#" class="tw-flex tw-space-x-2">
                    <img src="http://192.168.18.104:8081/icons/MIDRAGON.png" class="tw-w-5 tw-h-5" />
                    <span class="tw-font-extrabold tw-uppercase tw-tracking-widest">MIDRAGON</span>
                </a>
            </div>

            <!-- nav -->
            <nav>
                <ul class="tw-list-none tw-p-0">
                    <li class="mid-menu-header">MAIN MENU</li>
                    <li class="mid-nav-link">
                        <a href="http://192.168.18.104:8081" class="mid-nav-link-child">
                            <i class="fas fa-home"></i>
                            <span class="tw-ml-3">Home</span>
                        </a>
                    </li>
                    <li class="mid-nav-link">
                        <a href="http://192.168.18.104:8081/articles" class="mid-nav-link-child">
                            <i class="fas fa-blog"></i>
                            <span class="tw-ml-3.5">Article</span>
                        </a>
                    </li>
                    <li class="mid-nav-link">
                        <a href="http://192.168.18.104:8081/project" class="mid-nav-link-child">
                            <i class="fas fa-project-diagram"></i>
                            <span class="tw-ml-3">Project/Kegiatan</span>
                        </a>
                    </li>
                    <li class="mid-nav-link">
                        <a href="http://192.168.18.104:8081/course" class="mid-nav-link-child">
                            <i class="fas fa-graduation-cap"></i>
                            <span class="tw-ml-3">Course</span>
                        </a>
                    </li>
                    <li class="mid-nav-link">
                        <a href="http://192.168.18.104:8081/religions" class="mid-nav-link-child">
                            <i class="fas fa-quran"></i>
                            <span class="tw-ml-4">Religions</span>
                        </a>
                    </li>
                    <li class="mid-nav-link">
                        <a href="http://192.168.18.104:8081/lectures" class="mid-nav-link-child">
                            <i class="fas fa-user-graduate"></i>
                            <span class="tw-ml-4">Lectures</span>
                        </a>
                    </li>
                </ul>
                <div class="tw-my-12"></div>
            </nav>
        </div>

        <nav id="navbar" class="tw-hidden tw-bg-transparent tw-py-5 tw-px-4 lg:tw-block">
            <div class="tw-container tw-max-w-7xl tw-mx-auto tw-flex tw-justify-between tw-items-center">
                <a href="http://192.168.18.104:8081" class="text-cyan-200 tw-tracking-widest tw-text-xl tw-font-bold">
                    <div class="tw-inline-flex hover:tw-bg-gray-900 hover:tw-rounded-lg hover:tw-px-3 hover:tw-py-2 tw-mt-2">
                        <img src="http://192.168.18.104:8081/icons/MIDRAGON.png" class="tw-w-8 tw-h-8 tw-rounded-full" />
                        <div class="tw-text-xs tw-tracking-wider tw-font-medium">
                            <p class="tw-ml-3 tw-text-cyan-400">Fahmi Ibrahim</p>
                            <p class="tw-ml-3">@fhmiibrhimdev</p>
                        </div>
                    </div>
                </a>
                <ul class="tw-flex tw-text-sm tw-space-x-4 tw-list-none">
                    <li><a href="http://192.168.18.104:8081" class="tw-text-cyan-400">Home</a></li>
                    <li><a href="http://192.168.18.104:8081/articles" class="tw-text-gray-400">Article</a></li>
                    <li><a href="http://192.168.18.104:8081/project" class="tw-text-gray-400">Project/Kegiatan</a></li>
                    <li><a href="http://192.168.18.104:8081/course" class="tw-text-gray-400">Course</a></li>
                    <li><a href="http://192.168.18.104:8081/religions" class="tw-text-gray-400">Religions</a></li>
                    <li><a href="http://192.168.18.104:8081/lectures" class="tw-text-gray-400">Lectures</a></li>
                </ul>
            </div>
        </nav>

        <div class="tw-flex tw-flex-col tw-min-h-screen tw-mt-20 md:tw-mt-20 lg:tw-mt-20" id="main-content">
            <main>
                <div class="tw-flex-grow tw-container tw-max-w-7xl tw-mx-auto">
                    <div class="tw-grid tw-grid-cols-1 tw-px-4 tw-gap-10 lg:tw-grid-cols-3 lg:tw-px-4">
                        <div class="tw-col-span-2">
                            <p class="tw-text-lg tw-text-cyan-300">Hello Everyone, I am</p>
                            <div id="typing-effect" class="tw-text-2xl tw-h-16 lg:tw-h-8 tw-font-bold tw-mt-2 tw-leading-relaxed tw-tracking-wide"></div>
                            <p class="tw-mt-10 tw-text-sm lg:tw-text-base tw-text-gray-300 tw-leading-relaxed tw-tracking-wide">Software Engineer with experience in developing applications integrated with IoT hardware. Adept in application design, server-side development, and technical problem-solving. Committed to continuous learning and innovation, with a passion for tackling new challenges in the tech industry.</p>
                            <div class="tw-mt-10 tw-flex">
                                <a href="http://192.168.18.104:8081/articles" class="tw-bg-white tw-px-4 tw-py-2 tw-rounded-full tw-text-black tw-text-sm">Let's Explore</a>
                                <a href="http://192.168.18.104:8081/images/CV_Fahmi_Ibrahim.pdf" class="tw-px-4 tw-py-2 tw-text-sm">
                                    <i class="fas fa-download tw-mr-2"></i>
                                    My Resume
                                </a>
                            </div>
                        </div>
                        <div class="tw-hidden md:tw-hidden lg:tw-block">
                            <div class="tw-ml-20">
                                <img class="tw-w-12/12 tw-rounded-full" src="http://192.168.18.104:8081/icons/my-photo2.png" alt="" />
                            </div>
                        </div>
                    </div>
                    <div class="tw-mt-16 tw-px-4 md:tw-px-4 lg:tw-mt-20 lg:tw-px-4">
                        <h4 class="tw-mt-3 tw-text-lg lg:tw-text-xl tw-font-medium tw-leading-6 tw-text-cyan-300">Work Experience</h4>
                        <div class="tw-flex tw-items-start tw-mt-8">
                            <img src="http://192.168.18.104:8081/icons/Intek.png" class="tw-w-14 lg:tw-w-20 tw-rounded-full tw-mr-5 mt-[-10px]" alt="" />
                            <div>
                                <p class="tw-font-medium tw-text-base lg:tw-text-lg">RnD - Mechatronics</p>
                                <p class="tw-text-sm tw-text-gray-300 tw-mt-1">
                                    <a href="https://intek.co.id/id/" target="_blank">PT. Solusi Intek Indonesia</a>
                                    • 3 June 2022 - 10 February 2024 • 1year 9mon
                                </p>
                                <div class="tw-flex tw-items-center tw-text-sm tw-text-cyan-300 tw-mt-5">
                                    <button class="tw-mr-2" id="see-more">See More</button>
                                    <i class="fas fa-angle-right"></i>
                                </div>
                            </div>
                        </div>
                        <div class="tw-text-gray-300 tw-ml-16 lg:tw-ml-24 tw-text-[13px] lg:tw-text-sm" style="display: none" id="description-see-more">
                            <ul class="tw-list-disc tw-mt-3 tw-space-y-2">
                                <li>Contributed to IoT research and development.</li>
                                <li>Managed server maintenance, domain administration, application development, and database optimization.</li>
                                <li>Handled hardware, software, and network troubleshooting.</li>
                                <li>Performed version control and documentation.</li>
                                <li>Developed electronic circuits, soldered components, and programmed microcontrollers.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="tw-mt-16 tw-px-4 md:tw-px-4 lg:tw-mt-20 lg:tw-px-4">
                        <h4 class="tw-mt-3 tw-text-lg lg:tw-text-xl tw-font-medium tw-leading-6 tw-text-cyan-300">Technology</h4>
                        <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-3 tw-gap-y-10 lg:tw-grid-cols-6 lg:tw-gap-x-20 tw-mt-7">
                            <div>
                                <p class="tw-text-gray-300 tw-font-medium tw-text-base tw-mb-5">Languages</p>
                                <ul id="tech-stack" class="tw-space-y-5 tw-p-0">
                                    <li class="tw-flex tw-items-center">
                                        <img src="http://192.168.18.104:8081/icons/HTML5.png" class="tw-rounded-lg tw-w-6 tw-mr-4" alt="" />
                                        <span>HTML5</span>
                                    </li>
                                    <li class="tw-flex tw-items-center">
                                        <img src="http://192.168.18.104:8081/icons/CSS3.png" class="tw-rounded-lg tw-w-6 tw-mr-4" alt="" />
                                        <span>CSS 3</span>
                                    </li>
                                    <li class="tw-flex tw-items-center">
                                        <img src="http://192.168.18.104:8081/icons/JavaScript.png" class="tw-rounded-lg tw-w-6 tw-mr-4" alt="" />
                                        <span>JavaScript</span>
                                    </li>
                                    <li class="tw-flex tw-items-center">
                                        <img src="http://192.168.18.104:8081/icons/php.svg" class="tw-rounded-lg tw-w-6 tw-mr-4" alt="" />
                                        <span>PHP</span>
                                    </li>
                                    <li class="tw-flex tw-items-center">
                                        <img src="http://192.168.18.104:8081/icons/Python.png" class="tw-rounded-lg tw-w-6 tw-mr-4" alt="" />
                                        <span>Python</span>
                                    </li>
                                    <li class="tw-flex tw-items-center">
                                        <img src="http://192.168.18.104:8081/icons/Dart.png" class="tw-rounded-lg tw-w-6 tw-mr-4" alt="" />
                                        <span>Dart</span>
                                    </li>
                                    <li class="tw-flex tw-items-center">
                                        <img src="http://192.168.18.104:8081/icons/Cplusplus.png" class="tw-rounded-lg tw-w-6 tw-mr-4" alt="" />
                                        <span>C++ Arduino</span>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <p class="tw-text-gray-300 tw-font-medium tw-text-base tw-mb-5">Databases</p>
                                <ul id="tech-stack" class="tw-space-y-5 tw-p-0">
                                    <li class="tw-flex tw-items-center">
                                        <img src="http://192.168.18.104:8081/icons/MySQL.png" class="tw-rounded-lg tw-w-6 tw-mr-4" alt="" />
                                        <span>MySQL</span>
                                    </li>
                                    <li class="tw-flex tw-items-center">
                                        <img src="http://192.168.18.104:8081/icons/MariaDB.png" class="tw-rounded-lg tw-w-6 tw-mr-4" alt="" />
                                        <span>MariaDB</span>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <p class="tw-text-gray-300 tw-font-medium tw-text-base tw-mb-5">JavaScript Library</p>
                                <ul id="tech-stack" class="tw-space-y-5 tw-p-0">
                                    <!-- <li class="tw-flex tw-items-center">
                                    <img src="http://192.168.18.104:8081/icons/ReactJS.png" class="tw-rounded-lg tw-w-6 tw-mr-4" alt="">
                                    <span>ReactJS</span>
                                </li> -->
                                    <li class="tw-flex tw-items-center">
                                        <img src="http://192.168.18.104:8081/icons/NodeJS.svg" class="tw-rounded-lg tw-w-6 tw-mr-4" alt="" />
                                        <span>NodeJS</span>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <p class="tw-text-gray-300 tw-font-medium tw-text-base tw-mb-5">Frameworks</p>
                                <ul id="tech-stack" class="tw-space-y-5 tw-p-0">
                                    <li class="tw-flex tw-items-center">
                                        <img src="http://192.168.18.104:8081/icons/Laravel.png" class="tw-rounded-lg tw-w-6 tw-mr-4" alt="" />
                                        <span>Laravel</span>
                                    </li>
                                    <li class="tw-flex tw-items-center">
                                        <img src="http://192.168.18.104:8081/icons/Flutter.png" class="tw-rounded-lg tw-w-5 tw-mr-5" alt="" />
                                        <span>Flutter</span>
                                    </li>
                                    <li class="tw-flex tw-items-center">
                                        <img src="http://192.168.18.104:8081/icons/TailwindCSS.png" class="tw-rounded-lg tw-w-6 tw-mr-4" alt="" />
                                        <span>TailwindCSS</span>
                                    </li>
                                    <li class="tw-flex tw-items-center">
                                        <img src="http://192.168.18.104:8081/icons/Bootstrap.png" class="tw-rounded-lg tw-w-7 tw-mr-3" alt="" />
                                        <span>Bootstrap</span>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <p class="tw-text-gray-300 tw-font-medium tw-text-base tw-mb-5">Microcontrollers</p>
                                <ul id="tech-stack" class="tw-space-y-5 tw-p-0">
                                    <li class="tw-flex tw-items-center">
                                        <img src="http://192.168.18.104:8081/icons/Arduino.png" class="tw-rounded-lg tw-w-5 tw-mr-5" alt="" />
                                        <span>Arduino</span>
                                    </li>
                                    <li class="tw-flex tw-items-center">
                                        <img src="http://192.168.18.104:8081/icons/ESP8266.png" class="tw-rounded-lg tw-w-6 tw-mr-4" alt="" />
                                        <span>ESP8266</span>
                                    </li>
                                    <li class="tw-flex tw-items-center">
                                        <img src="http://192.168.18.104:8081/icons/ESP32.png" class="tw-rounded-lg tw-w-6 tw-mr-4" alt="" />
                                        <span>ESP32</span>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <p class="tw-text-gray-300 tw-font-medium tw-text-base tw-mb-5">Others</p>
                                <ul id="tech-stack" class="tw-space-y-5 tw-p-0">
                                    <li class="tw-flex tw-items-center">
                                        <img src="http://192.168.18.104:8081/icons/jQuery.png" class="tw-rounded-lg tw-w-5 tw-mr-5" alt="" />
                                        <span>jQuery</span>
                                    </li>
                                    <li class="tw-flex tw-items-center">
                                        <img src="http://192.168.18.104:8081/icons/Github.png" class="tw-rounded-lg tw-w-6 tw-mr-4" alt="" />
                                        <span>GitHub</span>
                                    </li>
                                    <li class="tw-flex tw-items-center">
                                        <img src="http://192.168.18.104:8081/icons/Postman.svg" class="tw-rounded-lg tw-w-6 tw-mr-4" alt="" />
                                        <span>Postman</span>
                                    </li>
                                    <li class="tw-flex tw-items-center">
                                        <img src="http://192.168.18.104:8081/icons/EasyEDA.jpg" class="tw-rounded-lg tw-w-6 tw-mr-4" alt="" />
                                        <span>EasyEDA</span>
                                    </li>
                                    <li class="tw-flex tw-items-center">
                                        <img src="http://192.168.18.104:8081/icons/MQTT.png" class="tw-rounded-lg tw-w-6 tw-mr-4" alt="" />
                                        <span>MQTT</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="tw-mt-16 tw-px-4 md:tw-px-4 lg:tw-mt-20 lg:tw-px-4">
                        <h4 class="tw-mt-3 tw-text-lg lg:tw-text-xl tw-font-medium tw-leading-6 tw-text-cyan-300">Credentials</h4>
                        <div class="tw-flex tw-items-start tw-mt-10">
                            <img src="http://192.168.18.104:8081/icons/Udemy.jpg" class="tw-w-16 tw-h-16 lg:tw-w-20 lg:tw-h-20 tw-rounded-full" alt="" />
                            <div class="tw-ml-4 tw-mt-1">
                                <h4 class="tw-text-base lg:tw-text-lg">
                                    NodeJS Course PZN
                                    <a href="http://192.168.18.104:8081/images/NodeJS_Course_Udemy.jpg" target="_BLANK"><i class="fas fa-external-link tw-ml-3 tw-text-sm lg:tw-text-base tw-text-gray-400"></i></a>
                                </h4>
                                <p class="tw-text-gray-400 tw-mt-2">Udemy • August 2023 • No Expired</p>
                            </div>
                        </div>
                        <div class="tw-flex tw-items-start tw-mt-7">
                            <img src="http://192.168.18.104:8081/icons/Intek.png" class="tw-w-16 lg:tw-w-20 tw-rounded-full" alt="" />
                            <div class="tw-ml-4 tw-mt-3">
                                <h4 class="tw-text-base lg:tw-text-lg">
                                    Divisi Mekatronika - Magang
                                    <a href="http://192.168.18.104:8081/images/Sertifikat_PT_Solusi_Intek.pdf" target="_BLANK"><i class="fas fa-external-link tw-ml-3 tw-text-sm lg:tw-text-base tw-text-gray-400"></i></a>
                                </h4>
                                <p class="tw-text-gray-400 tw-mt-2">PT. Solusi Intek Indonesia • May 2023 • No Expired</p>
                            </div>
                        </div>
                        <div class="tw-flex tw-items-start tw-mt-7">
                            <img src="http://192.168.18.104:8081/icons/SMKN5.png" class="tw-w-16 tw-h-16 lg:tw-ml-0 tw-ml-2 lg:tw-w-20 lg:tw-h-20 tw-rounded-full" alt="" />
                            <div class="tw-ml-4 tw-mt-0">
                                <h4 class="tw-text-base lg:tw-text-lg">
                                    SMKN 5 JAKARTA
                                    <a href="http://192.168.18.104:8081/images/Sertifikat_SMKN5JKT.pdf" target="_BLANK"><i class="fas fa-external-link tw-ml-3 tw-text-sm lg:tw-text-base tw-text-gray-400"></i></a>
                                </h4>
                                <p class="tw-text-gray-400 tw-mt-2">SMKN 5 Jakarta • May 2023 • May 2023 - May 2026</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="tw-bg-transparent tw-text-gray-500 tw-py-8 tw-mt-32">
                <div class="tw-container tw-mx-auto tw-text-center">
                    <div id="social-media" class="tw-hidden tw-justify-center tw-items-center tw-space-x-10 tw-mb-14 lg:tw-flex">
                        <ul class="tw-flex tw-space-x-10 tw-text-sm tw-text-gray-400 tw-font-semibold tw-list-none">
                            <li class="tw-ml-[-40px]"><a href="#" class="">Home</a></li>
                            <li><a href="#">Article</a></li>
                            <li><a href="#">Project/Kegiatan</a></li>
                            <li><a href="#">Course</a></li>
                            <li><a href="#">Religions</a></li>
                            <li><a href="#">Lectures</a></li>
                        </ul>
                    </div>
                    <div id="social-media" class="tw-flex tw-justify-center tw-items-center tw-space-x-10 tw-mb-14">
                        <a href="https://facebook.com/fahmiibrahimdev" target="_BLANK"><i class="fab fa-facebook tw-text-2xl"></i></a>
                        <a href="https://instagram.com/fahmiibrahimdev_" target="_BLANK"><i class="fab fa-instagram tw-text-2xl"></i></a>
                        <a href="https://github.com/fhmiibrhimdev/" target="_BLANK"><i class="fab fa-github tw-text-2xl"></i></a>
                        <a href="https://www.youtube.com/@midracode" target="_BLANK"><i class="fab fa-youtube tw-text-2xl"></i></a>
                        <a href="https://www.linkedin.com/in/fahmiibrahimdev/" target="_BLANK"><i class="fab fa-linkedin tw-text-2xl"></i></a>
                    </div>
                    <div id="copyright" class="tw-text-sm">
                        <p>&copy; 2023 Midragon. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Livewire Scripts -->
        <script>
            const seeMoreText = document.getElementById('see-more');
            const descriptionSeeMore = document.getElementById('description-see-more');

            seeMoreText.addEventListener('click', () => {
                if (descriptionSeeMore.style.display === 'none') {
                    descriptionSeeMore.style.display = 'block';
                } else {
                    descriptionSeeMore.style.display = 'none';
                }
            });
        </script>
        <script>
            const textElement = document.getElementById('typing-effect');
            const textsToType = ['Fahmi Ibrahim', 'Software Enginner', 'Studying at Politeknik Negeri Jakarta.'];
            let currentTextIndex = 0;

            function typeText(text, delay) {
                let index = 0;
                const typingInterval = setInterval(function () {
                    textElement.textContent += text[index];
                    index++;

                    if (index === text.length) {
                        clearInterval(typingInterval);
                        setTimeout(function () {
                            eraseText(text, delay);
                        }, 1000); // Jeda sebelum menghapus teks
                    }
                }, delay);
            }

            function eraseText(text, delay) {
                const erasingInterval = setInterval(function () {
                    textElement.textContent = textElement.textContent.slice(0, -1);

                    if (textElement.textContent === '') {
                        clearInterval(erasingInterval);
                        currentTextIndex = (currentTextIndex + 1) % textsToType.length; // Ganti ke teks berikutnya
                        setTimeout(function () {
                            typeText(textsToType[currentTextIndex], delay);
                        }, 500); // Jeda sebelum mengetik ulang
                    }
                }, delay / 2);
            }

            // Memulai efek tulisan mengetik pertama kali
            typeText(textsToType[currentTextIndex], 100);
        </script>
        <script src="https://unpkg.com/flowbite@1.4.7/dist/flowbite.js"></script>
        <script src="http://192.168.18.104:8081/assets/katex/katex.min.js"></script>
        <script>
            const btn = document.querySelector('.mobile-menu-button');
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('#overlay');
            const body = document.querySelector('body');

            btn.addEventListener('click', () => {
                sidebar.classList.toggle('-tw-translate-x-full');
                overlay.classList.toggle('tw-hidden');
                body.classList.toggle('tw-overflow-hidden');
                if (!sidebar.classList.contains('-tw-translate-x-full')) {
                    overlay.classList.remove('tw-hidden');
                    body.classList.add('tw-overflow-hidden');
                } else {
                    overlay.classList.add('tw-hidden');
                    body.classList.remove('tw-overflow-hidden');
                }
            });

            overlay.addEventListener('click', () => {
                sidebar.classList.add('-tw-translate-x-full');
                overlay.classList.add('tw-hidden');
                body.classList.remove('tw-overflow-hidden');
            });
        </script>
        <script>
            const navbar = document.getElementById('navbar');

            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    navbar.style.backgroundColor = 'rgba(1, 0, 27, 0.5)';
                    navbar.style.backdropFilter = 'blur(5px)';
                } else {
                    navbar.style.backgroundColor = 'transparent';
                    navbar.style.backdropFilter = 'none';
                }
            });
        </script>
    </body>
</html>
