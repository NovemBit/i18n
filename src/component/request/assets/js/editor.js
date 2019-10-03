/*
* NovemBit i18n framework (c)
* Request Editor
* */
(function () {

    window.novembit.i18n.editor = {
        wrapper: document.createElement('div'),
        text_node_selector: "[" + window.novembit.i18n.prefix + "-text]",
        attr_node_selector: "[" + window.novembit.i18n.prefix + "-attr]",
        node_num_attr: window.novembit.i18n.prefix + '-selector-num',
        active_node_class: window.novembit.i18n.prefix + '-active',
        last_node_index: 0,
        updateNodeText: function (node, index, value) {
            node.childNodes[index].nodeValue = value;
            console.log("Text Update");
        },
        updateNodeAttr: function (node, attr_key, value) {
            node.setAttribute(attr_key,value);
            console.log("Attr Update");
        },
        submitForm: function (form) {

            let params = new FormData(form);


            var http = new XMLHttpRequest();
            var url = window.location.href;
            // var params = 'orem=ipsum&name=binny';
            http.open('POST', url, true);

            http.onreadystatechange = function() {//Call a function when the state changes.
                if(http.readyState === 4 && http.status === 200) {
                    form.classList.add('saved');
                }
            };

            http.send(params);

            console.log('Submit form.');
        },
        initNodeInspector: function (selector, node) {
            let editor = this;
            let inspector = selector.querySelector('.inspector');
            if (typeof inspector === 'undefined' || inspector === null) {
                let data = editor.getNodeData(node);
                inspector = document.createElement('div');
                inspector.classList.add('inspector');

                let title = document.createElement('div');
                title.classList.add('title');

                let description = document.createElement('div');
                description.classList.add('description');
                description.innerText = "Texts: " + data.text.length + " | Attributes: " + Object.keys(data.attr).length;

                title.innerText = node.nodeName;
                inspector.appendChild(title);
                inspector.appendChild(description);

                let forms = document.createElement('div'), submit, form;
                forms.classList.add('forms');

                form = document.createElement('form');
                form.method = 'post';
                form.classList.add('form');
                form.classList.add('form-texts');

                /*
                * Texts form
                * */
                if (Object.keys(data.text).length > 0) {
                    let title = document.createElement('h4');
                    title.innerText = "Texts";
                    title.classList.add('title');
                    form.appendChild(title);
                }

                for (let i = 0; i < data.text.length; i++) {
                    let input = document.createElement('input');
                    let label = document.createElement('label');
                    label.innerText = "Text " + (i + 1);
                    input.type = 'text';
                    input.value = data.text[i][1];
                    input.name = window.novembit.i18n.prefix + "-form[" + data.text[i][0] + "]";

                    input.oninput = function (e) {
                        editor.updateNodeText(node, i, this.value);

                        if(this.form.classList.contains('saved')){
                            this.form.classList.remove('saved');
                        }
                    };

                    let original = document.createElement('div');
                    original.classList.add('original');
                    original.textContent = data.text[i][0];

                    label.appendChild(original);

                    if (data.text[i][2] !== 'text') {
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
                if (Object.keys(data.attr).length > 0) {
                    let title = document.createElement('div');
                    title.classList.add('title');
                    title.innerText = "Attributes";
                    form.appendChild(title);
                }

                for (let attr_key in data.attr) {

                    if (!data.attr.hasOwnProperty(attr_key)) continue;

                    let label = document.createElement('label');
                    label.classList.add('form-label');
                    label.innerText = attr_key;

                    let original = document.createElement('div');
                    original.classList.add('original');
                    original.textContent = data.attr[attr_key][0];

                    label.appendChild(original);

                    let input = document.createElement('input');
                    input.name = window.novembit.i18n.prefix + "-form[" + data.attr[attr_key][0] + "]";
                    input.type = 'text';
                    input.value = data.attr[attr_key][1];
                    input.oninput = function (e) {
                        editor.updateNodeAttr(node, attr_key, this.value);
                        if(this.form.classList.contains('saved')){
                            this.form.classList.remove('saved');
                        }
                    };

                    if (data.attr[attr_key][2] !== 'text') {
                        input.disabled = true;
                        let hint = document.createElement('p');
                        hint.innerText = '* Is not text value.';
                        hint.classList.add('hint');
                        label.appendChild(hint);
                    }

                    label.appendChild(input);
                    form.appendChild(label);
                }

                submit = document.createElement('input');
                submit.type = 'submit';
                submit.value = "Save";
                form.appendChild(submit);
                form.onsubmit = function (e) {
                    e.preventDefault();
                    editor.submitForm(form);
                };
                forms.appendChild(form);


                inspector.appendChild(forms);

                selector.appendChild(inspector);

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
        activeSelector: function (selector) {
            let siblings = selector.parentElement.getElementsByClassName('active');
            for (let i = 0; i < siblings.length; i++) {
                siblings[i].classList.remove('active');
            }
            selector.classList.add('active');
        },
        inactiveSelector: function (selector) {
            selector.classList.remove('active');
        },
        expandSelector: function (selector) {
            let siblings = selector.parentElement.getElementsByClassName('expanded');
            for (let i = 0; i < siblings.length; i++) {
                this.collapseSelector(siblings[i]);
            }
            selector.classList.add('expanded');
        },
        collapseSelector: function (selector) {
            selector.classList.remove('expanded');
        },
        initSelectors: function initSelectors() {
            let editor = this;
            let nodes = document.querySelectorAll(this.text_node_selector + ',' + this.attr_node_selector);
            for (let i = 0; i < nodes.length; i++) {
                this.last_node_index++;
                let key = this.last_node_index;
                let node = nodes[i], nodePos = this.getNodeOffset(node), selector = null;

                if (!node.hasAttribute(this.node_num_attr)) {
                    let data = editor.getNodeData(node);

                    selector = document.createElement('div');
                    node.setAttribute(this.node_num_attr, key);
                    selector.setAttribute('n', key);

                    node.onmouseover = function () {
                        editor.initNodeInspector(selector, node);
                        editor.markNode(node);
                    };
                    node.onmouseout = function () {
                        editor.unMarkNode(node);
                    };
                    selector.onclick = function () {
                        editor.activeSelector(this);
                        editor.expandSelector(selector);
                    };
                    /*
                    * On mouse hovered on selector
                    * */
                    selector.onmouseover = function () {
                        editor.activeSelector(this);
                        editor.markNode(node);
                        editor.initNodeInspector(selector, node);
                    };
                    /*
                    * Mouse leave selector
                    * */
                    selector.onmouseout = function () {
                        editor.inactiveSelector(this);
                        editor.unMarkNode(node);
                    };

                    this.wrapper.appendChild(selector);
                } else {
                    key = node.getAttribute(this.node_num_attr);
                    selector = editor.wrapper.querySelector("[n=\"" + key + "\"]");
                }

                selector.style.position = this.isNodeFixed(node) ? 'fixed' : 'absolute';
                selector.style.top = nodePos.top + "px";
                selector.style.left = nodePos.left + "px";
            }
        },
        start: function () {
            document.body.appendChild(this.wrapper);
            this.wrapper.id = window.novembit.i18n.prefix + "-editor-wrapper";
            this.initSelectors();
        },
        update: function () {
            this.initSelectors();
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

    window.onload = function () {
        this.novembit.i18n.editor.start();
    };

    stopScroll(window, function () {
        window.novembit.i18n.editor.update();
    })

})();