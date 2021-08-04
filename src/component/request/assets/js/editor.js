/*
* NovemBit i18n framework (c)
* Request Editor
* */
(function () {
    window.novembit.i18n.editor = {
        ...window.novembit.i18n.editor,
        ...{
            wrapper: document.createElement('div'),
            selectors: document.createElement('div'),
            context_menu: document.createElement('menu'),
            text_node_selector: "[" + window.novembit.i18n.prefix + "-text]",
            attr_node_selector: "[" + window.novembit.i18n.prefix + "-attr]",
            node_num_attr: window.novembit.i18n.prefix + '-selector-num',
            active_node_class: window.novembit.i18n.prefix + '-active',
            last_node_index: 0,
            translation_svg: '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#ffffff"><path d="M0 0h24v24H0z" fill="none"/><path d="M12.87 15.07l-2.54-2.51.03-.03c1.74-1.94 2.98-4.17 3.71-6.53H17V4h-7V2H8v2H1v1.99h11.17C11.5 7.92 10.44 9.75 9 11.35 8.07 10.32 7.3 9.19 6.69 8h-2c.73 1.63 1.73 3.17 2.98 4.56l-5.09 5.02L4 19l5-5 3.11 3.11.76-2.04zM18.5 10h-2L12 22h2l1.12-3h4.75L21 22h2l-4.5-12zm-2.62 7l1.62-4.33L19.12 17h-3.24z"/></svg>',
            html_tags: {
                'a': 'Anchor',
                'p': 'Paragraph',
                'img': 'Image',
                'button': 'Button',
                'h1': 'Heading',
                'h2': 'Heading',
                'h3': 'Heading',
                'h4': 'Heading',
                'h5': 'Heading',
                'h6': 'Heading',
                'video': 'Video',
                'form': 'Form',
            },
            updateNodeText: function (node, index, value, prefix, suffix) {
                if (node.childNodes[index].nodeType !== 3) {
                    index++;
                    this.updateNodeText(node, index, value, prefix, suffix);
                } else {
                    node.childNodes[index].nodeValue = prefix + value + suffix;
                }
            },
            updateNodeAttr: function (node, attr_key, value, prefix, suffix) {
                node.setAttribute(attr_key, prefix + value + suffix);
            },
            submitForm: function (form) {
                let params = new FormData(form);
                let http = new XMLHttpRequest();
                let url = window.location.href;
                http.open('POST', url, true);
                http.onreadystatechange = function () {//Call a function when the state changes.
                    if (http.readyState === 4 && http.status === 200) {
                        form.classList.add('saved');
                        let submit = form.getElementsByClassName('loading');
                        submit[0].classList.remove('loading');
                    }
                };
                http.send(params);
            },
            initNodeInspector: function (node) {
                let editor = this;
                let inspector = node.selector.querySelector('.inspector');
                if (typeof inspector === 'undefined' || inspector === null) {

                    inspector = document.createElement('div');
                    inspector.classList.add(window.novembit.i18n.prefix + '-editor-inspector');
                    inspector.classList.add('inspector');

                    let unexpand = document.createElement('div');
                    unexpand.classList.add('unexpand');
                    unexpand.onclick = function (e) {
                        e.stopPropagation();
                        editor.unExpandSelector(node);
                    };

                    let title = document.createElement('div');
                    title.classList.add('title');

                    //  let description = document.createElement('div');
                    //  description.classList.add('description');
                    // description.innerText = "Texts: " + node.data.text.length + " | Attributes: " + Object.keys(node.data.attr).length;
                    //console.log(node)
                    title.innerText = this.getNodeType(node) + ' ' + '<' + node.nodeName.toLowerCase() + '>';
                    editor.getNodeType(node);
                    inspector.appendChild(unexpand);
                    inspector.appendChild(title);
                    //inspector.appendChild(description);

                    let forms = document.createElement('div'), submit, form;
                    forms.classList.add('forms');

                    form = document.createElement('form');
                    form.method = 'post';
                    form.classList.add('form');
                    form.classList.add('form-texts');

                    /*
                    * Texts form
                    * */
                    // if (Object.keys(node.data.text).length > 0) {
                    //     let title = document.createElement('h4');
                    //     title.innerText = "Content";
                    //     title.classList.add('title');
                    //     form.appendChild(title);
                    // }

                    for (let i = 0; i < node.data.text.length; i++) {

                        let input = document.createElement('textarea');
                        let label = document.createElement('label');
                        //label.innerText = "Text " + (i + 1);
                        input.value = node.data.text[i][1];
                        input.name = window.novembit.i18n.prefix + "-form[" + node.data.text[i][2] + "][" + node.data.text[i][0] + "]";

                        input.oninput = function () {
                            editor.updateNodeText(node, i, this.value, node.data.text[i][4], node.data.text[i][5]);
                            if (this.form.classList.contains('saved')) {
                                this.form.classList.remove('saved');
                            }
                        };

                        let original = document.createElement('div');
                        original.classList.add('original');
                        original.textContent = node.data.text[i][0];
                        original.classList.add('level-' + node.data.text[i][3] + '');


                        label.appendChild(original);

                        label.appendChild(input);
                        form.appendChild(label);
                    }

                    forms.appendChild(form);

                    /*
                    * Attr form
                    * */
                    if (Object.keys(node.data.attr).length > 0) {
                        // let title = document.createElement('div');
                        // title.classList.add('title');
                        // title.innerText = "Attributes";
                        // form.appendChild(title);
                    }

                    for (let attr_key in node.data.attr) {

                        if (!node.data.attr.hasOwnProperty(attr_key)) continue;

                        let label = document.createElement('label');
                        label.classList.add('form-label');
                        label.innerHTML = 'attribute: ' + '<b>' + attr_key + '</b>';

                        let original = document.createElement('div');
                        original.classList.add('original');
                        original.textContent = node.data.attr[attr_key][0];
                        original.classList.add('level-' + node.data.attr[attr_key][3] + '');

                        label.appendChild(original);

                        let input = document.createElement('textarea');
                        input.name = window.novembit.i18n.prefix + "-form[" + node.data.attr[attr_key][2] + "][" + node.data.attr[attr_key][0] + "]";
                        input.value = node.data.attr[attr_key][1];
                        input.oninput = function () {
                            editor.updateNodeAttr(node, attr_key, this.value, node.data.attr[attr_key][4], node.data.attr[attr_key][5]);
                            if (this.form.classList.contains('saved')) {
                                this.form.classList.remove('saved');
                            }
                        };

                        label.appendChild(input);
                        form.appendChild(label);
                    }

                    submit = document.createElement('button');
                    submit.type = 'submit';
                    submit.innerText = "Save";
                    form.appendChild(submit);
                    form.onsubmit = function (e) {
                        submit.classList.add('loading');
                        e.preventDefault();
                        editor.submitForm(form);
                    };
                    forms.appendChild(form);
                    inspector.appendChild(forms);

                    node.selector.appendChild(inspector);

                } else {
                    inspector.classList.add('visible');
                }

                return inspector;
            },
            markNode: function (node) {
                node.classList.add(this.active_node_class);
            },
            unMarkNode: function (node) {
                node.classList.remove(this.active_node_class);
            },
            getNodeOffset: function (node) {
                let top = 0, left = 0;
                do {
                    top += node.offsetTop || 0;
                    left += node.offsetLeft || 0;
                    node = node.offsetParent;
                } while (node);

                return {
                    top: top,
                    left: left
                };
            },
            isNodeFixed: function (node) {
                while (typeof node === 'object' && node !== null && node.nodeName.toLowerCase() !== 'body') {
                    if (window.getComputedStyle(node).getPropertyValue('position').toLowerCase() === 'fixed') return true;
                    node = node.parentElement;
                }
                return false;
            },
            getNodeAttr: function (node) {
                if (node.hasAttribute(window.novembit.i18n.prefix + '-attr')) {
                    let string = node.getAttribute(window.novembit.i18n.prefix + '-attr');
                    return JSON.parse(string);
                }
                return {};
            },
            getNodeText: function (node) {
                if (node.hasAttribute(window.novembit.i18n.prefix + '-text')) {
                    let string = node.getAttribute(window.novembit.i18n.prefix + '-text');
                    return JSON.parse(string);
                }
                return {};
            },
            getNodeData: function (node) {
                return {
                    attr: this.getNodeAttr(node),
                    text: this.getNodeText(node),
                };
            },
            getNodeType: function (node) {
                const nodeName = node.tagName.toLowerCase();
                if (this.html_tags[nodeName]) {
                    return this.html_tags[nodeName];
                } else {
                    return 'Text';
                }
            },
            activeSelector: function (node) {
                let siblings = node.selector.parentElement.getElementsByClassName('active');
                for (let i = 0; i < siblings.length; i++) {
                    siblings[i].classList.remove('active');
                }
                node.selector.classList.add('active');
            },
            inactiveSelector: function (node) {
                node.selector.classList.remove('active');
            },
            expandSelector: function (node) {
                let siblings = node.selector.parentElement.getElementsByClassName('expanded');
                for (let i = 0; i < siblings.length; i++) {
                    siblings[i].classList.remove('expanded');
                }
                node.selector.classList.add('expanded');
            },
            unExpandSelector: function (node) {
                node.selector.classList.remove('expanded');
            },
            showNodeContextMenu: function (node) {
                let editor = this;
            },
            contextMenuHide() {
                this.context_menu.classList.remove("shown");
            },
            initSelectors: function () {
                let editor = this;
                let nodes = document.querySelectorAll(this.text_node_selector + ',' + this.attr_node_selector);
                for (let i = 0; i < nodes.length; i++) {
                    this.last_node_index++;
                    let key = this.last_node_index;
                    let node = nodes[i], nodePos = this.getNodeOffset(node);

                    if (!node.hasOwnProperty('data')) {

                        node.data = editor.getNodeData(node);

                        node.selector = document.createElement('div');
                        node.selector.mainNode = node;
                        node.selector.classList.add('selector');
                        node.setAttribute(this.node_num_attr, key);
                        node.selector.setAttribute('n', key);
                        const content_text = editor.getNodeContentData(node).join(', ').replace(/[\n\r]/g, "").substr(0, 40) ;
                        const string_bold = document.createElement('b');
                        string_bold.innerText = '<' + node.tagName.toLowerCase() + '> ';
                        node.selector.innerText = content_text;
                        node.selector.prepend(string_bold);

                        node.onmouseover = function () {
                            editor.initNodeInspector(node);
                            editor.markNode(node);
                        };
                        node.onmouseout = function () {
                            editor.unMarkNode(node);
                        };

                        node.addEventListener("contextmenu", function (e) {
                            e.preventDefault();
                            let posX = (e.clientX + 150 > document.documentElement.clientWidth) ? e.clientX - 150 : e.clientX;
                            let posY = (e.clientY + 140 + 55 > document.documentElement.clientHeight) ? e.clientY - 140 : e.clientY;
                            editor.context_menu.style.top = posY + "px";
                            editor.context_menu.style.left = posX + "px";
                            editor.context_menu.classList.add("shown");
                            editor.context_menu.querySelector('.translate').onclick = function () {
                                editor.activeSelector(node);
                                editor.expandSelector(node);
                                editor.context_menu.classList.remove("shown");
                            }
                            e.stopPropagation();
                        });

                        node.selector.onclick = function () {
                            editor.activeSelector(node);
                            editor.expandSelector(node);
                        };
                        /*
                        * On mouse hovered on selector
                        * */
                        node.selector.onmouseover = function () {
                            editor.activeSelector(node);
                            editor.markNode(node);
                            editor.initNodeInspector(node);
                        };
                        /*
                        * Mouse leave selector
                        * */
                        node.selector.onmouseout = function () {
                            editor.inactiveSelector(node);
                            editor.unMarkNode(node);
                        };

                        if (node.data.hasOwnProperty('attr')) {
                            for (let _attr in node.data.attr) {
                                if (node.data.attr.hasOwnProperty(_attr)) {
                                    if (node.data.attr[_attr][2] === 'url') {
                                        let url = node.getAttribute(_attr);
                                        url = this.addParameterToURL(url, window.novembit.i18n.prefix + '-' + this.query_key, 1);
                                        node.setAttribute(_attr, url);
                                    }
                                }
                            }
                        }

                        if (typeof node.data.text[0] !== 'undefined' && typeof node.data.text[0][3] !== 'undefined') {
                            node.selector.classList.add('level-' + node.data.text[0][3] + '-bg');
                        }

                        this.selectors.appendChild(node.selector);
                    } else {
                        key = node.getAttribute(this.node_num_attr);
                        node.selector = editor.selectors.querySelector("[n=\"" + key + "\"]");
                    }
                }
            },
            addParameterToURL: function (url, key, value) {
                url = new URL(url, document.baseURI);
                url.searchParams.set(key, value);
                return url.href;
            },
            initContextMenu: function () {
                let editor = this;

                this.context_menu.id = window.novembit.i18n.prefix + "-context-menu";

                let item;

                item = document.createElement('a');
                item.classList.add("translate");
                item.innerText = "Translate node";
                this.context_menu.appendChild(item);

                item = document.createElement('hr');
                this.context_menu.appendChild(item);

                item = document.createElement('a');
                item.href = "javascript:;";
                item.onclick = function () {
                    editor.filterResults(':meta:');
                    editor.contextMenuHide();
                    editor.wrapper.classList.add(window.novembit.i18n.prefix + "-show");
                };
                item.classList.add("translate_meta");
                item.innerText = "Translate page meta";
                this.context_menu.appendChild(item);

                item = document.createElement('a');
                item.onclick = function () {
                    let node = document.querySelector('meta[type="i18n-current-url"]');
                    editor.initNodeInspector(node);
                    editor.activeSelector(node);
                    editor.expandSelector(node);
                    editor.contextMenuHide();
                };
                item.classList.add("translate_url_slug");
                item.innerText = "Translate page URL";
                this.context_menu.appendChild(item);

                item = document.createElement('a');
                item.href = "javascript:;";
                item.onclick = function () {
                    editor.filterResults('');
                    editor.contextMenuHide();
                    editor.wrapper.classList.add(window.novembit.i18n.prefix + "-show");
                };
                item.classList.add("translate_all");
                item.innerText = "Show all";
                this.context_menu.appendChild(item);

                document.body.appendChild(this.context_menu);

                document.addEventListener("click", function (e) {
                    let target = e.target;
                    while (target.nodeType !== Node.DOCUMENT_NODE) {
                        if (target === editor.context_menu) return;
                        else target = target.parentNode;
                    }
                    editor.context_menu.classList.remove("shown");
                });
            },
            meta_tags: [
                'title',
                'link',
                'meta'
            ],
            getNodeContentData(node, include_translations = false) {
                if (!node.hasOwnProperty('data')) {
                    return;
                }
                let result = [];
                const data = node.data;

                for (const type in data) {
                    if (data.hasOwnProperty(type) && data[type] !== null) {
                        let contents = data[type];
                        if (typeof contents === 'object' && contents !== null) {
                            for (const content_key in contents) {
                                if (contents.hasOwnProperty(content_key)) {
                                    let content = contents[content_key];
                                    if(content) {
                                        if(content[0]) {
                                            result.push(content[0]);
                                        }
                                        if(include_translations) {
                                            if (content[1]) {
                                                result.push(content[1]);
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            contents.forEach(function (content) {
                                if(content) {
                                    if(content[0]) {
                                        result.push(content[0].trim());
                                    }
                                    if(include_translations) {
                                        if (content[1]) {
                                            result.push(content[1].trim());
                                        }
                                    }
                                }
                            });
                        }
                    }
                }
                return result;
            },
            filterResults: function (query) {
                const editor = window.novembit.i18n.editor;

                // (C) GET THE SEARCH TERM
                query = query.toLowerCase();

                // (D) GET ALL LIST ITEMS
                const all = editor.selectors.querySelectorAll(":scope > div.selector");

                let meta = null;
                if (query === ':meta:') {
                    meta = true;
                }

                // (E) LOOP THROUGH LIST ITELS - ONLY SHOW ITEMS THAT MATCH SEARCH
                for (let i of all) {

                    let label = '';

                    if (!i.hasOwnProperty('mainNode')) {
                        continue;
                    }

                    let content_string = editor.getNodeContentData(i.mainNode, true).join(', ').toLowerCase();

                    if (meta) {
                        if (!editor.meta_tags.includes(i.mainNode.tagName.toLowerCase())) {
                            i.classList.add("hidden");
                        } else {
                            i.classList.remove("hidden");
                        }
                    } else {
                        if (content_string.indexOf(query) === -1) {
                            i.classList.add("hidden");
                        } else {
                            i.classList.remove("hidden");
                        }
                    }
                }

                return true;
            },
            setupSelectorsSearch: function () {
                const editor = window.novembit.i18n.editor;
                const searchForm = document.createElement('div');
                searchForm.classList.add('search-container');
                const inputField = document.createElement('input');

                let oldValue = inputField.value;
                setInterval(function () {
                        if (oldValue !== inputField.value) {
                            editor.filterResults(inputField.value);
                            oldValue = inputField.value;
                        }
                    }, 150
                );

                inputField.type = 'text';
                inputField.placeholder = 'Search texts';
                searchForm.appendChild(inputField);
                this.wrapper.appendChild(searchForm);
            },
            start: function () {
                document.body.appendChild(this.wrapper);
                this.setupSelectorsSearch();
                this.selectors.classList.add('selectors');
                this.wrapper.appendChild(this.selectors);
                this.wrapper.id = window.novembit.i18n.prefix + "-editor-wrapper";
                const toggle = document.createElement('button');
                toggle.innerHTML = this.translation_svg;
                toggle.className = window.novembit.i18n.prefix + '-wrapper__toggle';
                this.wrapper.prepend(toggle);
                this.initContextMenu();
                this.initSelectors();

                toggle.addEventListener('click', event => {
                    this.wrapper.classList.toggle(window.novembit.i18n.prefix + '-show');
                    event.stopPropagation();
                });

            },
            update: function () {
                this.initSelectors();
            }
        }
    };

    function stopScroll(el, callback) {
        let a = true;
        el.onscroll = function () {
            if (a === true) {
                a = false;
                setTimeout(function () {
                    a = true;
                    callback();
                }, 500);
            }
        }
    }

    document.addEventListener("DOMContentLoaded", function (event) {
        if (window.novembit.i18n.editor.is_editor) {
            window.novembit.i18n.editor.start();
        }
    });

    stopScroll(window, function () {
        window.novembit.i18n.editor.update();
    });

})();