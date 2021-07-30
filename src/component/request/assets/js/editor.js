/*
* NovemBit i18n framework (c)
* Request Editor
* */
(function () {

    window.novembit.i18n.editor = {
        ...window.novembit.i18n.editor,
        ...{
            wrapper: document.createElement('div'),
            context_menu: document.createElement('menu'),
            text_node_selector: "[" + window.novembit.i18n.prefix + "-text]",
            attr_node_selector: "[" + window.novembit.i18n.prefix + "-attr]",
            node_num_attr: window.novembit.i18n.prefix + '-selector-num',
            active_node_class: window.novembit.i18n.prefix + '-active',
            last_node_index: 0,
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
                    inspector.classList.add('inspector');

                    let unexpand = document.createElement('div');
                    unexpand.classList.add('unexpand');
                    unexpand.onclick = function (e) {
                        e.stopPropagation();
                        editor.unexpandSelector(node);
                    };

                    let title = document.createElement('div');
                    title.classList.add('title');

                    //  let description = document.createElement('div');
                    //  description.classList.add('description');
                    // description.innerText = "Texts: " + node.data.text.length + " | Attributes: " + Object.keys(node.data.attr).length;
                    //console.log(node)
                    title.innerText = this.getNodeType(node) +' '+ '<' + node.nodeName.toLowerCase() + '>';
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
                        input.name = window.novembit.i18n.prefix + "-form[" + node.data.text[i][0] + "]";

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

                        if (node.data.text[i][2] !== 'text') {
                            input.disabled = true;
                            let hint = document.createElement('p');
                            hint.innerText = '* Is not text value.';
                            hint.classList.add('hint');
                            label.appendChild(hint);
                        }

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
                        label.innerHTML = 'attribute: '+ '<b>' + attr_key + '</b>';

                        let original = document.createElement('div');
                        original.classList.add('original');
                        original.textContent = node.data.attr[attr_key][0];
                        original.classList.add('level-' + node.data.attr[attr_key][3] + '');

                        label.appendChild(original);

                        let input = document.createElement('textarea');
                        input.name = window.novembit.i18n.prefix + "-form[" + node.data.attr[attr_key][0] + "]";
                        input.value = node.data.attr[attr_key][1];
                        input.oninput = function () {
                            editor.updateNodeAttr(node, attr_key, this.value, node.data.attr[attr_key][4], node.data.attr[attr_key][5]);
                            if (this.form.classList.contains('saved')) {
                                this.form.classList.remove('saved');
                            }
                        };

                        if (node.data.attr[attr_key][2] !== 'text') {
                            input.disabled = true;
                            let hint = document.createElement('span');
                            hint.innerText = '* url slug';
                            hint.classList.add('hint');
                            original.appendChild(hint);
                        }

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
                switch (node.nodeName) {
                    case 'A':
                        return 'Anchor';
                        break;
                    case 'P':
                        return 'Paragraph';
                    case 'IMG':
                        return 'Image';
                        break;
                    case 'BUTTON':
                        return 'Button';
                        break;
                    case 'H1':
                    case 'H2':
                    case 'H3':
                    case 'H4':
                    case 'H5':
                    case 'H6':
                        return 'Heading';
                        break;
                    case 'VIDEO':
                        return 'Video';
                        break;
                    case 'AUDIO':
                        return 'Audio';
                        break;
                    case 'FORM':
                        return 'Form';
                        break;
                    default:
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
            unexpandSelector: function (node) {
                node.selector.classList.remove('expanded');
            },
            showNodeContextMenu: function (node) {
                let editor = this;
            },
            initSelectors: function () {
                let editor = this;
                let nodes = document.body.querySelectorAll(this.text_node_selector + ',' + this.attr_node_selector);
                for (let i = 0; i < nodes.length; i++) {
                    this.last_node_index++;
                    let key = this.last_node_index;
                    let node = nodes[i], nodePos = this.getNodeOffset(node);

                    if (!node.hasOwnProperty('data')) {

                        node.data = editor.getNodeData(node);

                        node.selector = document.createElement('div');
                        node.setAttribute(this.node_num_attr, key);
                        node.selector.setAttribute('n', key);

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

                        this.wrapper.appendChild(node.selector);
                    } else {
                        key = node.getAttribute(this.node_num_attr);
                        node.selector = editor.wrapper.querySelector("[n=\"" + key + "\"]");
                    }


                    node.selector.style.position = this.isNodeFixed(node) ? 'fixed' : 'absolute';
                    node.selector.style.top = nodePos.top + "px";
                    node.selector.style.left = nodePos.left + "px";
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

                let item = document.createElement('a');
                item.href = "javascript:;";
                item.classList.add("title");
                item.innerHTML = "<b>Menu</b>";

                this.context_menu.appendChild(item);

                item = document.createElement('a');
                item.href = "javascript:;";
                item.classList.add("translate");
                item.innerText = "Translate";

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
            start: function () {
                document.body.appendChild(this.wrapper);
                this.wrapper.id = window.novembit.i18n.prefix + "-editor-wrapper";
                this.initContextMenu();
                this.initSelectors();
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
        window.novembit.i18n.editor.start();
    });

    stopScroll(window, function () {
        window.novembit.i18n.editor.update();
    });

})();