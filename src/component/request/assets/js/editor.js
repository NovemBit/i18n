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
        initNodeInspector: function (selector, node) {
            let editor = this;
            let inspector = selector.querySelector('.' + window.novembit.i18n.prefix + '-inspector');
            if (typeof inspector === 'undefined' || inspector === null) {
                let data = editor.getNodeData(node);
                inspector = document.createElement('div');
                inspector.classList.add(window.novembit.i18n.prefix + '-inspector');
                inspector.classList.add(window.novembit.i18n.prefix + '-inspector-visible');

                let title = document.createElement('div');
                title.classList.add(window.novembit.i18n.prefix + '-inspector-title');

                let description = document.createElement('div');
                description.classList.add(window.novembit.i18n.prefix + '-inspector-description');
                description.innerText = "Texts: " + data.text.length + " | Attributes: " + data.attr.length;

                title.innerText = node.nodeName;
                inspector.appendChild(title);
                inspector.appendChild(description);

                let form = document.createElement('form');
                form.method = 'post';

                for (let i = 0; i < data.text.length; i++) {
                    let input = document.createElement('input');
                    input.type = 'text';
                    input.value = data.text[i][1];
                    form.appendChild(input);
                }
                inspector.appendChild(form);
                selector.appendChild(inspector);

            } else {
                inspector.classList.add(window.novembit.i18n.prefix + '-inspector-visible');
            }

            return inspector;
        },
        openNodeInspector: function (selector, node) {
            let opened_inspectors = selector.parentElement.getElementsByClassName(window.novembit.i18n.prefix + '-inspector-opened');
            for (let i = 0; i < opened_inspectors.length; i++) {
                this.closeInspector(opened_inspectors[i]);
            }
            let inspector = this.initNodeInspector(selector, node);
            inspector.classList.add(window.novembit.i18n.prefix + '-inspector-opened');
        },
        markNode: function (node) {
            node.classList.add(this.active_node_class);
        },
        unMarkNode: function (node) {
            node.classList.remove(this.active_node_class);
        },
        hideInspector(inspector) {
            inspector.classList.remove(window.novembit.i18n.prefix + '-inspector-visible');
        },
        closeInspector(inspector) {
            inspector.classList.remove(window.novembit.i18n.prefix + '-inspector-opened');
            inspector.classList.remove(window.novembit.i18n.prefix + '-inspector-visible');
        },
        hideNodeInspector: function (selector, node) {
            let inspector = selector.getElementsByClassName(window.novembit.i18n.prefix + '-inspector')[0];
            this.hideInspector(inspector);
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
                        editor.hideNodeInspector(selector, node);
                        editor.unMarkNode(node);
                    };
                    selector.onclick = function () {
                        editor.openNodeInspector(selector, node);
                    };
                    /*
                    * On mouse hovered on selector
                    * */
                    selector.onmouseover = function () {
                        editor.markNode(node);
                        editor.initNodeInspector(selector, node);
                    };
                    /*
                    * Mouse leave selector
                    * */
                    selector.onmouseout = function () {
                        editor.unMarkNode(node);
                        editor.hideNodeInspector(selector, node);
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
        document.body.appendChild(this.novembit.i18n.editor.wrapper);
        this.novembit.i18n.editor.wrapper.id = this.novembit.i18n.prefix + "-editor-wrapper";
        this.novembit.i18n.editor.initSelectors();
    };

    stopScroll(window, function () {
        window.novembit.i18n.editor.initSelectors();
    })

})();